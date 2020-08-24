/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */

(function($) {

  // Use this variable to set up the common and page specific functions. If you
  // rename this variable, you will also need to rename the namespace below.
  var Sage = {
    // All pages
    'common': {
      init: function() {
        // JavaScript to be fired on all pages
      },
      finalize: function() {
        // JavaScript to be fired on all pages, after page specific JS is fired
      }
    },
    // Home page
    'home': {
      init: function() {
        // JavaScript to be fired on the home page
      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
      }
    },
    // About us page, note the change from about-us to about_us.
    'about_us': {
      init: function() {
        // JavaScript to be fired on the about us page
      }
    }
  };

  // The routing fires all common scripts, followed by the page specific scripts.
  // Add additional events for more control over timing e.g. a finalize event
  var UTIL = {
    fire: function(func, funcname, args) {
      var fire;
      var namespace = Sage;
      funcname = (funcname === undefined) ? 'init' : funcname;
      fire = func !== '';
      fire = fire && namespace[func];
      fire = fire && typeof namespace[func][funcname] === 'function';

      if (fire) {
        namespace[func][funcname](args);
      }
    },
    loadEvents: function() {
      // Fire common init JS
      UTIL.fire('common');

      // Fire page-specific init JS, and then finalize JS
      $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function(i, classnm) {
        UTIL.fire(classnm);
        UTIL.fire(classnm, 'finalize');
      });

      // Fire common finalize JS
      UTIL.fire('common', 'finalize');
    }
  };

  // Load Events
  $(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.


// EARTHLING INTERACTIVE
jQuery(document).ready(function() {
  jQuery('[data-toggle="tooltip"]').tooltip({
    container: 'body'
  });
  
  jQuery('.side-contents .side-target').click(function() {
    if(!jQuery('.side-contents').hasClass('show-content')) {
      jQuery('.side-contents').addClass('show-content');
      // window.scrollTo(0, 0);
      jQuery('html, body').animate({scrollTop : 0}, 200);
    }
  });
  
  jQuery('.side-contents .side-close').click(function() {
    if(jQuery('.side-contents').hasClass('show-content')) {
      jQuery('.side-contents').removeClass('show-content'); 
    }
  });
  
  jQuery('.side-contents .side-content a').click(function() {
    jQuery('.side-contents').removeClass('show-content');
  });

  jQuery('.landing-header').affix({
    offset: {
      // top: 100
      top: function () {
        return (jQuery('header.banner').height());
      }
    }
  });
  
  
  jQuery('#mobile-menu').sidr({
    name: 'sidr-left',
    side: 'left'
  });
  jQuery('.sidr-left-close').click(function() {
    jQuery.sidr('close', 'sidr-left');
  });
  
  jQuery('#sidr-left').css('display', 'block');
  
  jQuery('.toggle-sub-nav').click(function() {
    jQuery(this).toggleClass('show-menu');
    jQuery('.sub-nav').toggleClass('show-menu');
  });
  
  jQuery('.mobile-header .search-icon').click(function() {
    jQuery('.mobile-search').toggleClass('show');
    jQuery('.mobile-search-form input.form-control').focus();
  });
  
  jQuery('.mobile-search-close').click(function() {
    jQuery('.mobile-search').removeClass('show');
  });
  
  jQuery('.mobile-search-submit').click(function() {
    jQuery('.mobile-search-form').submit();
  });
  
  jQuery('.sub-nav-select').on('change', function() {
    window.location.href = this.value;
  });
  
  jQuery('.side-contents .side-content ul li a').click(function() {
    setTimeout(function() {
      $('html, body').animate({
          scrollTop: $(window).scrollTop() + 100
      });
    }, 100);
  });

  // jQuery('.side-contents .side-target').click();
  jQuery('.side-contents').addClass('show-content');
  
  jQuery('.show-mentors').click(function(e) {
    e.preventDefault();
    e.stopPropagation();
    jQuery(this).closest('.unmatched-request-item, .matched-request-item').toggleClass('active');
    // available-mentors
  });
});