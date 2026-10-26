<?php
/*
 * ai_config.sample.php  ->  copy this file to ai_config.php and fill it in.
 *
 * ai_config.php turns on the AI model for Admin > Tags > AI-Assisted Tagger (Issue #67).
 * It is listed in .gitignore, so your secret API key is never committed to GitHub.
 * Without ai_config.php (or with no key), the tagger uses its built-in keyword rules.
 *
 * provider: 'openai' or 'anthropic'
 * api_key : your secret key (OpenAI: platform.openai.com, Anthropic: console.anthropic.com)
 * model   : optional; leave '' for the default small model of that provider
 *
 * The same file also powers the "Ask ABCD" RAG chatbot (chatbot.php):
 * chat_model        : optional model for chatbot answers (defaults to `model`)
 * embedding_api_key : optional OpenAI key for meaning-based (semantic) search.
 *                     Not needed when provider is 'openai' (its key is reused).
 *                     Without it the chatbot uses keyword search, which still works well.
 */
return [
    'provider' => 'openai',
    'api_key'  => 'your-api-key-here',
    'model'    => '',
    // 'chat_model'        => '',
    // 'embedding_api_key' => 'your-openai-key-here',
];
