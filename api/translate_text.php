<?php
// Best practice would be to hide this API key in a .env file, but would require a package and additional configuration
$apiKey = 'AIzaSyAHnHq5LBIfWLsVOMCaoXfjhqEzm-jhNso';
$url = 'https://translation.googleapis.com/language/translate/v2?key=' . $apiKey;

$textToTranslate = '';
$translatedText = '';
$translateToLanguage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inputText'])) {
    $textToTranslate = $_POST['inputText'];

    if (isset($_POST['telegu'])) {
        $translateToLanguage = 'te';
    } elseif (isset($_POST['french'])) {
        $translateToLanguage = 'fr';
    }

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
        echo 'Error: ' . curl_error($ch);
    } else {
        $responseData = json_decode($response, true);

        if (isset($responseData['data']['translations'][0]['translatedText'])) {
            $translatedText = $responseData['data']['translations'][0]['translatedText'];
        } else {
            echo 'Translation not available.';
        }
    }

    curl_close($ch);
}