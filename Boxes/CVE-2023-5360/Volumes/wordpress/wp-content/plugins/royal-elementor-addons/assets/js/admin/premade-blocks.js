jQuery(document).ready(function( $ ) {
	"use strict";

	// Run Macy
	var macy = Macy({
		container: $('.wpr-tplib-template-gird-inner')[0],
		waitForImages: true,
		margin: 30,
		columns: 5,
		breakAt: {
			1370: 4,
			940: 3,
			520: 2,
			400: 1
		}
	});

	setTimeout(function(){
		macy.recalculate(true);
	}, 300 );

	setTimeout(function(){
		macy.recalculate(true);
	}, 600 );


	// Filters
	$('.wpr-tplib-filters').on('click', function(){
		if ( '0' == $('.wpr-tplib-filters-list').css('opacity') ) {
			$('.wpr-tplib-filters-list').css({
				'opacity' : '1',
				'visibility' : 'visible'
			});
		} else {
			$('.wpr-tplib-filters-list').css({
				'opacity' : '0',
				'visibility' : 'hidden'
			});
		}
	});

	$('body').on('click', function(){
		if ( '1' == $('.wpr-tplib-filters-list').css('opacity') ) {
			$('.wpr-tplib-filters-list').css({
				'opacity' : '0',
				'visibility' : 'hidden'
			});
		}
	});

	$( '.wpr-tplib-filters-list ul li' ).on( 'click', function() {
		var current = $(this).attr( 'data-filter' );

		// Show/Hide
		if ( 'all' === current ) {
			$( '.wpr-tplib-template' ).parent().show();
		} else {
			$( '.wpr-tplib-template' ).parent().hide();
			$( '.wpr-tplib-template[data-filter="'+ current +'"]' ).parent().fadeIn(500);
		}

		$('.wpr-tplib-filters h3 span').attr('data-filter', current).text($(this).text());

		// Fix Grid
		macy.recalculate(true);

		setTimeout(function() {
			macy.recalculate(true);
		}, 500);
	});

	$('.wpr-tplib-filters').after('<a href="https://www.youtube.com/watch?v=sTpPq0Kal9I" class="wpr-premade-blocks-tutorial" target="_blank">How to use Premade Blocks <span class="dashicons dashicons-video-alt3"></span></a>');

	// Preview Links and Referrals
	$('.wpr-tplib-template-media').on( 'click', function() {
		var module = $(this).parent().attr('data-filter'),
			template = $(this).parent().attr('data-slug'),
			previewUrl = 'https://royal-elementor-addons.com/premade-styles/'+ $(this).parent().attr('data-preview-url'),
			proRefferal = '';

		if ( $(this).closest('.wpr-tplib-pro-wrap').length ) {
			proRefferal = '-pro';
		}

		window.open(previewUrl +'?ref=rea-plugin-backend-premade-blocks'+ proRefferal, '_blank');
	});

	$('.wpr-tplib-insert-pro').on( 'click', function() {
		var module = $(this).closest('.wpr-tplib-template').attr('data-filter');
		window.open('https://royal-elementor-addons.com/?ref=rea-plugin-backend-premade-blocks-'+ module +'-upgrade-pro#purchasepro', '_blank');
	});

}); // end dom ready