<?php
/*
 * rag_ask.php
 * The question endpoint of the "Ask ABCD" RAG chatbot.
 *
 * POST  question=<text>
 * Reply (JSON):
 *   { "answer": "...", "grounded": true|false, "sources": [{"dress_id": 4, "title": "Saree"}], "mode": "..." }
 *
 * "grounded" is false when the answer is the refusal message, i.e. the database had nothing
 * relevant, so the chatbot never answers from the AI model's general knowledge.
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'db_configuration.php';
require_once 'rag_functions.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Use POST with a "question" field.']);
    exit;
}

// Very small rate limit per browser session: at most 20 questions per minute.
// (Each question can cost an API call, so this protects the API key from abuse.)
$now = time();
$_SESSION['rag_times'] = array_values(array_filter($_SESSION['rag_times'] ?? [], function ($t) use ($now) {
    return $t > $now - 60;
}));
if (count($_SESSION['rag_times']) >= 20) {
    http_response_code(429);
    echo json_encode(['answer' => 'You are asking very quickly. Please wait a minute and try again.',
                      'grounded' => false, 'sources' => []]);
    exit;
}
$_SESSION['rag_times'][] = $now;

$question = $_POST['question'] ?? '';
$result   = rag_answer($db, $question);

echo json_encode($result, JSON_UNESCAPED_UNICODE);
