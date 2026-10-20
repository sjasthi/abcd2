<?php
/*
 * ai_tagger_classifier.php
 * Decides which tags a dress should get (Issue #67: AI-Assisted Tagger).
 *
 * Input for each dress: its title (`name`), `description` and `did_you_know` text.
 * Output: one or more tags, always taken from the fixed tag list (reports/abcd_tags.txt).
 *
 * Two ways of classifying:
 *   1. AI model   - used when ai_config.php holds an API key (OpenAI or Anthropic Claude).
 *                   Several dresses are sent in one request to keep it fast and cheap.
 *   2. Keyword rules - built in, needs no key or internet. Also used as a safety net for any
 *                   dress the AI returns no valid tag for, so EVERY dress ends up with a tag.
 */

/* ---------- Configuration ---------- */

/**
 * Reads ai_config.php (not committed to git, because it holds a secret key).
 * Copy ai_config.sample.php to ai_config.php and fill it in to turn the AI on.
 */
function ai_tagger_config() {
    $config = ['provider' => 'none', 'api_key' => '', 'model' => ''];
    $file = __DIR__ . '/ai_config.php';
    if (is_readable($file)) {
        $loaded = include $file;           // the file returns an array
        if (is_array($loaded)) {
            $config = array_merge($config, $loaded);
        }
    }
    $config['provider'] = strtolower(trim((string)$config['provider']));
    if (!in_array($config['provider'], ['openai', 'anthropic'], true) || trim($config['api_key']) === ''
        || stripos($config['api_key'], 'your-') === 0) {
        $config['provider'] = 'none';      // no usable key: keyword rules only
    }
    if ($config['model'] === '') {
        // Small, inexpensive models are plenty for picking tags from a short list.
        $config['model'] = $config['provider'] === 'anthropic' ? 'claude-haiku-5-5' : 'gpt-4o-mini';
    }
    return $config;
}

/** Human-readable name of the method in use, shown on the tagger page. */
function ai_tagger_method_label($config) {
    if ($config['provider'] === 'openai')    return 'AI model (OpenAI ' . $config['model'] . ')';
    if ($config['provider'] === 'anthropic') return 'AI model (Claude ' . $config['model'] . ')';
    return 'Built-in keyword rules (no AI key configured)';
}

/* ---------- Main entry point ---------- */

/**
 * Classifies a batch of dresses.
 * $dresses = [['id'=>1, 'name'=>'...', 'description'=>'...', 'did_you_know'=>'...'], ...]
 * Returns  [dress_id => ['tags' => ['Sarees', 'Womenswear'], 'source' => 'ai'|'rules'], ...]
 * plus a 'warning' key (string) when the AI call failed and rules were used instead.
 */
function ai_tagger_classify_batch(array $dresses, array $tagList, $config) {
    $results = [];
    $warning = null;

    if ($config['provider'] !== 'none') {
        try {
            $aiTags = ai_tagger_ask_model($dresses, $tagList, $config);
            foreach ($dresses as $d) {
                $valid = ai_tagger_valid_tags($aiTags[$d['id']] ?? [], $tagList);
                if ($valid) {
                    $results[$d['id']] = ['tags' => $valid, 'source' => 'ai'];
                }
            }
        } catch (Exception $e) {
            // Network problem, bad key, rate limit... keep going with the keyword rules.
            $warning = 'AI request failed (' . $e->getMessage() . '); keyword rules were used for this batch.';
        }
    }

    // Any dress still without a tag (no AI, AI failed, or AI gave nothing valid) -> rules.
    foreach ($dresses as $d) {
        if (!isset($results[$d['id']])) {
            $results[$d['id']] = ['tags' => ai_tagger_rule_tags($d, $tagList), 'source' => 'rules'];
        }
    }

    if ($warning !== null) {
        $results['warning'] = $warning;
    }
    return $results;
}

/**
 * Keeps only tags that are in the fixed list, fixing capitalization
 * ("sarees" -> "Sarees"). Anything the model invents is dropped.
 */
function ai_tagger_valid_tags($tags, array $tagList) {
    $lookup = [];
    foreach ($tagList as $t) {
        $lookup[strtolower($t)] = $t;
    }
    $valid = [];
    foreach ((array)$tags as $t) {
        $key = strtolower(trim((string)$t));
        if (isset($lookup[$key])) {
            $valid[$lookup[$key]] = true;
        }
    }
    return array_keys($valid);
}

/* ---------- 1. AI model ---------- */

/** Builds the instructions sent to the model. */
function ai_tagger_prompt(array $dresses, array $tagList) {
    $items = [];
    foreach ($dresses as $d) {
        $items[] = [
            'id'           => (int)$d['id'],
            'title'        => ai_tagger_clean($d['name'], 150),
            'description'  => ai_tagger_clean($d['description'], 1200),
            'did_you_know' => ai_tagger_clean($d['did_you_know'], 400),
        ];
    }
    return "You classify entries from Project ABCD (A Bite of Culture in Dresses), a collection of "
         . "traditional Indian dresses, costumes and notable people.\n\n"
         . "Allowed tags (use these exact spellings, nothing else):\n- " . implode("\n- ", $tagList) . "\n\n"
         . "For each entry, read its title, description and did_you_know text and choose every tag that "
         . "clearly applies. Most entries get 1 to 3 tags; never return an empty list. "
         . "Sheroes are notable women and Heroes are notable men. Womenswear and Menswear describe who "
         . "wears the outfit.\n\n"
         . "Reply with JSON only, in exactly this shape:\n"
         . "{\"results\":[{\"id\":1,\"tags\":[\"Tag A\",\"Tag B\"]}]}\n\n"
         . "Entries:\n" . json_encode($items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

/** Sends one batch to the configured model. Returns [dress_id => [tag, ...]]. Throws on failure. */
function ai_tagger_ask_model(array $dresses, array $tagList, $config) {
    $prompt = ai_tagger_prompt($dresses, $tagList);

    if ($config['provider'] === 'anthropic') {
        // Anthropic Messages API
        $url     = 'https://api.anthropic.com/v1/messages';
        $headers = [
            'Content-Type: application/json',
            'x-api-key: ' . $config['api_key'],
            'anthropic-version: 2023-06-01',
        ];
        $body = [
            'model'      => $config['model'],
            'max_tokens' => 2000,
            'messages'   => [['role' => 'user', 'content' => $prompt]],
        ];
    } else {
        // OpenAI Chat Completions API (the same API chatbot.php uses)
        $url     = 'https://api.openai.com/v1/chat/completions';
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $config['api_key'],
        ];
        $body = [
            'model'           => $config['model'],
            'temperature'     => 0,
            'response_format' => ['type' => 'json_object'],
            'messages'        => [['role' => 'user', 'content' => $prompt]],
        ];
    }

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_POSTFIELDS     => json_encode($body, JSON_UNESCAPED_UNICODE),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT        => 60,
    ]);
    $raw    = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error  = curl_error($ch);
    curl_close($ch);

    if ($raw === false) {
        throw new Exception('network error: ' . $error);
    }
    $json = json_decode($raw, true);
    if ($status !== 200) {
        $msg = $json['error']['message'] ?? ('HTTP ' . $status);
        throw new Exception($msg);
    }

    // Pull the model's text out of each API's response shape.
    $text = $config['provider'] === 'anthropic'
        ? ($json['content'][0]['text'] ?? '')
        : ($json['choices'][0]['message']['content'] ?? '');

    return ai_tagger_parse_reply($text);
}

/**
 * Turns the model's reply text into [dress_id => [tag, ...]].
 * Forgiving: takes everything from the first "{" to the last "}", so extra words or a
 * ```json fence around the answer don't break it. Throws if no usable JSON is found.
 */
function ai_tagger_parse_reply($text) {
    $start = strpos($text, '{');
    $end   = strrpos($text, '}');
    $data  = ($start !== false && $end !== false) ? json_decode(substr($text, $start, $end - $start + 1), true) : null;
    if (!is_array($data) || !isset($data['results']) || !is_array($data['results'])) {
        throw new Exception('the model did not return the expected JSON');
    }

    $out = [];
    foreach ($data['results'] as $r) {
        if (isset($r['id'], $r['tags'])) {
            $out[(int)$r['id']] = (array)$r['tags'];
        }
    }
    return $out;
}

/** Trims text, removes HTML and limits its length so prompts stay small. */
function ai_tagger_clean($text, $max) {
    $text = trim(preg_replace('/\s+/u', ' ', strip_tags((string)$text)));
    return mb_substr($text, 0, $max);
}

/* ---------- 2. Built-in keyword rules ---------- */

/**
 * Words and phrases that point to each of the default tags. Matching is
 * case-insensitive and on whole words. A tag an admin adds later without an entry
 * here is matched on its own name (e.g. "Jewelry" matches "jewelry").
 */
function ai_tagger_keywords() {
    return [
        'Sarees'           => ['saree', 'sarees', 'sari', 'saris', 'pallu', 'nauvari', 'kasavu', 'kanchipuram',
                               'kanjeevaram', 'banarasi', 'paithani', 'drape', 'draped', 'madisar', 'ludga'],
        'Weddings'         => ['wedding', 'weddings', 'bride', 'brides', 'bridal', 'groom', 'marriage', 'married',
                               'unmarried'],
        'Traditions'       => ['tradition', 'traditional', 'traditionally', 'custom', 'customary', 'ritual',
                               'religious', 'ceremony', 'ceremonies', 'festival', 'festivals', 'greeting',
                               'heritage', 'culture', 'cultural', 'temple', 'puja', 'worship', 'sacred'],
        'Womenswear'       => ['skirt', 'skirts', 'kurti', 'frock', 'gown', 'sharara', 'ghagra', 'women', 'woman', 'womens', 'girl', 'girls', 'ladies', 'female', 'lehenga',
                               'choli', 'blouse', 'dupatta', 'salwar', 'kameez', 'pavadai', 'bangles', 'anklets'],
        'Menswear'         => ['turbans', 'peta', 'paag', 'kediyu', 'bandhgala', 'nehru jacket', 'men', 'mens', 'boy', 'boys', 'male', 'dhoti', 'dhothi', 'turban', 'pagdi', 'pagri',
                               'sherwani', 'lungi', 'mundu', 'pehta', 'bandi', 'waistcoat', 'kurta pajama'],
        'Folk'             => ['folk', 'tribal', 'tribe', 'tribes', 'village', 'villagers', 'rural', 'harvest',
                               'banjara', 'lambadi', 'nomadic'],
        'Dances'           => ['dance', 'dances', 'dancer', 'dancers', 'dancing', 'natyam', 'bharatanatyam',
                               'kathak', 'kathakali', 'kuchipudi', 'odissi', 'manipuri', 'mohiniyattam',
                               'sattriya', 'garba', 'dandiya', 'dandia', 'bhangra', 'bihu', 'lavani', 'ghoomar'],
        'Costumes'         => ['costume', 'costumes', 'fancy dress', 'mascot', 'makeup', 'headpiece', 'mask',
                               'masks', 'outfit for performance', 'stage'],
        'Professions'      => ['delivery', 'carrier', 'carriers', 'collector', 'collectors', 'astrology', 'reader', 'singer', 'singers', 'priests', 'monk', 'monks', 'drummer', 'dabbawala', 'dabbawalas', 'politician', 'lawyer', 'economist', 'actress', 'actor', 'profession', 'professions', 'farmer', 'farmers', 'doctor', 'nurse', 'teacher',
                               'police', 'policeman', 'milkmaid', 'milkmaids', 'fisherman', 'fishermen',
                               'priest', 'pilot', 'chef', 'cook', 'vendor', 'postman', 'potter', 'weaver',
                               'worker', 'workers', 'job', 'occupation', 'airline', 'sport', 'athlete',
                               'cricketer', 'player'],
        'Scientists'       => ['scientist', 'scientists', 'physicist', 'chemist', 'mathematician',
                               'astronomer', 'engineer', 'isro', 'research', 'researcher', 'invented',
                               'inventor', 'discovery', 'discovered', 'theory', 'laboratory', 'space program'],
        'Changemakers'     => ['discrimination', 'untouchables', 'campaign', 'campaigns', 'reshaped', 'revival', 'tolerance', 'teachings', 'reformer', 'social reformer', 'activist', 'changemaker', 'helped the poor',
                               'humanitarian', 'charity', 'founded', 'founder', 'equality', 'rights',
                               'education for', 'nobel peace', 'social work', 'nun', 'missionaries'],
        'Freedom Fighters' => ['nationalism', 'freedom struggle', 'independence', 'freedom fighter', 'freedom fighters', 'independence movement', 'revolutionary',
                               'british rule', 'british raj', 'nationalist', 'satyagraha', 'quit india',
                               'swaraj', 'non-violence', 'nonviolence', 'independence of india',
                               'indian national congress', 'colonial'],
        'Armed Forces'     => ['army', 'navy', 'naval', 'air force', 'soldier', 'soldiers', 'military',
                               'regiment', 'war', 'battle', 'param vir chakra', 'general', 'commander',
                               'officer', 'uniform', 'marine', 'warrior', 'warriors'],
        // Deity names that are also common human names (Rama, Krishna, Lakshmi...) are left out
        // on purpose: they mislabeled real people such as Gopal Krishna Gokhale.
        'Characters'       => ['gods', 'goddesses', 'avatar', 'lord', 'robot', 'movie', 'film character', 'mythology', 'mythological', 'god', 'goddess', 'deity', 'epic', 'ramayana',
                               'mahabharata', 'hanuman', 'deities', 'devotee', 'devotees',
                               'character', 'characters', 'legend', 'legendary', 'story', 'stories',
                               'cartoon', 'fictional', 'maharaja mascot'],
    ];
}

/** Counts whole-word matches of $words in $text. */
function ai_tagger_count_matches($text, array $words) {
    $hits = 0;
    foreach ($words as $w) {
        $hits += preg_match_all('/\b' . preg_quote($w, '/') . '\b/iu', $text);
    }
    return $hits;
}

/**
 * Keyword-rule classification for one dress. Always returns at least one tag.
 * Scoring: a match in the title counts 3, a match in description / did_you_know counts 1.
 * Every tag scoring 2+ is kept (best first, at most 4); if none reaches 2, the single best
 * tag is used; if nothing matches at all, "Traditions" (or the first tag in the list).
 */
function ai_tagger_rule_tags(array $dress, array $tagList) {
    $title = ' ' . ai_tagger_clean($dress['name'], 300) . ' ';
    $body  = ' ' . ai_tagger_clean($dress['description'], 3000) . ' '
           . ai_tagger_clean($dress['did_you_know'], 1000) . ' ';
    $all   = $title . $body;
    $map   = ai_tagger_keywords();

    $scores = [];
    foreach ($tagList as $tag) {
        if ($tag === 'Sheroes' || $tag === 'Heroes') continue;   // handled below
        $words = $map[$tag] ?? [];
        // Custom tags (and every tag's own name) match on the tag's name, singular or plural.
        $name    = strtolower($tag);
        $words[] = $name;
        $words[] = rtrim($name, 's');
        $words   = array_unique(array_filter($words));
        $scores[$tag] = 3 * ai_tagger_count_matches($title, $words) + ai_tagger_count_matches($body, $words);
    }

    // Sheroes / Heroes: entries about a notable PERSON, split by the pronouns used.
    $personSignals = ['was born', 'born in', 'born on', 'died', 'passed away', 'his life', 'her life',
                      'leader', 'fought', 'awarded', 'nobel', 'prime minister', 'president', 'freedom fighter',
                      'revolutionary', 'scientist', 'reformer', 'activist', 'queen', 'king', 'rani', 'famous for',
                      'politician', 'poet', 'writer', 'author', 'actress', 'actor', 'singer', 'musician',
                      'composer', 'painter', 'monk', 'saint', 'guru', 'economist', 'lawyer', 'minister',
                      'vice president', 'chief minister', 'known for', 'is known', 'was known', 'contributed'];
    $person = ai_tagger_count_matches($all, $personSignals);
    if ($person >= 2) {
        $she = ai_tagger_count_matches($all, ['she', 'her', 'hers', 'herself']);
        $he  = ai_tagger_count_matches($all, ['he', 'his', 'him', 'himself']);
        $who = $she > $he ? 'Sheroes' : 'Heroes';
        if (in_array($who, $tagList, true)) {
            $scores[$who] = 2 + $person;
        }
        // A person entry is about the person, not who usually wears the outfit.
        unset($scores['Womenswear'], $scores['Menswear']);
    }

    arsort($scores);
    $chosen = [];
    foreach ($scores as $tag => $score) {
        if ($score >= 2 && count($chosen) < 4) {
            $chosen[] = $tag;
        }
    }
    if (!$chosen) {
        $best = key($scores);
        if ($best !== null && $scores[$best] > 0) {
            $chosen[] = $best;
        } elseif (in_array('Traditions', $tagList, true)) {
            $chosen[] = 'Traditions';
        } elseif ($tagList) {
            $chosen[] = $tagList[0];
        }
    }
    return $chosen;
}
?>
