<?php
require 'objects/dress.php';
require 'api_tools.php';

Dress::setConnection($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? $_POST['name'] : null;
    $description = isset($_POST['description']) ? $_POST['description'] : null;
    $did_you_know = isset($_POST['did_you_know']) ? $_POST['did_you_know'] : null;
    $category = isset($_POST['category']) ? $_POST['category'] : null;
    $type = isset($_POST['type']) ? $_POST['type'] : null;
    $state_name = isset($_POST['state_name']) ? $_POST['state_name'] : null;
    $key_words = isset($_POST['key_words']) ? $_POST['key_words'] : null;
    $image_url = isset($_POST['image_url']) ? $_POST['image_url'] : null;
    $status = isset($_POST['status']) ? $_POST['status'] : null;
    $notes = isset($_POST['notes']) ? $_POST['notes'] : null;

    $dress = new Dress();
    $result = $dress->addDress($name, $description, $did_you_know, $category, $type, $state_name, $key_words, $image_url, $status, $notes);

    if ($result) {
        response(201, "Dress added successfully", $result);
    } else {
        invalidResponse("Failed to add the dress");
    }
} else {
    invalidResponse("Invalid request method. Please use POST.");
}
?>