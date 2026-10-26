/*
 * chat_widget.js - behaviour of the "Ask ABCD" chat balloon (chat_widget.php).
 *
 * - Opens and closes the panel (balloon click, the X, or the Escape key).
 * - Sends each question to rag_ask.php and shows the answer plus its source entries.
 * - Keeps the conversation while the visitor moves between pages (sessionStorage),
 *   so it is not lost every time they click a link. It is cleared when the tab closes.
 *
 * Plain JavaScript only: the site loads the "slim" jQuery build, which has no $.ajax.
 * All text is inserted with textContent, never innerHTML, so a question or an answer
 * can never run as HTML or script on the page.
 */
(function () {
    'use strict';

    var root = document.getElementById('abcd-chat');
    if (!root || root.dataset.ready) return;   // already set up (e.g. included twice)
    root.dataset.ready = '1';

    var panel    = document.getElementById('abcd-chat-panel');
    var toggle   = document.getElementById('abcd-chat-toggle');
    var closeBtn = document.getElementById('abcd-chat-close');
    var list     = document.getElementById('abcd-chat-messages');
    var form     = document.getElementById('abcd-chat-form');
    var input    = document.getElementById('abcd-chat-input');
    var sendBtn  = document.getElementById('abcd-chat-send');

    var STORE_KEY = 'abcdChat.v1';
    var GREETING  = 'Hi! Ask me about any dress, hero or shero in Project ABCD. ' +
                    'I only answer from our own collection.';

    /* ---------- Saved state (wrapped in try/catch: storage can be blocked) ---------- */

    function loadState() {
        try {
            var raw = window.sessionStorage.getItem(STORE_KEY);
            var s = raw ? JSON.parse(raw) : null;
            if (s && Array.isArray(s.messages)) return s;
        } catch (e) { /* private mode or blocked storage: start fresh */ }
        return { open: false, messages: [] };
    }

    function saveState() {
        try {
            // keep only the last 30 messages so storage stays small
            state.messages = state.messages.slice(-30);
            window.sessionStorage.setItem(STORE_KEY, JSON.stringify(state));
        } catch (e) { /* ignore: the chat still works, it just won't survive a page change */ }
    }

    var state = loadState();

    /* ---------- Drawing messages ---------- */

    function addBubble(role, text, sources) {
        var div = document.createElement('div');
        div.className = 'abcd-chat-msg ' + (role === 'user' ? 'from-user' : 'from-bot');
        div.textContent = text;

        if (sources && sources.length) {
            var src = document.createElement('span');
            src.className = 'abcd-chat-source';
            src.textContent = 'Source: ' + sources.map(function (s) { return s.title; }).join(', ');
            div.appendChild(src);
        }
        list.appendChild(div);
        list.scrollTop = list.scrollHeight;
        return div;
    }

    function showTyping() {
        var div = document.createElement('div');
        div.className = 'abcd-chat-msg from-bot abcd-chat-typing';
        div.setAttribute('aria-label', 'Ask ABCD is typing');
        div.appendChild(document.createElement('span'));
        div.appendChild(document.createElement('span'));
        div.appendChild(document.createElement('span'));
        list.appendChild(div);
        list.scrollTop = list.scrollHeight;
        return div;
    }

    function renderHistory() {
        list.textContent = '';
        addBubble('bot', GREETING);
        state.messages.forEach(function (m) { addBubble(m.role, m.text, m.sources); });
    }

    /* ---------- Open / close ---------- */

    function setOpen(open, focus) {
        state.open = open;
        panel.hidden = !open;
        root.classList.toggle('is-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'Close Ask ABCD chat' : 'Open Ask ABCD chat');
        if (open) {
            list.scrollTop = list.scrollHeight;
            if (focus) input.focus();
        } else if (focus) {
            toggle.focus();
        }
        saveState();
    }

    toggle.addEventListener('click', function () { setOpen(panel.hidden, true); });
    closeBtn.addEventListener('click', function () { setOpen(false, true); });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !panel.hidden) setOpen(false, true);
    });

    /* ---------- Asking a question ---------- */

    function ask(question) {
        addBubble('user', question);
        state.messages.push({ role: 'user', text: question });
        saveState();

        sendBtn.disabled = true;
        var typing = showTyping();

        var body = new URLSearchParams();
        body.append('question', question);

        fetch('rag_ask.php', { method: 'POST', body: body, credentials: 'same-origin' })
            .then(function (r) {
                // rag_ask.php replies with JSON even for errors (e.g. 429 too many questions)
                return r.json().catch(function () { throw new Error('bad reply'); });
            })
            .then(function (res) {
                var text = (res && res.answer) ? res.answer : 'Sorry, something went wrong. Please try again.';
                var sources = (res && res.sources) || [];
                typing.remove();
                addBubble('bot', text, sources);
                state.messages.push({ role: 'bot', text: text, sources: sources });
                saveState();
            })
            .catch(function () {
                typing.remove();
                addBubble('bot', 'Sorry, I could not reach the server. Please try again.');
            })
            .finally(function () {
                sendBtn.disabled = false;
                input.focus();
            });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var q = input.value.trim();
        if (!q || sendBtn.disabled) return;
        input.value = '';
        input.style.height = '';
        ask(q);
    });

    // Enter sends, Shift+Enter makes a new line
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            form.requestSubmit ? form.requestSubmit() : form.dispatchEvent(new Event('submit', { cancelable: true }));
        }
    });

    // The text box grows with the question, up to the CSS max-height
    input.addEventListener('input', function () {
        input.style.height = 'auto';
        input.style.height = input.scrollHeight + 'px';
    });

    /* ---------- Start ---------- */

    renderHistory();
    // Re-open the panel if it was open on the previous page, or if the page asks for it
    // (chatbot.php sets $abcd_chat_open, which adds data-open-on-load="1").
    var openOnLoad = root.dataset.openOnLoad === '1';
    setOpen(state.open || openOnLoad, openOnLoad);
})();
