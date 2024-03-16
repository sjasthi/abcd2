
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



<?php 
/*********************************************************************************/
//Code for getting info from OpenAI API
session_start();
$message = null;
$result = null;
$_SESSION['chat_history'] = null; // resetting history for now. third message in chat breaks the json messaging format for some reason.

if (isset($_POST['input'])) {
    
    $message = $_POST['input'];
    $message = '{"role": "user", "content": "'.$message.'"}'; // jsonify it
    
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer  Api here'
    ];
    
    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    $json_end_braces = ']}';
    
    if (isset($_SESSION['chat_history'])) { // if chat history already exitst, append the new message to prior history
        $json_data = $_SESSION['chat_history'] . "," . $message;

    } else { // no chat history yet
        
        // EXAMPLE JSON FORMAT FOR REFERENCE:
        // $json_data = '{"model":"gpt-3.5-turbo",
        //     "messages": [{"role": "system", "content": "You are a chatbot for assisting users on an educational website about Indian heroes and sheroes. The content includes history about traditional dresses and outfits worn by historical figures. Please assist them with questions related to this context."}, 
        //     {"role": "user", "content": "'.$message.'"} ]}';

        $json_data = '{"model":"gpt-3.5-turbo",
            "messages": [{"role": "system", "content": "You are a chatbot for assisting users on an educational website about Indian heroes and sheroes. The content includes history about traditional dresses and outfits worn by historical figures. Please assist them with questions related to this context."}, 
            '.$message.'';

        $_SESSION['chat_history'] = $json_data;
    }
    // append the end braces to the json
    $json_data = $json_data . $json_end_braces;

    curl_setopt_array($ch, [
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => 2,
        CURLOPT_POSTFIELDS => $json_data
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($response, true);
    
    /************************************************************************************/
   
    // now we append the response part to $_SESSION['chat_history'].
    // format needed is: {"role": "assistant", "content": "response message here"},
    $result = $result['choices'][0]['message']['content'];
    $response_message = '{"role": "assistant", "content": "'.$result.'"}'; // jsonify it
    $_SESSION['chat_history'] = $_SESSION['chat_history'] . "," . $response_message; // append the response to the chat history
    
    if (isset($_POST['past_messages'])){
        $x = $_POST['past_messages'];
        $result = $x.$message."<br/>".$result."<br/>";
    }
}
?>

<!-- GITHUB EXAMPLE FORM -->

<!-- change the action to whatever page you want it to be displayed on -->
<!-- <form method="post" action="" hidden> 
    <input type="text" name="input" id="input" placeholder="Enter your question here.">
    <button class="p-2 bg-indigo-800 text-white rounded" name="past_messages" value="<?= $result?>">Submit</button>
</form> -->

 <!-- DEBUG OUTPUTS -->
<!-- <?php 
    // if ($result != null) {
    //     echo $result;
    //     echo $_SESSION['chat_history']; // debugging
    //     echo $json_data; // debugging
    //     echo $message; // debugging
    //     echo $response; // debugging
    //     echo implode($result_json);
    // }
?> -->


<!-- CHAT BOX -->
<div class="chat-box-container">

<button class="open-button" onclick="openForm()">Chat</button>

<div class="chat-popup" id="myForm">
  <form id ="chat-form" method="post" class="form-container" action="">
    <h1>Chat</h1>

    <div class="chat-messages">
       <?php if ($result != null) { echo $result; } ?> 
    </div>
    
    <label id = "message-input" for="msg"><b>Message</b></label>
    <textarea placeholder="Type your message.." name="input" required></textarea>

    <button id = "chat-submit" type="submit" class="btn" onclick = "openForm()" >Send</button>
    <button type="button" class="btn cancel" onclick ="closeForm()">Close</button>
  </form>
</div> 



</div>


</body>
</html>
