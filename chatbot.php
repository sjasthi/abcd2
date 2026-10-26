<?php
/*
 * chatbot.php
 * The chatbot now lives in a balloon on every page (chat_widget.php, included by header.php).
 * This page is kept so old links still work: it shows a short intro and opens the balloon.
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$page_title = 'Project ABCD > Ask ABCD';
$abcd_chat_open = true;   // chat_widget.php reads this and opens the panel on this page
include('header.php');
?>

<div class="container mt-5">
    <br><br>
    <h2>Ask ABCD</h2>
    <p>Ask questions about the dresses, heroes and sheroes in Project ABCD. The chat answers only
       from our own collection, so questions about anything else get a polite "not in the system" reply.</p>
    <p>The chat balloon is in the bottom-right corner of every page, so you can ask from anywhere on the site.</p>
</div>
