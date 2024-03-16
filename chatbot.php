<?php
    if(!isset($_SESSION)) 
    { 
        session_start();
    } 
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/chatbot.css">
</head>

<body>

<!-- <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script> -->
<!-- <script src="./js/chatbot.js"></script> -->
<script>
function openForm() {
    document.getElementById("myForm").style.display = "block";
}
  
function closeForm() {
    document.getElementById("myForm").style.display = "none";
} 
</script>

<script src ="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    // Event for form submission
    $(document).on('submit', '#chat-form', function (event) {
        event.preventDefault(); // Prevent default form submission
        var message = $('textarea[name="input"]').val(); // Get the message from the textarea
        appendUserMessage(message); // Append the user's message to the chat box with label
        sendMessageToChatbot(message); // Send the user's message to the chatbot
    });

    function appendUserMessage(message) {
        $('#chat-messages').append('<div class="message-label">You:</div>'); // Add user label
        $('#chat-messages').append('<div class="user-message">' + message + '</div>');
        $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
    }

    function sendMessageToChatbot(message) {
        $.ajax({
            type: 'POST',
            url: '',
            data: { input: message },
            success: function (response) {
                $('#chat-messages').append('<div class="message-label">Chatbot:</div>'); // Add chatbot label
                $('#chat-messages').append('<div class="message">' + response + '</div>');
                $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#chat-messages').append('<div class="error-message">' + errorThrown + '</div>');
                $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
            }
        });
    }
});
</script>


<?php 
/*********************************************************************************/
//Code for getting info from OpenAI API
$message = null;
$result = null;
// $_SESSION['chat_history'] = null; // resetting history for now. third message in chat breaks the json messaging format for some reason.

if (isset($_POST['input'])) {
    // Interaction with the OpenAI API
    $message = '{"role": "user", "content": "' . $_POST['input'] . '"}';

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer APIKEYHERE'
    ];
    
    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    $json_data = '{"model":"gpt-3.5-turbo",
        "messages": [{"role": "system", "content": "You are a chatbot for assisting users on an educational website about Indian heroes and sheroes. The content includes history about traditional dresses and outfits worn by historical figures. Please assist them with questions related to this context."}, 
        ' . $message . ']}';

    curl_setopt_array($ch, [
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => 2,
        CURLOPT_POSTFIELDS => $json_data
    ]);
    
    $response = curl_exec($ch);
    if ($response === false) {
        echo "Request error: " . curl_error($ch);
    } else {
        $decoded_response = json_decode($response, true);
        if (isset($decoded_response['error'])) {
            // If an error occurred, echo the error message
            echo $decoded_response['error']['message'];
        } else {
            // Echo only the response message
            echo $decoded_response['choices'][0]['message']['content'];
        }
    }
    curl_close($ch);
    exit(); // Stop further execution after sending the response
}
?>

 <!-- DEBUG OUTPUTS -->
<!-- <?php 
    // echo $result;
    // echo $_SESSION['chat_history'];
    // echo $json_data;
    // echo $response;
?> -->


<!-- CHAT BOX -->

<div class="chat-box-container">

<button class="open-button" onclick="openForm()">Chat</button>

<div class="chat-popup" id="myForm">
  <form id="chat-form" method="post" class="form-container" role="form">
    <h1>Chat</h1>

     <div class="chat-messages" id="chat-messages"></div>
    
    <label id="message-input" for="msg"><b>Message</b></label>
    <textarea placeholder="Type your message..." name="input" required></textarea>

    <button id="chat-submit" type="submit" class="btn" >Send</button>
    <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
  </form>
</div> 
</div>



</body>
</html>
