<?php
function translateText($textToTranslate, $translateToLanguage) {
    $apiKey = 'AIzaSyBEcCCI8VAHl-ezAiQ6cv9cygDnio7zp5I';
    $url = 'https://translation.googleapis.com/language/translate/v2?key=' . $apiKey;

    $data = array(
        'q' => $textToTranslate,
        'source' => 'en',
        'target' => $translateToLanguage,
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return 'Error: ' . curl_error($ch);
    } else {
        $responseData = json_decode($response, true);

        if (isset($responseData['data']['translations'][0]['translatedText'])) {
            return $responseData['data']['translations'][0]['translatedText'];
        } else {
            return 'Translation not available.';
        }
    }

    curl_close($ch);
}