<?php
require 'objects/dress.php';
require 'api_tools.php';

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
?>