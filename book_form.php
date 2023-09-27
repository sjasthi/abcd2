<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Book</title>
    <link rel="stylesheet" href="css/book_form_styles.css"> <!-- Assuming you have a CSS file for styling -->
</head>
<body>

<div class="form-container">
    <h2>Book Generation Form</h2>

    <form action="generated_book.php" method="post">

        <!-- Textbox for Dress IDs -->
        <div class="form-group">
            <label for="dress_numbers">Enter Dress IDs:</label>
            <input type="text" id="dress_numbers" name="dress_numbers" placeholder="Example: 1,2,3,4">
        </div>

        <!-- Layout Choices -->
        <div class="form-group">
            <label for="layout">Choose a layout:</label>
            <select id="layout" name="layout">
                <option value="portrait">Portrait</option>
                <option value="landscape">Landscape</option>
            </select>
        </div>

        <!-- Sort Order Choices -->
        <div class="form-group">
            <label for="sort_order">Sort Order:</label>
            <select id="sort_order" name="sort_order">
                <option value="ascending">Ascending</option>
                <option value="descending">Descending</option>
            </select>
        </div>

        <!-- Preferences -->
        <div class="form-group">
            <label for="text_size">Text Size:</label>
            <input type="number" id="text_size" name="text_size" placeholder="Example: 14">
        </div>

        <div class="form-group">
            <label for="font_choice">Font:</label>
            <select id="font_choice" name="font_choice">
                <option value="Arial">Arial</option>
                <option value="Verdana">Verdana</option>
                <!-- We can add other font options here -->
            </select>
        </div>

        <div class="form-group">
            <label for="picture_dimensions">Picture Dimensions:</label>
            <input type="text" id="picture_dimensions" name="picture_dimensions" placeholder="Example: 200x300">
        </div>

        <!-- Numbering Choices -->
        <div class="form-group">
            <label for="numbering_choice">Numbering Choice:</label>
            <select id="numbering_choice" name="numbering_choice">
                <option value="roman">Roman Numerals</option>
                <option value="numeric">Numeric</option>
            </select>
        </div>

        <!-- NEW: Choose Number Options -->
        <div class="form-group">
            <label for="number_options">Choose Number Options:</label>
            <select id="number_options" name="number_options">
                <option value="page_no">Show Page Number</option>
                <option value="dress_id">Show Dress ID</option>
                <option value="both">Show Both Page Number and Dress ID</option>
            </select>
        </div>

        <!-- Checkbox for Translation -->
        <div class="form-group">
            <input type="checkbox" id="enable_translation" name="enable_translation" value="yes">
            <label for="enable_translation">Enable Translation</label>
        </div>

        <!-- Generate Button -->
        <div class="form-group">
            <input type="submit" value="Generate">
        </div>

    </form>
</div>

</body>
</html>

