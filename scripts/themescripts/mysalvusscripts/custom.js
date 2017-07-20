$(document).ready(function(){
/* sticky header */
/* --------------------------------------------------------------------- */
$(window).scroll(function(){
	if ($(this).scrollTop() > 40){
		/*$('body').addClass("sticky-for-body");
		$('header').addClass("sticky-for-header");
		$('header .toolbar').addClass('toolbar_hidden');*/
	}
	
	else{
		/*$('body').removeClass("sticky-for-body");
		$('header').removeClass("sticky-for-header");
		$('header .toolbar').removeClass('toolbar_hidden');*/
	}
});
/* --------------------------------------------------------------------- */

/*	Scroll to top
/* ---------------------------------------------------------------------- */
  $(document).ready(function(){ 
 
        $(window).scroll(function(){
            if ($(this).scrollTop() > 100) {
                $('.scroll-up').fadeIn();
            } else {
                $('.scroll-up').fadeOut();
            }
        }); 
 
        $('.scroll-up').click(function(){
            $("html, body").animate({ scrollTop: 0 }, 800);
            return false;
        });
 
    });
/* --------------------------------------------------------------------- */

/* For Tooltips 
/* ---------------------------------------------------------------------- */
$(function () {
        $(".tooltip-top").tooltip({
            placement : 'top'
        });
        $(".tooltip-right").tooltip({
            placement : 'right'
        });
        $(".tooltip-bottom").tooltip({
            placement : 'bottom'
        });
        $(".tooltip-left").tooltip({
            placement : 'left'
        });
});

/* Popovers
/* ---------------------------------------------------------------------- */
$(function () {
    $("[data-toggle=popover]") 
    .popover() 
});
/* --------------------------------------------------------------------- */

/* for Mega Menu
/* ---------------------------------------------------------------------- */
$(function() {
        window.prettyPrint && prettyPrint()
        $(document).on('click', '.megamenu .dropdown-menu', function(e) {
          e.stopPropagation()
        })
      });
/* --------------------------------------------------------------------- */

/* For dropdown menus on hover rather than click 
/* ------------------------------------------------------------- */
$(document).ready(function() {
    $('.nav li.dropdown').hover(function() {
        $(this).addClass('open');
    }, function() {
        $(this).removeClass('open');
    });
});

/* ------------------------------------------------------------- */

/* FAQ with Categories
/* ------------------------------------------------------------- */
$(document).ready(function() {
    $('.collapse').on('show.bs.collapse', function() {
        var id = $(this).attr('id');
        $('a[href="#' + id + '"]').closest('.panel-heading').addClass('active-faq');
        $('a[href="#' + id + '"] .panel-title span').html('<i class="glyphicon glyphicon-minus"></i>');
    });
    $('.collapse').on('hide.bs.collapse', function() {
        var id = $(this).attr('id');
        $('a[href="#' + id + '"]').closest('.panel-heading').removeClass('active-faq');
        $('a[href="#' + id + '"] .panel-title span').html('<i class="glyphicon glyphicon-plus"></i>');
    });
});
/* ------------------------------------------------------------- */

/* Shop List Grid View
/* ---------------------------------------------- */
$(document).ready(function() {
    $('#list').click(function(event){event.preventDefault();$('#products .item').addClass('list-group-item');});
    $('#grid').click(function(event){event.preventDefault();$('#products .item').removeClass('list-group-item');$('#products .item').addClass('grid-group-item');});
});
/* ---------------------------------------------- */

/* Stop carousel
/* ---------------------------------------------- */
$(document).ready(function() {
  $('.carousel').carousel({
	interval: false
  });
});
try {
    $(".blog-carousel-slider").owlCarousel({
        autoplay: true,
        autoplayTimeout: 10000,
        nav:true,
        autoplayHoverPause: false,
        dots: false,
        items : 1,
        singleItem : true,
        autoHeight : false,
        animateOut: 'slideOutLeft',
        // animateIn: 'slideInRight',
        loop: true
    });
} catch(err) {

}
/* ---------------------------------------------- */

/* Tooltip for Timeline
/* ---------------------------------------------- */
$(document).ready(function(){
    var my_posts = $("[rel=tooltip]");
	var i;
    for(i=0;i<my_posts.length;i++){
        the_post = $(my_posts[i]);
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
$(document).ready(function(){
    var wow = new WOW(
      {
        animateClass: 'animated',
        offset:       100,
        callback:     function(box) {
          // console.log("WOW: animating <" + box.tagName.toLowerCase() + ">")
        }
      }
    );
    wow.init();
});
/* ---------------------------------------------- */
          
    // FANCY BOX ( LIVE BOX) WITH MEDIA SUPPORT
    if($.fn.fancybox){

        $(".fancybox").fancybox();
        /*
        *  Different effects
        */

        // Change title type, overlay closing speed
        $(".fancybox-effects-a").fancybox({
            helpers: {
                title : {
                    type : 'outside'
                },
                overlay : {
                    speedOut : 0
                }
            }
        });

        // Disable opening and closing animations, change title type
        $(".fancybox-effects-b").fancybox({
            openEffect  : 'none',
            closeEffect : 'none',

            helpers : {
                title : {
                    type : 'over'
                }
            }
        });

        // Set custom style, close if clicked, change title type and overlay color
        $(".fancybox-effects-c").fancybox({
            wrapCSS    : 'fancybox-custom',
            closeClick : true,

            openEffect : 'none',

            helpers : {
                title : {
                    type : 'inside'
                },
                overlay : {
                    css : {
                        'background' : 'rgba(238,238,238,0.85)'
                    }
                }
            }
        });

        // Remove padding, set opening and closing animations, 
        // close if clicked and disable overlay
        $(".fancybox-effects-d").fancybox({
            padding: 0,

            openEffect : 'elastic',
            openSpeed  : 150,

            closeEffect : 'elastic',
            closeSpeed  : 150,

            closeClick : true,

            helpers : {
                overlay : null
            }
        });
    }


});