<?php
require 'objects/dress.php';

// initialize
$category = array();
$type = array();
$keywords = array();

if (isset($_GET['category'])) $category = json_decode($_GET['category']);
if (isset($_GET['type'])) $type = json_decode($_GET['type']);
if (isset($_GET['keywords'])) $keywords = json_decode($_GET['keywords']);

// run query
$dressList = Dress::getByCategoryAndTypeAndKeyword($category, $type, $keywords);

// prepare result
response(200, count($dressList) . " matching results", $dressList);

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
