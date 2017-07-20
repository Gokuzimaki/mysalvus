(function($) {
    "use strict";
	
/*	Scroll to top
/* ---------------------------------------------------------------------- */
  jQuery(document).ready(function(){ 
 
        jQuery(window).scroll(function(){
            if (jQuery(this).scrollTop() > 100) {
                jQuery('.scroll-up').fadeIn();
            } else {
                jQuery('.scroll-up').fadeOut();
            }
        }); 
 
        jQuery('.scroll-up').click(function(){
            jQuery("html, body").animate({ scrollTop: 0 }, 800);
            return false;
        });
 
    });
/* --------------------------------------------------------------------- */

/* For Tooltips 
/* ---------------------------------------------------------------------- */
jQuery(function () {
        jQuery(".tooltip-top").tooltip({
            placement : 'top'
        });
        jQuery(".tooltip-right").tooltip({
            placement : 'right'
        });
        jQuery(".tooltip-bottom").tooltip({
            placement : 'bottom'
        });
        jQuery(".tooltip-left").tooltip({
            placement : 'left'
        });
});

/* Popovers
/* ---------------------------------------------------------------------- */
jQuery(function () {
    jQuery("[data-toggle=popover]") 
    .popover() 
});
/* --------------------------------------------------------------------- */

/* for Mega Menu
/* ---------------------------------------------------------------------- */
jQuery(function() {
        window.prettyPrint && prettyPrint()
        jQuery(document).on('click', '.megamenu .dropdown-menu', function(e) {
          e.stopPropagation()
        })
      });
/* --------------------------------------------------------------------- */

/* For dropdown menus on hover rather than click 
/* ------------------------------------------------------------- */
jQuery(document).ready(function() {
    jQuery('.nav li.dropdown').hover(function() {
        jQuery(this).addClass('open');
    }, function() {
        jQuery(this).removeClass('open');
    });
});
/* ------------------------------------------------------------- */

/* FAQ with Categories
/* ------------------------------------------------------------- */
jQuery(document).ready(function() {
    jQuery('.collapse').on('show.bs.collapse', function() {
        var id = jQuery(this).attr('id');
        jQuery('a[href="#' + id + '"]').closest('.panel-heading').addClass('active-faq');
        jQuery('a[href="#' + id + '"] .panel-title span').html('<i class="glyphicon glyphicon-minus"></i>');
    });
    jQuery('.collapse').on('hide.bs.collapse', function() {
        var id = jQuery(this).attr('id');
        jQuery('a[href="#' + id + '"]').closest('.panel-heading').removeClass('active-faq');
        jQuery('a[href="#' + id + '"] .panel-title span').html('<i class="glyphicon glyphicon-plus"></i>');
    });
});
/* ------------------------------------------------------------- */

/* Shop List Grid View
/* ---------------------------------------------- */
jQuery(document).ready(function() {
    jQuery('#list').click(function(event){event.preventDefault();jQuery('#products .item').addClass('list-group-item');});
    jQuery('#grid').click(function(event){event.preventDefault();jQuery('#products .item').removeClass('list-group-item');jQuery('#products .item').addClass('grid-group-item');});
});
/* ---------------------------------------------- */

/* Stop carousel
/* ---------------------------------------------- */
jQuery(document).ready(function() {
  jQuery('.carousel').carousel({
	interval: false
  });
});
/* ---------------------------------------------- */

/* Tooltip for Timeline
/* ---------------------------------------------- */
jQuery(document).ready(function(){
    var my_posts = jQuery("[rel=tooltip]");
	var i;
    for(i=0;i<my_posts.length;i++){
        the_post = jQuery(my_posts[i]);
        if(the_post.hasClass('invert')){
            the_post.tooltip({ placement: 'left'});
            the_post.css("cursor","pointer");
        }else{
            the_post.tooltip({ placement: 'right'});
            the_post.css("cursor","pointer");
        }
    }
});
/* ---------------------------------------------- */

/* Animation Css Class
/* ---------------------------------------------- */
jQuery(document).ready(function(){
    var wow = new WOW(
      {
        animateClass: 'animated',
        offset:       100,
        callback:     function(box) {
          console.log("WOW: animating <" + box.tagName.toLowerCase() + ">")
        }
      }
    );
    wow.init();
});
/* ---------------------------------------------- */

})(jQuery);