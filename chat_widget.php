<?php
/*
 * chat_widget.php
 * The "Ask ABCD" chat balloon that floats in the bottom-right corner of every page.
 *
 * It is included once from header.php, so any page that uses the shared header gets it.
 * Clicking the balloon opens a small chat panel; questions go to rag_ask.php, which answers
 * only from the dresses database (see rag_functions.php).
 *
 * Everything is namespaced with "abcd-chat" so it cannot clash with Bootstrap or page styles,
 * and it uses plain JavaScript (fetch) because the site loads the "slim" jQuery build, which
 * has no $.ajax.
 *
 * A page can open the panel automatically by setting  $abcd_chat_open = true;  before it
 * includes header.php (chatbot.php does this).
 */
?>
<link rel="stylesheet" href="css/chat_widget.css">

<div id="abcd-chat" class="abcd-chat"<?= !empty($abcd_chat_open) ? ' data-open-on-load="1"' : '' ?>>
    <!-- The panel: hidden until the balloon is clicked -->
    <section id="abcd-chat-panel" class="abcd-chat-panel" role="dialog" aria-label="Ask ABCD chat" hidden>
        <header class="abcd-chat-header">
            <div>
                <strong>Ask ABCD</strong>
                <span>Answers from our dresses, heroes and sheroes</span>
            </div>
            <button type="button" id="abcd-chat-close" class="abcd-chat-close" aria-label="Close chat">&times;</button>
        </header>

        <!-- aria-live lets screen readers announce new answers as they arrive -->
        <div id="abcd-chat-messages" class="abcd-chat-messages" aria-live="polite"></div>

        <form id="abcd-chat-form" class="abcd-chat-form" autocomplete="off">
            <label for="abcd-chat-input" class="abcd-chat-sr">Your question</label>
            <textarea id="abcd-chat-input" rows="1" maxlength="500" placeholder="Ask about a dress, hero or shero..." required></textarea>
            <button type="submit" id="abcd-chat-send" class="abcd-chat-send" aria-label="Send">
                <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="M3.4 20.4 21 12 3.4 3.6 3.4 10l12.6 2-12.6 2z"/></svg>
            </button>
        </form>
    </section>

    <!-- The balloon button itself -->
    <button type="button" id="abcd-chat-toggle" class="abcd-chat-toggle" aria-label="Open Ask ABCD chat" aria-expanded="false" aria-controls="abcd-chat-panel">
        <svg class="abcd-chat-icon-open" viewBox="0 0 24 24" width="28" height="28" aria-hidden="true">
            <path fill="currentColor" d="M12 3C6.5 3 2 6.6 2 11c0 2.2 1.1 4.2 2.9 5.6-.2 1.5-.9 2.9-2 4 2.1-.1 4.1-.9 5.6-2.2 1.1.3 2.3.5 3.5.5 5.5 0 10-3.6 10-8s-4.5-8-10-8zm-5 9.3a1.3 1.3 0 1 1 0-2.6 1.3 1.3 0 0 1 0 2.6zm5 0a1.3 1.3 0 1 1 0-2.6 1.3 1.3 0 0 1 0 2.6zm5 0a1.3 1.3 0 1 1 0-2.6 1.3 1.3 0 0 1 0 2.6z"/>
        </svg>
        <svg class="abcd-chat-icon-close" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true">
            <path fill="currentColor" d="M18.3 5.7 12 12l6.3 6.3-1.4 1.4L10.6 13.4 4.3 19.7 2.9 18.3 9.2 12 2.9 5.7 4.3 4.3l6.3 6.3 6.3-6.3z" transform="translate(1.4 0)"/>
        </svg>
    </button>
</div>

<script src="js/chat_widget.js"></script>
