<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Book</title>
    <link rel="stylesheet" href="css/book_form_styles.css"> 
</head>
<body>

<div class="form-container">
    <h2>Book Generation Form</h2>

    <form action="generated_book.php" method="post">

        <!-- Textbox for Dress IDs -->
        <div class="form-group">
            <label for="dress_numbers">Dress Numbers:</label>
            <textarea id="dress_numbers" name="dress_numbers" placeholder="Example: 1,2,3,4"></textarea>
        </div>

        <!-- Layout Choices -->
        <div class="form-group">
            <label for="layout">Layout:</label>
            <select id="layout" name="layout">
                <option value="portrait">Picture on the left Page - Text on Right Page - Two Page Mode - Portrait Mode</option>
                <option value="landscape_right">Picture on Right - Text on Left - Single Page - Landscape Mode</option>
                <option value="landscape_left">Picture on Left - Text on Right - Single Page - Landscape Mode</option>
            </select>
        </div>

        <!-- Sort Order Choices -->
        <div class="form-group">
            <label for="sort_order">Sort Order:</label>
            <select id="sort_order" name="sort_order">
                <option value="name">By Name</option>
                <option value="id">By ID</option>
                <option value="input_order">By Input Order</option>
            </select>
        </div>

        <!-- Preferences -->
        <div class="form-group">
            <label for="text_size">Text size:</label>
            <input type="number" id="text_size" name="text_size" placeholder="Example: 14">
        </div>

        <div class="form-group">
            <label for="title_size">Title size:</label>
            <input type="number" id="title_size" name="title_size" placeholder="Example: 18">
        </div>

        <div class="form-group">
            <label for="subtitle_size">Subtitle size:</label>
            <input type="number" id="subtitle_size" name="subtitle_size" placeholder="Example: 16">
        </div>

        <div class="form-group">
            <label for="text_font">Text Font:</label>
            <select id="text_font" name="text_font">
                <option value="Times New Roman">Times New Roman (serif)</option>
                <option value="Arial">Arial (sans-serif)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="title_font">Title Font:</label>
            <select id="title_font" name="title_font">
                <option value="Arial">Arial (sans-serif)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="subtitle_font">Subtitle Font:</label>
            <select id="subtitle_font" name="subtitle_font">
                <option value="Arial">Arial (sans-serif)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="pic_width">Pic Width:</label>
            <input type="text" id="pic_width" name="pic_width" placeholder="Example: 720">
        </div>

        <div class="form-group">
            <label for="pic_height">Pic Height:</label>
            <input type="text" id="pic_height" name="pic_height" placeholder="Example: 1040">
        </div>

        <!-- Numbering Choices -->
        <div class="form-group">
            <label for="numbering_choice">Numbering:</label>
            <select id="numbering_choice" name="numbering_choice">
                <option value="both">Show both Page No and Dress ID</option>
                <option value="page_no">Show Page Number</option>
                <option value="dress_id">Show Dress ID</option>
            </select>
        </div>

        <!-- Dropdown for Translation Language Selection -->
<div class="form-group">
    <label for="translation_language">Translate to:</label>
    <select id="translation_language" name="translation_language">
        <option value="en">Telugu</option>
        <option value="fr">French</option>
        <option value="es">Spanish</option>
        <option value="de">German</option>
        <!-- Add other language options here -->
    </select>
</div>

 <!-- Generate and Help Buttons -->
        <div class="form-group">
			<button type="button" class="action-button" onclick="window.open('http://localhost/ics-499/abcd2-Bears--main/pdf/book_form_pdf.php', '_blank')">Generate</button>
			<button type="button" class="action-button" onclick="window.open('', '_blank')">Help</button>
		</div>
    </form>
</div>

</body>
</html>




