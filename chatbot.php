
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/chatbot.css">
</head>
<body>
<div class="chat-box">
    <div class="chat-box-header">
        <h3>Message Us</h3>
        <p><i class="fa fa-times"></i></p>
    </div>
    <div class="chat-box-body">
        <div class="chat-box-body-send">
            <p>This is my message.</p>
            <span>12:00</span>
        </div>
        <div class="chat-box-body-receive">
            <p>This is my message.</p>
            <span>12:00</span>
        </div>
        <div class="chat-box-body-receive">
            <p>This is my message.</p>
            <span>12:00</span>
        </div>
        <div class="chat-box-body-send">
            <p>This is my message.</p>
            <span>12:00</span>
        </div>
        <div class="chat-box-body-send">
            <p>This is my message.</p>
            <span>12:00</span>
        </div>
        <div class="chat-box-body-receive">
            <p>This is my message.</p>
            <span>12:00</span>
        </div>
        <div class="chat-box-body-receive">
            <p>This is my message.</p>
            <span>12:00</span>
        </div>
        <div class="chat-box-body-send">
            <p>This is my message.</p>
            <span>12:00</span>
        </div>
    </div>
    <div class="chat-box-footer">
        <button id="addExtra"><i class="fa fa-plus"></i></button>
        <input placeholder="Enter Your Message" type="text" />
        <i class="send far fa-paper-plane"></i>
    </div>
</div>
<div class="chat-button"><span></span></div>
<div class="modal">
    <div class="modal-content">
        <span class="modal-close-button">&times;</span>
        <h1>Add What you want here.</h1>
    </div>
</div>
</div>
</body>
</html>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script  src="./js/chatbot.js"> </script>

<?php 
/*********************************************************************************/
//Code for getting info from OpenAI API
$message = null;
$result = null;
if(isset($_POST['input'])) {
    $message = $_POST['input'];
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer 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'
    ];
    
    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    $json_data = '{"model":"gpt-3.5-turbo",
        "messages": [{"role": "system", "content": "You are a chatbot for assisting users on a website."}, {"role": "user", "content": "'.$message.'"}]}';
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

    $result = $result['choices'][0]['message']['content'];
    
    if(isset($_POST['past_messages'])){
        $x = $_POST['past_messages'];
        $result = $x.$message."<br/>".$result."<br/>";
    }
}
?>

<form method="post" action="index.php"> <!-- change the action to whatever page you want it to be displayed on -->
    <input type="text" name="input" id="input" placeholder="Enter your question here.">
    <button class="p-2 bg-indigo-800 text-white rounded" name="past_messages" value="<?= $result?>">Submit</button>
</form>
<p>This might take awhile, please wait until the page is finished loading to enter a new prompt.</p>
<?php 
    if ($result != null) {
    echo $result;
    }
?>