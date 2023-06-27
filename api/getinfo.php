<?php
require 'objects\dress.php';

// initialize
$id = intval($_GET['id']);
$dress = Dress::getById($id);

// prepare results
if($dress) {
    response(200, "Dress found", $dress);
}
else {
    invalidResponse("No dress found for id " . $id);
}

function invalidResponse($message) {
    response(400, $message, NULL);
}

function response($responseCode, $message, $data) {
    // Locally cache results for two hours
    header('Cache-Control: max-age=7200');

    // JSON Header
    header('Content-type:application/json;charset=utf-8');

    http_response_code($responseCode);
    $response = array("response_code" => $responseCode, "message" => $message, "data" => $data);
    $json = json_encode($response);
    echo $json;
}
