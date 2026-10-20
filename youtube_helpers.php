<?php
/*
 * youtube_helpers.php
 * Shared helpers for showing YouTube videos anywhere on the site (Issue #68).
 *
 * Before this file, each page handled YouTube links its own way:
 *   - blog_fill.php only converted "watch?v=" links and used an <embed type="video/mp4">
 *     tag, which browsers cannot use to play YouTube, so blog videos showed up blank.
 *   - display_the_resource.php put the saved URL straight into an <iframe>, which only
 *     works for "/embed/" links. YouTube refuses normal "watch?v=" links inside a frame.
 *
 * Now every page passes whatever link the admin pasted to youtube_embed_html(), which
 * finds the video id and builds a proper YouTube <iframe> for it.
 */

if (!function_exists('youtube_video_id')) {
    /**
     * Pulls the 11-character video id out of any common YouTube link:
     *   https://www.youtube.com/watch?v=ID   (also with extra parameters like &t=30s or &si=...)
     *   https://youtu.be/ID
     *   https://www.youtube.com/embed/ID
     *   https://www.youtube.com/shorts/ID
     *   https://www.youtube.com/live/ID
     *   https://m.youtube.com/... and youtube-nocookie.com/embed/ID
     * Returns null when the text is not a YouTube link.
     */
    function youtube_video_id($url) {
        if (!is_string($url) || trim($url) === '') {
            return null;
        }
        $pattern = '~(?:youtube(?:-nocookie)?\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/|v/)|youtu\.be/)([A-Za-z0-9_-]{11})~i';
        if (preg_match($pattern, trim($url), $m)) {
            return $m[1];
        }
        return null;
    }
}

if (!function_exists('youtube_embed_url')) {
    /** Returns the embed address YouTube allows inside an <iframe>, or null for non-YouTube links. */
    function youtube_embed_url($url) {
        $id = youtube_video_id($url);
        return $id === null ? null : 'https://www.youtube.com/embed/' . $id;
    }
}

if (!function_exists('youtube_embed_html')) {
    /**
     * Builds the HTML for a responsive YouTube player.
     * $url   - any YouTube link the admin saved
     * $title - text for screen readers (describes the video)
     * Returns an empty string when $url is empty or not a YouTube link, so callers can
     * simply echo the result without checking first.
     */
    function youtube_embed_html($url, $title = 'YouTube video') {
        $embed = youtube_embed_url($url);
        if ($embed === null) {
            return '';
        }
        $src   = htmlspecialchars($embed, ENT_QUOTES, 'UTF-8');
        $label = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

        // The outer div keeps the player at a 16:9 shape and lets it shrink on phones
        // (max-width 560px on desktop, full width on small screens).
        return '<div class="yt-embed" style="max-width:560px; margin:10px auto;">'
             .   '<div style="position:relative; width:100%; padding-top:56.25%;">'
             .     '<iframe src="' . $src . '" title="' . $label . '"'
             .     ' style="position:absolute; top:0; left:0; width:100%; height:100%; border:0;"'
             .     ' allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"'
             .     ' referrerpolicy="strict-origin-when-cross-origin"'   // YouTube needs the referrer or it shows "Error 153"
             .     ' allowfullscreen loading="lazy"></iframe>'
             .   '</div>'
             . '</div>';
    }
}
?>
