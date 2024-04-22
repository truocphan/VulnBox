"use strict";

(function ($) {
    $(document).ready(function () {
        var selectr = '.nav.nav-tabs';
        $('body').on('click', "".concat(selectr, " > li"), function (e) {
            e.preventDefault();
            var $this = $(this);
            var $nav_tabs = $(this).closest(selectr);
            var $tabs_content = $nav_tabs.next();
            if($nav_tabs.next().prop('className') !== 'tab-content') {
                $tabs_content = $nav_tabs.parent().next();
            }
            var selector = $this.find('a').attr('href');
            if (selector && selector.indexOf('#') !== 0) {
                selector = '#' + selector;
            }
            var loginId = '#stm-lms-login-modal';
            var registerId = '#stm-lms-register';

            switch (selector) {
                case '#stm-lms-register': {
                    jQuery(loginId).removeClass('active');
                    jQuery(registerId).addClass('active');
                    jQuery(loginId + ' .stm_lms_login_wrapper').css('display','none');
                    $nav_tabs.find('li').removeClass('active');
                    $(this).addClass('active');
                    break;
                }
                case '#stm-lms-login-modal': {
                    jQuery(loginId).addClass('active');
                    jQuery(registerId).removeClass('active');
                    jQuery(loginId + ' .stm_lms_login_wrapper').css('display','block');
                    $nav_tabs.find('li').removeClass('active');
                    $(this).addClass('active');
                    break;
                }
            }

            if (!$tabs_content.hasClass('tab-content')) {
                if ($tabs_content.hasClass('tab-pane')) {

                    $nav_tabs.find('li').removeClass('active');
                    $(this).addClass('active');

                }
                else {
                    return false;
                }
                return  false;
            }
            else {
                $nav_tabs.find('li').removeClass('active');
                $(this).addClass('active');
                $tabs_content.find('.tab-pane').removeClass('active');
                $tabs_content.find(selector).addClass('active');
                $('.section_items .dragArea').click(function (event) {
                    if (event.target.closest('.stm_lms_questions_v2') == null) {
                        $('.stm_lms_item_modal__inner .nav-tabs').each(function () {
                            $(this).find('li').removeClass('active')
                            $(this).find('li:first').addClass('active')
                        })
                        $('.stm_lms_item_modal__inner').each(function () {
                            $(this).find('.tab-pane').removeClass('active')
                            $(this).find('.tab-pane:first').addClass('active')
                        })
                    }
                })

            }

        });

    });
    $(window).on('load', function(){
        $('.ms_plugin_loader_bg_').fadeOut('fast');
    });
})(jQuery);
