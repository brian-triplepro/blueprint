/**
 * Main JS for Blueprint theme
 *
 * @package Blueprint
 */

document.addEventListener('DOMContentLoaded', function () {

    var header = document.querySelector('.site-header');
    if ( header ) {
        var lastKnownScrollY = 0;
        var ticking = false;
        var threshold = 60; 
        
        function onScroll() {
            lastKnownScrollY = window.scrollY;
            if ( ! ticking ) {
                window.requestAnimationFrame(function() {
                    if ( lastKnownScrollY > threshold ) {
                        if ( ! header.classList.contains('site-header--scrolled') ) {
                            header.classList.add('site-header--scrolled');
                        }
                    } else {
                        if ( header.classList.contains('site-header--scrolled') ) {
                            header.classList.remove('site-header--scrolled');
                        }
                    }
                    ticking = false;
                });
                ticking = true;
            }
        }

        window.addEventListener('scroll', onScroll, { passive: true });
    }

    // Mobile menu toggle
    var menuToggle = document.querySelector('.menu-toggle');
    var siteHeader = document.querySelector('.site-header');
    var mobileMenu = document.getElementById('mobile-mega-menu');

    if ( menuToggle && siteHeader && mobileMenu ) {
      function closeMenu(){
        siteHeader.classList.remove('mobile-open');
        menuToggle.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('no-scroll');

        // collapse any open submenus
        var openToggles = mobileMenu.querySelectorAll('.submenu-toggle[aria-expanded="true"]');
        openToggles.forEach(function(t){
          t.setAttribute('aria-expanded','false');
          var sign = t.querySelector('.toggle-sign');
          if ( sign ) sign.textContent = '▸';
          var s = document.getElementById(t.getAttribute('aria-controls'));
          if (s) s.style.display = 'none';
          var p = t.closest('.menu-item');
          if (p) p.classList.remove('submenu-open');
        });
      }

      function openMenu(){
        siteHeader.classList.add('mobile-open');
        menuToggle.setAttribute('aria-expanded', 'true');
        document.body.classList.add('no-scroll');
      }

      menuToggle.addEventListener('click', function(e){
        e.preventDefault();
        if ( siteHeader.classList.contains('mobile-open') ) {
          closeMenu();
        } else {
          openMenu();
        }
      });

      var mobileClose = mobileMenu.querySelector('.mobile-close');
      if ( mobileClose ) {
        mobileClose.addEventListener('click', function(e){
          e.preventDefault();
          closeMenu();
          menuToggle.focus();
        });
      }

      // close when clicking outside
      document.addEventListener('click', function(e){
        if ( siteHeader.classList.contains('mobile-open') ) {
          if ( ! mobileMenu.contains(e.target) && ! menuToggle.contains(e.target) ) {
            closeMenu();
          }
        }
      });

      // close on escape
      document.addEventListener('keydown', function(e){
        if ( e.key === 'Escape' && siteHeader.classList.contains('mobile-open') ) {
          closeMenu();
          menuToggle.focus();
        }
      });

      // ensure cleanup on navigation
      var mobileLinks = mobileMenu.querySelectorAll('a');
      mobileLinks.forEach(function(link){
        link.addEventListener('click', function(){ closeMenu(); });
      });

      // add submenu toggles for items with children
      var submenuParents = mobileMenu.querySelectorAll('.mobile-menu .menu-item-has-children');
      submenuParents.forEach(function(parent, idx){
        var submenu = parent.querySelector('.sub-menu');
        if (!submenu) return;

        // create toggle button
        var toggle = document.createElement('button');
        toggle.className = 'submenu-toggle';
        toggle.setAttribute('aria-expanded', 'false');

        // ensure submenu has an id for aria-controls
        if (!submenu.id) {
          submenu.id = 'mobile-submenu-' + idx;
        }
        toggle.setAttribute('aria-controls', submenu.id);
        toggle.innerHTML = '<span class="sr-only">Toggle submenu</span><span class="toggle-sign" aria-hidden="true">▸</span>';

        // insert toggle after the link
        var link = parent.querySelector('a');
        if (link) {
          link.parentNode.insertBefore(toggle, link.nextSibling);
        } else {
          parent.insertBefore(toggle, parent.firstChild);
        }

        // collapse submenu initially
        submenu.style.display = 'none';

        toggle.addEventListener('click', function(e){
          e.preventDefault();
          var expanded = toggle.getAttribute('aria-expanded') === 'true';
          if (expanded) {
            toggle.setAttribute('aria-expanded', 'false');
            toggle.querySelector('.toggle-sign').textContent = '▸';
            parent.classList.remove('submenu-open');
            submenu.style.display = 'none';
          } else {
            toggle.setAttribute('aria-expanded', 'true');
            toggle.querySelector('.toggle-sign').textContent = '▾';
            parent.classList.add('submenu-open');
            submenu.style.display = 'block';
          }
        });
      });
    }
});
