/*
 * js/gallery.js
 * Front-end behaviour for gallery.php:
 *   1. Photo / Video filter buttons
 *   2. Lightbox viewer with previous/next (keyboard arrows + Esc work too)
 *   3. Admin upload form: show/hide, drag-and-drop, list of chosen files
 */
(function () {
  'use strict';

  /* ---------- 1. Filters ---------- */
  var filterButtons = document.querySelectorAll('.g-filter');
  var items = Array.prototype.slice.call(document.querySelectorAll('.gallery-item'));
  var noneMatch = document.getElementById('gallery-none-match');

  function applyFilter(filter) {
    var visible = 0;
    items.forEach(function (item) {
      var show = filter === 'all' || item.dataset.type === filter;
      item.hidden = !show;
      if (show) visible++;
    });
    filterButtons.forEach(function (btn) {
      var active = btn.dataset.filter === filter;
      btn.classList.toggle('is-active', active);
      btn.setAttribute('aria-selected', active ? 'true' : 'false');
    });
    if (noneMatch) noneMatch.hidden = visible > 0;
  }

  filterButtons.forEach(function (btn) {
    btn.addEventListener('click', function () {
      applyFilter(btn.dataset.filter);
      // Remember the filter in the address bar so links like gallery.php#video work.
      history.replaceState(null, '', btn.dataset.filter === 'all' ? location.pathname : '#' + btn.dataset.filter);
    });
  });

  // Open the page already filtered when the URL ends in #image or #video.
  var startFilter = location.hash.replace('#', '');
  if (startFilter === 'image' || startFilter === 'video') applyFilter(startFilter);

  /* ---------- 2. Lightbox ---------- */
  var lightbox = document.getElementById('gallery-lightbox');
  var lbMedia = document.getElementById('lb-media');
  var lbTitle = document.getElementById('lb-title');
  var lbCaption = document.getElementById('lb-caption');
  var currentIndex = -1;
  var lastFocused = null;

  // Only items that are visible under the current filter take part in prev/next.
  function visibleThumbs() {
    return items.filter(function (i) { return !i.hidden; })
                .map(function (i) { return i.querySelector('.gallery-thumb'); });
  }

  function render(thumb) {
    var d = thumb.dataset;
    lbMedia.innerHTML = ''; // also stops any video that was playing

    var el;
    if (d.type === 'image') {
      el = document.createElement('img');
      el.src = d.src;
      el.alt = d.title;
    } else if (d.youtube) {
      el = document.createElement('iframe');
      el.src = 'https://www.youtube.com/embed/' + encodeURIComponent(d.youtube) + '?autoplay=1&rel=0';
      el.title = d.title;
      el.allow = 'autoplay; encrypted-media; picture-in-picture; fullscreen';
      el.allowFullscreen = true;
      el.className = 'lb-iframe';
    } else {
      el = document.createElement('video');
      el.src = d.src;
      el.controls = true;
      el.autoplay = true;
      el.playsInline = true;
    }
    lbMedia.appendChild(el);
    lbTitle.textContent = d.title;
    lbTitle.hidden = !d.title; // pictures without a title show no heading
    lbCaption.textContent = d.caption || '';
    lbCaption.hidden = !d.caption;
  }

  function open(thumb) {
    var list = visibleThumbs();
    currentIndex = list.indexOf(thumb);
    lastFocused = thumb;
    render(thumb);
    lightbox.hidden = false;
    document.body.classList.add('lb-open');
    var single = list.length < 2;
    lightbox.querySelector('.lb-prev').hidden = single;
    lightbox.querySelector('.lb-next').hidden = single;
    lightbox.querySelector('.lb-close').focus();
  }

  function close() {
    lightbox.hidden = true;
    lbMedia.innerHTML = '';
    document.body.classList.remove('lb-open');
    if (lastFocused) lastFocused.focus();
  }

  function step(delta) {
    var list = visibleThumbs();
    if (list.length < 2) return;
    currentIndex = (currentIndex + delta + list.length) % list.length; // wraps around
    lastFocused = list[currentIndex];
    render(list[currentIndex]);
  }

  if (lightbox) {
    document.querySelectorAll('.gallery-thumb').forEach(function (thumb) {
      thumb.addEventListener('click', function () { open(thumb); });
    });
    lightbox.querySelector('.lb-close').addEventListener('click', close);
    lightbox.querySelector('.lb-prev').addEventListener('click', function () { step(-1); });
    lightbox.querySelector('.lb-next').addEventListener('click', function () { step(1); });
    // Clicking the dark background (not the picture itself) closes the viewer.
    lightbox.addEventListener('click', function (e) {
      if (e.target === lightbox || e.target.classList.contains('lb-figure')) close();
    });
    document.addEventListener('keydown', function (e) {
      if (lightbox.hidden) return;
      if (e.key === 'Escape') close();
      else if (e.key === 'ArrowLeft') step(-1);
      else if (e.key === 'ArrowRight') step(1);
    });
  }

  /* ---------- 3. Admin upload form ---------- */
  var toggle = document.getElementById('gallery-add-toggle');
  var form = document.getElementById('gallery-upload-form');
  if (!toggle || !form) return; // not an admin

  var cancel = document.getElementById('gallery-cancel');
  var dropzone = document.getElementById('gallery-dropzone');
  var fileInput = document.getElementById('g-media');
  var fileList = document.getElementById('gallery-file-list');

  function showForm(show) {
    form.hidden = !show;
    toggle.hidden = show;
    toggle.setAttribute('aria-expanded', show ? 'true' : 'false');
  }
  toggle.addEventListener('click', function () { showForm(true); });
  cancel.addEventListener('click', function () { form.reset(); fileList.innerHTML = ''; showForm(false); });

  // Show the names of the chosen files under the drop area.
  function listFiles() {
    fileList.innerHTML = '';
    Array.prototype.forEach.call(fileInput.files, function (f) {
      var chip = document.createElement('span');
      chip.className = 'g-file-chip';
      var icon = f.type.indexOf('video') === 0 ? 'fa-video-camera' : 'fa-picture-o';
      chip.innerHTML = '<i class="fa ' + icon + '"></i> ';
      chip.appendChild(document.createTextNode(f.name));
      fileList.appendChild(chip);
    });
  }
  fileInput.addEventListener('change', listFiles);

  // Drag-and-drop: hand the dropped files to the real <input type="file">.
  ['dragenter', 'dragover'].forEach(function (evt) {
    dropzone.addEventListener(evt, function (e) { e.preventDefault(); dropzone.classList.add('is-dragging'); });
  });
  ['dragleave', 'drop'].forEach(function (evt) {
    dropzone.addEventListener(evt, function (e) { e.preventDefault(); dropzone.classList.remove('is-dragging'); });
  });
  dropzone.addEventListener('drop', function (e) {
    if (e.dataTransfer && e.dataTransfer.files.length) {
      fileInput.files = e.dataTransfer.files;
      listFiles();
    }
  });

  // Simple "uploading…" state so a large video upload doesn't look frozen.
  form.addEventListener('submit', function () {
    var btn = form.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Uploading…';
  });
})();
