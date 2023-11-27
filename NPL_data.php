<?php
include('header.php');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abcd_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT ID, Name, description, key_words, did_you_know FROM dresses";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $cell) {
            echo "<td>$cell</td>";
        }

        // Count the number of words in the "description" column
        $descriptionCount = str_word_count($row['description']);
        echo "<td>$descriptionCount</td>";

        // Count the number of nouns in the "description" column using Compromise.js
        $descNounCount = getNounCount($row['description']);
        echo "<td>$descNounCount</td>";

        // Count the number of adjectives in the "description" column using Compromise.js
        $descAdjectiveCount = getAdjectiveCount($row['description']);
        echo "<td>$descAdjectiveCount</td>";

        // Count the number of words in the "dyk_description" column
        $dykWordCount = str_word_count($row['did_you_know']);
        echo "<td>$dykWordCount</td>";

        // Count the number of nouns in the "dyk_description" column using Compromise.js
        $dykNounCount = getNounCount($row['did_you_know']);
        echo "<td>$dykNounCount</td>";

        // Count the number of adjectives in the "dyk_description" column using Compromise.js
        $dykAdjectiveCount = getAdjectiveCount($row['did_you_know']);
        echo "<td>$dykAdjectiveCount</td>";

        // Calculate total word count
        $totalWordCount = $descriptionCount + $dykWordCount;
        echo "<td>$totalWordCount</td>";

        // Calculate total noun count
        $totalNounCount = $descNounCount + $dykNounCount;
        echo "<td>$totalNounCount</td>";

        // Calculate total adjective count
        $totalAdjectiveCount = $descAdjectiveCount + $dykAdjectiveCount;
        echo "<td>$totalAdjectiveCount</td>";

        echo "</tr>";
    }
} else {
    echo "0 results";
}

$conn->close();

// Function to get noun count using Compromise.js
function getNounCount($text) {
    $doc = nlp($text);
    $nouns = $doc->nouns()->out('array');
    return count($nouns);
}

// Function to get adjective count using Compromise.js
function getAdjectiveCount($text) {
    $doc = nlp($text);
    $adjectives = $doc->adjectives()->out('array');
    return count($adjectives);
}
?>
