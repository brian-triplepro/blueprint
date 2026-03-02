(function(){
  'use strict';

  function onReady(fn){
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
  }

  onReady(function(){
    var el = document.getElementById('timed-popup');
    if (!el) return;

    var key = (window.blueprintTimedNotices && (window.blueprintTimedNotices.key || window.blueprintTimedNotices.postId)) || '0';
    var active = (window.blueprintTimedNotices && (typeof window.blueprintTimedNotices.active !== 'undefined')) ? Number(window.blueprintTimedNotices.active) : 1;
    var storageKey = 'bp_timed_notices_shown'; // single storage item containing { key, active, ts }

    // Use a single storage item and compare stored key + active with current values.
    // If the stored key AND active match current values, we've already shown this exact notice — skip.
    // If stored key or active differs, remove stored value so the new notice can display and we will replace it on close.
    try {
      var raw = null;
      try { raw = localStorage && localStorage.getItem(storageKey); } catch(e){ try { raw = sessionStorage && sessionStorage.getItem(storageKey); } catch(e){} }

      if ( raw ) {
        try {
          var parsed = JSON.parse( raw );
          if ( parsed && parsed.key === key && Number(parsed.active) === active ) return; // already shown this exact notice + state
        } catch (e) {
          // ignore parse errors and continue
        }

        // different notice or active state stored — remove stored entry to allow showing the new/changed notice
        try { if (localStorage) localStorage.removeItem(storageKey); } catch(e){ try { if (sessionStorage) sessionStorage.removeItem(storageKey); } catch(e){} }
      }
    } catch (e) {
      // storage unavailable; continue and use best-effort sessionStorage on close
    }

    function openPopup(){
      el.setAttribute('aria-hidden','false');
      el.classList.add('timed-popup--open');
      document.body.classList.add('no-scroll');
      // focus the close button
      var close = el.querySelector('.timed-popup__close');
      if (close) close.focus();
    }

    function closePopup(){
      el.setAttribute('aria-hidden','true');
      el.classList.remove('timed-popup--open');
      document.body.classList.remove('no-scroll');
      try {
        var payload = JSON.stringify({ key: key, active: active, ts: Date.now() });
        if (localStorage) localStorage.setItem(storageKey, payload);
        else if (sessionStorage) sessionStorage.setItem(storageKey, payload);
      } catch(e) {
        try { if (sessionStorage) sessionStorage.setItem(storageKey, JSON.stringify({ key: key, ts: Date.now() })); } catch(e){}
      }
    }

    // open shortly after load for UX
    setTimeout(openPopup, 600);

    // handlers
    el.addEventListener('click', function(e){
      if ( e.target.hasAttribute('data-tp-close') ) {
        e.preventDefault();
        closePopup();
      }
    });

    document.addEventListener('keydown', function(e){
      if (e.key === 'Escape' && el.classList.contains('timed-popup--open')) {
        closePopup();
      }
    });

    // close when clicking links inside
    var links = el.querySelectorAll('a');
    links.forEach(function(a){ a.addEventListener('click', function(){ closePopup(); }); });

  });
})();