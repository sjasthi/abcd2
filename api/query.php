<?php
require 'objects/dress.php';
require 'api_tools.php';

// run query
try {
    $idList = Dress::getByParams($_GET);
    response(200, count($idList) . " matching results", $idList);
} catch (mysqli_sql_exception $e) {
    invalidResponse("Invalid SQL syntax. Parameter names must match database column names.");
}
?>