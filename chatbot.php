<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/chatbot.css">
</head>
<body>
    
</body>
</html>

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