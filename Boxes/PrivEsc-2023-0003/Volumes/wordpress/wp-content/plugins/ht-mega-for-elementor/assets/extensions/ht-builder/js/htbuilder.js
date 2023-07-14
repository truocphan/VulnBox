(function($){
"use strict";

    // init Masonry
    var $grid = $('.htbuilder-post-area').masonry({
        // options
        itemSelector: '.htbuilder-post-col',
        columnWidth: '.htbuilder-post-col'
    });
    
    // layout Masonry after each image loads
    $('.htbuilder-post-area').imagesLoaded().progress( function() {
        $grid.masonry('layout');
    });

    /*====== mobile off canvas active ======*/
    function htbuildermobilemenu() {
        var navbarTrigger = $('.htbuilder-mobile-button'),
            endTrigger = $('.htbuilder-mobile-close'),
            container = $('.htbuilder-mobile-menu-area'),
            wrapper = $('body');
        
        wrapper.prepend('<div class="htbuilder-overlay"></div>');
        
        navbarTrigger.on('click', function(e) {
            e.preventDefault();
            container.addClass('inside');
            wrapper.addClass('htbuilder-overlay-active');
        });
        
        endTrigger.on('click', function() {
            container.removeClass('inside');
            wrapper.removeClass('htbuilder-overlay-active');
        });
        
        $('.htbody-overlay').on('click', function() {
            container.removeClass('inside');
            wrapper.removeClass('htbuilder-overlay-active');
        });


        var $offCanvasNav = $('.htbuilder-mobile-menu'),
        $offCanvasNavSubMenu = $offCanvasNav.find('.sub-menu');
    
        /*Add Toggle Button With Off Canvas Sub Menu*/
        $offCanvasNavSubMenu.parent().prepend('<span class="menu-expand"><i class="fa fa-plus"></i></span>');
        
        /*Close Off Canvas Sub Menu*/
        $offCanvasNavSubMenu.slideUp();
        
        /*Category Sub Menu Toggle*/
        $offCanvasNav.on('click', 'li a, li .menu-expand', function(e) {
            var $this = $(this);
            if ( ($this.parent().attr('class').match(/\b(menu-item-has-children|has-children|has-sub-menu)\b/)) && ($this.attr('href') === '#' || $this.hasClass('menu-expand')) ) {
                e.preventDefault();
                if ($this.siblings('ul:visible').length){
                    $this.parent('li').removeClass('active');
                    $this.siblings('ul').slideUp();
                } else {
                    $this.parent('li').addClass('active');
                    $this.closest('li').siblings('li').removeClass('active').find('li').removeClass('active');
                    $this.closest('li').siblings('li').find('ul:visible').slideUp();
                    $this.siblings('ul').slideDown();
                }
            }

        });
    };
    htbuildermobilemenu();

    
})(jQuery);