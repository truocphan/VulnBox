(function ($) {
    "use strict";

    $(document).ready(function () {
        headermobileAside();
    });

    /*====== mobile off canvas active ======*/
    function headermobileAside() {
        var navbarTrigger = $('.htmobile-aside-button'),
            endTrigger = $('.htmobile-aside-close'),
            container = $('.htmobile-menu-wrap'),
            wrapper = $('#page');

        wrapper.prepend('<div class="htbody-overlay"></div>');
        
        navbarTrigger.on('click', function(e) {
            e.preventDefault();
            container =  $(this).closest('.htmega-menu-container').find('.htmobile-menu-wrap');
            $(container[0]).addClass('inside');
            wrapper.addClass('htoverlay-active');
        });
        
        endTrigger.on('click', function() {
            container =  $(this).closest('.htmega-menu-container').find('.htmobile-menu-wrap');
            container.removeClass('inside');
            wrapper.removeClass('htoverlay-active');
        });
        
        $('.htbody-overlay').on('click', function() {
            container =  $(this).closest('.htmega-menu-container').find('.htmobile-menu-wrap');
            container.removeClass('inside');
            wrapper.removeClass('htoverlay-active');
        });


        var $offCanvasNav = $('.htmobile-navigation'),
        $offCanvasNavSubMenu = $offCanvasNav.find('.htmegamenu-content-wrapper,.sub-menu'),
        $offCanvasNavSubMenuExp = $offCanvasNav.find('.menu-item-has-children');
    
        /*Add Toggle Button With Off Canvas Sub Menu*/
        $offCanvasNavSubMenu.parent().prepend('<span class="menu-expand"><i class="fa fa-plus"></i></span>');
        $offCanvasNavSubMenuExp.prepend('<span class="menu-expand"><i class="fa fa-plus"></i></span>');
        
        /*Close Off Canvas Sub Menu*/
        $offCanvasNavSubMenu.slideUp();
        
        /*Category Sub Menu Toggle*/
        $offCanvasNav.on('click', 'li a, li .menu-expand', function(e) {
            var $this = $(this);

            if ( ($this.parent().attr('class').match(/\b(htmega_mega_menu)\b/)) && ($this.attr('href') === '#' || $this.hasClass('menu-expand')) ) {
                e.preventDefault();
                if ($this.siblings('div:visible').length){
                    $this.parent('li').removeClass('active');
                    $this.siblings('div').slideUp();
                } else {
                    $this.parent('li').addClass('active');
                    $this.closest('li').siblings('li').removeClass('active').find('li').removeClass('active');
                    $this.closest('li').siblings('li').find('div:visible').slideUp();
                    $this.siblings('div').slideDown();
                }
            }else{
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
            }

        });


    };
    


})(jQuery);