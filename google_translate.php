<?php
include('api/translate_text.php')
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Translate</title>
</head>
<body>
    <h1>Google Translate</h1>
    <form method="post">
        <label for="text">Enter Text to Translate:</label>
        <textarea id="text" name="inputText" rows="4" cols="50"></textarea>
        <br>
        <label for="telegu">
            <input type="checkbox" id="telegu" name="telegu" value="1">
            Translate to Telegu
        </label>
        <label for="french">
            <input type="checkbox" id="french" name="french" value="1">
            Translate to French
        </label>
        <br>
        <input type="submit" name="translate" value="Translate">
    </form>

    <?php
    if (!empty($translatedText)) {
        echo '<h2>Translated Text:</h2>';
        echo '<p>' . $translatedText . '</p>';
    }
    ?>
</body>
</html>
