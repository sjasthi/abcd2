<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/f40040d297.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/chatbot.css">
</head>

<body>


<script src ="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    // Function to open the chat form
    function openForm() {
        $("#myForm").show();
    }

    // Function to close the chat form
    function closeForm() {
        $("#myForm").hide();
    }

    // Event listener for the "Chat" button
    $(".open-button").click(openForm);

    // Event listener for the "Close" button
    $(".cancel").click(closeForm);

    // Event listener for form submission
    $('#chat-form').submit(function (event) {
        event.preventDefault(); // Prevent default form submission
        var message = $('textarea[name="input"]').val(); // Get the message from the textarea
        if (message.trim() !== '') { // Check if the message is not empty
            appendUserMessage(message); // Append the user's message to the chat box with label
            sendMessageToChatbot(message); // Send the user's message to the chatbot
        }
        // Clear the textarea after submission
        $('textarea[name="input"]').val('');
    });

    function appendUserMessage(message) {
        $('#chat-messages').append('<div class="message-label">You:</div>'); // add user label
        $('#chat-messages').append($('<div class="user-message"></div>').text(message));
        $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
    }

    // Shows text safely: .text() never turns user or model text into HTML (prevents XSS).
    function appendBotMessage(text, sources) {
        $('#chat-messages').append('<div class="message-label">Ask ABCD:</div>');
        var box = $('<div class="message"></div>').text(text);
        if (sources && sources.length) {
            var titles = sources.map(function (s) { return s.title; }).join(', ');
            box.append($('<div class="chat-sources"></div>').text('Source: ' + titles));
        }
        $('#chat-messages').append(box);
        $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
    }

    function sendMessageToChatbot(message) {
        // Disable form submission while the AJAX request is in progress
        $('#chat-submit').prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: 'rag_ask.php',
            data: { question: message },
            dataType: 'json',
            success: function (res) {
                appendBotMessage(res.answer, res.sources);
            },
            error: function (jqXHR) {
                var msg = (jqXHR.responseJSON && jqXHR.responseJSON.answer) || 'Something went wrong. Please try again.';
                appendBotMessage(msg, []);
            },
            complete: function() {
                // Re-enable form submission after the request is completed
                $('#chat-submit').prop('disabled', false);
            }
        });
    }
});
</script>


<?php
/*
 * The answers now come from the "Ask ABCD" RAG pipeline (rag_ask.php):
 * the question is matched against OUR dress entries only, and the AI model (if configured
 * in ai_config.php) writes the answer from those entries. Questions the database cannot
 * answer get the standard refusal message. The API key no longer lives in this file.
 */
?>



<!-- CHAT BOX -->

<div class="chat-box-container">

<button class="open-button">Chat  <i class="fa-regular fa-comment"></i></button>
    <div class="chat-popup" id="myForm">
    <form id="chat-form" class="form-container">
        <div class="d-flex justify-content-between">
            <h2>Ask ABCD</h2>
            <h2><i class="fa-regular fa-comments"></i></h2>
        </div>

        <div class="chat-messages" id="chat-messages"></div>
        
        <label id="message-input" for="msg"><b>Message</b></label>
        <textarea placeholder="Ask about a dress, hero or shero..." name="input" class="chatbox-text-area" required></textarea>

        <button id="chat-submit" type="submit" class="btn">Send <i class="fa-regular fa-paper-plane"></i></button>
        <button type="button" class="btn cancel">Close <i class="fa-regular fa-rectangle-xmark"></i></button>
    </form>
    </div> 
</div>



</body>
</html>
