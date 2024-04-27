"use strict"
jQuery(document).ready(function ($) {
    
    $('#ppom_meta_sortable').sortable({
        opacity: 0.6,
        revert: true,
        cursor: 'move',
        handle: '.hndle',
        placeholder: {
            element: function (currentItem) {
                return $("<li style='background:#E7E8AD'>&nbsp;</li>")[0];
            },
            update: function (container, p) {
                return;
            }
        }
    });
    $('.sortable').disableSelection();
});