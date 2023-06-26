<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require 'objects\dress.php';

// initialize
$dress = new Dress();
$id = intval($_GET['id']);
$result = "";

// run query
$data = $dress->read($id);

// prepare results
if ($data->num_rows > 0) {
    http_response_code(200);
    // there will be at most 1 result since id is primary key
    $result = mysqli_fetch_assoc($data);
}
else {
    http_response_code(404);
    $result = array("message" => "No dress found for id " . $id . ".");
}

// send results
echo json_encode($result);
