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
 */
return [
    'provider' => 'openai',
    'api_key'  => 'your-api-key-here',
    'model'    => '',
];
