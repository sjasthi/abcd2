<?php
require 'objects\dress.php';

// initialize
$category = "";
$type = "";
$keywords = "";

if (isset($_GET['category'])) $category = $_GET['category'];
if (isset($_GET['type'])) $type = $_GET['type'];
if (isset($_GET['keywords'])) $keywords = $_GET['keywords'];

// run query
$dressList = Dress::getByCategoryAndTypeAndKeyword($category, $type, $keywords);

// prepare results
if(!is_null($dressList)) {
        response(200, count($dressList) . " matching results", $dressList);
}
else {
    //invalidResponse("No dresses found with category " . $category . ", type " . $type . ", and keywords " . $keywords);
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
