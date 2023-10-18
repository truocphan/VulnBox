( function( $, elementor ) {

	"use strict";

	var actionTargetProductId;

	var WprElements = {

		init: function() {

			var widgets = {
				'wpr-nav-menu.default' : WprElements.widgetNavMenu,
				'wpr-mega-menu.default' : WprElements.widgetMegaMenu,
				'wpr-onepage-nav.default' : WprElements.OnepageNav,
				'wpr-grid.default' : WprElements.widgetGrid,
				'wpr-magazine-grid.default' : WprElements.widgetMagazineGrid,
				'wpr-media-grid.default' : WprElements.widgetGrid,
				'wpr-woo-grid.default' : WprElements.widgetGrid,
				'wpr-woo-category-grid-pro.default' : WprElements.widgetGrid,
				'wpr-category-grid-pro.default' : WprElements.widgetGrid,
				'wpr-featured-media.default' : WprElements.widgetFeaturedMedia,
				'wpr-countdown.default' : WprElements.widgetCountDown,
				'wpr-google-maps.default' : WprElements.widgetGoogleMaps,
				'wpr-before-after.default' : WprElements.widgetBeforeAfter,
				'wpr-mailchimp.default' : WprElements.widgetMailchimp,
				'wpr-advanced-slider.default' : WprElements.widgetAdvancedSlider,
				'wpr-testimonial.default' : WprElements.widgetTestimonialCarousel,
				'wpr-search.default' : WprElements.widgetSearch,
				'wpr-advanced-text.default' : WprElements.widgetAdvancedText,
				'wpr-progress-bar.default' : WprElements.widgetProgressBar,
				'wpr-image-hotspots.default' : WprElements.widgetImageHotspots,
				'wpr-flip-box.default' : WprElements.widgetFlipBox,
				'wpr-content-ticker.default' : WprElements.widgetContentTicker,
				'wpr-tabs.default' : WprElements.widgetTabs,
				'wpr-content-toggle.default' : WprElements.widgetContentToogle,
				'wpr-back-to-top.default': WprElements.widgetBackToTop,
				'wpr-lottie-animations.default': WprElements.widgetLottieAnimations,
				'wpr-posts-timeline.default' : WprElements.widgetPostsTimeline,
				'wpr-sharing-buttons.default' : WprElements.widgetSharingButtons,
				'wpr-twitter-feed.default' : WprElements.widgetTwitterFeed,
				'wpr-instagram-feed.default' : WprElements.widgetInstagramFeed,
				'wpr-facebook-feed.default' : WprElements.widgetFacebookFeed,
				'wpr-flip-carousel.default': WprElements.widgetFlipCarousel,
				'wpr-feature-list.default' : WprElements.widgetFeatureList,
				'wpr-advanced-accordion.default' : WprElements.widgetAdvancedAccordion,
                'wpr-image-accordion.default' : WprElements.widgetImageAccordion,
				'wpr-product-media.default' : WprElements.widgetProductMedia,
				'wpr-product-add-to-cart.default' : WprElements.widgetProductAddToCart,
				'wpr-product-mini-cart.default' : WprElements.widgetProductMiniCart,
				'wpr-product-filters.default' : WprElements.widgetProductFilters,
				'wpr-page-cart.default' : WprElements.widgetPageCart,
				'wpr-my-account-pro.default' : WprElements.widgetPageMyAccount,
				'wpr-reading-progress-bar.default' : WprElements.widgetReadingProgressBar,
				'wpr-data-table.default' : WprElements.widgetDataTable,
				'wpr-charts.default': WprElements.widgetCharts,
				'wpr-taxonomy-list.default': WprElements.widgetTaxonomyList,
				'wpr-offcanvas.default': WprElements.widgetOffcanvas,
				'wpr-wishlist-button-pro.default' : WprElements.widgetWishlistButton,
				'wpr-mini-wishlist-pro.default' : WprElements.widgetMiniWishlist,
				'wpr-wishlist-pro.default' : WprElements.widgetWishlist,
				'wpr-compare-button-pro.default' : WprElements.widgetCompareButton,
				'wpr-mini-compare-pro.default' : WprElements.widgetMiniCompare,
				'wpr-compare-pro.default' : WprElements.widgetCompare,
				'wpr-form-builder.default': WprElements.widgetFormBuilder,
				'global': WprElements.widgetSection,

				// Single
				'wpr-post-media.default' : WprElements.widgetPostMedia,
			};
			
			$.each( widgets, function( widget, callback ) {
				window.elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widget, callback );
			});

			// Remove Mega Menu Templates from "Edit with Elementor"
			WprElements.changeAdminBarMenu();
		},

		widgetPostMedia: function( $scope ) {
			// var gallery = $scope.find( '.wpr-gallery-slider' ),
			// 	gallerySettings = gallery.attr( 'data-slick' );
			
			// gallery.animate({ 'opacity' : '1' }, 1000 );//tmp

			// if ( '[]' !== gallerySettings ) {
			// 	gallery.slick({
			// 		appendDots : $scope.find( '.wpr-gallery-slider-dots' ),
			// 		customPaging : function ( slider, i ) {
			// 			var slideNumber = (i + 1),
			// 				totalSlides = slider.slideCount;

			// 			return '<span class="wpr-gallery-slider-dot"></span>';
			// 		}
			// 	});
			// }

			// Lightbox
			var lightboxSettings = $( '.wpr-featured-media-image' ).attr( 'data-lightbox' );

			if ( typeof lightboxSettings !== typeof undefined && lightboxSettings !== false && ! WprElements.editorCheck() ) {
				var MediaWrap = $scope.find( '.wpr-featured-media-wrap' );
					lightboxSettings = JSON.parse( lightboxSettings );

				// Init Lightbox
				MediaWrap.lightGallery( lightboxSettings );

				// Show/Hide Controls
				MediaWrap.on( 'onAferAppendSlide.lg, onAfterSlide.lg', function( event, prevIndex, index ) {
					var lightboxControls = $( '#lg-actual-size, #lg-zoom-in, #lg-zoom-out, #lg-download' ),
						lightboxDownload = $( '#lg-download' ).attr( 'href' );

					if ( $( '#lg-download' ).length ) {
						if ( -1 === lightboxDownload.indexOf( 'wp-content' ) ) {
							lightboxControls.addClass( 'wpr-hidden-element' );
						} else {
							lightboxControls.removeClass( 'wpr-hidden-element' );
						}
					}

					// Autoplay Button
					if ( '' === lightboxSettings.autoplay ) {
						$( '.lg-autoplay-button' ).css({
							 'width' : '0',
							 'height' : '0',
							 'overflow' : 'hidden'
						});
					}
				});
			}
		}, // End widgetFeaturedMedia

		widgetSection: function( $scope ) {

			if ( $scope.attr('data-wpr-particles') || $scope.find('.wpr-particle-wrapper').attr('data-wpr-particles-editor') ) {
				particlesEffect();
            }

			if ( $scope.hasClass('wpr-jarallax') || $scope.hasClass('wpr-jarallax-yes') ) {
				parallaxBackground();
			}

			if ( $scope.hasClass('wpr-parallax-yes') ) {
				parallaxMultiLayer();
			}

			if ( $scope.hasClass('wpr-sticky-section-yes') ) {
				stickySection();
			}

			function stickySection() {
			    var positionType = !WprElements.editorCheck() ? $scope.attr('data-wpr-position-type') : $scope.find('.wpr-sticky-section-yes-editor').attr('data-wpr-position-type'),
				    positionLocation = !WprElements.editorCheck() ? $scope.attr('data-wpr-position-location') : $scope.find('.wpr-sticky-section-yes-editor').attr('data-wpr-position-location'),
				    positionOffset = !WprElements.editorCheck() ? $scope.attr('data-wpr-position-offset') : $scope.find('.wpr-sticky-section-yes-editor').attr('data-wpr-position-offset'),
				    viewportWidth = $('body').prop('clientWidth') + 17,
				    availableDevices = !WprElements.editorCheck() ? $scope.attr('data-wpr-sticky-devices') : $scope.find('.wpr-sticky-section-yes-editor').attr('data-wpr-sticky-devices'),
				    activeDevices = !WprElements.editorCheck() ? $scope.attr('data-wpr-active-breakpoints') : $scope.find('.wpr-sticky-section-yes-editor').attr('data-wpr-active-breakpoints'),
				    stickySectionExists = $scope.hasClass('wpr-sticky-section-yes') || $scope.find('.wpr-sticky-section-yes-editor') ? true : false,
				    positionStyle,
                    adminBarHeight,
					stickyEffectsOffset = 0,
					stickyHideDistance = 0,
					$window = $(window),
					prevScrollPos = $window.scrollTop(),
				    stickyHeaderFooter = '',
					stickyAnimation = 'none',
					stickyAnimationHide = '',
					headerFooterZIndex = !WprElements.editorCheck() ? $scope.attr('data-wpr-z-index') : $scope.find('.wpr-sticky-section-yes-editor').attr('data-wpr-z-index'),
					stickType = !WprElements.editorCheck() ? $scope.attr('data-wpr-sticky-type') : $scope.find('.wpr-sticky-section-yes-editor').attr('data-wpr-sticky-type');

					var distanceFromTop = $scope.offset().top;

					if ( $scope.data('settings').sticky_animation ) {
						stickyAnimation = $scope.data('settings').sticky_animation;
					} else {
						stickyAnimation = $scope.find('.wpr-sticky-section-yes-editor').attr('data-wpr-sticky-animation');
					}

					var stickyAnimDuration = $scope.attr('data-wpr-animation-duration') ? $scope.attr('data-wpr-animation-duration') + 's' : 500 + 's';

					// if ( WprElements.editorCheck() ) { // needs different approach
					// 	if ( $scope.next('section').length > 0 && ($scope.next('section').offset().top < ($scope.offset().top + $scope.height())) ) {
					// 		$scope.next('section').css('margin-top', $scope.offset().top + $scope.height() + 'px');
					// 	}
					// }

				if ( $scope.closest('div[data-elementor-type="wp-post"]').length > 0 ) {
					stickyHeaderFooter = $scope.closest('div[data-elementor-type="wp-post"]');
				} else if ( $scope.closest('div[data-elementor-type="header"]').length > 0 ) {
					stickyHeaderFooter = $scope.closest('div[data-elementor-type="header"]');
				} else if ( $scope.closest('div[data-elementor-type="footer"]').length > 0 ) {
					stickyHeaderFooter = $scope.closest('div[data-elementor-type="footer"]');
				}

			    if ( !$scope.find('.wpr-sticky-section-yes-editor').length) {
			        positionType = $scope.attr('data-wpr-position-type');
			        positionLocation = $scope.attr('data-wpr-position-location');
			        positionOffset = $scope.attr('data-wpr-position-offset');
			        availableDevices = $scope.attr('data-wpr-sticky-devices');
			        activeDevices = $scope.attr('data-wpr-active-breakpoints');
					headerFooterZIndex = $scope.attr('data-wpr-z-index');
			    }

                if ( 'top' === positionLocation && 'auto' === $scope.css('top') ) {
                    var offsetTop = 0;
                    $scope.css('top', 0);
                } else {
                    var offsetTop = +$scope.css('top').slice(0, -2);
                }

			    if ( 0 == availableDevices.length ) {
			        positionType = 'relative';
			    }

			    if ( WprElements.editorCheck() && availableDevices ) {
			        var attributes = $scope.find('.wpr-sticky-section-yes-editor').attr('data-wpr-sticky-devices');
			        $scope.attr('data-wpr-sticky-devices', attributes);
			        availableDevices = $scope.attr('data-wpr-sticky-devices');
			    }

			    changePositionType();
			    changeAdminBarOffset();

			    $(window).smartresize(function() { 
					distanceFromTop = $scope.offset().top;
			        viewportWidth = $('body').prop('clientWidth') + 17;
					if ( $(window).scrollTop() <= stickyEffectsOffset ) {
						changePositionType();
					}
			    });
			    
			    if (!stickySectionExists) {
			        positionStyle = 'relative';
			    }

			    function changePositionType() {
			        if ( !$scope.hasClass('wpr-sticky-section-yes') && !$scope.find('.wpr-sticky-section-yes-editor') ) {
			            positionStyle = 'relative';
			            return;
			        }

			        var checkDevices = [['mobile_sticky', 768], ['mobile_extra_sticky', 881], ['tablet_sticky', 1025], ['tablet_extra_sticky', 1201], ['laptop_sticky', 1216],  ['desktop_sticky', 2400], ['widescreen_sticky', 4000]];
			        var emptyVariables = [];

			        var checkedDevices = checkDevices.filter((item, index) => {
			            return activeDevices.indexOf(item[0]) != -1;
			        }).reverse();
			        
			        checkedDevices.forEach((device, index) => {
						if ( device[1] > viewportWidth ) {
							var deviceName = device[0].replace("_sticky", "");

							if ( 'desktop' == deviceName ) {
								if ( $scope.data('settings') ) {
									stickyEffectsOffset = distanceFromTop + $scope.data('settings').wpr_sticky_effects_offset;
								} else {
									stickyEffectsOffset = distanceFromTop + $scope.find('.wpr-sticky-section-yes-editor').attr('data-wpr-offset-settings');
								}
							} else {
								if ( $scope.data('settings') ) {
									stickyEffectsOffset = distanceFromTop + $scope.data('settings')['wpr_sticky_effects_offset_' + deviceName];
								} else {
									stickyEffectsOffset = distanceFromTop + $scope.find('.wpr-sticky-section-yes-editor').attr('data-wpr-offset-settings');
								}
							}

							if ( availableDevices.indexOf(device[0]) === -1 ) {
								positionStyle = activeDevices?.indexOf(device[0]) !== -1 ? 'relative' : (emptyVariables[index - 1] ? emptyVariables[index - 1] : positionType);
								// positionStyle = activeDevices && activeDevices.indexOf(device[0]) !== -1 ? 'static' : (emptyVariables[index - 1] ? emptyVariables[index - 1] : positionType);
								emptyVariables[index] = positionStyle;
							} else if ( availableDevices.indexOf(device[0]) !== -1 ) {
								positionStyle = positionType;
							}
						}
			        });

					var handleScroll = function() {
						let scrollPos = $window.scrollTop();
						
						if ( 'fixed' != positionStyle ) {
							if ( scrollPos > distanceFromTop) {
								applyPosition();
							} else if ( scrollPos <= distanceFromTop ) {
								$scope.css({'position': 'relative' });
							}
						}

						if ( 'relative' !== positionStyle ) {
							if ( scrollPos > stickyEffectsOffset ) {
								if ( 'yes' == $scope.data('wpr-replace-header') ) {

									if ( 'yes' === $scope.data('wpr-sticky-hide') ) {

										if ( scrollPos >= distanceFromTop ) {
											$scope.addClass('wpr-visibility-hidden');
										}

										if ( scrollPos < prevScrollPos) {
											$scope.next().addClass('wpr-hidden-header').addClass('wpr-' + stickyAnimation + '-in');
										}
									} else {
										$scope.addClass('wpr-visibility-hidden');
										$scope.next().addClass('wpr-hidden-header').addClass('wpr-' + stickyAnimation + '-in');
									}
								} else {
									$scope.addClass('wpr-sticky-header');
								}
							} else if ( scrollPos <= stickyEffectsOffset ) {
								if ( 'yes' == $scope.data('wpr-replace-header') ) {
									$scope.next().removeClass('wpr-hidden-header');
									$scope.removeClass('wpr-visibility-hidden');
									$scope.next().removeClass('wpr-' + stickyAnimation + '-in');
								} else {
									$scope.removeClass('wpr-sticky-header');
								}
							}
						}

						if ( 'yes' === $scope.data('wpr-sticky-hide') ) {
							if ( scrollPos >= distanceFromTop ) {
								if ( scrollPos < prevScrollPos ) {
									// Scrolling up
									if ( 'yes' === $scope.data('wpr-replace-header') ) {
										$scope.next().removeClass('wpr-' + stickyAnimation + '-out');
										$scope.next().addClass('wpr-' + stickyAnimation + '-in');
									} else {
										$scope.removeClass('wpr-' + stickyAnimation + '-out');
										$scope.addClass('wpr-' + stickyAnimation + '-in');
									}
								} else {
									// Scrolling down or no direction change
									if ( 'yes' === $scope.data('wpr-replace-header') ) {
										$scope.next().removeClass('wpr-' + stickyAnimation + '-in');
										$scope.next().addClass('wpr-' + stickyAnimation + '-out');
									} else {
										$scope.removeClass('wpr-' + stickyAnimation + '-in');
										$scope.addClass('wpr-' + stickyAnimation + '-out');
									}
								}
							}
							
							// else if ( scrollPos <= stickyHideDistance ) {
							// 	// At or above the top
							// 	$scope.removeClass('wpr-sticky-hide');
							// }	
						}

						// Clear any previous timeout
						clearTimeout(scrollEndTimeout);
					  
						// Set a new timeout to update prevScrollPos after 150 milliseconds (adjust the delay as needed)
						scrollEndTimeout = setTimeout(() => {
						  prevScrollPos = scrollPos;
						}, 10);
					}

					// const debouncedHandleScroll = _.debounce(handleScroll, 50);
					
					if ( 'sticky' == positionStyle ) {
						// $(window).scroll(debouncedHandleScroll);
						$(window).scroll(handleScroll);
						
						// $(window).scroll(function() {
						// 	debounceScroll(handleScroll, 50);
						// });
					} else if ( 'fixed' == positionStyle ) {
						applyPosition();
						$(window).scroll(handleScroll);
					}

					if ( 'yes' == $scope.data('wpr-replace-header') ) {
  						$scope.next().get(0).style.setProperty('--wpr-animation-duration', stickyAnimDuration);
					}
					
					function debounceScroll(method, delay) {
						clearTimeout(method._tId);
						method._tId= setTimeout(function(){
							method();
						}, delay);
					}

					let scrollEndTimeout;
			    }
			    
			    function applyPosition() {
			        var bottom = +window.innerHeight - (+$scope.css('top').slice(0, -2) + $scope.height());
			        var top = +window.innerHeight - (+$scope.css('bottom').slice(0, -2) + $scope.height());

					if ( 'yes' === $scope.data('wpr-sticky-hide') && prevScrollPos < $window.scrollTop() ) {
						return;
					}

					if ( '' == stickType ) {
						stickType = 'fixed';
					}
					
					$scope.css({'position': stickType });
			    }

			    function changeAdminBarOffset() {	
			        if ( $('#wpadminbar').length ) {
			            adminBarHeight = $('#wpadminbar').css('height').slice(0, $('#wpadminbar').css('height').length - 2);
			            // if ( 'top'  ===  positionLocation && ( 'fixed' == $scope.css('position')  || 'sticky' == $scope.css('position') ) ) {
			            if ( 'top'  ===  positionLocation && ( 'fixed' == $scope.css('position') ) ) {
			                $scope.css('top', +adminBarHeight + offsetTop + 'px');
			                $scope.css('bottom', 'auto');
			            } 
			        }
			    }
			}

			function particlesEffect() {
				var elementType = $scope.data('element_type'),
					sectionID = $scope.data('id'),
					particlesJSON = ! WprElements.editorCheck() ? $scope.attr('data-wpr-particles') : $scope.find('.wpr-particle-wrapper').attr('data-wpr-particles-editor');

				if ( ('section' === elementType || 'container' === elementType) && undefined !== particlesJSON ) {
					// Frontend
					if ( ! WprElements.editorCheck() ) {
						$scope.prepend('<div class="wpr-particle-wrapper" id="wpr-particle-'+ sectionID +'"></div>');
	
						particlesJS('wpr-particle-'+ sectionID, $scope.attr('particle-source') == 'wpr_particle_json_custom' ? JSON.parse(particlesJSON) : modifyJSON(particlesJSON));

						setTimeout(function() {
							window.dispatchEvent(new Event('resize'));
						}, 500);

						setTimeout(function() {
							window.dispatchEvent(new Event('resize'));
						}, 1500);
					// Editor
					} else {
						if ( $scope.hasClass('wpr-particle-yes') ) {
							particlesJS( 'wpr-particle-'+ sectionID, $scope.find('.wpr-particle-wrapper').attr('particle-source') == 'wpr_particle_json_custom' ? JSON.parse(particlesJSON) : modifyJSON(particlesJSON));
	
							$scope.find('.elementor-column').css('z-index', 9);
	
							setTimeout(function() {
								window.dispatchEvent(new Event('resize'));
							}, 500);

							setTimeout(function() {
								window.dispatchEvent(new Event('resize'));
							}, 1500);
						} else {
							$scope.find('.wpr-particle-wrapper').remove();
						}
					}
				}
			}

			function modifyJSON(json) {
				var wpJson = JSON.parse(json),
					particles_quantity = ! WprElements.editorCheck() ? $scope.attr('wpr-quantity') : $scope.find('.wpr-particle-wrapper').attr('wpr-quantity'),
					particles_color = ! WprElements.editorCheck() ? $scope.attr('wpr-color') || '#000000' : $scope.find('.wpr-particle-wrapper').attr('wpr-color') ? $scope.find('.wpr-particle-wrapper').attr('wpr-color') : '#000000',
					particles_speed = ! WprElements.editorCheck() ? $scope.attr('wpr-speed') : $scope.find('.wpr-particle-wrapper').attr('wpr-speed'),
					particles_shape = ! WprElements.editorCheck() ? $scope.attr('wpr-shape') : $scope.find('.wpr-particle-wrapper').attr('wpr-shape'),
					particles_size = ! WprElements.editorCheck() ? $scope.attr('wpr-size')  : $scope.find('.wpr-particle-wrapper').attr('wpr-size');
				
				wpJson.particles.size.value = particles_size;
				wpJson.particles.number.value = particles_quantity;
				wpJson.particles.color.value = particles_color;
				wpJson.particles.shape.type = particles_shape;
				wpJson.particles.line_linked.color = particles_color;
				wpJson.particles.move.speed = particles_speed;
				
				return wpJson;
			}

			function parallaxBackground() {
				if ( $scope.hasClass('wpr-jarallax-yes') ) {
					if ( ! WprElements.editorCheck() && $scope.hasClass('wpr-jarallax') ) {
						$scope.css('background-image', 'url("' + $scope.attr('bg-image') + '")');
						$scope.jarallax({
							type: $scope.attr('scroll-effect'),
							speed: $scope.attr('speed-data'),
						});
					} else if ( WprElements.editorCheck() ) {
						$scope.css('background-image', 'url("' + $scope.find('.wpr-jarallax').attr('bg-image-editor') + '")');
						$scope.jarallax({
							type: $scope.find('.wpr-jarallax').attr('scroll-effect-editor'),
							speed: $scope.find('.wpr-jarallax').attr('speed-data-editor')
						});
					}
				} 
			}

			function parallaxMultiLayer() {
				if ( $scope.hasClass('wpr-parallax-yes') ) {
					var scene = document.getElementsByClassName('wpr-parallax-multi-layer');

					var parallaxInstance = Array.from(scene).map(item => {
						return new Parallax(item, {
							invertY: item.getAttribute('direction') == 'yes' ? true : false,
							invertX: item.getAttribute('direction') == 'yes' ? true : false,
							scalarX: item.getAttribute('scalar-speed'),
							scalarY: item.getAttribute('scalar-speed'),
							hoverOnly: true,
							pointerEvents: true
						});
					});
	
					parallaxInstance.forEach(parallax => {
						parallax.friction(0.2, 0.2);
					});
				}
				if ( ! WprElements.editorCheck() ) {						
					var newScene = [];

					document.querySelectorAll('.wpr-parallax-multi-layer').forEach((element, index) => {
						element.parentElement.style.position = "relative";
						element.style.position = "absolute";
						newScene.push(element);
						element.remove();
					});

					document.querySelectorAll('.wpr-parallax-ml-children').forEach((element, index) => {
						element.style.position = "absolute";
						element.style.top = element.getAttribute('style-top');
						element.style.left = element.getAttribute('style-left');
					});

					$('.wpr-parallax-yes').each(function(index) {
						$(this).append(newScene[index]);
					});
				}
			}
		}, // end widgetSection

		widgetNavMenu: function( $scope ) {

			var $navMenu = $scope.find( '.wpr-nav-menu-container' ),
				$mobileNavMenu = $scope.find( '.wpr-mobile-nav-menu-container' );

			// Menu
			var subMenuFirst = $navMenu.find( '.wpr-nav-menu > li.menu-item-has-children' ),
				subMenuDeep = $navMenu.find( '.wpr-sub-menu li.menu-item-has-children' );

			if ( $scope.find('.wpr-mobile-toggle').length ) {
				$scope.find('a').on('click', function() {
					if (this.pathname == window.location.pathname && !($(this).parent('li').children().length > 1)) {
						$scope.find('.wpr-mobile-toggle').trigger('click');
					}
				});
			}

			if ( $navMenu.attr('data-trigger') === 'click' ) {
				// First Sub
				subMenuFirst.children('a').on( 'click', function(e) {
					var currentItem = $(this).parent(),
						childrenSub = currentItem.children('.wpr-sub-menu');

					// Reset
					subMenuFirst.not(currentItem).removeClass('wpr-sub-open');
					if ( $navMenu.hasClass('wpr-nav-menu-horizontal') || ( $navMenu.hasClass('wpr-nav-menu-vertical') && $scope.hasClass('wpr-sub-menu-position-absolute') ) ) {
						subMenuAnimation( subMenuFirst.children('.wpr-sub-menu'), false );
					}

					if ( ! currentItem.hasClass( 'wpr-sub-open' ) ) {
						e.preventDefault();
						currentItem.addClass('wpr-sub-open');
						subMenuAnimation( childrenSub, true );
					} else {
						currentItem.removeClass('wpr-sub-open');
						subMenuAnimation( childrenSub, false );
					}
				});

				// Deep Subs
				subMenuDeep.on( 'click', function(e) {
					var currentItem = $(this),
						childrenSub = currentItem.children('.wpr-sub-menu');

					// Reset
					if ( $navMenu.hasClass('wpr-nav-menu-horizontal') ) {
						subMenuAnimation( subMenuDeep.find('.wpr-sub-menu'), false );
					}

					if ( ! currentItem.hasClass( 'wpr-sub-open' ) ) {
						e.preventDefault();
						currentItem.addClass('wpr-sub-open');
						subMenuAnimation( childrenSub, true );

					} else {
						currentItem.removeClass('wpr-sub-open');
						subMenuAnimation( childrenSub, false );
					}
				});

				// Reset Subs on Document click
				$( document ).mouseup(function (e) {
					if ( ! subMenuFirst.is(e.target) && subMenuFirst.has(e.target).length === 0 ) {
						subMenuFirst.not().removeClass('wpr-sub-open');
						subMenuAnimation( subMenuFirst.children('.wpr-sub-menu'), false );
					}
					if ( ! subMenuDeep.is(e.target) && subMenuDeep.has(e.target).length === 0 ) {
						subMenuDeep.removeClass('wpr-sub-open');
						subMenuAnimation( subMenuDeep.children('.wpr-sub-menu'), false );
					}
				});
			} else {
				// Mouse Over
				subMenuFirst.on( 'mouseenter', function() {
					if ( $navMenu.hasClass('wpr-nav-menu-vertical') && $scope.hasClass('wpr-sub-menu-position-absolute') ) {
						$navMenu.find('li').not(this).children('.wpr-sub-menu').hide();
						// BUGFIX: when menu is vertical and absolute positioned, lvl2 depth sub menus wont show properly on hover
					}

					subMenuAnimation( $(this).children('.wpr-sub-menu'), true );
				});

				// Deep Subs
				subMenuDeep.on( 'mouseenter', function() {
					subMenuAnimation( $(this).children('.wpr-sub-menu'), true );
				});


				// Mouse Leave
				if ( $navMenu.hasClass('wpr-nav-menu-horizontal') ) {
					subMenuFirst.on( 'mouseleave', function() {
						subMenuAnimation( $(this).children('.wpr-sub-menu'), false );
					});

					subMenuDeep.on( 'mouseleave', function() {
						subMenuAnimation( $(this).children('.wpr-sub-menu'), false );
					});	
				} else {

					$navMenu.on( 'mouseleave', function() {
						subMenuAnimation( $(this).find('.wpr-sub-menu'), false );
					});
				}
			}


			// Mobile Menu
			var mobileMenu = $mobileNavMenu.find( '.wpr-mobile-nav-menu' );

			// Toggle Button
			$mobileNavMenu.find( '.wpr-mobile-toggle' ).on( 'click', function() {
				$(this).toggleClass('wpr-mobile-toggle-fx');

				if ( ! $(this).hasClass('wpr-mobile-toggle-open') ) {
					$(this).addClass('wpr-mobile-toggle-open');

					if ( $(this).find('.wpr-mobile-toggle-text').length ) {
						$(this).children().eq(0).hide();
						$(this).children().eq(1).show();
					}
				} else {
					$(this).removeClass('wpr-mobile-toggle-open');
					$(this).trigger('focusout');

					if ( $(this).find('.wpr-mobile-toggle-text').length ) {
						$(this).children().eq(1).hide();
						$(this).children().eq(0).show();
					}
				}

				// Show Menu
				$(this).parent().next().stop().slideToggle();

				// Fix Width
				fullWidthMobileDropdown();
			});

			// Sub Menu Class
			mobileMenu.find('.sub-menu').removeClass('wpr-sub-menu').addClass('wpr-mobile-sub-menu');

			// Sub Menu Dropdown
			mobileMenu.find('.menu-item-has-children').children('a').on( 'click', function(e) {
				var parentItem = $(this).closest('li');

				// Toggle
				if ( ! parentItem.hasClass('wpr-mobile-sub-open') ) {
					e.preventDefault();
					parentItem.addClass('wpr-mobile-sub-open');
					parentItem.children('.wpr-mobile-sub-menu').first().stop().slideDown();
				} else {
					parentItem.removeClass('wpr-mobile-sub-open');
					parentItem.children('.wpr-mobile-sub-menu').first().stop().slideUp();
				}
			});

			// Run Functions
			fullWidthMobileDropdown();

			// Run Functions on Resize
			$(window).smartresize(function() {
				fullWidthMobileDropdown();
			});

			// Full Width Dropdown
			function fullWidthMobileDropdown() {
				if ( ! $scope.hasClass( 'wpr-mobile-menu-full-width' ) || ! $scope.closest('.elementor-column').length ) {
					return;
				}

				var eColumn   = $scope.closest('.elementor-column'),
					mWidth 	  = $scope.closest('.elementor-top-section').outerWidth() - 2 * mobileMenu.offset().left,
					mPosition = eColumn.offset().left + parseInt(eColumn.css('padding-left'), 10);

				mobileMenu.css({
					'width' : mWidth +'px',
					'left' : - mPosition +'px'
				});
			}

			// Sub Menu Animation
			function subMenuAnimation( selector, show ) {
				if ( show === true ) {
					if ( $scope.hasClass('wpr-sub-menu-fx-slide') ) {
						selector.stop().slideDown();
					} else {
						selector.stop().fadeIn();
					}
				} else {
					if ( $scope.hasClass('wpr-sub-menu-fx-slide') ) {
						selector.stop().slideUp();
					} else {
						selector.stop().fadeOut();
					}
				}
			}

		}, // End widgetNavMenu

		widgetMegaMenu: function( $scope ) {

			var $navMenu = $scope.find( '.wpr-nav-menu-container' ),
				$mobileNavMenu = $scope.find( '.wpr-mobile-nav-menu-container' );

			// Menu
			var subMenuFirst = $navMenu.find( '.wpr-nav-menu > li.menu-item-has-children' ),
				subMenuDeep = $navMenu.find( '.wpr-sub-menu li.menu-item-has-children' );

			if ( $scope.find('.wpr-mobile-toggle').length ) {
				$scope.find('a').on('click', function() {
					if (this.pathname == window.location.pathname && !($(this).parent('li').children().length > 1)) {
						$scope.find('.wpr-mobile-toggle').trigger('click');
					}
				});
			}

			// Click
			if ( $navMenu.attr('data-trigger') === 'click' ) {

				// First Sub
				subMenuFirst.children('a').on( 'click', function(e) {
					var currentItem = $(this).parent(),
						childrenSub = currentItem.children('.wpr-sub-menu, .wpr-sub-mega-menu');

					// Reset
					subMenuFirst.not(currentItem).removeClass('wpr-sub-open');
					if ( $navMenu.hasClass('wpr-nav-menu-horizontal') || ( $navMenu.hasClass('wpr-nav-menu-vertical') ) ) {
						subMenuAnimation( subMenuFirst.children('.wpr-sub-menu, .wpr-sub-mega-menu'), false );
					}

					if ( ! currentItem.hasClass( 'wpr-sub-open' ) ) {
						e.preventDefault();
						currentItem.addClass('wpr-sub-open');
						subMenuAnimation( childrenSub, true );
					} else {
						currentItem.removeClass('wpr-sub-open');
						subMenuAnimation( childrenSub, false );
					}
				});

				// Deep Subs
				subMenuDeep.on( 'click', function(e) {
					var currentItem = $(this),
						childrenSub = currentItem.children('.wpr-sub-menu');

					// Reset
					if ( $navMenu.hasClass('wpr-nav-menu-horizontal') ) {
						subMenuAnimation( subMenuDeep.find('.wpr-sub-menu'), false );
					}

					if ( ! currentItem.hasClass( 'wpr-sub-open' ) ) {
						e.preventDefault();
						currentItem.addClass('wpr-sub-open');
						subMenuAnimation( childrenSub, true );

					} else {
						currentItem.removeClass('wpr-sub-open');
						subMenuAnimation( childrenSub, false );
					}
				});

				// Reset Subs on Document click
				$( document ).mouseup(function (e) {
					if ( ! subMenuFirst.is(e.target) && subMenuFirst.has(e.target).length === 0 ) {
						subMenuFirst.not().removeClass('wpr-sub-open');
						subMenuAnimation( subMenuFirst.children('.wpr-sub-menu, .wpr-sub-mega-menu'), false );
					}
					if ( ! subMenuDeep.is(e.target) && subMenuDeep.has(e.target).length === 0 ) {
						subMenuDeep.removeClass('wpr-sub-open');
						subMenuAnimation( subMenuDeep.children('.wpr-sub-menu'), false );
					}
				});
			
			// Hover
			} else {
				// Mouse Over
				subMenuFirst.on( 'mouseenter', function() {
					subMenuAnimation( $(this).children('.wpr-sub-menu, .wpr-sub-mega-menu'), true );
				});

				subMenuDeep.on( 'mouseenter', function() {
					subMenuAnimation( $(this).children('.wpr-sub-menu'), true );
				});

				// Mouse Leave
				subMenuFirst.on( 'mouseleave', function() {
					subMenuAnimation( $(this).children('.wpr-sub-menu, .wpr-sub-mega-menu'), false );
				});

				subMenuDeep.on( 'mouseleave', function() {
					subMenuAnimation( $(this).children('.wpr-sub-menu'), false );
				});	
			}

			// Mobile Menu
			var mobileMenu = $mobileNavMenu.find( '.wpr-mobile-nav-menu' );

			// Toggle Button
			$mobileNavMenu.find( '.wpr-mobile-toggle' ).on( 'click', function() {
				// Change Toggle Text
				if ( ! $(this).hasClass('wpr-mobile-toggle-open') ) {
					$(this).addClass('wpr-mobile-toggle-open');

					if ( $(this).find('.wpr-mobile-toggle-text').length ) {
						$(this).children().eq(0).hide();
						$(this).children().eq(1).show();
					}
				} else {
					$(this).removeClass('wpr-mobile-toggle-open');
					$(this).trigger('focusout');

					if ( $(this).find('.wpr-mobile-toggle-text').length ) {
						$(this).children().eq(1).hide();
						$(this).children().eq(0).show();
					}
				}

				// Show Menu
				if ( $scope.hasClass('wpr-mobile-menu-display-offcanvas') ) {
					$(this).closest('.elementor-top-section').addClass('wpr-section-full-height');
					$('body').css('overflow', 'hidden');
					$(this).parent().siblings('.wpr-mobile-mega-menu-wrap').toggleClass('wpr-mobile-mega-menu-open');
				} else {
					$(this).parent().siblings('.wpr-mobile-mega-menu-wrap').stop().slideToggle();
				}

				// Hide Off-Canvas Menu
				$scope.find('.mobile-mega-menu-close').on('click', function() {
					$(this).closest('.wpr-mobile-mega-menu-wrap').removeClass('wpr-mobile-mega-menu-open');
					$('body').css('overflow', 'visible');
					$(this).closest('.elementor-top-section').removeClass('wpr-section-full-height');
				});
				$scope.find('.wpr-mobile-mega-menu-overlay').on('click', function() {
					$(this).siblings('.wpr-mobile-mega-menu-wrap').removeClass('wpr-mobile-mega-menu-open');
					$('body').css('overflow', 'visible');
					$(this).closest('.elementor-top-section').removeClass('wpr-section-full-height');
				});

				// Fix Width
				fullWidthMobileDropdown();
			});

			// Sub Menu Class
			mobileMenu.find('.sub-menu').removeClass('wpr-sub-menu').addClass('wpr-mobile-sub-menu');

			// Add Submenu Icon
			let mobileSubIcon = mobileMenu.find('.wpr-mobile-sub-icon'),
				mobileSubIconClass = 'fas ';

			if ( $scope.hasClass('wpr-sub-icon-caret-down') ) {
				mobileSubIconClass += 'fa-caret-down';
			} else if ( $scope.hasClass('wpr-sub-icon-angle-down') ) {
				mobileSubIconClass += 'fa-angle-down';
			} else if ( $scope.hasClass('wpr-sub-icon-chevron-down') ) {
				mobileSubIconClass += 'fa-chevron-down';
			} else if ( $scope.hasClass('wpr-sub-icon-plus') ) {
				mobileSubIconClass += 'fa-plus';
			}

			mobileSubIcon.addClass(mobileSubIconClass);

			// Sub Menu Dropdown
			mobileMenu.find('.menu-item-has-children > a .wpr-mobile-sub-icon, .menu-item-has-children > a[href="#"]').on( 'click', function(e) {
				e.preventDefault();
				e.stopPropagation();

				var parentItem = $(this).closest('li.menu-item');

				// Toggle
				if ( ! parentItem.hasClass('wpr-mobile-sub-open') ) {
					e.preventDefault();
					parentItem.addClass('wpr-mobile-sub-open');

					if ( ! $scope.hasClass('wpr-mobile-menu-display-offcanvas') ) {
						$(window).trigger('resize');
						parentItem.children('.wpr-mobile-sub-menu').first().stop().slideDown();
					}

					// Mega Menu
					if ( parentItem.hasClass('wpr-mega-menu-true') ) {
						if ( parentItem.hasClass('wpr-mega-menu-ajax') && ! parentItem.find('.wpr-mobile-sub-mega-menu').find('.elementor').length  ) {
							let subIcon = parentItem.find('.wpr-mobile-sub-icon');

							$.ajax({
								type: 'GET',
								url: WprConfig.resturl + '/wprmegamenu/',
								data: {
									item_id: parentItem.data('id')
								},
								beforeSend:function() {
									subIcon.removeClass(mobileSubIconClass).addClass('fas fa-circle-notch fa-spin');
								},
								success: function( response ) {
									subIcon.removeClass('fas fa-circle-notch fa-spin').addClass(mobileSubIconClass);

									if ( $scope.hasClass('wpr-mobile-menu-display-offcanvas') ) {
										parentItem.find('.wpr-menu-offcanvas-back').after(response);
										offCanvasSubMenuAnimation( parentItem );
									} else {
										parentItem.find('.wpr-mobile-sub-mega-menu').html(response);
										parentItem.children('.wpr-mobile-sub-mega-menu').slideDown();
									}

									parentItem.find('.wpr-mobile-sub-mega-menu').find('.elementor-element').each(function() {
										elementorFrontend.elementsHandler.runReadyTrigger($(this));
									});
								}
							});
						} else {
							if ( $scope.hasClass('wpr-mobile-menu-display-offcanvas') ) {
								offCanvasSubMenuAnimation( parentItem );
							} else {
								parentItem.children('.wpr-mobile-sub-mega-menu').slideDown();
							}
						}
					} else {
						if (  $scope.hasClass('wpr-mobile-menu-display-offcanvas') ) {
							offCanvasSubMenuAnimation( parentItem );
						}	
					}
				
				} else {
					// SlideUp
					parentItem.removeClass('wpr-mobile-sub-open');

					if ( ! $scope.hasClass('wpr-mobile-menu-display-offcanvas') ) {
						parentItem.children('.wpr-mobile-sub-menu').slideUp();
						parentItem.children('.wpr-mobile-sub-mega-menu').slideUp();
					}
				}
			});

			// Off-Canvas Back Button
			$scope.find('.wpr-menu-offcanvas-back').on('click', function() {
				$(this).closest('.wpr-mobile-mega-menu').removeClass('wpr-mobile-sub-offcanvas-open');
				$(this).closest('.menu-item').removeClass('wpr-mobile-sub-open');
				$scope.find('.wpr-mobile-mega-menu-wrap').removeAttr('style');
                $scope.find('.wpr-mobile-sub-mega-menu').removeAttr('style');
			});

			// Run Functions
			MegaMenuCustomWidth();
			fullWidthMobileDropdown();

			// Run Functions on Resize
			$(window).smartresize(function() {
				MegaMenuCustomWidth();
				fullWidthMobileDropdown();
			});

			// Mega Menu Full or Custom Width
			function MegaMenuCustomWidth() {
				let megaItem = $scope.find('.wpr-mega-menu-true');

				megaItem.each(function() {
					let megaSubMenu = $(this).find('.wpr-sub-mega-menu')

					if ( $(this).hasClass('wpr-mega-menu-width-full') ) {
						megaSubMenu.css({
							'max-width' : $(window).width() +'px',
							'left' : - $scope.find('.wpr-nav-menu-container').offset().left +'px'
						});	// conditions for sticky replace needed
					} else if ( $(this).hasClass('wpr-mega-menu-width-stretch') ) {
						let elContainer = $(this).closest('.elementor-section');
							elContainer = elContainer.hasClass('elementor-inner-section') ? elContainer : elContainer.children('.elementor-container');

						let elWidgetGap = !elContainer.hasClass('elementor-inner-section') ? elContainer.find('.elementor-element-populated').css('padding') : '0';
							elWidgetGap = parseInt(elWidgetGap.replace('px', ''), 10);

						let elContainerWidth = elContainer.outerWidth() - (elWidgetGap * 2),
							offsetLeft = -($scope.offset().left - elContainer.offset().left) + elWidgetGap;

						megaSubMenu.css({
							'width' : elContainerWidth +'px',
							'left' : offsetLeft +'px'
						});
					} else if ( $(this).hasClass('wpr-mega-menu-width-custom') ) {
						megaSubMenu.css({
							'width' : $(this).data('custom-width') +'px',
						});
					} else if ( $(this).hasClass('wpr-mega-menu-width-default') && $(this).hasClass('wpr-mega-menu-pos-relative') ) {
						megaSubMenu.css({
							'width' : $(this).closest('.elementor-column').outerWidth() +'px',
						});
					}
				});
			}

			// Full Width Dropdown
			function fullWidthMobileDropdown() {
				if ( ! $scope.hasClass( 'wpr-mobile-menu-full-width' ) || ! $scope.closest('.elementor-column').length ) {
					return;
				}

				var eColumn   = $scope.closest('.elementor-column'),
					mWidth 	  = $scope.closest('.elementor-top-section').outerWidth() - 2 * mobileMenu.offset().left,
					mPosition = eColumn.offset().left + parseInt(eColumn.css('padding-left'), 10);

				mobileMenu.parent('div').css({
					'width' : mWidth +'px',
					'left' : - mPosition +'px'
				});
			}

			// Sub Menu Animation
			function subMenuAnimation( selector, show ) {
				if ( show === true ) {
					selector.stop().addClass('wpr-animate-sub');
			} else {
					selector.stop().removeClass('wpr-animate-sub');
				}
			}

			// Off-Canvas Sub Menu Animation
			function offCanvasSubMenuAnimation( selector ) {
				let title = selector.children('a').clone().children().remove().end().text();

				selector.closest('.wpr-mobile-mega-menu').addClass('wpr-mobile-sub-offcanvas-open');
				selector.find('.wpr-menu-offcanvas-back').find('h3').text(title);

				let parentItem = $scope.find('.wpr-mobile-mega-menu').children('.wpr-mobile-sub-open'),
				    subSelector = parentItem.children('ul').length ? parentItem.children('ul') : parentItem.children('.wpr-mobile-sub-mega-menu'),
				    subHeight = subSelector.outerHeight();

				if ( subHeight > $(window).height() ) {
                    $scope.find('.wpr-mobile-sub-mega-menu').not(selector.find('.wpr-mobile-sub-mega-menu')).hide();
					$scope.find('.wpr-mobile-mega-menu-wrap').css('overflow-y', 'scroll');
				}
			}

		}, // End widgetMegaMenu

		OnepageNav: function( $scope ) {
			
			// GOGA - remove extra code before update
			$(document).ready(function(){
				// Get all the links with the class "nav-link"
				var $navLinks = $scope.find( '.wpr-onepage-nav-item' ),
					scrollSpeed = parseInt( $scope.find('.wpr-onepage-nav').attr( 'data-speed' ), 10 ),
					// sections = $( '.elementor-section' );
					getSections = [];
					$navLinks.each(function() {
						getSections.push($($(this).find('a').attr('href')));
					});

					var sections = $(getSections);

				var currentUrl = window.location.href;
				var sectionId = currentUrl.split("#")[1];
				
				// Check if the URL contains a section id
				if(sectionId) {
					// Get the section element
					var $section = $("#" + sectionId);
				
					// Get the offset position of the section
					var sectionPos = $section.offset().top;
				
					// Smoothly scroll to the section
					$('html, body').animate({
					scrollTop: sectionPos
					}, scrollSpeed);
				}

				$navLinks.each(function() {
					if(currentUrl.indexOf($(this).find('a').attr('href')) !== -1){
						$(this).addClass('wpr-onepage-active-item');
					}
				});
			
				// Iterate over each link
				$navLinks.each(function() {
					// Add a click event to each link
					$(this).click(function(event) {
						event.preventDefault();
						// Remove the active class from all links
						$navLinks.removeClass('wpr-onepage-active-item');
						// Add the active class to the clicked link
						$(this).addClass('wpr-onepage-active-item');
						// Get the id of the section the link points to
						var sectionId = $(this).find('a').attr('href');
						// Get the section element
						var $section = $(sectionId);
						// Get the offset position of the section
						var sectionPos = $section.offset().top;
						// Smoothly scroll to the section
						$('html, body').animate({
							scrollTop: sectionPos
						}, scrollSpeed);
					});
				});
			
				$(window).on("scroll", function() {
					// Get the current scroll position
					var scrollPos = $(this).scrollTop();

					if ( !$.isEmptyObject(sections) ) {
						// Iterate over each section
						sections.each(function() {
							if ( $(this).length > 0 ) {
								// Get the offset position of the section
								var sectionPos = $(this).offset().top;
								// Get the height of the section
								var sectionHeight = sectionPos + $(this).outerHeight();
							
								// Check if the section is in view
								if (scrollPos >= sectionPos - 50 && scrollPos < sectionPos + sectionHeight - 50) {
								// if ( scrollPos >= sectionPos && scrollPos < sectionPos + sectionHeight ) {
									// Get the id of the section
									var sectionId = "#" + $(this).attr("id");
							
									// Remove the active class from all links
									$navLinks.removeClass("wpr-onepage-active-item");
							
									// Add the active class to the corresponding link
									$navLinks.filter(function(){
										return $(this).find('a[href=' + sectionId + ']').length;
									}).addClass("wpr-onepage-active-item");
								}
							}
						});
					}
				});
				
						// // Old Code
						// $scope.find( '.wpr-onepage-nav-item' ).on( 'click', function(event) {
						// 	event.preventDefault();

						// 	var section = $( $(this).find( 'a' ).attr( 'href' ) ),
						// 		scrollSpeed = parseInt( $(this).parent().attr( 'data-speed' ), 10 );

						// 	if (section) {
						// 		$( 'html, body' ).animate({ scrollTop: section.offset().top }, scrollSpeed );
						// 	}
						// 	// $( 'body' ).animate({ scrollTop: section.offset().top }, scrollSpeed );

						// 	// Active Class
						// 	getSectionOffset( $(window).scrollTop() );
						// });

						// // Trigger Fake Scroll
						// if ( 'yes' === $scope.find( '.wpr-onepage-nav' ).attr( 'data-highlight' ) ) {
						// 	setTimeout(function() {
						// 		$(window).scroll();
						// 	}, 10 );
						// }
						
						// // Active Class
						// $(window).scroll(function() {
						// 	getSectionOffset( $(this).scrollTop() );
						// });

						// // // Get Offset
						// // function getSectionOffset( scrollTop ) {
						// // 	if ( 'yes' !== $scope.find( '.wpr-onepage-nav' ).attr( 'data-highlight' ) ) {
						// // 		return;
						// // 	}
						// // 	// Reset Active
						// // 	$scope.find( '.wpr-onepage-nav-item' ).children( 'a' ).removeClass( 'wpr-onepage-active-item' );
			
						// // 	// Set Active
						// // 	$( '.elementor-section' ).each(function() {
						// // 		var secOffTop = $(this).offset().top,
						// // 			secOffBot = secOffTop + $(this).outerHeight();
			
						// // 		if ( scrollTop >= secOffTop && scrollTop < secOffBot ) {
						// // 			$scope.find( '.wpr-onepage-nav-item' ).children( 'a[href="#'+ $(this).attr('id') +'"]' ).addClass( 'wpr-onepage-active-item' );
						// // 		}
						// // 	});
						// // }

						// // Get Offset
						// function getSectionOffset( scrollTop ) {
						// 	if ( 'yes' !== $scope.find( '.wpr-onepage-nav' ).attr( 'data-highlight' ) ) {
						// 		return;
						// 	}
						// 	// Reset Active
						// 	$scope.find( '.wpr-onepage-nav' ).find( 'a' ).removeClass( 'wpr-onepage-active-item' );

						// 	// Set Active
						// 	$( '.elementor-section' ).each(function() {
						// 		var secOffTop = $(this).offset().top,
						// 			secOffBot = secOffTop + $(this).outerHeight();

						// 		if ( scrollTop >= secOffTop && scrollTop < secOffBot ) {
						// 			$scope.find( '.wpr-onepage-nav' ).find( 'a[href="#'+ $(this).attr('id') +'"]' ).addClass( 'wpr-onepage-active-item' );
						// 		}
						// 	});
						// }

			});

		}, // End OnepageNav

		widgetGrid: function( $scope ) {
			var iGrid = $scope.find( '.wpr-grid' );
			var loadedItems;

			if ( ! iGrid.length ) {
				return;
			}

			if ( $scope.find('.woocommerce-result-count').length ) {
				var resultCountText = $scope.find('.woocommerce-result-count').text();
				resultCountText = resultCountText.replace( /\d\u2013\d+/, '1\u2013' + $scope.find('.wpr-grid-item').length );

				$scope.find('.woocommerce-result-count').text(resultCountText);
			}

			// Settings
			var settings = iGrid.attr( 'data-settings' );
			
			if ( $scope.find(".wpr-grid-orderby form").length ) {
				var select = $scope.find(".wpr-grid-orderby form");
				$scope.find(".orderby").on("change", function () {
					select.trigger("submit");
				});
			}

			// Grid
			if ( typeof settings !== typeof undefined && settings !== false ) {
				settings = JSON.parse( iGrid.attr( 'data-settings' ) );

				// Init Functions
				isotopeLayout( settings );
				setTimeout(function() {
					isotopeLayout( settings );
				}, 100 );

				if ( WprElements.editorCheck() ) {
					setTimeout(function() {
						isotopeLayout( settings );
					}, 500 );
					setTimeout(function() {
						isotopeLayout( settings );
					}, 1000 );
				}

				$( window ).on( 'load', function() {
					setTimeout(function() {
						isotopeLayout( settings );
					}, 100 );
				});

				$(document).ready(function() {
					setTimeout(function() {
						isotopeLayout( settings );
					}, 100 );
				});

				$(window).smartresize(function(){
					setTimeout(function() {
						isotopeLayout( settings );
					}, 200 );
				});

				isotopeFilters( settings );

				var initialItems = 0;

				// Filtering Transitions
				iGrid.on( 'arrangeComplete', function( event, filteredItems ) {
					var deepLinkStager = 0,
						filterStager = 0,
						initStager = 0,
						duration = settings.animation_duration,
						filterDuration = settings.filters_animation_duration;

					if ( iGrid.hasClass( 'grid-images-loaded' ) ) {
						initStager = 0;
					} else {
						iGrid.css( 'opacity', '1' );

						// Default Animation
						if ( 'default' === settings.animation && 'default' === settings.filters_animation ) {
							return;
						}
					}

					for ( var key in filteredItems ) {
						if ( initialItems == 0 || key > initialItems - 1 ) {
							initStager += settings.animation_delay;
							$scope.find( filteredItems[key]['element'] ).find( '.wpr-grid-item-inner' ).css({
								'opacity' : '1',
								'top' : '0',
								'transform' : 'scale(1)',
								'transition' : 'all '+ duration +'s ease-in '+ initStager +'s',
							});
						}

						filterStager += settings.filters_animation_delay;
						if ( iGrid.hasClass( 'grid-images-loaded' ) ) {
							$scope.find( filteredItems[key]['element'] ).find( '.wpr-grid-item-inner' ).css({
								'transition' : 'all '+ filterDuration +'s ease-in '+ filterStager +'s',
							});
						}

						// DeepLinking
						var deepLink = window.location.hash;

						if ( deepLink.indexOf( '#filter:' ) >= 0 && deepLink.indexOf( '#filter:*' ) < 0 ) {
							deepLink = deepLink.replace( '#filter:', '' );

							$scope.find( filteredItems[key]['element'] ).filter(function() {
								if ( $(this).hasClass( deepLink ) ) {
									deepLinkStager += settings.filters_animation_delay;
									return $(this);
								}
							}).find( '.wpr-grid-item-inner' ).css({
								'transition-delay' : deepLinkStager +'s'
							});
						}
					}

					initialItems = filteredItems.length;
				});

				// iGrid.imagesLoaded().progress( function( instance, image ) {
				// });

				// Grid Images Loaded
				iGrid.imagesLoaded(function() {
					if ( '1' !== iGrid.css( 'opacity' ) ) {
						iGrid.css( 'opacity', '1' );
					}
					
					setTimeout(function() {
						iGrid.addClass( 'grid-images-loaded' );
					}, 500 );

					// Equal Heights
					setEqualHeight(settings);
				});

				// Infinite Scroll / Load More
				if ( ( 'load-more' === settings.pagination_type || 'infinite-scroll' === settings.pagination_type ) && ( $scope.find( '.wpr-grid-pagination' ).length && ! WprElements.editorCheck() ) ) {
					
					var pagination = $scope.find( '.wpr-grid-pagination' ),
						scopeClass = '.elementor-element-'+ $scope.attr( 'data-id' );

					var navClass = false,
						threshold = false;

					if ( 'infinite-scroll' === settings.pagination_type ) {
						threshold = 300;
						navClass = scopeClass +' .wpr-load-more-btn';
					}

					iGrid.infiniteScroll({
						path: scopeClass +' .wpr-grid-pagination a',
						hideNav: navClass,
						append: false,
		  				history: false,
		  				scrollThreshold: threshold,
		  				status: scopeClass +' .page-load-status',
		  				onInit: function() {
							this.on( 'load', function() {
								iGrid.removeClass( 'grid-images-loaded' );
							});
						}
					});

					// Request
					iGrid.on( 'request.infiniteScroll', function( event, path ) {
						pagination.find( '.wpr-load-more-btn' ).hide();
						pagination.find( '.wpr-pagination-loading' ).css( 'display', 'inline-block' );
					});

					// Load
					var pagesLoaded = 0;

					iGrid.on( 'load.infiniteScroll', function( event, response ) {
						pagesLoaded++;

						// get posts from response
						var items = $( response ).find( scopeClass ).find( '.wpr-grid-item' );

						if ( $scope.find('.woocommerce-result-count').length ) {
							var resultCount = $scope.find('.woocommerce-result-count').text();
							var updatedResultCount = resultCount.replace( /\d\u2013\d+/, '1\u2013' + ( $scope.find('.wpr-grid-item').length + items.length ) );
							$scope.find('.woocommerce-result-count').text(updatedResultCount);
						}
						
						iGrid.infiniteScroll( 'appendItems', items );
						iGrid.isotopewpr( 'appended', items );

						items.imagesLoaded().progress( function( instance, image ) {
							isotopeLayout( settings );

							// Fix Layout
							setTimeout(function() {
								isotopeLayout( settings );
								isotopeFilters( settings );
							}, 10 );
				
							setTimeout(function() {
								iGrid.addClass( 'grid-images-loaded' );
							}, 500 );
						});

						// Loading
						pagination.find( '.wpr-pagination-loading' ).hide();

						if ( settings.pagination_max_pages - 1 !== pagesLoaded ) {
							if ( 'load-more' === settings.pagination_type ) {
								pagination.find( '.wpr-load-more-btn' ).fadeIn();

								if ( $scope.find('.wpr-grid-filters').length ) {
									if ( '*' !== $scope.find('.wpr-active-filter').attr('data-filter') ) {
										if ( 0 < $scope.find('.wpr-active-filter').length ) {
											let dataFilterClass = $scope.find('.wpr-active-filter').attr('data-filter').substring(1);
											items.each(function() {
												if ( !$(this).hasClass(dataFilterClass) ) {
													loadedItems = false;
												} else {
													loadedItems = true;
													return false;
												}
											});
				
											if ( !loadedItems ) {
												$scope.find( '.wpr-grid' ).infiniteScroll( 'loadNextPage' );
											}
										}
									}
								}
							}
						} else {
							pagination.find( '.wpr-pagination-finish' ).fadeIn( 1000 );
							pagination.delay( 2000 ).fadeOut( 1000 );
							setTimeout(function() {
								pagination.find( '.wpr-pagination-loading' ).hide();
							}, 500 );
						}

						// Init Likes
						// No need for this anymore
						// setTimeout(function() {
						// 	postLikes( settings );
						// }, 300 );

						// Init Lightbox
						lightboxPopup( settings );

						// Fix Lightbox
						iGrid.data( 'lightGallery' ).destroy( true );
						iGrid.lightGallery( settings.lightbox );

						// Init Media Hover Link
						mediaHoverLink();

						// Init Post Sharing
						postSharing();

						lazyLoadObserver();
						// Maybe there is some other way
						window.dispatchEvent(new Event('resize'));
					});

					pagination.find( '.wpr-load-more-btn' ).on( 'click', function() {
						iGrid.infiniteScroll( 'loadNextPage' );
						return false;
					});

				}

			// Slider
			} else {
				iGrid.animate({ 'opacity': '1' }, 1000);

				settings = JSON.parse( iGrid.attr( 'data-slick' ) );

				var sliderClass = $scope.attr('class'),
					sliderColumnsDesktop = sliderClass.match(/wpr-grid-slider-columns-\d/) ? sliderClass.match(/wpr-grid-slider-columns-\d/).join().slice(-1) : 2,
					sliderColumnsWideScreen = sliderClass.match(/columns--widescreen\d/) ? sliderClass.match(/columns--widescreen\d/).join().slice(-1) : sliderColumnsDesktop,
					sliderColumnsLaptop = sliderClass.match(/columns--laptop\d/) ? sliderClass.match(/columns--laptop\d/).join().slice(-1) : sliderColumnsDesktop,
					sliderColumnsTablet = sliderClass.match(/columns--tablet\d/) ? sliderClass.match(/columns--tablet\d/).join().slice(-1) : 2,
					sliderColumnsTabletExtra = sliderClass.match(/columns--tablet_extra\d/) ? sliderClass.match(/columns--tablet_extra\d/).join().slice(-1) : sliderColumnsTablet,
					sliderColumnsMobileExtra = sliderClass.match(/columns--mobile_extra\d/) ? sliderClass.match(/columns--mobile_extra\d/).join().slice(-1) : sliderColumnsTablet,
					sliderColumnsMobile = sliderClass.match(/columns--mobile\d/) ? sliderClass.match(/columns--mobile\d/).join().slice(-1) : 1,
					sliderRows = settings.sliderRows,
					sliderSlidesToScroll = settings.sliderSlidesToScroll;

				// GOGA - add rows control and vertical gutter maybe
				iGrid.slick({
					appendDots : $scope.find( '.wpr-grid-slider-dots' ),
					rows: sliderRows,
					customPaging : function ( slider, i ) {
						var slideNumber = (i + 1),
							totalSlides = slider.slideCount;

						return '<span class="wpr-grid-slider-dot"></span>';
					},
					slidesToShow: sliderColumnsDesktop,
					responsive: [
						{
							breakpoint: 10000,
							settings: {
								slidesToShow: sliderColumnsWideScreen,
								slidesToScroll: sliderSlidesToScroll > sliderColumnsWideScreen ? 1 : sliderSlidesToScroll
							}
						},
						{
							breakpoint: 2399,
							settings: {
								slidesToShow: sliderColumnsDesktop,
								slidesToScroll: sliderSlidesToScroll > sliderColumnsDesktop ? 1 : sliderSlidesToScroll
							}
						},
						{
							breakpoint: 1221,
							settings: {
								slidesToShow: sliderColumnsLaptop,
								slidesToScroll: sliderSlidesToScroll > sliderColumnsLaptop ? 1 : sliderSlidesToScroll
							}
						},
						{
							breakpoint: 1200,
							settings: {
								slidesToShow: sliderColumnsTabletExtra,
								slidesToScroll: sliderSlidesToScroll > sliderColumnsTabletExtra ? 1 : sliderSlidesToScroll
							}
						},
						{
							breakpoint: 1024,
							settings: {
								slidesToShow: sliderColumnsTablet,
								slidesToScroll: sliderSlidesToScroll > sliderColumnsTablet ? 1 : sliderSlidesToScroll
							}
						},
						{
							breakpoint: 880,
							settings: {
								slidesToShow: sliderColumnsMobileExtra,
							 	slidesToScroll: sliderSlidesToScroll > sliderColumnsMobileExtra ? 1 : sliderSlidesToScroll
							}
						},
						{
							breakpoint: 768,
							settings: {
								slidesToShow: sliderColumnsMobile,
								slidesToScroll: sliderSlidesToScroll > sliderColumnsMobile ? 1 : sliderSlidesToScroll
							}
						}
					],
				});

				var gridNavPrevArrow = $scope.find('.wpr-grid-slider-prev-arrow');
				var gridNavNextArrow = $scope.find('.wpr-grid-slider-next-arrow');

				if ( gridNavPrevArrow.length > 0 && gridNavNextArrow.length > 0 ) {
					var positionSum = gridNavPrevArrow.position().left * -2;
					if ( positionSum > 0 ) {
						$(window).on('load', function() {
							if ( $(window).width() <= ($scope.outerWidth() + gridNavPrevArrow.outerWidth() + gridNavNextArrow.outerWidth() + positionSum) ) {
								gridNavPrevArrow.addClass('wpr-adjust-slider-prev-arrow');
								gridNavNextArrow.addClass('wpr-adjust-slider-next-arrow');
							}
						});
		
						$(window).smartresize(function() {
							if ( $(window).width() <= ($scope.outerWidth() + gridNavPrevArrow.outerWidth() + gridNavNextArrow.outerWidth() + positionSum) ) {
								gridNavPrevArrow.addClass('wpr-adjust-slider-prev-arrow');
								gridNavNextArrow.addClass('wpr-adjust-slider-next-arrow');
							} else {
								gridNavPrevArrow.removeClass('wpr-adjust-slider-prev-arrow');
								gridNavNextArrow.removeClass('wpr-adjust-slider-next-arrow');
							}
						});
					}
				}

				// Adjust Horizontal Pagination
				if ( $scope.find( '.slick-dots' ).length && $scope.hasClass( 'wpr-grid-slider-dots-horizontal') ) {
					// Calculate Width
					var dotsWrapWidth = $scope.find( '.slick-dots li' ).outerWidth() * $scope.find( '.slick-dots li' ).length - parseInt( $scope.find( '.slick-dots li span' ).css( 'margin-right' ), 10 );

					// on Load
					if ( $scope.find( '.slick-dots' ).length ) {
						$scope.find( '.slick-dots' ).css( 'width', dotsWrapWidth );
					}


					$(window).smartresize(function() {
						setTimeout(function() {
							// Calculate Width
							var dotsWrapWidth = $scope.find( '.slick-dots li' ).outerWidth() * $scope.find( '.slick-dots li' ).length - parseInt( $scope.find( '.slick-dots li span' ).css( 'margin-right' ), 10 );

							// Set Width
							$scope.find( '.slick-dots' ).css( 'width', dotsWrapWidth );
						}, 300 );
					});
				}
			}
			
			checkWishlistAndCompare();
			addRemoveCompare();
			addRemoveWishlist();
	
			var mutationObserver = new MutationObserver(function(mutations) {
				// checkWishlistAndCompare();
				addRemoveCompare();
				addRemoveWishlist();
			});

			mutationObserver.observe($scope[0], {
				childList: true,
				subtree: true,
			});

			// Add To Cart AJAX
			if ( iGrid.find( '.wpr-grid-item-add-to-cart' ).length ) {
				var addCartIcon = iGrid.find( '.wpr-grid-item-add-to-cart' ).find( 'i' ),
					addCartIconClass = addCartIcon.attr( 'class' );

				if ( addCartIcon.length ) {
					addCartIconClass = addCartIconClass.substring( addCartIconClass.indexOf('fa-'), addCartIconClass.length );
				}

				$( 'body' ).on( 'adding_to_cart', function( ev, button, data ) {
					button.fadeTo( 'slow', 0 );
				});

				$( 'body' ).on( 'added_to_cart', function(ev, fragments, hash, button) {
					var product_id = button.data('product_id');

					button.next().fadeTo( 700, 1 );

					button.css('display', 'none');

					if ( 'sidebar' === button.data('atc-popup') ) {
						if ( $('.wpr-mini-cart-toggle-wrap a').length ) {
							$('.wpr-mini-cart-toggle-wrap a').each(function() {
								if ( 'none' === $(this).closest('.wpr-mini-cart-inner').find('.wpr-mini-cart').css('display') ) {
									$(this).trigger('click');
								}
							});
						}
					} else if ( 'popup' === button.data('atc-popup') ) {
						var popupItem = button.closest('.wpr-grid-item'),
							popupText = popupItem.find('.wpr-grid-item-title').text(),
							popupLink = button.next().attr('href'),
							popupImageSrc = popupItem.find('.wpr-grid-image-wrap').length ? popupItem.find('.wpr-grid-image-wrap').data('src') : '',
							popupAnimation = button.data('atc-animation'),
							fadeOutIn = button.data('atc-fade-out-in'),
							animTime = button.data('atc-animation-time'),
							popupImage,
							animationClass = 'wpr-added-to-cart-default',
							removeAnimationClass;

						if ( 'slide-left' === popupAnimation ) {
							animationClass = 'wpr-added-to-cart-slide-in-left';
							removeAnimationClass = 'wpr-added-to-cart-slide-out-left';
						} else if ( 'scale-up' === popupAnimation ) {
							animationClass = 'wpr-added-to-cart-scale-up';
							removeAnimationClass = 'wpr-added-to-cart-scale-down';
						} else if ( 'skew' === popupAnimation ) {
							animationClass = 'wpr-added-to-cart-skew';
							removeAnimationClass = 'wpr-added-to-cart-skew-off';
						} else if ( 'fade' === popupAnimation ) {
							animationClass = 'wpr-added-to-cart-fade';
							removeAnimationClass = 'wpr-added-to-cart-fade-out';
						} else {
							removeAnimationClass = 'wpr-added-to-cart-popup-hide';
						}

						if ( '' !== popupImageSrc ) {
							popupImage = '<div class="wpr-added-tc-popup-img"><img src='+popupImageSrc+' alt="" /></div>';
						} else {
							popupImage = '';
						}
						
						if ( !($scope.find('.wpr-grid').find('#wpr-added-to-cart-'+product_id).length > 0) ) {
							$scope.find('.wpr-grid').append('<div id="wpr-added-to-cart-'+product_id+'" class="wpr-added-to-cart-popup ' + animationClass + '">'+ popupImage +'<div class="wpr-added-tc-title"><p>'+ popupText + ' ' + WprConfig.addedToCartText +'</p><p><a href='+popupLink+'>'+ WprConfig.viewCart +'</a></p></div></div>');

							setTimeout(() => {
								$(this).find('#wpr-added-to-cart-'+product_id).addClass(removeAnimationClass);
								setTimeout(() => {
									$(this).find('#wpr-added-to-cart-'+product_id).remove();
								}, animTime * 1000);
							}, fadeOutIn * 1000);
						}
					}

					if ( addCartIcon.length ) {
						button.find( 'i' ).removeClass( addCartIconClass ).addClass( 'fa-check' );
						setTimeout(function() {
							button.find( 'i' ).removeClass( 'fa-check' ).addClass( addCartIconClass );
						}, 3500 );
					}
				});
			}

			// Init Post Sharing
			postSharing();

			// Post Sharing
			function postSharing() {
				if ( $scope.find( '.wpr-sharing-trigger' ).length ) {
					var sharingTrigger = $scope.find( '.wpr-sharing-trigger' ),
						sharingInner = $scope.find( '.wpr-post-sharing-inner' ),
						sharingWidth = 5;

					// Calculate Width
					sharingInner.first().find( 'a' ).each(function() {
						sharingWidth += $(this).outerWidth() + parseInt( $(this).css('margin-right'), 10 );
					});

					// Calculate Margin
					var sharingMargin = parseInt( sharingInner.find( 'a' ).css('margin-right'), 10 );

					// Set Positions
					if ( 'left' === sharingTrigger.attr( 'data-direction') ) {
						// Set Width
						sharingInner.css( 'width', sharingWidth +'px' );

						// Set Position
						sharingInner.css( 'left', - ( sharingMargin + sharingWidth ) +'px' );
					} else if ( 'right' === sharingTrigger.attr( 'data-direction') ) {
						// Set Width
						sharingInner.css( 'width', sharingWidth +'px' );

						// Set Position
						sharingInner.css( 'right', - ( sharingMargin + sharingWidth ) +'px' );
					} else if ( 'top' === sharingTrigger.attr( 'data-direction') ) {
						// Set Margins
						sharingInner.find( 'a' ).css({
							'margin-right' : '0',
							'margin-top' : sharingMargin +'px'
						});

						// Set Position
						sharingInner.css({
							'top' : -sharingMargin +'px',
							'left' : '50%',
							'-webkit-transform' : 'translate(-50%, -100%)',
							'transform' : 'translate(-50%, -100%)'
						});
					} else if ( 'right' === sharingTrigger.attr( 'data-direction') ) {
						// Set Width
						sharingInner.css( 'width', sharingWidth +'px' );

						// Set Position
						sharingInner.css({
							'left' : sharingMargin +'px',
							// 'bottom' : - ( sharingInner.outerHeight() + sharingTrigger.outerHeight() ) +'px',
						});
					} else if ( 'bottom' === sharingTrigger.attr( 'data-direction') ) {
						// Set Margins
						sharingInner.find( 'a' ).css({
							'margin-right' : '0',
							'margin-bottom' : sharingMargin +'px'
						});

						// Set Position
						sharingInner.css({
							'bottom' : -sharingMargin +'px',
							'left' : '50%',
							'-webkit-transform' : 'translate(-50%, 100%)',
							'transform' : 'translate(-50%, 100%)'
						});
					}

					if ( 'click' === sharingTrigger.attr( 'data-action' ) ) {
						sharingTrigger.on( 'click', function() {
							var sharingInner = $(this).next();

							if ( 'hidden' === sharingInner.css( 'visibility' ) ) {
								sharingInner.css( 'visibility', 'visible' );
								sharingInner.find( 'a' ).css({
									'opacity' : '1',
									'top' : '0'
								});

								setTimeout( function() {
									sharingInner.find( 'a' ).addClass( 'wpr-no-transition-delay' );
								}, sharingInner.find( 'a' ).length * 100 );
							} else {
								sharingInner.find( 'a' ).removeClass( 'wpr-no-transition-delay' );

								sharingInner.find( 'a' ).css({
									'opacity' : '0',
									'top' : '-5px'
								});
								setTimeout( function() {
									sharingInner.css( 'visibility', 'hidden' );
								}, sharingInner.find( 'a' ).length * 100 );
							}
						});
					} else {
						sharingTrigger.on( 'mouseenter', function() {
							var sharingInner = $(this).next();

							sharingInner.css( 'visibility', 'visible' );
							sharingInner.find( 'a' ).css({
								'opacity' : '1',
								'top' : '0',
							});
							
							setTimeout( function() {
								sharingInner.find( 'a' ).addClass( 'wpr-no-transition-delay' );
							}, sharingInner.find( 'a' ).length * 100 );
						});
						$scope.find( '.wpr-grid-item-sharing' ).on( 'mouseleave', function() {
							var sharingInner = $(this).find( '.wpr-post-sharing-inner' );

							sharingInner.find( 'a' ).removeClass( 'wpr-no-transition-delay' );

							sharingInner.find( 'a' ).css({
								'opacity' : '0',
								'top' : '-5px'
							});
							setTimeout( function() {
								sharingInner.css( 'visibility', 'hidden' );
							}, sharingInner.find( 'a' ).length * 100 );
						});
					}
				}				
			}

			// Init Media Hover Link
			mediaHoverLink();

			// Media Hover Link
			function mediaHoverLink() {
				// console.log(iGrid.find('.wpr-grid-media-wrap').find('img').length);
				if ( 'yes' === $scope.find('.wpr-grid-image-wrap').data('img-on-hover') ) {
					var img;
					var thisImgSrc;
					let secondaryImg;
					iGrid.find('.wpr-grid-media-wrap').on('mouseover', function() {
							// img = $(this).find( 'img' );
							// thisImgSrc = img.attr('src');
							
							// secondaryImg = $(this).find('.wpr-grid-image-wrap').data('src-secondary');
							
							// if ( isValidHttpUrl(secondaryImg) ) {
							// 	img.attr( 'src', secondaryImg );
							// }
							
							if ( $(this).find('img:nth-of-type(2)').attr('src') !== undefined && $(this).find('img:nth-of-type(2)').attr('src') !== '' ) {
								// $(this).find('img:first-of-type').fadeOut(0).addClass('wpr-hidden-img');
								// $(this).find('img:nth-of-type(2)').fadeIn(500).removeClass('wpr-hidden-img');
								$(this).find('img:first-of-type').addClass('wpr-hidden-img');
								$(this).find('img:nth-of-type(2)').removeClass('wpr-hidden-img');
							}
						});
		
						iGrid.find('.wpr-grid-media-wrap').on('mouseleave', function() {
							// if ( secondaryImg == img.attr('src') ) {
							// 	img.attr('src', thisImgSrc);
							// }
		
							if ( $(this).find('img:nth-of-type(2)').attr('src') !== undefined && $(this).find('img:nth-of-type(2)').attr('src') !== '' ) {
								// $(this).find('img:nth-of-type(2)').fadeOut(0).addClass('wpr-hidden-img');
								// $(this).find('img:first-of-type').fadeIn(500).removeClass('wpr-hidden-img');
								$(this).find('img:nth-of-type(2)').addClass('wpr-hidden-img');
								$(this).find('img:first-of-type').removeClass('wpr-hidden-img');
							}
						});
				}
				
				function isValidHttpUrl(string) {
					let url;
					try {
					  url = new URL(string);
					} catch (_) {
					  return false;
					}
					return url.protocol === "http:" || url.protocol === "https:";
				}

				if ( 'yes' === iGrid.find( '.wpr-grid-media-wrap' ).attr( 'data-overlay-link' ) && ! WprElements.editorCheck() ) {
					iGrid.find( '.wpr-grid-media-wrap' ).css('cursor', 'pointer');

					iGrid.find( '.wpr-grid-media-wrap' ).on( 'click', function( event ) {
						var targetClass = event.target.className;

						if ( -1 !== targetClass.indexOf( 'inner-block' ) || -1 !== targetClass.indexOf( 'wpr-cv-inner' ) || 
							 -1 !== targetClass.indexOf( 'wpr-grid-media-hover' ) ) {
							event.preventDefault();

							var itemUrl = $(this).find( '.wpr-grid-media-hover-bg' ).attr( 'data-url' ),
								itemUrl = itemUrl.replace('#new_tab', '');

							if ( '_blank' === iGrid.find( '.wpr-grid-item-title a' ).attr('target') ) {
								window.open(itemUrl, '_blank').focus();
							} else {
								window.location.href = itemUrl;
							}
						}
					});
				}				
			}

			// Init Lightbox
			if ( !$scope.hasClass('elementor-widget-wpr-woo-category-grid-pro') && !$scope.hasClass('elementor-widget-wpr-category-grid-pro') ) {
				lightboxPopup( settings );
			}

			// Lightbox Popup
			function lightboxPopup( settings ) {
				if ( -1 === $scope.find( '.wpr-grid-item-lightbox' ).length ) {
					return;
				}

				var lightbox = $scope.find( '.wpr-grid-item-lightbox' ),
					lightboxOverlay = lightbox.find( '.wpr-grid-lightbox-overlay' ).first();

				// Set Src Attributes
				lightbox.each(function() {
					var source = $(this).find('.inner-block > span').attr( 'data-src' ),
						gridItem = $(this).closest( 'article' ).not('.slick-cloned');

					if ( ! iGrid.hasClass( 'wpr-media-grid' ) ) {
						gridItem.find( '.wpr-grid-image-wrap' ).attr( 'data-src', source );
					}

					var dataSource = gridItem.find( '.wpr-grid-image-wrap' ).attr( 'data-src' );

					if ( typeof dataSource !== typeof undefined && dataSource !== false ) {
						if ( -1 === dataSource.indexOf( 'wp-content' ) ) {
							gridItem.find( '.wpr-grid-image-wrap' ).attr( 'data-iframe', 'true' );
						}
					}
				});

				// Init Lightbox
				iGrid.lightGallery( settings.lightbox );

				// Fix LightGallery Thumbnails
				iGrid.on('onAfterOpen.lg',function() {
					if ( $('.lg-outer').find('.lg-thumb-item').length ) {
					    $('.lg-outer').find('.lg-thumb-item').each(function() {
					    	var imgSrc = $(this).find('img').attr('src'),
					    		newImgSrc = imgSrc,
					    		extIndex = imgSrc.lastIndexOf('.'),
					    		imgExt = imgSrc.slice(extIndex),
					    		cropIndex = imgSrc.lastIndexOf('-'),
					    		cropSize = /\d{3,}x\d{3,}/.test(imgSrc.substring(extIndex,cropIndex)) ? imgSrc.substring(extIndex,cropIndex) : false;
					    	
					    	if ( 42 <= imgSrc.substring(extIndex,cropIndex).length ) {
					    		cropSize = '';
					    	}

					    	if ( cropSize !== '' ) {
					    		if ( false !== cropSize ) {
					    			newImgSrc = imgSrc.replace(cropSize, '-150x150');
					    		} else {
					    			newImgSrc = [imgSrc.slice(0, extIndex), '-150x150', imgSrc.slice(extIndex)].join('');
					    		}
					    	}

					    	// Change SRC
					    	$(this).find('img').attr('src', newImgSrc);
					    });
				    }
				});

				// Show/Hide Controls
				$scope.find( '.wpr-grid' ).on( 'onAferAppendSlide.lg, onAfterSlide.lg', function( event, prevIndex, index ) {
					var lightboxControls = $( '#lg-actual-size, #lg-zoom-in, #lg-zoom-out, #lg-download' ),
						lightboxDownload = $( '#lg-download' ).attr( 'href' );

					if ( $( '#lg-download' ).length ) {
						if ( -1 === lightboxDownload.indexOf( 'wp-content' ) ) {
							lightboxControls.addClass( 'wpr-hidden-element' );
						} else {
							lightboxControls.removeClass( 'wpr-hidden-element' );
						}
					}

					// Autoplay Button
					if ( '' === settings.lightbox.autoplay ) {
						$( '.lg-autoplay-button' ).css({
							 'width' : '0',
							 'height' : '0',
							 'overflow' : 'hidden'
						});
					}
				});

				// Overlay
				if ( lightboxOverlay.length ) {
					$scope.find( '.wpr-grid-media-hover-bg' ).after( lightboxOverlay.remove() );

					$scope.find( '.wpr-grid-lightbox-overlay' ).on( 'click', function() {
						if ( ! WprElements.editorCheck() ) {
							$(this).closest( 'article' ).find( '.wpr-grid-image-wrap' ).trigger( 'click' );
						} else {
							alert( 'Lightbox is Disabled in the Editor!' );
						}
					});
				} else {
					lightbox.find( '.inner-block > span' ).on( 'click', function() {
						if ( ! WprElements.editorCheck() ) {
							var imageWrap = $(this).closest( 'article' ).find( '.wpr-grid-image-wrap' );
								imageWrap.trigger( 'click' );
						} else {
							alert( 'Lightbox is Disabled in the Editor!' );
						}
					});
				}
			}

			// Init Likes
			postLikes( settings );

			// Likes
			function postLikes( settings ) {
				if ( ! $scope.find( '.wpr-post-like-button' ).length ) {
					return;
				}
				
				$scope.on('click', '.wpr-post-like-button', function(e) {
					e.preventDefault();

					var current = $(this);

					if ( '' !== current.attr( 'data-post-id' ) ) {

					$.ajax({
						type: 'POST',
						url: current.attr( 'data-ajax' ),
						data: {
							action : 'wpr_likes_init',
							post_id : current.attr( 'data-post-id' ),
							nonce : current.attr( 'data-nonce' )
						},
						beforeSend:function() {
							current.fadeTo( 500, 0.5 );
						},	
						success: function( response ) {
							// Get Icon
							var iconClass = current.attr( 'data-icon' );

							// Get Count
							var countHTML = response.count;

							if ( '' === countHTML.replace(/<\/?[^>]+(>|$)/g, "") ) {
								countHTML = '<span class="wpr-post-like-count">'+ current.attr( 'data-text' ) +'</span>';

								if ( ! current.hasClass( 'wpr-likes-zero' ) ) {
									current.addClass( 'wpr-likes-zero' );
								}
							} else {
								current.removeClass( 'wpr-likes-zero' );
							}

							// Update Icon
							if ( current.hasClass( 'wpr-already-liked' ) ) {
								current.prop( 'title', 'Like' );
								current.removeClass( 'wpr-already-liked' );
								current.html( '<i class="'+ iconClass.replace( 'fas', 'far' ) +'"></i>' + countHTML );
							} else {
								current.prop( 'title', 'Unlike' );
								current.addClass( 'wpr-already-liked' );
								current.html( '<i class="'+ iconClass.replace( 'far', 'fas' ) +'"></i>' + countHTML );
							}

							current.fadeTo( 500, 1 );
						}
					});

					}

					return false;
				});
			}

			// Isotope Layout
			function isotopeLayout( settings ) {
				var grid = $scope.find( '.wpr-grid' ),
					item = grid.find( '.wpr-grid-item' ),
					itemVisible = item.filter( ':visible' ),
					layout = settings.layout,
					defaultLayout = settings.layout,
					mediaAlign = settings.media_align,
					mediaWidth = settings.media_width,
					mediaDistance = settings.media_distance,
					columns = 3,
					columnsMobile = 1,
					columnsMobileExtra,
					columnsTablet = 2,
					columnsTabletExtra,
					columnsDesktop = parseInt(settings.columns_desktop, 10),
					columnsLaptop,
					columnsWideScreen,
					gutterHr = settings.gutter_hr,
					gutterVr = settings.gutter_vr,
					gutterHrMobile = settings.gutter_hr_mobile,
					gutterVrMobile = settings.gutter_vr_mobile,
					gutterHrMobileExtra = settings.gutter_hr_mobile_extra,
					gutterVrMobileExtra = settings.gutter_vr_mobile_extra,
					gutterHrTablet = settings.gutter_hr_tablet,
					gutterVrTablet = settings.gutter_vr_tablet,
					gutterHrTabletExtra = settings.gutter_hr_tablet_extra,
					gutterVrTabletExtra = settings.gutter_vr_tablet_extra,
					gutterHrWideScreen = settings.gutter_hr_widescreen,
					gutterVrWideScreen = settings.gutter_vr_widescreen,
					gutterHrLaptop = settings.gutter_hr_laptop,
					gutterVrLaptop = settings.gutter_vr_laptop,
					contWidth = grid.width() + gutterHr - 0.3,
					// viewportWidth = $( 'body' ).prop( 'clientWidth' ),
					viewportWidth = $(window).outerWidth(),
					defaultLayout,
					transDuration = 400;

				// Get Responsive Columns
				var prefixClass = $scope.attr('class'),
					prefixClass = prefixClass.split(' ');

				for ( var i=0; i < prefixClass.length - 1; i++ ) {

					if ( -1 !== prefixClass[i].search(/mobile\d/) ) {
						columnsMobile = prefixClass[i].slice(-1);
					}

					if ( -1 !== prefixClass[i].search(/mobile_extra\d/) ) {
						columnsMobileExtra = prefixClass[i].slice(-1);
					}

					if ( -1 !== prefixClass[i].search(/tablet\d/) ) {
						columnsTablet = prefixClass[i].slice(-1);
					}

					if ( -1 !== prefixClass[i].search(/tablet_extra\d/) ) {
						columnsTabletExtra = prefixClass[i].slice(-1);
					}

					if ( -1 !== prefixClass[i].search(/widescreen\d/) ) {
						columnsWideScreen = prefixClass[i].slice(-1);
					}

					if ( -1 !== prefixClass[i].search(/laptop\d/) ) {
						columnsLaptop = prefixClass[i].slice(-1);
					}
				}

				var MobileResp = +elementorFrontend.config.responsive.breakpoints.mobile.value;
				var MobileExtraResp = +elementorFrontend.config.responsive.breakpoints.mobile_extra.value;
				var TabletResp = +elementorFrontend.config.responsive.breakpoints.tablet.value;
				var TabletExtraResp = +elementorFrontend.config.responsive.breakpoints.tablet_extra.value;
				var LaptopResp = +elementorFrontend.config.responsive.breakpoints.laptop.value;
				var wideScreenResp = +elementorFrontend.config.responsive.breakpoints.widescreen.value;

				var activeBreakpoints = elementorFrontend.config.responsive.activeBreakpoints;

				// Mobile
				if ( MobileResp >= viewportWidth && activeBreakpoints.mobile != null ) {
					columns = columnsMobile;
					gutterHr = gutterHrMobile;
					gutterVr = gutterVrMobile;

				// Mobile Extra
				} else if ( MobileExtraResp >= viewportWidth && activeBreakpoints.mobile_extra != null ) {
					columns = (columnsMobileExtra) ? columnsMobileExtra : columnsTablet;
					gutterHr = gutterHrMobileExtra;
					gutterVr = gutterVrMobileExtra;

				// Tablet
				} else if ( TabletResp >= viewportWidth && activeBreakpoints.tablet != null ) {
					columns = columnsTablet;
					gutterHr = gutterHrTablet;
					gutterVr = gutterVrTablet;

				// Tablet Extra
				} else if ( TabletExtraResp >= viewportWidth && activeBreakpoints.tablet_extra != null ) {
					columns = (columnsTabletExtra) ? columnsTabletExtra : columnsTablet;
					gutterHr = gutterHrTabletExtra;
					gutterVr = gutterVrTabletExtra;

				// Laptop
				} else if ( LaptopResp >= viewportWidth && activeBreakpoints.laptop != null ) {
					columns = (columnsLaptop) ? columnsLaptop : columnsDesktop;
					gutterHr = gutterHrLaptop;
					gutterVr = gutterVrLaptop;

				// Desktop
				} else if ( wideScreenResp > viewportWidth ) {
					columns = columnsDesktop;
					gutterHr = settings.gutter_hr;
					gutterVr = settings.gutter_vr;
				}  else {
					columns = (columnsWideScreen) ? columnsWideScreen : columnsDesktop;
					gutterHr = gutterHrWideScreen;
					gutterVr = gutterVrWideScreen;
				}

				// Limit Columns for Higher Screens
				if ( columns > 8 ) {
					columns = 8;
				}

				if ( 'string' == typeof(columns) && -1 !== columns.indexOf('pro') ) {
					columns = 3;
				}

				contWidth = grid.width() + gutterHr - 0.3;

				// Calculate Item Width
				item.outerWidth( Math.floor( contWidth / columns - gutterHr ) );

				// Set Vertical Gutter
				item.css( 'margin-bottom', gutterVr +'px' );

				// Reset Vertical Gutter for 1 Column Layout
				if ( 1 === columns ) {
					item.last().css( 'margin-bottom', '0' );
				}

				// add last row & make all post equal height
				var maxTop = -1;
				itemVisible.each(function ( index ) {

					// define
					var thisHieght = $(this).outerHeight(),
						thisTop = parseInt( $(this).css( 'top' ) , 10 );

					// determine last row
					if ( thisTop > maxTop ) {
						maxTop = thisTop;
					}
					
				});

				if ( 'fitRows' === layout ) {
					itemVisible.each(function() {
						if ( parseInt( $(this).css( 'top' ) ) === maxTop  ) {
							$(this).addClass( 'rf-last-row' );
						}
					});
				}

				// List Layout
				if ( 'list' === layout ) {
					var imageHeight = item.find( '.wpr-grid-image-wrap' ).outerHeight();
						item.find( '.wpr-grid-item-below-content' ).css( 'min-height', imageHeight +'px' );

					if ( $( 'body' ).prop( 'clientWidth' ) < 480 ) {

						item.find( '.wpr-grid-media-wrap' ).css({
							'float' : 'none',
							'width' : '100%'
						});

						item.find( '.wpr-grid-item-below-content' ).css({
							'float' : 'none',
							'width' : '100%',
						});

						item.find( '.wpr-grid-image-wrap' ).css( 'padding', '0' );

						item.find( '.wpr-grid-item-below-content' ).css( 'min-height', '0' );

						if ( 'zigzag' === mediaAlign ) {
							item.find( '[class*="elementor-repeater-item"]' ).css( 'text-align', 'center' );
						}

					} else {

						if ( 'zigzag' !== mediaAlign ) {

							item.find( '.wpr-grid-media-wrap' ).css({
								'float' : mediaAlign,
								'width' : mediaWidth +'%'
							});

							var listGutter = 'left' === mediaAlign ? 'margin-right' : 'margin-left';
								item.find( '.wpr-grid-media-wrap' ).css( listGutter, mediaDistance +'px' );

							item.find( '.wpr-grid-item-below-content' ).css({
								'float' : mediaAlign,
								'width' : 'calc((100% - '+ mediaWidth +'%) - '+ mediaDistance +'px)',
							});

						// Zig-zag
						} else {
							// Even
							item.filter(':even').find( '.wpr-grid-media-wrap' ).css({
								'float' : 'left',
								'width' : mediaWidth +'%'
							});
							item.filter(':even').find( '.wpr-grid-item-below-content' ).css({
								'float' : 'left',
								'width' : 'calc((100% - '+ mediaWidth +'%) - '+ mediaDistance +'px)',
							});
							item.filter(':even').find( '.wpr-grid-media-wrap' ).css( 'margin-right', mediaDistance +'px' );

							// Odd
							item.filter(':odd').find( '.wpr-grid-media-wrap' ).css({
								'float' : 'right',
								'width' : mediaWidth +'%'
							});
							item.filter(':odd').find( '.wpr-grid-item-below-content' ).css({
								'float' : 'right',
								'width' : 'calc((100% - '+ mediaWidth +'%) - '+ mediaDistance +'px)',
							});
							item.filter(':odd').find( '.wpr-grid-media-wrap' ).css( 'margin-left', mediaDistance +'px' );

							// Fix Elements Align
							if ( ! grid.hasClass( 'wpr-grid-list-ready' ) ) {
								item.each( function( index ) {
									var element = $(this).find( '[class*="elementor-repeater-item"]' );

									if ( index % 2 === 0 ) {
										element.each(function() {
											if ( ! $(this).hasClass( 'wpr-grid-item-align-center' ) ) {
												if ( 'none' === $(this).css( 'float' ) ) {
													$(this).css( 'text-align', 'left' );
												} else {
													$(this).css( 'float', 'left' );
												}

												var inner = $(this).find( '.inner-block' );
											}
										});
									} else {
										element.each(function( index ) {
											if ( ! $(this).hasClass( 'wpr-grid-item-align-center' ) ) {
												if ( 'none' === $(this).css( 'float' ) ) {
													$(this).css( 'text-align', 'right' );
												} else {
													$(this).css( 'float', 'right' );
												}

												var inner = $(this).find( '.inner-block' );

												if ( '0px' !== inner.css( 'margin-left' ) ) {
													inner.css( 'margin-right', inner.css( 'margin-left' ) );
													inner.css( 'margin-left', '0' );
												}

												// First Item
												if ( 0 === index ) {
													if ( '0px' !== inner.css( 'margin-right' ) ) {
														inner.css( 'margin-left', inner.css( 'margin-right' ) );
														inner.css( 'margin-right', '0' );
													}
												}
											}
										});
									}
								});

							}

							setTimeout(function() {
								if ( ! grid.hasClass( 'wpr-grid-list-ready' ) ) {
									grid.addClass( 'wpr-grid-list-ready' );
								}
							}, 500 );
						}

					}
				}

				// Set Layout
				defaultLayout = layout;
				if ( 'list' === layout ) {
					layout = 'fitRows';
				}

				// No Transition
				if ( 'default' !== settings.filters_animation ) {
					transDuration = 0;
				}

				// Run Isotope
				var iGrid = grid.isotopewpr({
					layoutMode: layout,
					masonry: {
						// columnWidth: contWidth / columns,
						gutter: gutterHr
					},
					fitRows: {
						// columnWidth: contWidth / columns,
						gutter: gutterHr
					},
					transitionDuration: transDuration,
  					percentPosition: true
				});
			}

			// Set equal height to all grid-items
			function setEqualHeight( settings ) {
				let iGrid = $scope.find( '.wpr-grid' ),
					items = iGrid.children('article'),
					columns = Math.floor(iGrid.outerWidth() / items.outerWidth());

				if ( 'fitRows' === settings.layout && columns > 1 ) {
					let maxHeight = Math.max.apply(null, items.map(function(item) {
						return $(this).outerHeight();
					}));

					items.each(function() {
						$(this).css('height', maxHeight + 'px');
					});
                    
                    if ( 'yes' === settings.stick_last_element_to_bottom ) {
                        $scope.addClass('wpr-grid-last-element-yes');
                    }
				}
			}

			function lazyLoadObserver() {
				setTimeout(function() {
					let lazyLoadObserver = new IntersectionObserver((entries, observer) => {
						entries.forEach(entry => {
							if(entry.isIntersecting && entry.target.src.includes('icon-256x256')) {
								setTimeout(function() {
									entry.target.src = entry.target.parentElement.dataset.src;
									entry.target.classList.toggle('wpr-hidden-image');
									$(window).trigger('resize');
								}, 100);
							}
						});
					}, {});
					
					$scope.find('.wpr-grid-image-wrap img:first-of-type').each(function() {
						lazyLoadObserver.observe($(this)[0]);
					});
				}, 100);
			}

			lazyLoadObserver();

			// Isotope Filters
			function isotopeFilters( settings ) {

				// Count
				if ( 'yes' === settings.filters_count ) {
					$scope.find( '.wpr-grid-filters a, .wpr-grid-filters span' ).each(function() {
						if ( '*' === $(this).attr( 'data-filter') ) {
							$(this).find( 'sup' ).text( $scope.find( '.wpr-grid-filters' ).next().find('article').length );
						} else {
							$(this).find( 'sup' ).text( $scope.find( $(this).attr( 'data-filter' ) ).length );
						}
					});
				}

				// Return if Disabled
				if ( 'yes' === settings.filters_linkable ) {
					return;
				}

				// Deeplinking on Load
				if ( 'yes' === settings.deeplinking ) {
					var deepLink = window.location.hash.replace( '#filter:', '.' );

					if ( window.location.hash.match( '#filter:all' ) ) {
						deepLink = '*';
					}

					var activeFilter = $scope.find( '.wpr-grid-filters span[data-filter="'+ deepLink +'"]:not(.wpr-back-filter)' ),
						activeFilterWrap = activeFilter.parent();

					// Sub Filters
					if ( 'parent' === activeFilter.parent().attr( 'data-role' ) ) {
						if ( activeFilterWrap.parent( 'ul' ).find( 'ul[data-parent="'+ deepLink +'"]').length ) {
							activeFilterWrap.parent( 'ul' ).children( 'li' ).css( 'display', 'none' );
							activeFilterWrap.siblings( 'ul[data-parent="'+ deepLink +'"]' ).css( 'display', 'block' );
						}
					} else if ( 'sub' === activeFilter.parent().attr( 'data-role' ) ) {
						activeFilterWrap.closest( '.wpr-grid-filters' ).children( 'li' ).css( 'display', 'none' );
						activeFilterWrap.parent( 'ul' ).css( 'display', 'inline-block' );
					}

					// Active Filter Class
					$scope.find( '.wpr-grid-filters span' ).removeClass( 'wpr-active-filter' );
					activeFilter.addClass( 'wpr-active-filter' );

					$scope.find( '.wpr-grid' ).isotopewpr({ filter: deepLink });

					// Fix Lightbox
					if ( '*' !== deepLink ) {
						settings.lightbox.selector = deepLink +' .wpr-grid-image-wrap';
					} else {
						settings.lightbox.selector = ' .wpr-grid-image-wrap';
					}

					lightboxPopup( settings );
				}

				// Hide Empty Filters
				if ( 'yes' === settings.filters_hide_empty ) {
					$scope.find( '.wpr-grid-filters span' ).each(function() {
						var searchClass = $(this).attr( 'data-filter' );

						if ( '*' !== searchClass ) {
							if ( 0 === iGrid.find(searchClass).length ) {
								$(this).parent( 'li' ).addClass( 'wpr-hidden-element' );
							} else {
								$(this).parent( 'li' ).removeClass( 'wpr-hidden-element' );
							}
						}
					});
				}

				// Set a Default Filter
				if ( !$scope.hasClass('elementor-widget-wpr-woo-category-grid-pro') && !$scope.hasClass('elementor-widget-wpr-category-grid-pro') ) {
					if ( '' !== settings.filters_default_filter ) {
						setTimeout(function() {
							$scope.find( '.wpr-grid-filters' ).find('span[data-filter*="-'+ settings.filters_default_filter +'"]')[0].click();
						}, 100)
					}
				}

				// Click Event
				$scope.find( '.wpr-grid-filters span' ).on( 'click', function() {
					initialItems = 0;
					var filterClass = $(this).data( 'filter' ),
						filterWrap = $(this).parent( 'li' ),
						filterRole = filterWrap.attr( 'data-role' );

					// Active Filter Class
					$scope.find( '.wpr-grid-filters span' ).removeClass( 'wpr-active-filter' );
					$(this).addClass( 'wpr-active-filter' );

					// Sub Filters
					if ( 'parent' === filterRole ) {
						if ( filterWrap.parent( 'ul' ).find( 'ul[data-parent="'+ filterClass +'"]').length ) {
							filterWrap.parent( 'ul' ).children( 'li' ).css( 'display', 'none' );
							filterWrap.siblings( 'ul[data-parent="'+ filterClass +'"]' ).css( 'display', 'block' );
						}
					} else if ( 'back' === filterRole ) {
						filterWrap.closest( '.wpr-grid-filters' ).children( 'li' ).css( 'display', 'inline-block' );
						filterWrap.parent().css( 'display', 'none' );
					}

					// Deeplinking
					if ( 'yes' === settings.deeplinking ) {
						var filterHash = '#filter:'+ filterClass.replace( '.', '' );

						if ( '*' === filterClass ) {
							filterHash = '#filter:all';
						}

						window.location.href = window.location.pathname + window.location.search + filterHash;
					}

					// Infinite Scroll
					if ( 'infinite-scroll' === settings.pagination_type ) {
						if ( 0 === iGrid.find($(this).attr('data-filter')).length ) {
							$scope.find( '.wpr-grid' ).infiniteScroll( 'loadNextPage' );
						}
					}

					// Load More
					if ( 'load-more' === settings.pagination_type ) {
						if ( 0 === iGrid.find($(this).attr('data-filter')).length ) {
							$scope.find( '.wpr-grid' ).infiniteScroll( 'loadNextPage' );
						}
					}

					// Filtering Animation
					if ( 'default' !== settings.filters_animation ) {
						$scope.find( '.wpr-grid-item-inner' ).css({
							'opacity' : '0',
							'transition' : 'none'
						});
					}

					if ( 'fade-slide' === settings.filters_animation ) {
						$scope.find( '.wpr-grid-item-inner' ).css( 'top', '20px' );
					} else if ( 'zoom' === settings.filters_animation ) {
						$scope.find( '.wpr-grid-item-inner' ).css( 'transform', 'scale(0.01)' );
					} else {
						$scope.find( '.wpr-grid-item-inner' ).css({
							'top' : '0',
							'transform' : 'scale(1)'
						});
					}

					// Filter Grid Items
					$scope.find( '.wpr-grid' ).isotopewpr({ filter: filterClass });

					// Fix Lightbox
					if ( '*' !== filterClass ) {
						settings.lightbox.selector = filterClass +' .wpr-grid-image-wrap';
					} else {
						settings.lightbox.selector = ' .wpr-grid-image-wrap';
					}

					// Destroy Lightbox
					iGrid.data('lightGallery').destroy( true );
					// Init Lightbox
					iGrid.lightGallery( settings.lightbox );
				});

			}

			// function checkWishlistAndCompare() {
			// 	if ( iGrid.find('.wpr-wishlist-add').length ) {
			// 		iGrid.find('.wpr-wishlist-add').each(function() {
			// 			var wishlistBtn = $(this);
			// 			$.ajax({
			// 				url: WprConfig.ajaxurl,
			// 				type: 'POST',
			// 				data: {
			// 					action: 'check_product_in_wishlist',
			// 					product_id: wishlistBtn.data('product-id')
			// 				},
			// 				success: function(response) {
			// 					if ( true == response ) {
			// 						if ( !wishlistBtn.hasClass('wpr-button-hidden') ) {
			// 							wishlistBtn.addClass('wpr-button-hidden');
			// 						}

			// 						if ( wishlistBtn.next().hasClass('wpr-button-hidden') ) {
			// 							wishlistBtn.next().removeClass('wpr-button-hidden');
			// 						}
			// 					}
			// 				},
			// 				error: function(error) {
			// 					console.log(error);
			// 				}
			// 			});
			// 		});
			// 	}

			// 	if ( iGrid.find('.wpr-compare-add').length ) {
			// 		iGrid.find('.wpr-compare-add').each(function() {
			// 			var compareBtn = $(this);
			// 			$.ajax({
			// 				url: WprConfig.ajaxurl,
			// 				type: 'POST',
			// 				data: {
			// 					action: 'check_product_in_compare',
			// 					product_id: compareBtn.data('product-id')
			// 				},
			// 				success: function(response) {
			// 					if ( true == response ) {
			// 						if ( !compareBtn.hasClass('wpr-button-hidden') ) {
			// 							compareBtn.addClass('wpr-button-hidden');
			// 						}

			// 						if ( compareBtn.next().hasClass('wpr-button-hidden') ) {
			// 							compareBtn.next().removeClass('wpr-button-hidden');
			// 						}
			// 					}
			// 				},
			// 				error: function(error) {
			// 					console.log(error);
			// 				}
			// 			});
			// 		});
			// 	}
			// }

			function checkWishlistAndCompare() {
				var wishlistArray;
				
				if ( iGrid.find('.wpr-wishlist-add').length ) {

					$.ajax({
							url: WprConfig.ajaxurl,
							type: 'POST',
							data: {
								action: 'check_product_in_wishlist_grid',
							},
							success: function(response) {
									wishlistArray = response;
							}
					});
					
					
					iGrid.find('.wpr-wishlist-add').each(function() {
						var wishlistBtn = $(this);
						
						if ( $.inArray(wishlistBtn.data('product-id'), wishlistArray) !== -1 ) {
							if ( !wishlistBtn.hasClass('wpr-button-hidden') ) {
								wishlistBtn.addClass('wpr-button-hidden');
							}

							if ( wishlistBtn.next().hasClass('wpr-button-hidden') ) {
								wishlistBtn.next().removeClass('wpr-button-hidden');
							}
						}
					});
				}

				if ( iGrid.find('.wpr-compare-add').length > 0 ) {
					var compareArray = [];
					
					$.ajax({
							url: WprConfig.ajaxurl,
							type: 'POST',
							data: {
								action: 'check_product_in_compare_grid',
							},
							success: function(response) {
								compareArray = response;
							},
							error: function(error) {
								console.log(error);
							}
					});
				
					
					iGrid.find('.wpr-compare-add').each(function() {
						var compareBtn = $(this);
						
						if ( $.inArray(compareBtn.data('product-id'), compareArray) !== -1 ) {
							if ( !compareBtn.hasClass('wpr-button-hidden') ) {
								compareBtn.addClass('wpr-button-hidden');
							}

							if ( compareBtn.next().hasClass('wpr-button-hidden') ) {
								compareBtn.next().removeClass('wpr-button-hidden');
							}
						}
					});
					
				}
			}

			function addRemoveCompare() {
				if ( iGrid.find('.wpr-compare-add').length ) {
					$scope.find('.wpr-compare-add').click(function(e) {
						e.preventDefault();
						var event_target = $(this);
						var product_id = $(this).data('product-id');

						event_target.fadeTo(500, 0);

						$.ajax({
							url: WprConfig.ajaxurl,
							type: 'POST',
							data: {
								action: 'add_to_compare',
								product_id: product_id
							},
							success: function() {
								$scope.find('.wpr-compare-add[data-product-id="' + product_id + '"]').hide();
								$scope.find('.wpr-compare-remove[data-product-id="' + product_id + '"]').show();
								$scope.find('.wpr-compare-remove[data-product-id="' + product_id + '"]').fadeTo(500, 1);
								WprElements.changeActionTargetProductId(product_id);
								$(document).trigger('added_to_compare');
	
								if ( 'sidebar' === event_target.data('atcompare-popup') ) {
									// GOGA - configure after adding compare dropdown functinality
									if ( $('.wpr-compare-toggle-btn').length ) {
										$('.wpr-compare-toggle-btn').each(function() {
											if ( 'none' === $(this).next('.wpr-compare').css('display') ) {
												$(this).trigger('click');
											}
										});
									}
								} else if ( 'popup' === event_target.data('atcompare-popup') ) {
									// Popup Link needs wishlist
									var popupItem = event_target.closest('.wpr-grid-item'),
										popupText = popupItem.find('.wpr-grid-item-title').text(),
										popupLink = WprConfig.comparePageURL,
										popupTarget = 'yes' == event_target.data('open-in-new-tab') ? '_blank' : '_self',
										popupImageSrc = popupItem.find('.wpr-grid-image-wrap').length ? popupItem.find('.wpr-grid-image-wrap').data('src') : '',
										popupAnimation = event_target.data('atcompare-animation'),
										fadeOutIn = event_target.data('atcompare-fade-out-in'),
										animTime = event_target.data('atcompare-animation-time'),
										popupImage,
										animationClass = 'wpr-added-to-compare-default',
										removeAnimationClass;
			
									if ( 'slide-left' === popupAnimation ) {
										animationClass = 'wpr-added-to-compare-slide-in-left';
										removeAnimationClass = 'wpr-added-to-compare-slide-out-left';
									} else if ( 'scale-up' === popupAnimation ) {
										animationClass = 'wpr-added-to-compare-scale-up';
										removeAnimationClass = 'wpr-added-to-compare-scale-down';
									} else if ( 'skew' === popupAnimation ) {
										animationClass = 'wpr-added-to-compare-skew';
										removeAnimationClass = 'wpr-added-to-compare-skew-off';
									} else if ( 'fade' === popupAnimation ) {
										animationClass = 'wpr-added-to-compare-fade';
										removeAnimationClass = 'wpr-added-to-compare-fade-out';
									} else {
										removeAnimationClass = 'wpr-added-to-compare-popup-hide';
									}
			
									if ( '' !== popupImageSrc ) {
										popupImage = '<div class="wpr-added-tcomp-popup-img"><img src='+popupImageSrc+' alt="" /></div>';
									} else {
										popupImage = '';
									}
									
									if ( !($scope.find('.wpr-grid').find('#wpr-added-to-comp-'+product_id).length > 0) ) {
										$scope.find('.wpr-grid').append('<div id="wpr-added-to-comp-'+product_id+'" class="wpr-added-to-compare-popup ' + animationClass + '">'+ popupImage +'<div class="wpr-added-tc-title"><p>'+ popupText +' was added to Compare</p><p><a target='+ popupTarget +' href='+popupLink+'>View Compare</a></p></div></div>');
			
										setTimeout(() => {
											$scope.find('#wpr-added-to-comp-'+product_id).addClass(removeAnimationClass);
											setTimeout(() => {
												$scope.find('#wpr-added-to-comp-'+product_id).remove();
											}, animTime * 1000);
										}, fadeOutIn * 1000);
									}
								}
							},
							error: function(response) {
								var error_message = response.responseJSON.message;
								// Display error message
								alert(error_message);
							}
						});
					});
	
					$scope.find('.wpr-compare-remove').click(function(e) {
						e.preventDefault();
						var product_id = $(this).data('product-id');
						$(this).fadeTo(500, 0);

						$.ajax({
							url: WprConfig.ajaxurl,
							type: 'POST',
							data: {
								action: 'remove_from_compare',
								product_id: product_id
							},
							success: function() {
								$scope.find('.wpr-compare-remove[data-product-id="' + product_id + '"]').hide();
								$scope.find('.wpr-compare-add[data-product-id="' + product_id + '"]').show();
								$scope.find('.wpr-compare-add[data-product-id="' + product_id + '"]').fadeTo(500, 1);
								WprElements.changeActionTargetProductId(product_id);
								$(document).trigger('removed_from_compare');
							}
						});
					});
	
					$(document).on('removed_from_compare', function() {
						$scope.find('.wpr-compare-remove[data-product-id="' + actionTargetProductId + '"]').hide();
						$scope.find('.wpr-compare-add[data-product-id="' + actionTargetProductId + '"]').show();
						$scope.find('.wpr-compare-add[data-product-id="' + actionTargetProductId + '"]').fadeTo(500, 1);
					});
	
				}
			}

			function addRemoveWishlist() {
				let isPopupActive = false;
				if ( iGrid.find('.wpr-wishlist-add').length ) {
					$scope.find('.wpr-wishlist-add').click(function(e) {
						e.preventDefault();
						var event_target = $(this);
						var product_id = $(this).data('product-id');

						event_target.fadeTo(500, 0);

						$.ajax({
							url: WprConfig.ajaxurl,
							type: 'POST',
							data: {
								action: 'add_to_wishlist',
								product_id: product_id
							},
							success: function() {
								$scope.find('.wpr-wishlist-add[data-product-id="' + product_id + '"]').hide();
								$scope.find('.wpr-wishlist-remove[data-product-id="' + product_id + '"]').show();
								$scope.find('.wpr-wishlist-remove[data-product-id="' + product_id + '"]').fadeTo(500, 1);
								WprElements.changeActionTargetProductId(product_id);
								$(document).trigger('added_to_wishlist');
	
								if ( 'sidebar' === event_target.data('atw-popup') ) {
									// GOGA - configure after adding wishlist dropdown functinality
									if ( $('.wpr-wishlist-toggle-btn').length ) {
										$('.wpr-wishlist-toggle-btn').each(function() {
											if ( 'none' === $(this).next('.wpr-wishlist').css('display') ) {
												$(this).trigger('click');
											}
										});
									}
								} else if ( 'popup' === event_target.data('atw-popup') ) {
									// Popup Link needs wishlist
									var popupItem = event_target.closest('.wpr-grid-item'),
										popupText = popupItem.find('.wpr-grid-item-title').text(),
										popupLink = WprConfig.wishlistPageURL,
										popupTarget = 'yes' == event_target.data('open-in-new-tab') ? '_blank' : '_self',
										popupImageSrc = popupItem.find('.wpr-grid-image-wrap').length ? popupItem.find('.wpr-grid-image-wrap').data('src') : '',
										popupAnimation = event_target.data('atw-animation'),
										fadeOutIn = event_target.data('atw-fade-out-in'),
										animTime = event_target.data('atw-animation-time'),
										popupImage,
										animationClass = 'wpr-added-to-wishlist-default',
										removeAnimationClass;
			
									if ( 'slide-left' === popupAnimation ) {
										animationClass = 'wpr-added-to-wishlist-slide-in-left';
										removeAnimationClass = 'wpr-added-to-wishlist-slide-out-left';
									} else if ( 'scale-up' === popupAnimation ) {
										animationClass = 'wpr-added-to-wishlist-scale-up';
										removeAnimationClass = 'wpr-added-to-wishlist-scale-down';
									} else if ( 'skew' === popupAnimation ) {
										animationClass = 'wpr-added-to-wishlist-skew';
										removeAnimationClass = 'wpr-added-to-wishlist-skew-off';
									} else if ( 'fade' === popupAnimation ) {
										animationClass = 'wpr-added-to-wishlist-fade';
										removeAnimationClass = 'wpr-added-to-wishlist-fade-out';
									} else {
										removeAnimationClass = 'wpr-added-to-wishlist-popup-hide';
									}
			
									if ( '' !== popupImageSrc ) {
										popupImage = '<div class="wpr-added-tw-popup-img"><img src='+popupImageSrc+' alt="" /></div>';
									} else {
										popupImage = '';
									}
									if (!isPopupActive) {
										isPopupActive = true;
										
										if ( !($scope.find('.wpr-grid').find('#wpr-added-to-wish-'+product_id).length > 0) ) {
											$scope.find('.wpr-grid').append('<div id="wpr-added-to-wish-'+product_id+'" class="wpr-added-to-wishlist-popup ' + animationClass + '">'+ popupImage +'<div class="wpr-added-tw-title"><p>'+ popupText +' was added to Wishlist</p><p><a target="'+ popupTarget +'" href='+popupLink+'>View Wishlist</a></p></div></div>');
				
											setTimeout(() => {
												$scope.find('#wpr-added-to-wish-'+product_id).addClass(removeAnimationClass);
												setTimeout(() => {
													$scope.find('#wpr-added-to-wish-'+product_id).remove();
												}, animTime * 1000);
											}, fadeOutIn * 1000);
										}
									}
								}
							},
							error: function(response) {
								var error_message = response.responseJSON.message;
								// Display error message
								alert(error_message);
							}
						});
					});
	
					$scope.find('.wpr-wishlist-remove').on('click', function(e) {
						e.preventDefault();
						var product_id = $(this).data('product-id');

						$(this).fadeTo(500, 0);

						$.ajax({
							url: WprConfig.ajaxurl,
							type: 'POST',
							data: {
								action: 'remove_from_wishlist',
								product_id: product_id
							},
							success: function() {
								$scope.find('.wpr-wishlist-remove[data-product-id="' + product_id + '"]').hide();
								$scope.find('.wpr-wishlist-add[data-product-id="' + product_id + '"]').show();
								$scope.find('.wpr-wishlist-add[data-product-id="' + product_id + '"]').fadeTo(500, 1);
								WprElements.changeActionTargetProductId(product_id);
								$(document).trigger('removed_from_wishlist');
							}
						});
					});
	
					$(document).on('removed_from_wishlist', function() {
						$scope.find('.wpr-wishlist-remove[data-product-id="' + actionTargetProductId + '"]').hide();
						$scope.find('.wpr-wishlist-add[data-product-id="' + actionTargetProductId + '"]').show();
						$scope.find('.wpr-wishlist-add[data-product-id="' + actionTargetProductId + '"]').fadeTo(500, 1);
					});
	
				}	
			}

		}, // End widgetGrid

		widgetMagazineGrid: function( $scope ) {
			// Settings
			var iGrid = $scope.find( '.wpr-magazine-grid-wrap' ),
				settings = iGrid.attr( 'data-slick' ),
				dataSlideEffect = iGrid.attr('data-slide-effect');

			// Slider
			if ( typeof settings !== typeof undefined && settings !== false ) {
				iGrid.slick({
					fade: 'fade' === dataSlideEffect ? true : false
				});
			}

			$(document).ready(function() {
				iGrid.css('opacity', 1);
			});

			var iGridLength = iGrid.find('.wpr-mgzn-grid-item').length;

			// $(window).smartresize(function() {
			// 	if (window.matchMedia("(max-width: 767px)").matches) { // If media query matches
			// 		iGrid.find('.wpr-magazine-grid.wpr-mgzn-grid-3-h')[0].style.gridTemplateRows = 'repeat('+ iGridLength +', 1fr)';
			// 	} else {
			// 		iGrid.find('.wpr-magazine-grid.wpr-mgzn-grid-3-h').removeAttr('style');
			// 	}
			// });

			// Media Hover Link
			if ( 'yes' === iGrid.find( '.wpr-grid-media-wrap' ).attr( 'data-overlay-link' ) && ! WprElements.editorCheck() ) {
				iGrid.find( '.wpr-grid-media-wrap' ).css('cursor', 'pointer');
				
				iGrid.find( '.wpr-grid-media-wrap' ).on( 'click', function( event ) {
					var targetClass = event.target.className;

					if ( -1 !== targetClass.indexOf( 'inner-block' ) || -1 !== targetClass.indexOf( 'wpr-cv-inner' ) || 
						 -1 !== targetClass.indexOf( 'wpr-grid-media-hover' ) ) {
						event.preventDefault();

						var itemUrl = $(this).find( '.wpr-grid-media-hover-bg' ).attr( 'data-url' );
						
						// GOGA - leave if necessary
						if ( iGrid.find( '.wpr-grid-item-title a' ).length ) {
							if ( '_blank' === iGrid.find( '.wpr-grid-item-title a' ).attr('target') ) {
								window.open(itemUrl, '_blank').focus();
							} else {
								window.location.href = itemUrl;
							}
						}
					}
				});
			}

			// Sharing
			if ( $scope.find( '.wpr-sharing-trigger' ).length ) {
				var sharingTrigger = $scope.find( '.wpr-sharing-trigger' ),
					sharingInner = $scope.find( '.wpr-post-sharing-inner' ),
					sharingWidth = 5;

				// Calculate Width
				sharingInner.first().find( 'a' ).each(function() {
					sharingWidth += $(this).outerWidth() + parseInt( $(this).css('margin-right'), 10 );
				});

				// Calculate Margin
				var sharingMargin = parseInt( sharingInner.find( 'a' ).css('margin-right'), 10 );

				// Set Positions
				if ( 'left' === sharingTrigger.attr( 'data-direction') ) {
					// Set Width
					sharingInner.css( 'width', sharingWidth +'px' );

					// Set Position
					sharingInner.css( 'left', - ( sharingMargin + sharingWidth ) +'px' );
				} else if ( 'right' === sharingTrigger.attr( 'data-direction') ) {
					// Set Width
					sharingInner.css( 'width', sharingWidth +'px' );

					// Set Position
					sharingInner.css( 'right', - ( sharingMargin + sharingWidth ) +'px' );
				} else if ( 'top' === sharingTrigger.attr( 'data-direction') ) {
					// Set Margins
					sharingInner.find( 'a' ).css({
						'margin-right' : '0',
						'margin-top' : sharingMargin +'px'
					});

					// Set Position
					sharingInner.css({
						'top' : -sharingMargin +'px',
						'left' : '50%',
						'-webkit-transform' : 'translate(-50%, -100%)',
						'transform' : 'translate(-50%, -100%)'
					});
				} else if ( 'right' === sharingTrigger.attr( 'data-direction') ) {
					// Set Width
					sharingInner.css( 'width', sharingWidth +'px' );

					// Set Position
					sharingInner.css({
						'left' : sharingMargin +'px',
						// 'bottom' : - ( sharingInner.outerHeight() + sharingTrigger.outerHeight() ) +'px',
					});
				} else if ( 'bottom' === sharingTrigger.attr( 'data-direction') ) {
					// Set Margins
					sharingInner.find( 'a' ).css({
						'margin-right' : '0',
						'margin-bottom' : sharingMargin +'px'
					});

					// Set Position
					sharingInner.css({
						'bottom' : -sharingMargin +'px',
						'left' : '50%',
						'-webkit-transform' : 'translate(-50%, 100%)',
						'transform' : 'translate(-50%, 100%)'
					});
				}

				if ( 'click' === sharingTrigger.attr( 'data-action' ) ) {
					sharingTrigger.on( 'click', function() {
						var sharingInner = $(this).next();

						if ( 'hidden' === sharingInner.css( 'visibility' ) ) {
							sharingInner.css( 'visibility', 'visible' );
							sharingInner.find( 'a' ).css({
								'opacity' : '1',
								'top' : '0'
							});

							setTimeout( function() {
								sharingInner.find( 'a' ).addClass( 'wpr-no-transition-delay' );
							}, sharingInner.find( 'a' ).length * 100 );
						} else {
							sharingInner.find( 'a' ).removeClass( 'wpr-no-transition-delay' );

							sharingInner.find( 'a' ).css({
								'opacity' : '0',
								'top' : '-5px'
							});
							setTimeout( function() {
								sharingInner.css( 'visibility', 'hidden' );
							}, sharingInner.find( 'a' ).length * 100 );
						}
					});
				} else {
					sharingTrigger.on( 'mouseenter', function() {
						var sharingInner = $(this).next();

						sharingInner.css( 'visibility', 'visible' );
						sharingInner.find( 'a' ).css({
							'opacity' : '1',
							'top' : '0',
						});
						
						setTimeout( function() {
							sharingInner.find( 'a' ).addClass( 'wpr-no-transition-delay' );
						}, sharingInner.find( 'a' ).length * 100 );
					});
					$scope.find( '.wpr-grid-item-sharing' ).on( 'mouseleave', function() {
						var sharingInner = $(this).find( '.wpr-post-sharing-inner' );

						sharingInner.find( 'a' ).removeClass( 'wpr-no-transition-delay' );

						sharingInner.find( 'a' ).css({
							'opacity' : '0',
							'top' : '-5px'
						});
						setTimeout( function() {
							sharingInner.css( 'visibility', 'hidden' );
						}, sharingInner.find( 'a' ).length * 100 );
					});
				}
			}

			// Likes
			if ( $scope.find( '.wpr-post-like-button' ).length ) {

				$scope.find( '.wpr-post-like-button' ).on( 'click', function() {
					var current = $(this);

					if ( '' !== current.attr( 'data-post-id' ) ) {

					$.ajax({
						type: 'POST',
						url: current.attr( 'data-ajax' ),
						data: {
							action : 'wpr_likes_init',
							post_id : current.attr( 'data-post-id' ),
							nonce : current.attr( 'data-nonce' )
						},
						beforeSend:function() {
							current.fadeTo( 500, 0.5 );
						},	
						success: function( response ) {
							// Get Icon
							var iconClass = current.attr( 'data-icon' );

							// Get Count
							var countHTML = response.count;

							if ( '' === countHTML.replace(/<\/?[^>]+(>|$)/g, "") ) {
								countHTML = '<span class="wpr-post-like-count">'+ current.attr( 'data-text' ) +'</span>';

								if ( ! current.hasClass( 'wpr-likes-zero' ) ) {
									current.addClass( 'wpr-likes-zero' );
								}
							} else {
								current.removeClass( 'wpr-likes-zero' );
							}

							// Update Icon
							if ( current.hasClass( 'wpr-already-liked' ) ) {
								current.prop( 'title', 'Like' );
								current.removeClass( 'wpr-already-liked' );
								current.html( '<i class="'+ iconClass.replace( 'fas', 'far' ) +'"></i>' + countHTML );
							} else {
								current.prop( 'title', 'Unlike' );
								current.addClass( 'wpr-already-liked' );
								current.html( '<i class="'+ iconClass.replace( 'far', 'fas' ) +'"></i>' + countHTML );
							}

							current.fadeTo( 500, 1 );
						}
					});

					}

					return false;
				});

			}

		}, // End widgetMagazineGrid

		widgetFeaturedMedia: function( $scope ) {
			var gallery = $scope.find( '.wpr-gallery-slider' ),
				gallerySettings = gallery.attr( 'data-slick' );
			
			gallery.animate({ 'opacity' : '1' }, 1000 );

			if ( '[]' !== gallerySettings ) {
				gallery.slick({
					appendDots : $scope.find( '.wpr-gallery-slider-dots' ),
					customPaging : function ( slider, i ) {
						var slideNumber = (i + 1),
							totalSlides = slider.slideCount;

						return '<span class="wpr-gallery-slider-dot"></span>';
					}
				});
			}

			// Lightbox
			var lightboxSettings = $( '.wpr-featured-media-image' ).attr( 'data-lightbox' );

			if ( typeof lightboxSettings !== typeof undefined && lightboxSettings !== false && ! WprElements.editorCheck() ) {
				var MediaWrap = $scope.find( '.wpr-featured-media-wrap' );
					lightboxSettings = JSON.parse( lightboxSettings );

				// Init Lightbox
				MediaWrap.lightGallery( lightboxSettings );

				// Show/Hide Controls
				MediaWrap.on( 'onAferAppendSlide.lg, onAfterSlide.lg', function( event, prevIndex, index ) {
					var lightboxControls = $( '#lg-actual-size, #lg-zoom-in, #lg-zoom-out, #lg-download' ),
						lightboxDownload = $( '#lg-download' ).attr( 'href' );

					if ( $( '#lg-download' ).length ) {
						if ( -1 === lightboxDownload.indexOf( 'wp-content' ) ) {
							lightboxControls.addClass( 'wpr-hidden-element' );
						} else {
							lightboxControls.removeClass( 'wpr-hidden-element' );
						}
					}

					// Autoplay Button
					if ( '' === lightboxSettings.autoplay ) {
						$( '.lg-autoplay-button' ).css({
							 'width' : '0',
							 'height' : '0',
							 'overflow' : 'hidden'
						});
					}
				});
			}
		}, // End widgetFeaturedMedia
        
        widgetProductMedia: function( $scope ) {
			// Fix Main Slider Distortion
			$(document).ready(function($) {
				$(window).trigger('resize');
				setTimeout(function() {
					$(window).trigger('resize');
					$scope.find('.wpr-product-media-wrap').removeClass('wpr-zero-opacity');
				}, 1000);
			});

			var sliderIcons = $scope.find('.wpr-gallery-slider-arrows-wrap');

			sliderIcons.remove();

			if ( $scope.find('.woocommerce-product-gallery__trigger').length ) {
				$scope.find('.woocommerce-product-gallery__trigger').remove();
			}

			$scope.find('.flex-viewport').append(sliderIcons);
			
			$scope.find('.wpr-gallery-slider-arrow').on('click', function() {
				if ($(this).hasClass('wpr-gallery-slider-prev-arrow')) {
					$scope.find('a.flex-prev').trigger('click');
				} else if ($(this).hasClass('wpr-gallery-slider-next-arrow')) {
					$scope.find('a.flex-next').trigger('click');
				}
			});
		
			// Lightbox
			var lightboxSettings = $( '.wpr-product-media-wrap' ).attr( 'data-lightbox' );
		
			if ( typeof lightboxSettings !== typeof undefined && lightboxSettings !== false && ! WprElements.editorCheck() ) {
				
				$scope.find('.woocommerce-product-gallery__image').each(function() {
					$(this).attr('data-lightbox', lightboxSettings);
					$(this).attr('data-src', $(this).find('a').attr('href'));
				});


				$scope.find('.woocommerce-product-gallery__image').on('click', function(e) {
					e.stopPropagation();
				});

				$scope.find('.wpr-product-media-lightbox').on('click', function() {
					$scope.find('.woocommerce-product-gallery__image').trigger('click');
				});

				var MediaWrap = $scope.find( '.woocommerce-product-gallery__wrapper' );
					lightboxSettings = JSON.parse( lightboxSettings );
		
				// Init Lightbox
				MediaWrap.lightGallery( lightboxSettings );
		
				// Show/Hide Controls
				MediaWrap.on( 'onAferAppendSlide.lg, onAfterSlide.lg', function( event, prevIndex, index ) {
					var lightboxControls = $( '#lg-actual-size, #lg-zoom-in, #lg-zoom-out, #lg-download' ),
						lightboxDownload = $( '#lg-download' ).attr( 'href' );
		
					if ( $( '#lg-download' ).length ) {
						if ( -1 === lightboxDownload.indexOf( 'wp-content' ) ) {
							lightboxControls.addClass( 'wpr-hidden-element' );
						} else {
							lightboxControls.removeClass( 'wpr-hidden-element' );
						}
					}
		
					// Autoplay Button
					if ( '' === lightboxSettings.autoplay ) {
						$( '.lg-autoplay-button' ).css({
							 'width' : '0',
							 'height' : '0',
							 'overflow' : 'hidden'
						});
					}
				});
			}

			if ( $scope.hasClass('wpr-product-media-thumbs-slider') && $scope.hasClass('wpr-product-media-thumbs-vertical') ) {

				var thumbsToShow = $scope.find('.wpr-product-media-wrap').data('slidestoshow');
				var thumbsToScroll = +$scope.find('.wpr-product-media-wrap').data('slidestoscroll');
	
				$scope.find('.flex-control-nav').css('height', ((100/thumbsToShow) * $scope.find('.flex-control-nav li').length) + '%');

				$scope.find('.flex-control-nav').wrap('<div class="wpr-fcn-wrap"></div>');

				var thumbIcon1 = $scope.find('.wpr-thumbnail-slider-prev-arrow');
				var thumbIcon2 = $scope.find('.wpr-thumbnail-slider-next-arrow');
	
				thumbIcon1.remove();
				thumbIcon2.remove();

				if ( $scope.find('.wpr-product-media-wrap').data('slidestoshow') < $scope.find('.flex-control-nav li').length ) {
					$scope.find('.wpr-fcn-wrap').prepend(thumbIcon1);
					$scope.find('.wpr-fcn-wrap').append(thumbIcon2);
				}

				var posy = 0;
				var slideCount = 0;
	
				$scope.find('.wpr-thumbnail-slider-next-arrow').on('click', function() {
						// var currTrans =  $scope.find('.flex-control-nav').css('transform') != 'none' ? $scope.find('.flex-control-nav').css('transform').split(/[()]/)[1] : 0;
						// posx = currTrans ? currTrans.split(',')[4] : 0;
						if ( (slideCount + thumbsToScroll) < $scope.find('.flex-control-nav li').length - 1 ) {
							posy++;
							slideCount = slideCount + thumbsToScroll;
							$scope.find('.flex-control-nav').css('transform', 'translateY('+ (parseInt(-posy) * (parseInt($scope.find('.flex-control-nav li:last-child').css('height').slice(0, -2)) + parseInt($scope.find('.flex-control-nav li').css('margin-bottom'))) * thumbsToScroll) +'px)');
							if ( posy >= 1 ) {
								$scope.find('.wpr-thumbnail-slider-prev-arrow').attr('disabled', false);
							} else {
								$scope.find('.wpr-thumbnail-slider-prev-arrow').attr('disabled', true);
							}
						} else {
							posy = 0;
							slideCount = 0;
							$scope.find('.flex-control-nav').css('transform', `translateY(0)`);
							$scope.find('.wpr-thumbnail-slider-prev-arrow').attr('disabled', true);
						}
				});
	
				$scope.find('.wpr-thumbnail-slider-prev-arrow').on('click', function() {
						if ( posy >= 1 ) {
							posy--;
							if ( posy == 0 ) {
								$(this).attr('disabled', true);
							}
							slideCount = slideCount - thumbsToScroll;
							$scope.find('.flex-control-nav').css('transform', 'translateY('+ parseInt(-posy) * (parseInt($scope.find('.flex-control-nav li').css('height').slice(0, -2)) + parseInt($scope.find('.flex-control-nav li:last-child').css('margin-top'))) * thumbsToScroll +'px)');
							if ( slideCount < $scope.find('.flex-control-nav li').length - 1 ) {
								$scope.find('.wpr-thumbnail-slider-next-arrow').attr('disabled', false);
							} else {
								$scope.find('.wpr-thumbnail-slider-next-arrow').attr('disabled', true);
							}
						} else {
							// slideCount = $scope.find('.flex-control-nav li').length - 1;
							// $scope.find('.flex-control-nav').css('transform', `translateX(0)`);
							$(this).attr('disabled', true);
						}
				});
			}

			if ( $scope.hasClass('wpr-product-media-thumbs-slider') && $scope.find('.wpr-product-media-wrap').hasClass('wpr-product-media-thumbs-horizontal') ) {

				var thumbsToShow = $scope.find('.wpr-product-media-wrap').data('slidestoshow');
				var thumbsToScroll = +$scope.find('.wpr-product-media-wrap').data('slidestoscroll');
	
				$scope.find('.flex-control-nav').css('width', ((100/thumbsToShow) * $scope.find('.flex-control-nav li').length) +'%');

				$scope.find('.flex-control-nav').wrap('<div class="wpr-fcn-wrap"></div>');

				var thumbIcon1 = $scope.find('.wpr-thumbnail-slider-prev-arrow');
				var thumbIcon2 = $scope.find('.wpr-thumbnail-slider-next-arrow');
	
				thumbIcon1.remove();
				thumbIcon2.remove();

				if ( $scope.find('.wpr-product-media-wrap').data('slidestoshow') < $scope.find('.flex-control-nav li').length ) {
					$scope.find('.wpr-fcn-wrap').prepend(thumbIcon1);
					$scope.find('.wpr-fcn-wrap').append(thumbIcon2);
					$scope.find('.wpr-thumbnail-slider-arrow').removeClass('wpr-tsa-hidden');
				}

				var posx = 0;
				var slideCount = 0;
				$scope.find('.wpr-thumbnail-slider-prev-arrow').attr('disabled', true);
	
				$scope.find('.wpr-thumbnail-slider-next-arrow').on('click', function() {
						// var currTrans =  $scope.find('.flex-control-nav').css('transform') != 'none' ? $scope.find('.flex-control-nav').css('transform').split(/[()]/)[1] : 0;
						// posx = currTrans ? currTrans.split(',')[4] : 0;
						if ( (slideCount + thumbsToScroll) < $scope.find('.flex-control-nav li').length - 1 ) {
							posx++;
							slideCount = slideCount + thumbsToScroll;
							$scope.find('.flex-control-nav').css('transform', 'translateX('+ (parseInt(-posx) * (parseInt($scope.find('.flex-control-nav li:last-child').css('width').slice(0, -2)) + parseInt($scope.find('.flex-control-nav li').css('margin-right'))) * thumbsToScroll) +'px)');
							if ( posx >= 1 ) {
								$scope.find('.wpr-thumbnail-slider-prev-arrow').attr('disabled', false);
							} else {
								$scope.find('.wpr-thumbnail-slider-prev-arrow').attr('disabled', true);
							}
						} else {
							posx = 0;
							slideCount = 0;
							$scope.find('.flex-control-nav').css('transform', `translateX(0)`);
							$scope.find('.wpr-thumbnail-slider-prev-arrow').attr('disabled', true);
						}
				});
	
				$scope.find('.wpr-thumbnail-slider-prev-arrow').on('click', function() {
						if ( posx >= 1 ) {
							posx--;
							if ( posx == 0 ) {
								$(this).attr('disabled', true);
							}
							slideCount = slideCount - thumbsToScroll;
							$scope.find('.flex-control-nav').css('transform', 'translateX('+ parseInt(-posx) * (parseInt($scope.find('.flex-control-nav li').css('width').slice(0, -2)) + parseInt($scope.find('.flex-control-nav li').css('margin-right'))) * thumbsToScroll +'px)');
							if ( slideCount < $scope.find('.flex-control-nav li').length - 1 ) {
								$scope.find('.wpr-thumbnail-slider-next-arrow').attr('disabled', false);
							} else {
								$scope.find('.wpr-thumbnail-slider-next-arrow').attr('disabled', true);
							}
						} else {
							// slideCount = $scope.find('.flex-control-nav li').length - 1;
							// $scope.find('.flex-control-nav').css('transform', `translateX(0)`);
							$(this).attr('disabled', true);
						}
				});

			}
		}, // End widgetProductMedia

		widgetCountDown: function( $scope ) {
			var countDownWrap = $scope.children( '.elementor-widget-container' ).children( '.wpr-countdown-wrap' ),
				countDownInterval = null,
				dataInterval = countDownWrap.data( 'interval' ),
				dataShowAgain = countDownWrap.data( 'show-again' ),
				endTime = new Date( dataInterval * 1000);

			// Evergreen End Time
			if ( 'evergreen' === countDownWrap.data( 'type' ) ) {
				var evergreenDate = new Date(),
					widgetID = $scope.attr( 'data-id' ),
					settings = JSON.parse( localStorage.getItem( 'WprCountDownSettings') ) || {};

				// End Time
				if ( settings.hasOwnProperty( widgetID ) ) {
					if ( Object.keys(settings).length === 0 || dataInterval !== settings[widgetID].interval ) {
						endTime = evergreenDate.setSeconds( evergreenDate.getSeconds() + dataInterval );
					} else {
						endTime = settings[widgetID].endTime;
					}
				} else {
					endTime = evergreenDate.setSeconds( evergreenDate.getSeconds() + dataInterval );
				}

				if ( endTime + dataShowAgain < evergreenDate.setSeconds( evergreenDate.getSeconds() ) ) {
					endTime = evergreenDate.setSeconds( evergreenDate.getSeconds() + dataInterval );
				}

				// Settings
				settings[widgetID] = {
					interval: dataInterval,
					endTime: endTime
				};

				// Save Settings in Browser
				localStorage.setItem( 'WprCountDownSettings', JSON.stringify( settings ) );
			}

			// Start CountDown
			if ( ! WprElements.editorCheck() ) { //tmp
			}
			// Init on Load
			initCountDown();

			// Start CountDown
			countDownInterval = setInterval( initCountDown, 1000 );

			function initCountDown() {
				var timeLeft = endTime - new Date();

				var numbers = {
					days: Math.floor(timeLeft / (1000 * 60 * 60 * 24)),
					hours: Math.floor(timeLeft / (1000 * 60 * 60) % 24),
					minutes: Math.floor(timeLeft / 1000 / 60 % 60),
					seconds: Math.floor(timeLeft / 1000 % 60)
				};

				if ( numbers.days < 0 || numbers.hours < 0 || numbers.minutes < 0 ) {
					numbers = {
						days: 0,
						hours: 0,
						minutes: 0,
						seconds: 0
					};
				}

				$scope.find( '.wpr-countdown-number' ).each(function() {
					var number = numbers[ $(this).attr( 'data-item' ) ];

					if ( 1 === number.toString().length ) {
						number = '0' + number;
					}

					$(this).text( number );

					// Labels
					var labels = $(this).next();

					if ( labels.length ) {
						if ( ! $(this).hasClass( 'wpr-countdown-seconds' ) ) {
							var labelText = labels.data( 'text' );

							if ( '01' == number ) {
								labels.text( labelText.singular );
							} else {
								labels.text( labelText.plural );
							}
						}
					}
				});

				// Stop Counting
				if ( timeLeft < 0 ) {
					clearInterval( countDownInterval );

					// Actions
					expiredActions();
				}
			}

			function expiredActions() {
				var dataActions = countDownWrap.data( 'actions' );

				if ( ! WprElements.editorCheck() ) {
					
					if ( dataActions.hasOwnProperty( 'hide-timer' ) ) {
						countDownWrap.hide();
					}
					
					if ( dataActions.hasOwnProperty( 'hide-element' ) ) {
						$( dataActions['hide-element'] ).hide();
					}
					
					if ( dataActions.hasOwnProperty( 'message' ) ) {
						if ( ! $scope.children( '.elementor-widget-container' ).children( '.wpr-countdown-message' ).length ) {
							countDownWrap.after( '<div class="wpr-countdown-message">'+ dataActions['message'] +'</div>' );
						}
					}
					
					if ( dataActions.hasOwnProperty( 'redirect' ) ) {
						window.location.href = dataActions['redirect'];
					}

					if ( dataActions.hasOwnProperty( 'load-template' ) ) {
						// countDownWrap.parent().find( '.elementor-inner' ).parent().show();
						countDownWrap.next('.elementor').show();
					}

				}
				
			}

		}, // End widgetCountDown

		widgetGoogleMaps: function( $scope ) {
			var googleMap = $scope.find( '.wpr-google-map' ),
				settings = googleMap.data( 'settings' ),
				controls = googleMap.data( 'controls' ),
				locations = googleMap.data( 'locations' ),
				gMarkers = [],
				bounds = new google.maps.LatLngBounds();

			// Create Map
			var map = new google.maps.Map( googleMap[0], {
				mapTypeId: settings.type,
				styles: get_map_style( settings ),
				zoom: settings.zoom_depth,
				gestureHandling: settings.zoom_on_scroll,

				// UI
				mapTypeControl: controls.type,
				fullscreenControl: controls.fullscreen,
                zoomControl: controls.zoom,
                streetViewControl: controls.streetview,
			} );

			// Set Markers
			for ( var i = 0; i < locations.length; i++ ) {
				var data = locations[i],
					iconOptions = '',
					iconSizeW = data.gm_marker_icon_size_width.size,
					iconSizeH = data.gm_marker_icon_size_height.size;

				// Empty Values
				if ( '' == data.gm_latitude || '' == data.gm_longtitude ) {
					continue;
				}

				// Custom Icon
				if ( 'yes' === data.gm_custom_marker ) {
					iconOptions = {
						url: data.gm_marker_icon.url,
						scaledSize: new google.maps.Size( iconSizeW, iconSizeH ),
					};
				}

				// Marker
				var marker = new google.maps.Marker({
					map: map,
					position: new google.maps.LatLng( parseFloat( data.gm_latitude ), parseFloat( data.gm_longtitude ) ),
					animation: google.maps.Animation[data.gm_marker_animation],
					icon: iconOptions
				});

				// Info Window
				if ( 'none' !== data.gm_show_info_window ) {
					infoWindow( marker, data );
				}

				gMarkers.push(marker);
				bounds.extend(marker.position);
			}

			// Center Map
			if ( locations.length > 1 ) {
				map.fitBounds(bounds);
			} else {
				map.setCenter( bounds.getCenter() );
			}

			// Marker Clusters
			if ( 'yes' === settings.cluster_markers ) {
				var markerCluster = new MarkerClusterer(map, gMarkers, {
					imagePath: settings.clusters_url
				});
			}

			// Info Wondow
			function infoWindow( marker, data ) {
				var content = '<div class="wpr-gm-iwindow"><h3>'+ data.gm_location_title +'</h3><p>'+ data.gm_location_description +'</p></div>',
					iWindow = new google.maps.InfoWindow({
						content: content,
						maxWidth: data.gm_info_window_width.size
					});

				if ( 'load' === data.gm_show_info_window ) {
					iWindow.open( map, marker );
				} else {
					marker.addListener( 'click', function() {
						iWindow.open( map, marker );
					});
				}
			}

			// Map Styles
			function get_map_style( settings ) {
				var style;

				switch ( settings.style ) {
					case 'simple':
						style = JSON.parse('[{"featureType":"road","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#fffffa"}]},{"featureType":"water","stylers":[{"lightness":50}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"transit","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry","stylers":[{"lightness":40}]}]');
						break;
					case 'white-black':
						style = JSON.parse('[{"featureType":"road","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"poi","stylers":[{"visibility":"off"}]},{"featureType":"administrative","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"weight":1}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"weight":0.8}]},{"featureType":"landscape","stylers":[{"color":"#ffffff"}]},{"featureType":"water","stylers":[{"visibility":"off"}]},{"featureType":"transit","stylers":[{"visibility":"off"}]},{"elementType":"labels","stylers":[{"visibility":"off"}]},{"elementType":"labels.text","stylers":[{"visibility":"on"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"elementType":"labels.icon","stylers":[{"visibility":"on"}]}]');
						break;
					case 'light-silver':
						style = JSON.parse('[{"featureType":"water","elementType":"geometry","stylers":[{"color":"#e9e9e9"},{"lightness":17}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":21}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#dedede"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":19}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]}]');
						break;
					case 'light-grayscale':
						style = JSON.parse('[{"featureType":"all","elementType":"geometry.fill","stylers":[{"weight":"2.00"}]},{"featureType":"all","elementType":"geometry.stroke","stylers":[{"color":"#9c9c9c"}]},{"featureType":"all","elementType":"labels.text","stylers":[{"visibility":"on"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#eeeeee"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#7b7b7b"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#46bcec"},{"visibility":"on"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#c8d7d4"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#070707"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]}]');
						break;
					case 'subtle-grayscale':
						style = JSON.parse('[{"featureType":"administrative","elementType":"all","stylers":[{"saturation":"-100"}]},{"featureType":"administrative.province","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","elementType":"all","stylers":[{"saturation":-100},{"lightness":"50"},{"visibility":"simplified"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":"-100"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"all","stylers":[{"lightness":"30"}]},{"featureType":"road.local","elementType":"all","stylers":[{"lightness":"40"}]},{"featureType":"transit","elementType":"all","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]},{"featureType":"water","elementType":"labels","stylers":[{"lightness":-25},{"saturation":-100}]}]');
						break;
					case 'mostly-white':
						style = JSON.parse('[{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#6195a0"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#e6f3d6"},{"visibility":"on"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45},{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#f4d2c5"},{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"labels.text","stylers":[{"color":"#4e4e4e"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#f4f4f4"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#787878"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#eaf6f8"},{"visibility":"on"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#eaf6f8"}]}]');
						break;
					case 'mostly-green':
						style = JSON.parse('[{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]}]');
						break;
					case 'neutral-blue':
						style = JSON.parse('[{"featureType":"water","elementType":"geometry","stylers":[{"color":"#193341"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#2c5a71"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#29768a"},{"lightness":-37}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#406d80"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#406d80"}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#3e606f"},{"weight":2},{"gamma":0.84}]},{"elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"administrative","elementType":"geometry","stylers":[{"weight":0.6},{"color":"#1a3541"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#2c5a71"}]}]');
						break;
					case 'blue-water':
						style = JSON.parse('[{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#46bcec"},{"visibility":"on"}]}]');
						break;
					case 'blue-essense':
						style = JSON.parse('[{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#e0efef"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"hue":"#1900ff"},{"color":"#c0e8e8"}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":100},{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"visibility":"on"},{"lightness":700}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#7dcdcd"}]}]');
						break;
					case 'golden-brown':
						style = JSON.parse('[{"featureType":"all","elementType":"all","stylers":[{"color":"#ff7000"},{"lightness":"69"},{"saturation":"100"},{"weight":"1.17"},{"gamma":"2.04"}]},{"featureType":"all","elementType":"geometry","stylers":[{"color":"#cb8536"}]},{"featureType":"all","elementType":"labels","stylers":[{"color":"#ffb471"},{"lightness":"66"},{"saturation":"100"}]},{"featureType":"all","elementType":"labels.text.fill","stylers":[{"gamma":0.01},{"lightness":20}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"saturation":-31},{"lightness":-33},{"weight":2},{"gamma":0.8}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"lightness":"-8"},{"gamma":"0.98"},{"weight":"2.45"},{"saturation":"26"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"lightness":30},{"saturation":30}]},{"featureType":"poi","elementType":"geometry","stylers":[{"saturation":20}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"lightness":20},{"saturation":-20}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":10},{"saturation":-30}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"saturation":25},{"lightness":25}]},{"featureType":"water","elementType":"all","stylers":[{"lightness":-20},{"color":"#ecc080"}]}]');
						break;
					case 'midnight-commander':
						style = JSON.parse('[{"featureType":"all","elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"color":"#000000"},{"lightness":13}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#144b53"},{"lightness":14},{"weight":1.4}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#08304b"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#0c4152"},{"lightness":5}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#0b434f"},{"lightness":25}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#0b3d51"},{"lightness":16}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"}]},{"featureType":"transit","elementType":"all","stylers":[{"color":"#146474"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#021019"}]}]');
						break;
					case 'shades-of-grey':
						style = JSON.parse('[{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":21}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":17}]}]');
						break;
					case 'yellow-black':
						style = JSON.parse('[{"featureType":"all","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"administrative.country","elementType":"labels.text.fill","stylers":[{"color":"#e5c163"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#c4c4c4"}]},{"featureType":"administrative.neighborhood","elementType":"labels.text.fill","stylers":[{"color":"#e5c163"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":21},{"visibility":"on"}]},{"featureType":"poi.business","elementType":"geometry","stylers":[{"visibility":"on"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#e5c163"},{"lightness":"0"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.highway","elementType":"labels.text.stroke","stylers":[{"color":"#e5c163"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#575757"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"labels.text.stroke","stylers":[{"color":"#2c2c2c"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#999999"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":17}]}]');
						break;
					case 'custom':
						style = JSON.parse( settings.custom_style );
						break;
					default:
						style = '';
				}

				return style;
			}

		}, // End widgetGoogleMaps

		widgetBeforeAfter: function( $scope ) {
			var imagesWrap = $scope.find( '.wpr-ba-image-container' ),
				imageOne = imagesWrap.find( '.wpr-ba-image-1' ),
				imageTwo = imagesWrap.find( '.wpr-ba-image-2' ),
				divider = imagesWrap.find( '.wpr-ba-divider' ),
				startPos = imagesWrap.attr( 'data-position' );

			// Horizontal
			if ( imagesWrap.hasClass( 'wpr-ba-horizontal' ) ) {
				// On Load
				divider.css( 'left', startPos +'%' );
				imageTwo.css( 'left', startPos +'%' );
				imageTwo.find( 'img' ).css( 'right', startPos +'%' );

				// On Move
				divider.on( 'move', function(e) {
					var overlayWidth = e.pageX - imagesWrap.offset().left;

					// Reset
					divider.css({
						'left' : 'auto',
						'right' : 'auto'
					});
					imageTwo.css({
						'left' : 'auto',
						'right' : 'auto'
					});

					if ( overlayWidth > 0  && overlayWidth < imagesWrap.outerWidth() ) {
						divider.css( 'left', overlayWidth );
						imageTwo.css( 'left', overlayWidth );
						imageTwo.find( 'img' ).css( 'right', overlayWidth );
					} else {
						if ( overlayWidth <= 0 ) {
							divider.css( 'left', 0 );
							imageTwo.css( 'left', 0 );
							imageTwo.find( 'img' ).css( 'right', 0 );
						} else if ( overlayWidth >= imagesWrap.outerWidth() ) {
							divider.css( 'right', - divider.outerWidth() / 2 );
							imageTwo.css( 'right', 0 );
							imageTwo.find( 'img' ).css( 'right', '100%' );
						}
					}

					hideLabelsOnTouch();
				});

			// Vertical
			} else {
				// On Load
				divider.css( 'top', startPos +'%' );
				imageTwo.css( 'top', startPos +'%' );
				imageTwo.find( 'img' ).css( 'bottom', startPos +'%' );

				// On Move
				divider.on( 'move', function(e) {
					var overlayWidth = e.pageY - imagesWrap.offset().top;

					// Reset
					divider.css({
						'top' : 'auto',
						'bottom' : 'auto'
					});
					imageTwo.css({
						'top' : 'auto',
						'bottom' : 'auto'
					});

					if ( overlayWidth > 0  && overlayWidth < imagesWrap.outerHeight() ) {
						divider.css( 'top', overlayWidth );
						imageTwo.css( 'top', overlayWidth );
						imageTwo.find( 'img' ).css( 'bottom', overlayWidth );
					} else {
						if ( overlayWidth <= 0 ) {
							divider.css( 'top', 0 );
							imageTwo.css( 'top', 0 );
							imageTwo.find( 'img' ).css( 'bottom', 0 );
						} else if ( overlayWidth >= imagesWrap.outerHeight() ) {
							divider.css( 'bottom', - divider.outerHeight() / 2 );
							imageTwo.css( 'bottom', 0 );
							imageTwo.find( 'img' ).css( 'bottom', '100%' );
						}
					}

					hideLabelsOnTouch();
				});
			}

			// Mouse Hover
			if ( 'mouse' === imagesWrap.attr( 'data-trigger' ) ) {

				imagesWrap.on( 'mousemove', function( event ) {

					// Horizontal
					if ( imagesWrap.hasClass( 'wpr-ba-horizontal' ) ) {
						var overlayWidth = event.pageX - $(this).offset().left;
						divider.css( 'left', overlayWidth );
						imageTwo.css( 'left', overlayWidth );
						imageTwo.find( 'img' ).css( 'right', overlayWidth );

					// Vertical
					} else {
						var overlayWidth = event.pageY - $(this).offset().top;
						divider.css( 'top', overlayWidth );
						imageTwo.css( 'top', overlayWidth );
						imageTwo.find( 'img' ).css( 'bottom', overlayWidth );
					}

					hideLabelsOnTouch();
				});

			}

			// Hide Labels
			hideLabelsOnTouch();

			function hideLabelsOnTouch() {
				var labelOne = imagesWrap.find( '.wpr-ba-label-1 div' ),
					labelTwo = imagesWrap.find( '.wpr-ba-label-2 div' );

				if ( ! labelOne.length && ! labelTwo.length ) {
					return;
				}

				// Horizontal
				if ( imagesWrap.hasClass( 'wpr-ba-horizontal' ) ) {
					var labelOneOffset = labelOne.position().left + labelOne.outerWidth(),
						labelTwoOffset = labelTwo.position().left + labelTwo.outerWidth();

					if ( labelOneOffset + 15 >= parseInt( divider.css( 'left' ), 10 ) ) {
						labelOne.stop().css( 'opacity', 0 );
					} else {
						labelOne.stop().css( 'opacity', 1 );
					}

					if ( (imagesWrap.outerWidth() - (labelTwoOffset + 15)) <= parseInt( divider.css( 'left' ), 10 ) ) {
						labelTwo.stop().css( 'opacity', 0 );
					} else {
						labelTwo.stop().css( 'opacity', 1 );
					}

				// Vertical
				} else {
					var labelOneOffset = labelOne.position().top + labelOne.outerHeight(),
						labelTwoOffset = labelTwo.position().top + labelTwo.outerHeight();

					if ( labelOneOffset + 15 >= parseInt( divider.css( 'top' ), 10 ) ) {
						labelOne.stop().css( 'opacity', 0 );
					} else {
						labelOne.stop().css( 'opacity', 1 );
					}

					if ( (imagesWrap.outerHeight() - (labelTwoOffset + 15)) <= parseInt( divider.css( 'top' ), 10 ) ) {
						labelTwo.stop().css( 'opacity', 0 );
					} else {
						labelTwo.stop().css( 'opacity', 1 );
					}
				}
			}

		}, // End widgetBeforeAfter

		widgetMailchimp: function( $scope ) {
			var mailchimpForm = $scope.find( 'form' );

			mailchimpForm.on( 'submit', function(e) {
				e.preventDefault();

				var buttonText = $(this).find('button').text();

				// Change Text
				$(this).find('button').text( $(this).find('button').data('loading') );

				$.ajax({
					url: WprConfig.ajaxurl,
					type: 'POST',
					data: {
						action: 'mailchimp_subscribe',
						fields: $(this).serialize(),
						listId: mailchimpForm.data( 'list-id' )
					},
					success: function(data) {
						if ( 'yes' == mailchimpForm.data('clear-fields') ) {
							mailchimpForm.find('input').each(function() {
								$(this).val('');
							});
						}

						mailchimpForm.find('button').text( buttonText );

						if ( 'subscribed' === data.status ) {
							$scope.find( '.wpr-mailchimp-success-message' ).show();
						} else {
							$scope.find( '.wpr-mailchimp-error-message' ).show();
						}
						
						$scope.find( '.wpr-mailchimp-message' ).fadeIn();
					}
				});

			});

		}, // End widgetMailchimp

		widgetAdvancedSlider: function( $scope ) {
			var $advancedSlider = $scope.find( '.wpr-advanced-slider' ),
			sliderData = $advancedSlider.data('slick'),
			videoBtnSize = $advancedSlider.data('video-btn-size');
			
			// customPaging: function(slider, i) { 
			// 	return '<span class="wpr-slider-dot" style="background-image:url('+ $(slider.$slides[i]).find('.wpr-slider-item-bg').css('background-image').replace('url(','').replace(')','').replace(/\"/gi, "") +')"></span>';
			// },

			// Slider Columns
			var sliderClass = $scope.attr('class'),
				sliderColumnsDesktop = sliderClass.match(/wpr-adv-slider-columns-\d/) ? sliderClass.match(/wpr-adv-slider-columns-\d/).join().slice(-1) : 2,
				sliderColumnsWideScreen = sliderClass.match(/columns--widescreen\d/) ? sliderClass.match(/columns--widescreen\d/).join().slice(-1) : sliderColumnsDesktop,
				sliderColumnsLaptop = sliderClass.match(/columns--laptop\d/) ? sliderClass.match(/columns--laptop\d/).join().slice(-1) : sliderColumnsDesktop,
				sliderColumnsTablet = sliderClass.match(/columns--tablet\d/) ? sliderClass.match(/columns--tablet\d/).join().slice(-1) : 2,
				sliderColumnsTabletExtra = sliderClass.match(/columns--tablet_extra\d/) ? sliderClass.match(/columns--tablet_extra\d/).join().slice(-1) : sliderColumnsTablet,
				sliderColumnsMobileExtra = sliderClass.match(/columns--mobile_extra\d/) ? sliderClass.match(/columns--mobile_extra\d/).join().slice(-1) : sliderColumnsTablet,
				sliderColumnsMobile = sliderClass.match(/columns--mobile\d/) ? sliderClass.match(/columns--mobile\d/).join().slice(-1) : 1,
				sliderSlidesToScroll = +(sliderClass.match(/wpr-adv-slides-to-scroll-\d/).join().slice(-1)),
				dataSlideEffect = $advancedSlider.attr('data-slide-effect');

			$advancedSlider.slick({
				appendArrows :  $scope.find('.wpr-slider-controls'),
				appendDots :  $scope.find('.wpr-slider-dots'),
				customPaging : function (slider, i) {
					var slideNumber = (i + 1),
						totalSlides = slider.slideCount;
					return '<span class="wpr-slider-dot"></span>';
				},
				slidesToShow: sliderColumnsDesktop,
				responsive: [
					{
						breakpoint: 10000,
						settings: {
							slidesToShow: sliderColumnsWideScreen,
							slidesToScroll: sliderSlidesToScroll > sliderColumnsWideScreen ? 1 : sliderSlidesToScroll,
							fade: (1 == sliderColumnsWideScreen && 'fade' === dataSlideEffect) ? true : false
						}
					},
					{
						breakpoint: 2399,
						settings: {
							slidesToShow: sliderColumnsDesktop,
							slidesToScroll: sliderSlidesToScroll > sliderColumnsDesktop ? 1 : sliderSlidesToScroll,
							fade: (1 == sliderColumnsDesktop && 'fade' === dataSlideEffect) ? true : false
						}
					},
					{
						breakpoint: 1221,
						settings: {
							slidesToShow: sliderColumnsLaptop,
							slidesToScroll: sliderSlidesToScroll > sliderColumnsLaptop ? 1 : sliderSlidesToScroll,
							fade: (1 == sliderColumnsLaptop && 'fade' === dataSlideEffect) ? true : false
						}
					},
					{
						breakpoint: 1200,
						settings: {
							slidesToShow: sliderColumnsTabletExtra,
							slidesToScroll: sliderSlidesToScroll > sliderColumnsTabletExtra ? 1 : sliderSlidesToScroll,
							fade: (1 == sliderColumnsTabletExtra && 'fade' === dataSlideEffect) ? true : false
						}
					},
					{
						breakpoint: 1024,
						settings: {
							slidesToShow: sliderColumnsTablet,
							slidesToScroll: sliderSlidesToScroll > sliderColumnsTablet ? 1 : sliderSlidesToScroll,
							fade: (1 == sliderColumnsTablet && 'fade' === dataSlideEffect) ? true : false
						}
					},
					{
						breakpoint: 880,
						settings: {
							slidesToShow: sliderColumnsMobileExtra,
						 	slidesToScroll: sliderSlidesToScroll > sliderColumnsMobileExtra ? 1 : sliderSlidesToScroll,
							fade: (1 == sliderColumnsMobileExtra && 'fade' === dataSlideEffect) ? true : false
						}
					},
					{
						breakpoint: 768,
						settings: {
							slidesToShow: sliderColumnsMobile,
							slidesToScroll: sliderSlidesToScroll > sliderColumnsMobile ? 1 : sliderSlidesToScroll,
							fade: (1 == sliderColumnsMobile && 'fade' === dataSlideEffect) ? true : false
						}
					}
				],
			});

			$(document).ready(function() {
                
                $scope.find('.slick-current').addClass('wpr-slick-visible');

				var maxHeight = -1;
				// $scope.find('.slick-slide').each(function() {
				// if ($(this).height() > maxHeight) {
				// 	maxHeight = $(this).height();
				// }
				// });
				// $scope.find('.slick-slide').each(function() {
				// if ($(this).height() < maxHeight) {
				// 	console.log(Math.ceil((maxHeight-$(this).height())/2) + 'px 0');
				// 	$(this).css('margin', Math.ceil((maxHeight-$(this).height())/2) + 'px 0');
				// 	// $(this).css('transform', 'translateY(-50%)');
				// }
				// });

				// GOGA - needs condition check if there are any images
				if ( $scope.find('.wpr-slider-img').length !== 0 ) {
					$scope.find('.wpr-advanced-slider').css('height', $scope.find('.slick-current').outerHeight());
				
					$scope.find('.wpr-slider-arrow').on('click', function() {
						console.log('works resize');
						$scope.find('.wpr-advanced-slider').css('height', $scope.find('.slick-current').outerHeight());
					});
		
					$(window).smartresize(function() {
						$scope.find('.wpr-advanced-slider').css('height', $scope.find('.slick-current').outerHeight());
					});
				}
			});
			
			function sliderVideoSize(){
				  
				// var sliderWidth = $advancedSlider.find('.wpr-slider-item').outerWidth(),
				// 	sliderHeight = $advancedSlider.find('.wpr-slider-item').outerHeight(),
				// 	sliderRatio = sliderWidth / sliderHeight,
				// 	iframeRatio = (16/9),
				// 	iframeHeight,
				// 	iframeWidth,
				// 	iframeTopDistance = 0,
				// 	iframeLeftDistance = 0;

				// if ( sliderRatio > iframeRatio ) {
				// 	iframeWidth = sliderWidth;
				// 	iframeHeight = iframeWidth / iframeRatio;
				// 	iframeTopDistance = '-'+ ( iframeHeight - sliderHeight ) / 2 +'px';
				// } else {
				// 	iframeHeight = sliderHeight;
				// 	iframeWidth = iframeHeight * iframeRatio;
				// 	iframeLeftDistance = '-'+ ( iframeWidth - sliderWidth ) / 2 +'px';
				// }

				// $advancedSlider.find('iframe').css({
				// 	'display': 'block',
				// 	'width': iframeWidth +'px',
				// 	'height': iframeHeight +'px',
				// 	'max-width': 'none',
				// 	'position': 'absolute',
				// 	'left': iframeLeftDistance +'',
				// 	'top': iframeTopDistance +'',
				// 	'text-align': 'inherit',
				// 	'line-height':'0px',
				// 	'border-width': '0px',
				// 	'margin': '0px',
				// 	'padding': '0px',
				// });
				
				$advancedSlider.find('iframe').attr('width', $scope.find('.wpr-slider-item').width());
				$advancedSlider.find('iframe').attr('height', $scope.find('.wpr-slider-item').height());

				var viewportWidth = $(window).outerWidth();

				var MobileResp = +elementorFrontend.config.responsive.breakpoints.mobile.value;
				var MobileExtraResp = +elementorFrontend.config.responsive.breakpoints.mobile_extra.value;
				var TabletResp = +elementorFrontend.config.responsive.breakpoints.tablet.value;
				var TabletExtraResp = +elementorFrontend.config.responsive.breakpoints.tablet_extra.value;
				var LaptopResp = +elementorFrontend.config.responsive.breakpoints.laptop.value;
				var wideScreenResp = +elementorFrontend.config.responsive.breakpoints.widescreen.value;

				var activeBreakpoints = elementorFrontend.config.responsive.activeBreakpoints;

				[...$scope[0].classList].forEach(className => {
					if (className.startsWith('wpr-slider-video-icon-size-')) {
						$scope[0].classList.remove(className);
					}
				});

				// Mobile
				if ( MobileResp >= viewportWidth && activeBreakpoints.mobile != null ) {
					$scope.addClass('wpr-slider-video-icon-size-'+videoBtnSize.mobile);
				// Mobile Extra
				} else if ( MobileExtraResp >= viewportWidth && activeBreakpoints.mobile_extra != null ) {
					$scope.addClass('wpr-slider-video-icon-size-'+videoBtnSize.mobile_extra);
				// Tablet
				} else if ( TabletResp >= viewportWidth && activeBreakpoints.tablet != null ) {
					$scope.addClass('wpr-slider-video-icon-size-'+videoBtnSize.tablet);
				// Tablet Extra
				} else if ( TabletExtraResp >= viewportWidth && activeBreakpoints.tablet_extra != null ) {
					$scope.addClass('wpr-slider-video-icon-size-'+videoBtnSize.tablet_extra);
				// Laptop
				} else if ( LaptopResp >= viewportWidth && activeBreakpoints.laptop != null ) {
					$scope.addClass('wpr-slider-video-icon-size-'+videoBtnSize.laptop);
				// Desktop
				} else if ( wideScreenResp > viewportWidth ) {
					$scope.addClass('wpr-slider-video-icon-size-'+videoBtnSize.desktop);
				}  else {
					$scope.addClass('wpr-slider-video-icon-size-'+videoBtnSize.widescreen);
				}
				// wpr-slider-video-icon-size-
			}

			$(window).on('load resize', function(){
				sliderVideoSize();
			});

			$(document).ready(function () {
				// Handler when all assets (including images) are loaded
				if ( $scope.find('.wpr-advanced-slider').length ) {
					$scope.find('.wpr-advanced-slider').css('opacity', 1);
					autoplayVideo();
				}
			});

			function autoplayVideo() {
				$advancedSlider.find('.slick-current').each(function() {

					var videoSrc = $(this).find('.wpr-slider-item').attr('data-video-src'),
					videoAutoplay = $(this).find('.wpr-slider-item').attr('data-video-autoplay');
					
					if ( $(this).find( '.wpr-slider-video' ).length !== 1 && videoAutoplay === 'yes' ) {
						if ( videoSrc.includes('vimeo') || videoSrc.includes('youtube') ) {
							if ( sliderColumnsDesktop == 1 ) {
								// $(this).find('.wpr-cv-inner').prepend('<div class="wpr-slider-video"><iframe src="'+ videoSrc +'" width="100%" height="100%"  frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>');
								$(this).find('.wpr-cv-inner').prepend('<div class="wpr-slider-video"><iframe src="'+ videoSrc +'"  frameborder="0" allow="autoplay" allowfullscreen></iframe></div>');
							} else {
								$(this).find('.wpr-cv-container').prepend('<div class="wpr-slider-video"><iframe src="'+ videoSrc +'" width="100%" height="100%"  frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>'); 
							}
							sliderVideoSize();
						} else {
							var videoMute = $(this).find('.wpr-slider-item').attr('data-video-mute');
							var videoControls = $(this).find('.wpr-slider-item').attr('data-video-controls');
							var videoLoop = $(this).find('.wpr-slider-item').attr('data-video-loop');

							$(this).find('.wpr-cv-inner').prepend('<div class="wpr-slider-video wpr-custom-video"><video autoplay '+ videoLoop + ' ' + videoMute + ' ' + videoControls + ' ' +  'src="'+ videoSrc +'" width="100%" height="100%"></video></div>');
							
							$advancedSlider.find('video').attr('width', $scope.find('.wpr-slider-item').width());
							$advancedSlider.find('video').attr('height', $scope.find('.wpr-slider-item').height());
						}

						// GOGA - remove condition if not necessary
						if ( $(this).find('.wpr-slider-content') ) {
							$(this).find('.wpr-slider-content').fadeOut(300);
						}
					}
				});
			}

			function slideAnimationOff() {
				if ( sliderColumnsDesktop == 1 ) {
					$advancedSlider.find('.wpr-slider-item').not('.slick-active').find('.wpr-slider-animation').removeClass( 'wpr-animation-enter' );
				}
			}

			function slideAnimationOn() {
				$advancedSlider.find('.slick-active').find('.wpr-slider-content').fadeIn(0);
				$advancedSlider.find('.slick-cloned').find('.wpr-slider-content').fadeIn(0);
				$advancedSlider.find('.slick-current').find('.wpr-slider-content').fadeIn(0);
				if ( sliderColumnsDesktop == 1 ) {
					$advancedSlider.find('.slick-active').find('.wpr-slider-animation').addClass( 'wpr-animation-enter' );
				}
			}
			
			slideAnimationOn();

			$advancedSlider.on( 'click', '.wpr-slider-video-btn', function() {

				var currentSlide = $(this).closest('.slick-slide'),
					videoSrc = currentSlide.find('.wpr-slider-item').attr('data-video-src');

					console.log(videoSrc);
			
				console.log(currentSlide, videoSrc);
			
				var allowFullScreen = '';
			
				if ( videoSrc.includes('youtube') ) {
					videoSrc += "&autoplay=1"; // Tell YouTube to autoplay
					allowFullScreen = 'allowfullscreen="allowfullscreen"';
				} else if ( videoSrc.includes('vimeo') ) {
					allowFullScreen = 'allowfullscreen';
				} else {
					var videoMute = currentSlide.find('.wpr-slider-item').attr('data-video-mute');
					var videoControls = currentSlide.find('.wpr-slider-item').attr('data-video-controls');
					var videoLoop = currentSlide.find('.wpr-slider-item').attr('data-video-loop');
					
					if ( currentSlide.find( '.wpr-slider-video' ).length !== 1 ) {
						currentSlide.find('.wpr-cv-container').prepend('<div class="wpr-slider-video wpr-custom-video"><video '+ videoLoop + ' ' + videoMute + ' ' + videoControls + ' ' + 'src="'+ videoSrc +'" width="100%" height="100%"></video></div>');

						$advancedSlider.find('video').attr('width', $scope.find('.wpr-slider-item').width());
						$advancedSlider.find('video').attr('height', $scope.find('.wpr-slider-item').height());

						currentSlide.find('.wpr-slider-content').fadeOut(300);

						currentSlide.find('video')[0].play();
					}
					return;
				}
			
				if ( currentSlide.find( '.wpr-slider-video' ).length !== 1 ) {
					// currentSlide.find('.wpr-cv-container').prepend('<div class="wpr-slider-video"><iframe src="'+ videoSrc +'" width="100%" height="100%"  frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture;"></iframe></div>');
					currentSlide.find('.wpr-cv-container').prepend('<div class="wpr-slider-video"><iframe src="'+ videoSrc +'" width="100%" height="100%"  frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture;"'+ allowFullScreen +'></iframe></div>');

					sliderVideoSize();
					currentSlide.find('.wpr-slider-content').fadeOut(300);
				}
			
			});

			$advancedSlider.on( {
				beforeChange: function() {
					$advancedSlider.find('.wpr-slider-item').not('.slick-active').find('.wpr-slider-video').remove();
					$advancedSlider.find('.wpr-animation-enter').find('.wpr-slider-content').fadeOut(300);
					slideAnimationOff();
				},
				afterChange: function( event, slick, currentSlide ) {
					slideAnimationOn();
					autoplayVideo();
					$scope.find('.slick-slide').removeClass('wpr-slick-visible');
					$scope.find('.slick-current').addClass('wpr-slick-visible');
					$scope.find('.slick-current').nextAll().slice(0, sliderColumnsDesktop - 1).addClass('wpr-slick-visible');
					$scope.find('.wpr-advanced-slider').css('height', $scope.find('.slick-current').outerHeight());
				}
			});

			// Adjust Horizontal Pagination
			if ( $scope.find( '.slick-dots' ).length && $scope.hasClass( 'wpr-slider-dots-horizontal') ) {
				// Calculate Width
				var dotsWrapWidth = $scope.find( '.slick-dots li' ).outerWidth() * $scope.find( '.slick-dots li' ).length - parseInt( $scope.find( '.slick-dots li span' ).css( 'margin-right' ), 10 );

				// on Load
				if ( $scope.find( '.slick-dots' ).length ) {
					$scope.find( '.slick-dots' ).css( 'width', dotsWrapWidth );
				}

				// on Resize
				$(window).smartresize(function() {
					setTimeout(function() {
						// Calculate Width
						var dotsWrapWidth = $scope.find( '.slick-dots li' ).outerWidth() * $scope.find( '.slick-dots li' ).length - parseInt( $scope.find( '.slick-dots li span' ).css( 'margin-right' ), 10 );

						// Set Width
						$scope.find( '.slick-dots' ).css( 'width', dotsWrapWidth );
					}, 300 );
				});
			}

		}, // End widgetAdvancedSlider

		widgetTestimonialCarousel: function( $scope ) {
			var testimonialCarousel = $scope.find( '.wpr-testimonial-carousel' );
			var settings = JSON.parse( testimonialCarousel.attr( 'data-slick' ) );
			
			// Slider Columns
			var sliderClass = $scope.attr('class'),
				sliderColumnsDesktop = sliderClass.match(/wpr-testimonial-slider-columns-\d/) ? sliderClass.match(/wpr-testimonial-slider-columns-\d/).join().slice(-1) : 2,
				sliderColumnsWideScreen = sliderClass.match(/columns--widescreen\d/) ? sliderClass.match(/columns--widescreen\d/).join().slice(-1) : sliderColumnsDesktop,
				sliderColumnsLaptop = sliderClass.match(/columns--laptop\d/) ? sliderClass.match(/columns--laptop\d/).join().slice(-1) : sliderColumnsDesktop,
				sliderColumnsTablet = sliderClass.match(/columns--tablet\d/) ? sliderClass.match(/columns--tablet\d/).join().slice(-1) : 2,
				sliderColumnsTabletExtra = sliderClass.match(/columns--tablet_extra\d/) ? sliderClass.match(/columns--tablet_extra\d/).join().slice(-1) : sliderColumnsTablet,
				sliderColumnsMobileExtra = sliderClass.match(/columns--mobile_extra\d/) ? sliderClass.match(/columns--mobile_extra\d/).join().slice(-1) : sliderColumnsTablet,
				sliderColumnsMobile = sliderClass.match(/columns--mobile\d/) ? sliderClass.match(/columns--mobile\d/).join().slice(-1) : 1,
				sliderSlidesToScroll = settings.sliderSlidesToScroll,
				dataSlideEffect = testimonialCarousel.attr('data-slide-effect');

			testimonialCarousel.slick({
				appendArrows: $scope.find('.wpr-testimonial-controls'),
				appendDots: $scope.find('.wpr-testimonial-dots'),
				customPaging: function (slider, i) {
					var slideNumber = (i + 1),
						totalSlides = slider.slideCount;

					return '<span class="wpr-testimonial-dot"></span>';
				},
				slidesToShow: sliderColumnsDesktop,
				responsive: [
					{
						breakpoint: 10000,
						settings: {
							slidesToShow: sliderColumnsWideScreen,
							slidesToScroll: sliderSlidesToScroll > sliderColumnsWideScreen ? 1 : sliderSlidesToScroll,
							fade: (1 == sliderColumnsWideScreen && 'fade' === dataSlideEffect) ? true : false
						}
					},
					{
						breakpoint: 2399,
						settings: {
							slidesToShow: sliderColumnsDesktop,
							slidesToScroll: sliderSlidesToScroll > sliderColumnsDesktop ? 1 : sliderSlidesToScroll,
							fade: (1 == sliderColumnsDesktop && 'fade' === dataSlideEffect) ? true : false
						}
					},
					{
						breakpoint: 1221,
						settings: {
							slidesToShow: sliderColumnsLaptop,
							slidesToScroll: sliderSlidesToScroll > sliderColumnsLaptop ? 1 : sliderSlidesToScroll,
							fade: (1 == sliderColumnsLaptop && 'fade' === dataSlideEffect) ? true : false
						}
					},
					{
						breakpoint: 1200,
						settings: {
							slidesToShow: sliderColumnsTabletExtra,
							slidesToScroll: sliderSlidesToScroll > sliderColumnsTabletExtra ? 1 : sliderSlidesToScroll,
							fade: (1 == sliderColumnsTabletExtra && 'fade' === dataSlideEffect) ? true : false
						}
					},
					{
						breakpoint: 1024,
						settings: {
							slidesToShow: sliderColumnsTablet,
							slidesToScroll: sliderSlidesToScroll > sliderColumnsTablet ? 1 : sliderSlidesToScroll,
							fade: (1 == sliderColumnsTablet && 'fade' === dataSlideEffect) ? true : false
						}
					},
					{
						breakpoint: 880,
						settings: {
							slidesToShow: sliderColumnsMobileExtra,
						 	slidesToScroll: sliderSlidesToScroll > sliderColumnsMobileExtra ? 1 : sliderSlidesToScroll,
							fade: (1 == sliderColumnsMobileExtra && 'fade' === dataSlideEffect) ? true : false
						}
					},
					{
						breakpoint: 768,
						settings: {
							slidesToShow: sliderColumnsMobile,
							slidesToScroll: sliderSlidesToScroll > sliderColumnsMobile ? 1 : sliderSlidesToScroll,
							fade: (1 == sliderColumnsMobile && 'fade' === dataSlideEffect) ? true : false
						}
					}
				],
			});

			// Show Arrows On Hover
			if ( $scope.hasClass( 'wpr-testimonial-nav-fade' ) ) {
				$scope.on( 'mouseover', function() {
					$scope.closest( 'section' ).find( '.wpr-testimonial-arrow' ).css({
						'opacity' : 1,
					});
				} );
				$scope.closest( 'section' ).on( 'mouseout', function() {
					$scope.find( '.wpr-testimonial-arrow' ).css({
						'opacity' : 0,
					});
				} );
			}

			// on Load
			if ( $scope.find( '.slick-dots' ).length ) {
				// Calculate Width
				var dotsWrapWidth = $scope.find( '.slick-dots li' ).outerWidth() * $scope.find( '.slick-dots li' ).length - parseInt( $scope.find( '.slick-dots li span' ).css( 'margin-right' ), 10 );

				// Set Width
				$scope.find( '.slick-dots' ).css( 'width', dotsWrapWidth );
			}

			$(window).smartresize(function() {
				setTimeout(function() {
					if ( $scope.find( '.slick-dots' ).length ) {
						// Calculate Width
						var dotsWrapWidth = $scope.find( '.slick-dots li' ).outerWidth() * $scope.find( '.slick-dots li' ).length - parseInt( $scope.find( '.slick-dots li span' ).css( 'margin-right' ), 10 );

						// Set Width
						$scope.find( '.slick-dots' ).css( 'width', dotsWrapWidth );
					}
				}, 300 );
			});

		}, // End widgetTestimonialCarousel

		widgetSearch: function( $scope ) {

			var isFound = false;

			$scope.find('.wpr-search-form-input').on( {
				focus: function() {
					$scope.addClass( 'wpr-search-form-input-focus' );
				},
				blur: function() {
					$scope.removeClass( 'wpr-search-form-input-focus' );
				}
			} );
            
            if ( $scope.find('.wpr-category-select').length > 0 ) {
                // Set the selected value on page load
                $(document).ready(function() {
                    var wprSelectedCategory = localStorage.getItem('wprSelectedCategory');
                    if (wprSelectedCategory) {
						$scope.find('.wpr-category-select option').each(function() {
							if ($(this).val() === wprSelectedCategory) {
								isFound = true;
								$scope.find('.wpr-category-select').val(wprSelectedCategory);
								return false; // Breaks out of the .each() loop
							} else {
								$scope.find('.wpr-category-select').val(0);
							}
						});
                    }
                });

                $scope.find('.wpr-category-select').on('change', function(e) {
                    
                    var selectedValue = $(this).val();
                    localStorage.setItem('wprSelectedCategory', selectedValue);

			        if ($scope.find('.wpr-search-form-input').attr('ajax-search') === 'yes') {
                        postsOffset = 0;
                        $scope.find('.wpr-data-fetch').hide();
                        $scope.find('.wpr-data-fetch ul').html('');
                        ajaxSearchCall($scope.find('.wpr-search-form-input'), postsOffset, e);
                    }
                });
            }

			// if ( $scope.find('.wpr-search-input-hidden') ) {
			// 	$scope.find('.wpr-search-form-submit').on('click', function(e) {
			// 		e.preventDefault();
			// 		if ($scope.find('input').hasClass('wpr-search-input-hidden')) {
			// 			$scope.find('input').removeClass('wpr-search-input-hidden');
			// 		} else {
			// 			$scope.find('input').addClass('wpr-search-input-hidden');
			// 			$scope.find('.wpr-search-form-input').val('');
			// 			$scope.find('.wpr-data-fetch').slideUp(200);
			// 			setTimeout(function() {
			// 				$scope.find('.wpr-data-fetch ul').html('');
			// 				$scope.find('.wpr-no-results').remove();
			// 			}, 400);
			// 			postsOffset = 0;
			// 		}
			// 	});
			// }

			var prevData;
			var searchTimeout = null;

			function ajaxSearchCall(thisObject, postsOffset, e) {
				if ( e.which === 13 ) {
					return false;
				}

				if (searchTimeout != null) {
					clearTimeout(searchTimeout);
				}
				var optionPostType = ($scope.find('.wpr-category-select').length > 0 && $scope.find('.wpr-category-select').find('option:selected').data('post-type'));
				var wprTaxonomyType = $scope.find('.wpr-search-form-input').attr('wpr-taxonomy-type');

				if ( $scope.find('.wpr-category-select').length > 0) {
					if (!wprTaxonomyType) {
						if ($scope.find('.wpr-search-form-input').attr('wpr-query-type') == 'product') {
							wprTaxonomyType = 'product_cat';
						} else {
							wprTaxonomyType = 'category';
						}
					}
				}

				searchTimeout = setTimeout(() => {
					var thisValue = thisObject.val();
					$.ajax({
						type: 'POST',
						url: WprConfig.ajaxurl,
						data: { 
							action: 'wpr_data_fetch',
							nonce: WprConfig.nonce,
							wpr_keyword: $scope.find('.wpr-search-form-input').val(),
							wpr_query_type: $scope.find('.wpr-search-form-input').attr('wpr-query-type'),
							wpr_option_post_type: optionPostType ? $scope.find('.wpr-category-select').find('option:selected').data('post-type') : '',
							wpr_taxonomy_type: wprTaxonomyType,
							wpr_category: $scope.find('.wpr-category-select').length > 0 ? $scope.find('.wpr-category-select').val() : '',
							wpr_number_of_results: $scope.find('.wpr-search-form-input').attr('number-of-results'),
							wpr_search_results_offset: postsOffset,
							wpr_show_description: $scope.find('.wpr-search-form-input').attr('show-description'),
							wpr_number_of_words: $scope.find('.wpr-search-form-input').attr('number-of-words'),
							wpr_show_ajax_thumbnail: $scope.find('.wpr-search-form-input').attr('show-ajax-thumbnails'),
							wpr_show_view_result_btn: $scope.find('.wpr-search-form-input').attr('show-view-result-btn'),
							wpr_view_result_text: $scope.find('.wpr-search-form-input').attr('view-result-text'),
							wpr_no_results: $scope.find('.wpr-search-form-input').attr('no-results'),
							wpr_exclude_without_thumb: $scope.find('.wpr-search-form-input').attr('exclude-without-thumb'),
							wpr_ajax_search_link_target: $scope.find('.wpr-search-form-input').attr('link-target'),
							// wpr_ajax_search_img_size: $scope.find('.wpr-search-form-input').attr('ajax-search-img-size')
						},
						success: function(data) {
							$scope.closest('section').addClass('wpr-section-z-index');
							if ( $scope.find('.wpr-data-fetch ul').html() === '' ) {
								$scope.find( '.wpr-pagination-loading' ).hide();
								$scope.find('.wpr-data-fetch ul').html( data );
								$scope.find('.wpr-no-more-results').fadeOut(100);
								setTimeout(function() {
									if (!data.includes('wpr-no-results')) {
										$scope.find('.wpr-ajax-search-pagination').css('display', 'flex');
										if ( $scope.find('.wpr-data-fetch ul').find('li').length < $scope.find('.wpr-search-form-input').attr('number-of-results') ||
											$scope.find('.wpr-data-fetch ul').find('li').length == $scope.find('.wpr-data-fetch ul').find('li').data('number-of-results')) {
											$scope.find('.wpr-ajax-search-pagination').css('display', 'none');
											$scope.find('.wpr-load-more-results').fadeOut(100);
										} else {
											$scope.find('.wpr-ajax-search-pagination').css('display', 'flex');
											$scope.find('.wpr-load-more-results').fadeIn(100);
										}
									} else {
										$scope.find('.wpr-ajax-search-pagination').css('display', 'none');
									}
								}, 100);
								prevData = data;
							} else {
								if ( data != prevData ) {
									prevData = data;
									if (data.includes('wpr-no-results')) {
										$scope.find('.wpr-ajax-search-pagination').css('display', 'none');
										$scope.find('.wpr-data-fetch ul').html('');
										$scope.closest('section').removeClass('wpr-section-z-index');
									} else {
										$scope.find('.wpr-ajax-search-pagination').css('display', 'flex');
									}

									$scope.find('.wpr-data-fetch ul').append( data );

									if (data == '') {
										$scope.find('.wpr-load-more-results').fadeOut(100);
										setTimeout(function() {
											$scope.find( '.wpr-pagination-loading' ).hide();
											$scope.find('.wpr-no-more-results').fadeIn(100);
										}, 100);
									} else {
										$scope.find( '.wpr-pagination-loading' ).hide();
										$scope.find('.wpr-load-more-results').show();
									}

									if ($scope.find('.wpr-data-fetch ul').find('li').length < $scope.find('.wpr-search-form-input').attr('number-of-results')) {
										$scope.find('.wpr-load-more-results').fadeOut(100);
										setTimeout(function() {
											$scope.find( '.wpr-pagination-loading' ).hide();
											$scope.find('.wpr-no-more-results').fadeIn(100);
										}, 100);
									} else {
										$scope.find('.wpr-load-more-results').show();
									}

									if ( $scope.find('.wpr-data-fetch ul').find('li').length == $scope.find('.wpr-data-fetch ul').find('li').data('number-of-results') ) {
										$scope.find('.wpr-load-more-results').fadeOut(100);
										setTimeout(function() {
											$scope.find( '.wpr-pagination-loading' ).hide();
											$scope.find('.wpr-no-more-results').fadeIn(100);
										}, 100);
									} else {
										$scope.find('.wpr-load-more-results').show();
									}
									// $scope.find( '.wpr-pagination-loading' ).hide();
								}
							}

							if (data.includes('wpr-no-results')) {
								$scope.find('.wpr-ajax-search-pagination').css('display', 'none');
								$scope.find('.wpr-load-more-results').fadeOut();
							} else {
								$scope.find('.wpr-ajax-search-pagination').css('display', 'flex');
							}
							
							if (thisValue.length > 2) {
								$scope.find('.wpr-data-fetch').slideDown(200);
								$scope.find('.wpr-data-fetch ul').fadeTo(200, 1);
							} else {
								$scope.find('.wpr-data-fetch').slideUp(200);
								$scope.find('.wpr-data-fetch ul').fadeTo(200, 0);
								setTimeout(function() {
									$scope.find('.wpr-data-fetch ul').html('');
									$scope.find('.wpr-no-results').remove();
									$scope.closest('section').removeClass('wpr-section-z-index');
								}, 600);
								postsOffset = 0;
							}
						},
						error: function(error) {
							console.log(error);
						}
					});
				}, 400);
			}

			if ($scope.find('.wpr-search-form-input').attr('ajax-search') === 'yes') {

				$scope.find('.wpr-search-form').attr('autocomplete', 'off');
				
				var postsOffset = 0;
				// $scope.find('.wpr-data-fetch ul').on('scroll', function(e) { 
				// 	if ( $(this).scrollTop() + $(this).innerHeight() >=  $(this)[0].scrollHeight ) {
				// 		postsOffset += +$scope.find('.wpr-search-form-input').attr('number-of-results');
				// 		ajaxSearchCall($scope.find('.wpr-search-form-input'), postsOffset, e);
				// 	}
				// });

				$scope.find('.wpr-load-more-results').on('click', function(e) {
					postsOffset += +$scope.find('.wpr-search-form-input').attr('number-of-results');
					$scope.find('.wpr-load-more-results').hide();
					$scope.find( '.wpr-pagination-loading' ).css( 'display', 'inline-block' );
					ajaxSearchCall($scope.find('.wpr-search-form-input'), postsOffset, e);
				});

				$scope.find('.wpr-search-form-input').on('keyup', function(e) {
					postsOffset = 0;
					$scope.find('.wpr-data-fetch').hide();
					$scope.find('.wpr-data-fetch ul').html('');
					ajaxSearchCall($(this), postsOffset, e);
				});
	
				$scope.find('.wpr-data-fetch').on('click', '.wpr-close-search', function() {
					$scope.find('.wpr-search-form-input').val('');
					$scope.find('.wpr-data-fetch').slideUp(200);
					setTimeout(function() {
						$scope.find('.wpr-data-fetch ul').html('');
						$scope.find('.wpr-no-results').remove();
						$scope.closest('section').removeClass('wpr-section-z-index');
					}, 400);
					postsOffset = 0;
				});

				$('body').on('click', function(e) {
					if ( !e.target.classList.value.includes('wpr-data-fetch') && !e.target.closest('.wpr-data-fetch') ) {
						if ( !e.target.classList.value.includes('wpr-search-form') && !e.target.closest('.wpr-search-form') ) {
							$scope.find('.wpr-search-form-input').val('');
							$scope.find('.wpr-data-fetch').slideUp(200);
							setTimeout(function() {
								$scope.find('.wpr-data-fetch ul').html('');
								$scope.find('.wpr-no-results').remove();
								$scope.closest('section').removeClass('wpr-section-z-index');
							}, 400);
							postsOffset = 0;
						}
					}
				});

				var mutationObserver = new MutationObserver(function(mutations) {
					$scope.find('.wpr-data-fetch li').on('click', function() {
						var itemUrl = $(this).find('a').attr('href');
						var itemUrlTarget = $(this).find('a').attr('target');
						window.open(itemUrl, itemUrlTarget).focus();
					});
				});

				// Listen to Mini Cart Changes
				mutationObserver.observe($scope[0], {
					childList: true,
					subtree: true,
				});
			}

		}, // End widgetSearch

		widgetAdvancedText: function( $scope ) {

			if ( $scope.hasClass('wpr-advanced-text-style-animated') ) {
				var animText = $scope.find( '.wpr-anim-text' ),
					animLetters = $scope.find( '.wpr-anim-text-letters' ),
					animDuration = animText.attr( 'data-anim-duration' ),
					animDurationData = animDuration.split( ',' ),
					animLoop = animText.attr( 'data-anim-loop' ),
					animTextLength = animText.find('b').length,
					animTextCount = 0;

				animText.find('b').first().addClass('wpr-anim-text-visible');
					
				// set animation timing
				var animDuration = parseInt( animDurationData[0], 10),
					animDelay = parseInt( animDurationData[1], 10),
					//type effect
					selectionDuration = 500,
					typeAnimationDelay = selectionDuration + 800;

				initHeadline();
			}

			function loadLongShadow() {

				var $clippedText = $scope.find( '.wpr-clipped-text' ),
					clippedOption = $clippedText.data('clipped-options'),
					currentDeviceMode = elementorFrontend.getCurrentDeviceMode();

				if ( clippedOption ) {
					var longShadowSize = clippedOption.longShadowSize,
						longShadowSizeTablet = clippedOption.longShadowSizeTablet,
						longShadowSizeMobile = clippedOption.longShadowSizeMobile;

					if ('desktop' === currentDeviceMode ) {
					   longShadowSize = clippedOption.longShadowSize;
					}

					if ('tablet' === currentDeviceMode && longShadowSizeTablet ) {
					   longShadowSize = longShadowSizeTablet;
					}

					if ('mobile' === currentDeviceMode && longShadowSizeMobile ) {
					   longShadowSize = longShadowSizeMobile;
					}

					$clippedText.find('.wpr-clipped-text-long-shadow').attr('style','text-shadow:'+longShadow( clippedOption.longShadowColor, longShadowSize, clippedOption.longShadowDirection ));
				}
			}

			loadLongShadow();

			$(window).on('resize', function() {
				loadLongShadow();
			});

			function initHeadline() {
				//insert <i> element for each letter of a changing word
				singleLetters(animLetters.find('b'));
				//initialise headline animation
				animateHeadline(animText);
			}

			function singleLetters($words) {
				$words.each(function() {
					var word = $(this),
						letters = word.text().split(''),
						selected = word.hasClass('wpr-anim-text-visible');
					for (var i in letters) {
						var letter = letters[i].replace(/ /g, '&nbsp;');
					
						letters[i] = (selected) ? '<i class="wpr-anim-text-in">' + letter + '</i>': '<i>' + letter + '</i>';
					}
					var newLetters = letters.join('');
					word.html(newLetters).css('opacity', 1);
				});
			}

			function animateHeadline($headlines) {
				var duration = animDelay;
				$headlines.each(function(){
					var headline = $(this),
						spanWrapper = headline.find('.wpr-anim-text-inner');
					
					if (headline.hasClass('wpr-anim-text-type-clip')){
						var newWidth = spanWrapper.outerWidth();
							spanWrapper.css('width', newWidth);
					}

					//trigger animation
					setTimeout(function(){
						hideWord( headline.find('.wpr-anim-text-visible').eq(0) );
					}, duration);

					// Fix Bigger Words Flip
					if ( headline.hasClass( 'wpr-anim-text-type-rotate-1' ) ) {
						spanWrapper.find( 'b' ).each(function() {
							if ( $(this).outerWidth() > spanWrapper.outerWidth() ) {
								spanWrapper.css( 'width', $(this).outerWidth() );
							}
						});
					}
				});
			}

			function hideWord($word) {
				var nextWord = takeNext($word);
				
				if ( animLoop !== 'yes' ) {

					animTextCount++;
					if ( animTextCount === animTextLength ) {
						return;
					}

				}
			   
				if ( $word.parents('.wpr-anim-text').hasClass('wpr-anim-text-type-typing') ) {
					var parentSpan = $word.parent('.wpr-anim-text-inner');
					parentSpan.addClass('wpr-anim-text-selected').removeClass('waiting'); 
					setTimeout(function(){ 
						parentSpan.removeClass('wpr-anim-text-selected'); 
						$word.removeClass('wpr-anim-text-visible').addClass('wpr-anim-text-hidden').children('i').removeClass('wpr-anim-text-in').addClass('wpr-anim-text-out');
					}, selectionDuration);
					setTimeout(function(){ showWord(nextWord, animDuration) }, typeAnimationDelay);
				
				} else if ( $word.parents('.wpr-anim-text').hasClass('wpr-anim-text-letters') ) {

					var bool = ( $word.children( 'i' ).length >= nextWord.children( 'i' ).length ) ? true : false;
						hideLetter($word.find('i').eq(0), $word, bool, animDuration);
						showLetter(nextWord.find('i').eq(0), nextWord, bool, animDuration);

				}  else if ( $word.parents('.wpr-anim-text').hasClass('wpr-anim-text-type-clip') ) {
					$word.parents('.wpr-anim-text-inner').animate({ width : '2px' }, animDuration, function(){
						switchWord($word, nextWord);
						showWord(nextWord);
					});

				} else {
					switchWord($word, nextWord);
					setTimeout(function(){ hideWord(nextWord) }, animDelay);
				}

			}

			function showWord($word, $duration) {
				if ( $word.parents( '.wpr-anim-text' ).hasClass( 'wpr-anim-text-type-typing' ) ) {
					showLetter( $word.find( 'i' ).eq(0), $word, false, $duration );
					$word.addClass( 'wpr-anim-text-visible' ).removeClass( 'wpr-anim-text-hidden' );

				} else if ( $word.parents( '.wpr-anim-text' ).hasClass( 'wpr-anim-text-type-clip' ) ) {
					$word.parents( '.wpr-anim-text-inner' ).animate({ 'width' : $word.outerWidth() }, animDuration, function() { 
						setTimeout( function() {
							hideWord($word);
						}, animDelay ); 
					});
				}
			}

			function hideLetter($letter, $word, $bool, $duration) {
				$letter.removeClass('wpr-anim-text-in').addClass('wpr-anim-text-out');
				
				if ( !$letter.is(':last-child') ) {
					setTimeout(function(){ hideLetter($letter.next(), $word, $bool, $duration); }, $duration);  
				} else if ( $bool ) { 
					setTimeout(function(){ hideWord(takeNext($word)) }, animDelay);
				}

				if ( $letter.is(':last-child') ) {
					var nextWord = takeNext($word);
					switchWord($word, nextWord);
				} 
			}

			function showLetter($letter, $word, $bool, $duration) {
				$letter.addClass('wpr-anim-text-in').removeClass('wpr-anim-text-out');
				
				if(!$letter.is(':last-child')) { 
					setTimeout(function(){ showLetter($letter.next(), $word, $bool, $duration); }, $duration); 
				} else { 
					if($word.parents('.wpr-anim-text').hasClass('wpr-anim-text-type-typing')) { setTimeout(function(){ $word.parents('.wpr-anim-text-inner').addClass('waiting'); }, 200);}
					if(!$bool) { setTimeout(function(){ hideWord($word) }, animDelay) }
				}
			}

			function takeNext($word) {
				return (!$word.is(':last-child')) ? $word.next() : $word.parent().children().eq(0);
			}

			function takePrev($word) {
				return (!$word.is(':first-child')) ? $word.prev() : $word.parent().children().last();
			}

			function switchWord($oldWord, $newWord) {
				$oldWord.removeClass('wpr-anim-text-visible').addClass('wpr-anim-text-hidden');
				$newWord.removeClass('wpr-anim-text-hidden').addClass('wpr-anim-text-visible');
			}

			function longShadow( shadowColor, shadowSize, shadowDirection ) {
			 
				var textshadow = '';

				for ( var i = 0, len = shadowSize; i < len; i++ ) {
					switch ( shadowDirection ) {
						case 'top':
							textshadow += '0 -'+ i +'px 0 '+ shadowColor +',';
						break;

						case 'right':
							textshadow += i +'px 0 0 '+ shadowColor +',';
						break;

						case 'bottom':
							textshadow += '0 '+ i +'px 0 '+ shadowColor +',';
						break;

						case 'left':
							textshadow += '-'+ i +'px 0 0 '+ shadowColor +',';
						break;

						case 'top-left':
							textshadow += '-'+ i +'px -'+ i +'px 0 '+ shadowColor +',';
						break;

						case 'top-right':
							textshadow += i +'px -'+ i +'px 0 '+ shadowColor +',';
						break;

						case 'bottom-left':
							textshadow += '-'+ i +'px '+ i +'px 0 '+ shadowColor +',';
						break;

						case 'bottom-right':
							textshadow += i +'px '+ i +'px 0 '+ shadowColor +',';
						break;

						default:
							textshadow += i +'px '+ i +'px 0 '+ shadowColor +',';
						break;
					}
				}

				textshadow = textshadow.slice(0, -1);

				return textshadow;
			}

		}, // End widgetAdvancedText

		widgetProgressBar: function( $scope ) {

			var $progressBar = $scope.find( '.wpr-progress-bar' ),
				prBarCircle = $scope.find( '.wpr-prbar-circle' ),
				$prBarCircleSvg = prBarCircle.find('.wpr-prbar-circle-svg'),
				$prBarCircleLine =  $prBarCircleSvg.find('.wpr-prbar-circle-line'),
				$prBarCirclePrline = $scope.find( '.wpr-prbar-circle-prline' ),
				prBarHrLine = $progressBar.find('.wpr-prbar-hr-line-inner'),
				prBarVrLine = $progressBar.find('.wpr-prbar-vr-line-inner'),
				prBarOptions = $progressBar.data('options'),
				prBarCircleOptions = prBarCircle.data('circle-options'),
				prBarCounter = $progressBar.find('.wpr-prbar-counter-value'),
				prBarCounterValue = prBarOptions.counterValue,
				prBarCounterValuePersent = prBarOptions.counterValuePersent,
				prBarAnimDuration = prBarOptions.animDuration,
				prBarAnimDelay = prBarOptions.animDelay,
				prBarLoopDelay = +prBarOptions.loopDelay,
				currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
				numeratorData = {
					toValue: prBarCounterValue,
					duration: prBarAnimDuration,
				};

			if ( 'yes' === prBarOptions.counterSeparator ) {
				numeratorData.delimiter = ',';
			}


			function isInViewport( $selector ) {
				if ( $selector.length ) {
					var elementTop = $selector.offset().top,
					elementBottom = elementTop + $selector.outerHeight(),
					viewportTop = $(window).scrollTop(),
					viewportBottom = viewportTop + $(window).height();

					if ( elementTop > $(window).height() ) {
						elementTop += 50;
					}

					return elementBottom > viewportTop && elementTop < viewportBottom;
				}
			};

			function progressBar() {

				if ( isInViewport( prBarVrLine ) ) {
					prBarVrLine.css({
						'height': prBarCounterValuePersent + '%'
					});
				}

				if ( isInViewport( prBarHrLine ) ) {
					prBarHrLine.css({
						'width': prBarCounterValuePersent + '%'
					});
				}

				if ( isInViewport( prBarCircle ) ) {
					var circleDashOffset = prBarCircleOptions.circleOffset;
					
					$prBarCirclePrline.css({
						'stroke-dashoffset': circleDashOffset
					});
				}

				// Set Delay
				if ( isInViewport( prBarVrLine ) || isInViewport( prBarHrLine ) || isInViewport( prBarCircle ) ) {
					setTimeout(function() {
						prBarCounter.numerator( numeratorData );
					}, prBarAnimDelay );
				}
			
			}

			progressBar();

			if (prBarOptions.loop === 'yes') {
				setInterval(function() {

					if ( isInViewport( prBarVrLine ) ) {
						prBarVrLine.css({
							'height': 0 + '%'
						});
					}
	
					if ( isInViewport( prBarHrLine ) ) {
						prBarHrLine.css({
							'width': 0 + '%'
						});
					}
	
					if ( isInViewport( prBarCircle ) ) {
						var circleDashOffset = prBarCircleOptions.circleOffset;
						
						$prBarCirclePrline.css({
							'stroke-dashoffset': $prBarCirclePrline.css('stroke-dasharray')
						});
					}

					// Set Delay
					if ( isInViewport( prBarVrLine ) || isInViewport( prBarHrLine ) || isInViewport( prBarCircle ) ) {
						setTimeout(function() {
							prBarCounter.numerator( {
								toValue: 0,
								duration: prBarAnimDuration,
							} );
						}, prBarAnimDelay);
					}

					setTimeout(function() {
						progressBar();
					}, prBarAnimDuration + prBarAnimDelay);
				}, (prBarAnimDuration + prBarAnimDelay) * prBarLoopDelay);
			}

			 $(window).on('scroll', function() {
				progressBar();
			});
				  
		}, // End widgetProgressBar

		widgetImageHotspots: function( $scope ) {

			var $imgHotspots = $scope.find( '.wpr-image-hotspots' ),
				hotspotsOptions = $imgHotspots.data('options'),
				$hotspotItem = $imgHotspots.find('.wpr-hotspot-item'),
				tooltipTrigger = hotspotsOptions.tooltipTrigger;

			if ( 'click' === tooltipTrigger ) {
				$hotspotItem.on( 'click', function() {
					if ( $(this).hasClass('wpr-tooltip-active') ) {
						$(this).removeClass('wpr-tooltip-active');
					} else {
						$hotspotItem.removeClass('wpr-tooltip-active');
						$(this).addClass('wpr-tooltip-active');
					}
					 event.stopPropagation();
				});

				$(window).on( 'click', function () {
					$hotspotItem.removeClass('wpr-tooltip-active');
				});
		   
			} else if ( 'hover' === tooltipTrigger ) {
				$hotspotItem.on( 'mouseenter', function () {
					$(this).addClass('wpr-tooltip-active');
				});
				
				$hotspotItem.on( 'mouseleave', function () {
					$(this).removeClass('wpr-tooltip-active');
				});

			} else {
				$hotspotItem.addClass('wpr-tooltip-active');
			}

		}, // End widgetImageHotspots

		widgetFlipBox: function( $scope ) {
			
			var $flipBox = $scope.find('.wpr-flip-box'),
				flipBoxTrigger = $flipBox.data('trigger');

			 if ( 'box' === flipBoxTrigger ) {

				$flipBox.find('.wpr-flip-box-front').on( 'click', function() {
					$(this).closest('.wpr-flip-box').addClass('wpr-flip-box-active'); 
				});

				$(window).on( 'click', function () {
					if( $(event.target).closest('.wpr-flip-box').length === 0 ) {
						$flipBox.removeClass('wpr-flip-box-active');
					}
				});
		   
			} else if ( 'btn' == flipBoxTrigger ) {
		  
				$flipBox.find('.wpr-flip-box-btn').on( 'click', function() {
					$(this).closest('.wpr-flip-box').addClass('wpr-flip-box-active');		   
				});

				$(window).on( 'click', function () {
					if( $(event.target).closest('.wpr-flip-box').length === 0 ) {
						$flipBox.removeClass('wpr-flip-box-active');
					}
				});

			  
			} else if ( 'hover' == flipBoxTrigger ) {
		  
				$flipBox.hover(function () {
					$(this).toggleClass('wpr-flip-box-active');
				});

			}

		}, // End widgetFlipBox

		widgetContentTicker: function( $scope ) {
			var $contentTickerSlider = $scope.find( '.wpr-ticker-slider' ),
				$contentTickerMarquee = $scope.find( '.wpr-ticker-marquee' ),
				marqueeData = $contentTickerMarquee.data('options');
			// Slider Columns
			var sliderClass = $scope.attr('class'),
				sliderColumnsDesktop = sliderClass.match(/wpr-ticker-slider-columns-\d/) ? sliderClass.match(/wpr-ticker-slider-columns-\d/).join().slice(-1) : 2,
				sliderColumnsWideScreen = sliderClass.match(/columns--widescreen\d/) ? sliderClass.match(/columns--widescreen\d/).join().slice(-1) : sliderColumnsDesktop,
				sliderColumnsLaptop = sliderClass.match(/columns--laptop\d/) ? sliderClass.match(/columns--laptop\d/).join().slice(-1) : sliderColumnsDesktop,
				sliderColumnsTablet = sliderClass.match(/columns--tablet\d/) ? sliderClass.match(/columns--tablet\d/).join().slice(-1) : 2,
				sliderColumnsTabletExtra = sliderClass.match(/columns--tablet_extra\d/) ? sliderClass.match(/columns--tablet_extra\d/).join().slice(-1) : sliderColumnsTablet,
				sliderColumnsMobileExtra = sliderClass.match(/columns--mobile_extra\d/) ? sliderClass.match(/columns--mobile_extra\d/).join().slice(-1) : sliderColumnsTablet,
				sliderColumnsMobile = sliderClass.match(/columns--mobile\d/) ? sliderClass.match(/columns--mobile\d/).join().slice(-1) : 1,
				dataSlideEffect = $contentTickerSlider.attr('data-slide-effect'),
				sliderSlidesToScroll = 'hr-slide' === dataSlideEffect && sliderClass.match(/wpr-ticker-slides-to-scroll-\d/) ? +(sliderClass.match(/wpr-ticker-slides-to-scroll-\d/).join().slice(-1)) : 1;

			$contentTickerSlider.slick({
				appendArrows : $scope.find('.wpr-ticker-slider-controls'),
				slidesToShow: sliderColumnsDesktop,
				responsive: [
					{
						breakpoint: 10000,
						settings: {
							slidesToShow: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect ) ? 1 : sliderColumnsWideScreen,
							slidesToScroll: sliderSlidesToScroll > sliderColumnsWideScreen ? 1 : sliderSlidesToScroll,
							fade: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect) ? true : false
						}
					},
					{
						breakpoint: 2399,
						settings: {
							slidesToShow: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect ) ? 1 : sliderColumnsDesktop,
							slidesToScroll: sliderSlidesToScroll > sliderColumnsDesktop ? 1 : sliderSlidesToScroll,
							fade: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect) ? true : false
						}
					},
					{
						breakpoint: 1221,
						settings: {
							slidesToShow: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect ) ? 1 : sliderColumnsLaptop,
							slidesToScroll: sliderSlidesToScroll > sliderColumnsLaptop ? 1 : sliderSlidesToScroll,
							fade: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect) ? true : false
						}
					},
					{
						breakpoint: 1200,
						settings: {
							slidesToShow: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect ) ? 1 : sliderColumnsTabletExtra,
							slidesToScroll: sliderSlidesToScroll > sliderColumnsTabletExtra ? 1 : sliderSlidesToScroll,
							fade: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect) ? true : false
						}
					},
					{
						breakpoint: 1024,
						settings: {
							slidesToShow: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect ) ? 1 : sliderColumnsTablet,
							slidesToScroll: sliderSlidesToScroll > sliderColumnsTablet ? 1 : sliderSlidesToScroll,
							fade: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect) ? true : false
						}
					},
					{
						breakpoint: 880,
						settings: {
							slidesToShow: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect ) ? 1 : sliderColumnsMobileExtra,
						 	slidesToScroll: sliderSlidesToScroll > sliderColumnsMobileExtra ? 1 : sliderSlidesToScroll,
							fade: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect) ? true : false
						}
					},
					{
						breakpoint: 768,
						settings: {
							slidesToShow: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect ) ? 1 : sliderColumnsMobile,
							slidesToScroll: sliderSlidesToScroll > sliderColumnsMobile ? 1 : sliderSlidesToScroll,
							fade: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect) ? true : false
						}
					}
				],
			});

			$contentTickerMarquee.marquee(marqueeData);

		}, // End widgetContentTicker

		widgetTabs: function( $scope ) {

			var $tabs = $( '.wpr-tabs', $scope ).first(),
				$tabList = $( '.wpr-tabs-wrap', $tabs ).first(),
				$contentWrap = $( '.wpr-tabs-content-wrap', $tabs ).first(),
				$tabList = $( '> .wpr-tab', $tabList ),
				$contentList = $( '> .wpr-tab-content', $contentWrap ),
				tabsData = $tabs.data('options');

			// Active Tab
			var activeTabIndex = tabsData.activeTab - 1;

			// ?active_tab=tab-index#your-id
			var activeTabIndexFromLocation = window.location.href.indexOf("active_tab=");

			if (activeTabIndexFromLocation > -1) {
				activeTabIndex = +window.location.href.substring(activeTabIndexFromLocation,  window.location.href.lastIndexOf("#")).replace("active_tab=", '') - 1;
			}

				$tabList.eq( activeTabIndex ).addClass( 'wpr-tab-active' );
				$contentList.eq( activeTabIndex ).addClass( 'wpr-tab-content-active wpr-animation-enter' );

			if ( tabsData.autoplay === 'yes' ) {
				
				var startIndex = activeTabIndex;

				var autoplayInterval = setInterval( function() {

					if ( startIndex < $tabList.length - 1 ) {
						startIndex++;
					} else {
						startIndex = 0;
					}

					wprTabsSwitcher( startIndex );

				}, tabsData.autoplaySpeed );
			}

			if ( 'hover' === tabsData.trigger ) {
				wprTabsHover();
			} else {
				wprTabsClick();
			}

			// Tab Switcher
			function wprTabsSwitcher( index ) {

				var activeTab = $tabList.eq( index ),
					activeContent = $contentList.eq( index ),
					activeContentHeight = 'auto';

				$contentWrap.css( { 'height': $contentWrap.outerHeight( true ) } );

				$tabList.removeClass( 'wpr-tab-active' );
				activeTab.addClass( 'wpr-tab-active' );

				$contentList.removeClass( 'wpr-tab-content-active wpr-animation-enter' );

				activeContentHeight = activeContent.outerHeight( true );
				activeContentHeight += parseInt( $contentWrap.css( 'border-top-width' ) ) + parseInt( $contentWrap.css( 'border-bottom-width' ) );


				activeContent.addClass( 'wpr-tab-content-active wpr-animation-enter' );

				$contentWrap.css({ 'height': activeContentHeight });

				setTimeout( function() {  
					$contentWrap.css( { 'height': 'auto' } );
				}, 500 );

			}

			// Tab Click Event
			function wprTabsClick() {

				$tabList.on( 'click', function() {

					var tabIndex = $( this ).data( 'tab' ) - 1;
					
					clearInterval( autoplayInterval );
					wprTabsSwitcher( tabIndex );

				});

			}

			// Tab Hover Event
			function wprTabsHover() {
			   $tabList.hover( function () {

					var tabIndex = $( this ).data( 'tab' ) - 1;

					clearInterval( autoplayInterval );
					wprTabsSwitcher( tabIndex );
				  
				});
			}

		}, // End widgetTabs

		widgetContentToogle: function( $scope ) {

			var $contentToggle = $( '.wpr-content-toggle', $scope ).first(),
				$switcherContainer = $( '.wpr-switcher-container', $contentToggle ).first(),
				$switcherWrap = $( '.wpr-switcher-wrap', $contentToggle ).first(),
				$contentWrap = $( '.wpr-switcher-content-wrap', $contentToggle ).first(),
				$switcherBg = $( '> .wpr-switcher-bg', $switcherWrap ),
				$switcherList = $( '> .wpr-switcher', $switcherWrap ),
				$contentList = $( '> .wpr-switcher-content', $contentWrap );

			// Active Tab
			var activeSwitcherIndex = parseInt( $switcherContainer.data('active-switcher') ) - 1;
			
			$switcherList.eq( activeSwitcherIndex ).addClass( 'wpr-switcher-active' );
			$contentList.eq( activeSwitcherIndex ).addClass( 'wpr-switcher-content-active wpr-animation-enter' );
	  
			function wprSwitcherBg( index ) {
				
				if ( ! $scope.hasClass( 'wpr-switcher-label-style-outer' ) ) {
				
					var switcherWidth = 100 / $switcherList.length,
						switcherBgDistance = index * switcherWidth;

					$switcherBg.css({
						'width' : switcherWidth + '%',
						'left': switcherBgDistance + '%'
					});
				}
			  
			}

			wprSwitcherBg( activeSwitcherIndex );

			// Tab Switcher
			function wprTabsSwitcher( index ) {
				var activeSwitcher = $switcherList.eq( index ),
					activeContent = $contentList.eq( index ),
					activeContentHeight = 'auto';

				// Switcher
				wprSwitcherBg( index );

				if ( ! $scope.hasClass( 'wpr-switcher-label-style-outer' ) ) {
					$switcherList.removeClass( 'wpr-switcher-active' );
					activeSwitcher.addClass( 'wpr-switcher-active' );

					if ( $scope.hasClass( 'wpr-switcher-style-dual' ) ) {
						$switcherContainer.attr( 'data-active-switcher', index + 1 );
					}
				}

				// Tabs
				$contentWrap.css( { 'height': $contentWrap.outerHeight( true ) } );

				$contentList.removeClass( 'wpr-switcher-content-active wpr-animation-enter' );

				activeContentHeight = activeContent.outerHeight( true );
				activeContentHeight += parseInt( $contentWrap.css( 'border-top-width' ) ) + parseInt( $contentWrap.css( 'border-bottom-width' ) );

				activeContent.addClass( 'wpr-switcher-content-active wpr-animation-enter' );

				$contentWrap.css({ 'height': activeContentHeight });

				setTimeout( function() {  
					$contentWrap.css( { 'height': 'auto' } );
				}, 500 );

			}

			// Tab Click Event
			function wprTabsClick() {

				// Outer Labels
				if ( $scope.hasClass( 'wpr-switcher-label-style-outer' ) ) {
					$switcherWrap.on( 'click', function() {
						var activeSwitcher = $switcherWrap.find( '.wpr-switcher-active' );

						if ( 1 === parseInt( activeSwitcher.data( 'switcher'), 10 ) ) {
							// Reset
							$switcherWrap.children( '.wpr-switcher' ).eq(0).removeClass( 'wpr-switcher-active' );

							// Set Active
							$switcherWrap.children( '.wpr-switcher' ).eq(1).addClass( 'wpr-switcher-active' );
							$switcherWrap.closest( '.wpr-switcher-container' ).attr( 'data-active-switcher', 2 );
							wprTabsSwitcher( 1 );

						} else if ( 2 === parseInt( activeSwitcher.data( 'switcher'), 10 ) ) {
							// Reset
							$switcherWrap.children( '.wpr-switcher' ).eq(1).removeClass( 'wpr-switcher-active' );

							// Set Active
							$switcherWrap.children( '.wpr-switcher' ).eq(0).addClass( 'wpr-switcher-active' );
							$switcherWrap.closest( '.wpr-switcher-container' ).attr( 'data-active-switcher', 1 );
							wprTabsSwitcher( 0 );
						}
					 
						// wprTabsSwitcher( switcherIndex );

					});

				// Inner Labels / Multi Labels
				} else {
					$switcherList.on( 'click', function() {

						var switcherIndex = $( this ).data( 'switcher' ) - 1;
					 
						wprTabsSwitcher( switcherIndex );

					});
				}
			}

			wprTabsClick();

		}, // End widgetContentToogle

		widgetBackToTop: function($scope) {
			var sttBtn = $scope.find( '.wpr-stt-btn' ),
				settings = sttBtn.attr('data-settings');
			
			// Get Settings	
			settings = JSON.parse(settings);

			if ( settings.fixed === 'fixed' ) {

				if ( 'none' !== settings.animation ) {
					sttBtn.css({
						'opacity' : '0'
					});

					if ( settings.animation ==='slide' ) {
						sttBtn.css({
							'margin-bottom': '-100px',
						});
					}
				}

				// Run on Load
				scrollToTop($(window).scrollTop(), sttBtn, settings);

				// Run on Scroll
				$(window).scroll(function() {
					scrollToTop($(this).scrollTop(), sttBtn, settings);
				});
			} // end fixed check
			 
			// Click to Scroll Top
			sttBtn.on('click', function() {
				$('html, body').animate({ scrollTop : 0}, settings.scrolAnim );
				return false;
			});

			function scrollToTop( scrollTop, button, settings ) {
				// Show
				if ( scrollTop > settings.animationOffset ) {
					
					if ( 'fade' === settings.animation ) {
	 					sttBtn.stop().css('visibility', 'visible').animate({
	 						'opacity' : '1'
	 					}, settings.animationDuration);
					} else if ( 'slide' === settings.animation ){
						sttBtn.stop().css('visibility', 'visible').animate({
							'opacity' : '1',
							'margin-bottom' : 0
						}, settings.animationDuration);
					} else {
						sttBtn.css('visibility', 'visible');
					}

				// Hide
				} else {

					if ( 'fade' === settings.animation ) {
						sttBtn.stop().animate({'opacity': '0'}, settings.animationDuration);
					} else if (settings.animation === 'slide') {
						sttBtn.stop().animate({
							'margin-bottom' : '-100px',
							'opacity' : '0'
						}, settings.animationDuration);
					} else {
						sttBtn.css('visibility', 'hidden');
					}

				}
			}

		}, // End of Back to Top
        
        widgetLottieAnimations: function($scope) {
			var lottieAnimations = $scope.find('.wpr-lottie-animations'),
				lottieAnimationsWrap = $scope.find('.wpr-lottie-animations-wrapper'),
				lottieJSON = JSON.parse(lottieAnimations.attr('data-settings'));

			var animation = lottie.loadAnimation({
			  container: lottieAnimations[0], // Required
			  path: lottieAnimations.attr('data-json-url'), // Required
			  renderer: lottieJSON.lottie_renderer, // Required
			  loop: 'yes' === lottieJSON.loop ? true : false, // Optional
			  autoplay: 'yes' === lottieJSON.autoplay ? true : false
			});

			animation.setSpeed(lottieJSON.speed);

			if( lottieJSON.reverse ) {
				animation.setDirection(-1);
			} 

			animation.addEventListener('DOMLoaded', function () {
				
				if ( 'hover' !== lottieJSON.trigger && 'none' !== lottieJSON.trigger ) {
				
				// if ( 'viewport' === lottieJSON.trigger ) {
					initLottie('load');
					$(window).on('scroll', initLottie);
				}
				
                if ( 'hover' === lottieJSON.trigger ) {
                    animation.pause();
                    lottieAnimations.hover(function () {
                        animation.play();
                    }, function () {
                        animation.pause();
                    });
                }

				function initLottie(event) {
					animation.pause();

					if (typeof lottieAnimations[0].getBoundingClientRect === "function") {
											
						var height = document.documentElement.clientHeight;
						var scrollTop = (lottieAnimations[0].getBoundingClientRect().top)/height * 100;
						var scrollBottom = (lottieAnimations[0].getBoundingClientRect().bottom)/height * 100;
						var scrollEnd = scrollTop < lottieJSON.scroll_end;
						var scrollStart = scrollBottom > lottieJSON.scroll_start;

						if ( 'viewport' === lottieJSON.trigger ) {
							scrollStart && scrollEnd ? animation.play() : animation.pause();
						}
						
						if ( 'scroll' === lottieJSON.trigger ) {
							if( scrollStart && scrollEnd) {
								animation.pause();
								
								// $(window).scroll(function() {
									// calculate the percentage the user has scrolled down the page
									var scrollPercent = 100 * $(window).scrollTop() / ($(document).height() - $(window).height());
								 
									var scrollPercentRounded = Math.round(scrollPercent);
							
									animation.goToAndStop( (scrollPercentRounded / 100) * 4000); // why 4000
								// });
							}
						};
					}
				}
			});
		}, // End of widgetLottieAnimations

		widgetCharts: function($scope) {
			var chartSettings = JSON.parse($scope.find('.wpr-charts-container').attr('data-settings'));
			var labels = chartSettings.chart_labels;
			var customDatasets = chartSettings.chart_datasets ? JSON.parse(chartSettings.chart_datasets) : '';
			
			var newLegendClickHandler = function (e, legendItem, legend) {
				if ( (chartTypesArray.includes(chartSettings.chart_type) || chartSettings.chart_type === 'radar') ) {
					const index = legendItem.datasetIndex;
					const ci = legend.chart;
					if (ci.isDatasetVisible(index)) {
						ci.hide(index);
						legendItem.hidden = true;
					} else {
						ci.show(index);
						legendItem.hidden = false;
					}
				}
			}
			
			const footer = (tooltipItems) => {
				let sum = 0;
			  
				tooltipItems.forEach(function(tooltipItem) {
				  sum += tooltipItem.parsed.y;
				});
				
				if ( 'bar_horizontal' === chartSettings.chart_type ) {
					sum = 0;
					tooltipItems.forEach(function(tooltipItem) {
						sum += tooltipItem.parsed.x;
					});
				}

				if ( "radar" == chartSettings.chart_type || "pie" == chartSettings.chart_type || "doughnut" == chartSettings.chart_type || "polarArea" == chartSettings.chart_type ) {
					return false;
				}
				return 'Sum: ' + sum;
			};

			var lineDotsWidth = window.innerWidth >= 768 ? chartSettings.line_dots_radius 
									: window.innerWidth <= 767 ? chartSettings.line_dots_radius_mobile : 0;
			var tooltipCaretSize = window.innerWidth >= 768 ? chartSettings.tooltip_caret_size 
									:  window.innerWidth <= 767 ? chartSettings.chart_tooltip_caret_size_mobile : 0;

			var myChart = '';
			var config = '';
			var chartTypesArray = ['bar', 'bar_horizontal', 'line'];
			var globalOptions = {
				responsive: true,
				// layout: { // needs other approach
				// 	padding: chartPadding,
				// },
				showLine: chartSettings.show_lines,
				animation: chartSettings.chart_animation === 'yes' ? true : false,
				animations: {
				  tension: {
					duration: chartSettings.chart_animation_duration,
					easing: chartSettings.animation_transition_type,
					from: 1,
					to: 0,
					loop: chartSettings.chart_animation_loop == 'yes' ? true : false,
				  },
				}, // specify exact inserting way
				events: [chartSettings.trigger_tooltip_on, chartSettings.exclude_dataset_on_click === 'yes' ? 'click' : '',],
				interaction: {
					// Overrides the global setting
					mode: chartSettings.chart_interaction_mode !== undefined ? chartSettings.chart_interaction_mode : 'nearest',
				},
				elements: {
					point: {
						radius: chartSettings.line_dots === 'yes' ? lineDotsWidth : 0 // default to disabled in all datasets
					}
				},
				scales: { // remove if corrupts other chart_types data
					x: {
						reverse: chartSettings.reverse_x == 'yes' ? true : false,
						stacked: chartSettings.stacked_bar_chart == 'yes' ? true : false,
						type: 'bar_horizontal' === chartSettings.chart_type ? chartSettings.data_type : 'category',
						min: chartSettings.min_value !== undefined ? chartSettings.min_value : null,
						max: chartSettings.max_value !== undefined ? chartSettings.max_value : null,
						grid: {
							display: chartSettings.display_x_axis,
							drawBorder: chartSettings.display_x_axis,
							drawOnChartArea: chartSettings.display_x_axis,
							drawTicks: chartSettings.display_x_axis,
							color: chartSettings.axis_grid_line_color_x,
							// borderColor: 'green',
							// borderWidth: 5,
							borderDash: [chartSettings.border_dash_length, chartSettings.border_dash_spacing],
							borderDashOffset: chartSettings.border_dash_offset,
							lineWidth: chartSettings.grid_line_width_x,
						},
						title: {
							display: chartSettings.display_x_axis_title,
							text: chartSettings.x_axis_title,
							color: chartSettings.axis_title_color_x,
							font: {
								size: chartSettings.axis_title_font_size_x,
								family: chartSettings.axis_title_font_family_x,
								style: chartSettings.axis_title_font_style_x,
								weight: chartSettings.axis_title_font_weight_x,
							}
						},
						ticks: {
							stepSize: 'bar_horizontal' === chartSettings.chart_type ? chartSettings.x_step_size : '',
							display: chartSettings.display_x_ticks,
							padding: chartSettings.ticks_padding_x,
							autoSkip: false,
							maxRotation: chartSettings.rotation_x,
							minRotation: chartSettings.rotation_x,
							color: chartSettings.ticks_color_x,
							// backdropColor: 'rgb(128,0,128)',
							font: {
								size: chartSettings.ticks_font_size_x,
								family: chartSettings.ticks_font_family_x,
								style: chartSettings.ticks_font_style_x,
								weight: chartSettings.ticks_font_weight_x,
							}
						},
					},
					y: {
						reverse: chartSettings.reverse_y == 'yes' ? true : false,
						stacked: chartSettings.stacked_bar_chart == 'yes' ? true : false,
						type: 'bar' === chartSettings.chart_type || 'line' === chartSettings.chart_type ? chartSettings.data_type : 'category',
						min: chartSettings.min_value !== undefined ? chartSettings.min_value : null,
						max: chartSettings.max_value !== undefined ? chartSettings.max_value : null,
						grid: {
							display: chartSettings.display_y_axis,
							drawBorder: chartSettings.display_y_axis,
							drawOnChartArea: chartSettings.display_y_axis,
							drawTicks: chartSettings.display_y_axis,
							color: chartSettings.axis_grid_line_color_y,
							// borderColor: 'green',
							// borderWidth: 5,
							borderDash: [chartSettings.border_dash_length, chartSettings.border_dash_spacing],
							borderDashOffset: chartSettings.border_dash_offset,
							lineWidth: chartSettings.grid_line_width_y,
						},
						title: {
							display: chartSettings.display_y_axis_title,
							text: chartSettings.y_axis_title,
							color: chartSettings.axis_title_color_y,
							font: {
								size: chartSettings.axis_title_font_size_y,
								family: chartSettings.axis_title_font_family_y,
								style: chartSettings.axis_title_font_style_y,
								weight: chartSettings.axis_title_font_weight_y,
							}
						},
						ticks: {
							stepSize: chartSettings.y_step_size,
							display: chartSettings.display_y_ticks,
							padding: chartSettings.ticks_padding_y,
							autoSkip: false,
							maxRotation: chartSettings.rotation_y,
							minRotation: chartSettings.rotation_y,
							color: chartSettings.ticks_color_y,
							// backdropColor: 'rgb(128,0,128)',
							font: {
								size: chartSettings.ticks_font_size_y,
								family: chartSettings.ticks_font_family_y,
								style: chartSettings.ticks_font_style_y,
								weight: chartSettings.ticks_font_weight_y,
							}
						},
					},
				},
				plugins: {
					datalabels: {
						color: chartSettings.inner_datalabels_color,
						// backgroundColor: chartSettings.inner_datalabels_bg_color,
						font: {
							// family: chartSettings.inner_datalabels_font_family,
							size: chartSettings.inner_datalabels_font_size,
							style: chartSettings.inner_datalabels_font_style,
							weight: chartSettings.inner_datalabels_font_weight,
						},
					},
					legend: {
						onHover: (event, chartElement) => {
							event.native.target.style.cursor = 'pointer';
						},
						onLeave: (event, chartElement) => {
							event.native.target.style.cursor = 'default';
						},
						onClick: newLegendClickHandler,
						reverse: chartSettings.reverse_legend === 'yes' ? true : false,
						display: chartSettings.show_chart_legend == 'yes' ? true : false,
						position: chartSettings.legend_position !== undefined ? chartSettings.legend_position : 'top',
						align: chartSettings.legend_align !== undefined ? chartSettings.legend_align : 'center',
						labels: {
							usePointStyle: chartSettings.legend_shape == 'point' ? true : false,
							padding: chartSettings.legend_padding,
							boxWidth: chartSettings.legend_box_width,
							boxHeight: chartSettings.legend_font_size,
							color: chartSettings.legend_text_color,
							font: {
								family: chartSettings.legend_font_family,
								size: chartSettings.legend_font_size,
								style: chartSettings.legend_font_style,
								weight: chartSettings.legend_font_weight,
							},
						}
					},
					title: {
						display: 'yes' === chartSettings.show_chart_title ? true : false,
						text: chartSettings.chart_title,
						align: chartSettings.chart_title_align !== undefined ? chartSettings.chart_title_align : 'center',
						position: chartSettings.chart_title_position !== undefined ? chartSettings.chart_title_position : 'top',
						color: chartSettings.chart_title_color !== undefined ? chartSettings.chart_title_color : '#000',
						padding: chartSettings.title_padding,
						font: {
							family: chartSettings.title_font_family,
							size: chartSettings.title_font_size,
							style: chartSettings.title_font_style,
							weight: chartSettings.title_font_weight,
						},
					},
					tooltip: {
						callbacks: {
						  footer: footer,
						},
						enabled: 'yes' === chartSettings.show_chart_tooltip ? true : false,
						position: chartSettings.tooltip_position !== undefined ? chartSettings.tooltip_position : 'nearest',
						padding: chartSettings.tooltip_padding !== undefined ? chartSettings.tooltip_padding : 10,
						caretSize: tooltipCaretSize,
						backgroundColor: chartSettings.chart_tooltip_bg_color !== undefined ? chartSettings.chart_tooltip_bg_color : 'rbga(0, 0, 0, 0.2)',
						titleColor: chartSettings.chart_tooltip_title_color !== undefined ? chartSettings.chart_tooltip_title_color : '#FFF',
						titleFont: {
							family: chartSettings.chart_tooltip_title_font,
							size: chartSettings.chart_tooltip_title_font_size,
						},
						titleAlign: chartSettings.chart_tooltip_title_align,
						titleMarginBottom: chartSettings.chart_tooltip_title_margin_bottom,
						bodyColor: chartSettings.chart_tooltip_item_color !== undefined ? chartSettings.chart_tooltip_item_color : '#FFF',
						bodyFont: {
							family: chartSettings.chart_tooltip_item_font,
							size: chartSettings.chart_tooltip_item_font_size,
						},
						bodyAlign: chartSettings.chart_tooltip_item_align,
						bodySpacing: chartSettings.chart_tooltip_item_spacing,
						boxPadding: 3
					}
				},
			};

			!chartTypesArray.includes(chartSettings.chart_type) && delete globalOptions.scales;

			if ( !chartTypesArray.includes(chartSettings.chart_type) && (chartSettings.chart_type !== 'doughnut' && chartSettings.chart_type !== 'pie') ) {
				
				globalOptions.scales = {
					r: {
						angleLines: {
						  color: chartSettings.angle_lines_color,
						},
						pointLabels: {
							color: chartSettings.point_labels_color_r,
							font: {
								size: chartSettings.point_labels_font_size_r,
								family: chartSettings.point_labels_font_family_r,
								style: chartSettings.point_labels_font_style_r,
								weight: chartSettings.point_labels_font_weight_r,
							}
						},
						ticks: {
							stepSize: chartSettings.r_step_size,
							display: chartSettings.display_r_ticks,
							backdropColor: chartSettings.axis_labels_bg_color,
							backdropPadding: +chartSettings.axis_labels_padding,
							color: chartSettings.axis_labels_color,
						},
						grid: {
							display: chartSettings.display_r_axis,
							drawBorder: chartSettings.display_r_axis,
							drawOnChartArea: chartSettings.display_r_axis,
							drawTicks: chartSettings.display_r_axis,
							color: chartSettings.axis_grid_line_color_r,
							borderDash: [chartSettings.border_dash_length_r, chartSettings.border_dash_spacing_r],
							borderDashOffset: chartSettings.border_dash_offset_r,
							lineWidth: chartSettings.grid_line_width_r,
						}
					},
				}
			}

			if ('custom' === chartSettings.data_source) {
				  const data = {
					labels: labels,
					datasets: JSON.parse(chartSettings.chart_datasets),
				  }; // todo apply conditions if not suitable for other chart_types
				
				  config = {
					plugins: [chartSettings.inner_datalabels ? ChartDataLabels : ''],
					type: chartSettings.chart_type == 'bar_horizontal' ? 'bar' : chartSettings.chart_type,
					data: data,
					options: globalOptions
				   };

				  chartSettings.chart_type == 'bar_horizontal' ? config.options.indexAxis = 'y' : '';

				  if (chartSettings.tooltips_percent || "pie" == chartSettings.chart_type || "doughnut" == chartSettings.chart_type || "polarArea" == chartSettings.chart_type) {
					config.options.plugins.tooltip.callbacks.label = function (data) {
						var prefixString = data.dataset.label + ": ";
	  
						if ("pie" == chartSettings.chart_type || "doughnut" == chartSettings.chart_type || "polarArea" == chartSettings.chart_type) {
							prefixString = data.label + ' ('+data.dataset.label+') ' + ": ";
						}
		
						var dataset = data.dataset;
		
						var total = dataset.data.reduce(function (previousValue, currentValue) {
							return parseFloat(previousValue) + parseFloat(currentValue);
						});
		
						var currentValue = data.formattedValue;
		
						var percentage = ((currentValue / total) * 100).toPrecision(3);
	  
						return (
							prefixString + (chartSettings.tooltips_percent ? percentage + "%" : data.formattedValue)
						);
					} 
				  }
	
				  myChart = new Chart(
					$scope.find('.wpr-chart'),
					config
				  );
			} else {
				if ( chartSettings.url && (chartTypesArray.includes(chartSettings.chart_type) || chartSettings.chart_type === 'radar') ) {
					$.ajax({
						url: chartSettings.url,
						type: "GET",
						success: function (res) {
							$scope.find(".wpr-rotating-plane").remove();
							renderCSVChart(res, chartSettings);
						},
						error: function (err) {
							console.log(err);
						}
					});
				} else if (!chartSettings.url && (chartTypesArray.includes(chartSettings.chart_type) || chartSettings.chart_type === 'radar')) {
					$scope.find(".wpr-rotating-plane").remove();
					$scope.find('.wpr-charts-container').html('<p class="wpr-charts-error-notice">Provide a csv file or remote URL</p>');
				} else {
					$scope.find(".wpr-rotating-plane").remove();
					$scope.find('.wpr-charts-container').html('<p class="wpr-charts-error-notice">doughnut, pie and polareArea charts only work with custom data source</p>');
				}
			}

			$(window).resize(function() {
				lineDotsWidth = window.innerWidth >= 768 ? chartSettings.line_dots_radius 
									: window.innerWidth <= 767 ? chartSettings.line_dots_radius_mobile : 0;
				config.options.elements.point.radius = lineDotsWidth;
				config.options.plugins.tooltip.caretSize = tooltipCaretSize;
			});

			function renderCSVChart (res, chartSettings) {
				var ctx = $scope.find('.wpr-chart'),
				rowsData = res.split(/\r?\n|\r/),
				labels = (rowsData.shift()).split(chartSettings.separator),
				data = {
					labels: labels,
					datasets: []
				};
			
				config = {
					type: chartSettings.chart_type == 'bar_horizontal' ? 'bar' : chartSettings.chart_type,
					data: data,
					options: globalOptions,
					plugins: [chartSettings.inner_datalabels ? ChartDataLabels : '', {
						beforeInit: function(chart, options) {
						  chart.legend.afterFit = function() {
							this.height = this.height + 50;
						  };
						}
					  }],
				};

				chartSettings.chart_type == 'bar_horizontal' ? config.options.indexAxis = 'y' : '';

				if (chartSettings.tooltips_percent) {
				  config.options.plugins.tooltip.callbacks.label = function (data) {
					  var prefixString = data.dataset.label + ": ";
	
					  if ("pie" == chartSettings.chart_type || "doughnut" == chartSettings.chart_type || "polarArea" == chartSettings.chart_type) {
						  prefixString = data.label + ' ('+data.dataset.label+') ' + ": ";
					  }
	  
					  var dataset = data.dataset;
	  
					  var total = dataset.data.reduce(function (previousValue, currentValue) {
						  return parseFloat(previousValue) + parseFloat(currentValue);
					  });
	  
					  var currentValue = data.formattedValue;
	  
					  var percentage = ((currentValue / total) * 100).toPrecision(3);
	
					  return (
						  prefixString + (chartSettings.tooltips_percent ? percentage + "%" : data.formattedValue)
					  );
				  } 
				}

				myChart = new Chart(ctx,
					config
				);
		
				rowsData.forEach(function (row, index) {
					if (row.length !== 0) {
						var colData = {};

						
						colData.data = row.split(chartSettings.separator);
						//add properties only if repeater element exists
						if (customDatasets[index]) {
							colData.borderColor = customDatasets[index].borderColor;
							colData.borderWidth = customDatasets[index].borderWidth;
							colData.backgroundColor = customDatasets[index].backgroundColor;
							colData.hoverBackgroundColor = customDatasets[index].hoverBackgroundColor;
							colData.label = customDatasets[index].label;
							colData.fill = customDatasets[index].fill
						}
		
						data.datasets.push(colData);
						myChart.update();
		
					}
				});
			}
		}, // End of widgetCharts

		widgetTaxonomyList: function($scope) {
			var taxList = $scope.find('.wpr-taxonomy-list');

			if ( taxList.data('show-on-click') == 'yes' ) {

				// $scope.find('.wpr-tax-dropdown').css('margin-left', -($scope.find('.wpr-tax-dropdown').width()));

				taxList.find('.wpr-taxonomy i.wpr-tax-dropdown').on('click', function(e) {

					e.preventDefault();

					if ( taxList.find('.wpr-sub-taxonomy[data-term-id="child-'+ $(this).closest('li').data('term-id') +'"]').hasClass('wpr-sub-hidden') ) {
						$(this).removeClass('fa-caret-right').addClass('fa-caret-down');
						// $scope.find('.fa-caret-down').css('margin-left', -($scope.find('.fa-caret-down').width()));
						taxList.find('.wpr-sub-taxonomy[data-term-id="child-'+ $(this).closest('li').data('term-id') +'"]').removeClass('wpr-sub-hidden');
					} else {
						$(this).removeClass('fa-caret-down').addClass('fa-caret-right');
						// $scope.find('.fa-caret-right').css('margin-left', -($scope.find('.fa-caret-right').width()));
						taxList.find('.wpr-sub-taxonomy[data-term-id="child-'+ $(this).closest('li').data('term-id') +'"]').addClass('wpr-sub-hidden');

						taxList.find('.wpr-inner-sub-taxonomy[data-term-id="grandchild-'+ $(this).closest('li').data('term-id') +'"]').each(function() {
							if ( !$(this).hasClass('wpr-sub-hidden') ) {
								taxList.find('.wpr-sub-taxonomy[data-id="'+ $(this).data('parent-id') +'"] i.wpr-tax-dropdown').removeClass('fa-caret-down').addClass('fa-caret-right');
								// $scope.find('.fa-caret-right').css('margin-left', -($scope.find('.fa-caret-right').width()));
								$(this).addClass('wpr-sub-hidden');
							}
						});

						taxList.find('.wpr-inner-sub-taxonomy-2[data-term-id="great-grandchild-'+ $(this).closest('li').data('term-id') +'"]').each(function() {
							if ( !$(this).hasClass('wpr-sub-hidden') ) {
								taxList.find('.wpr-sub-taxonomy[data-id="'+ $(this).data('parent-id') +'"] i.wpr-tax-dropdown').removeClass('fa-caret-down').addClass('fa-caret-right');
								// $scope.find('.fa-caret-right').css('margin-left', -($scope.find('.fa-caret-right').width()));
								$(this).addClass('wpr-sub-hidden');
							}
						});

						// if (!taxList.find('.wpr-inner-sub-taxonomy[data-term-id="grandchild-'+ $(this).parent('li').data('term-id') +'"]').hasClass('wpr-sub-hidden')) {
						// 	taxList.find('.wpr-sub-taxonomy[data-term-id="child-'+ $(this).parent('li').data('term-id') +'"] i').removeClass('fa-caret-down').addClass('fa-caret-right');
						// 	taxList.find('.wpr-inner-sub-taxonomy[data-term-id="grandchild-'+ $(this).parent('li').data('term-id') +'"]').addClass('wpr-sub-hidden');
						// }
					}

					taxList.find('.wpr-inner-sub-taxonomy[data-term-id="grandchild-'+ $(this).closest('li').data('term-id') +'"] i.wpr-tax-dropdown').removeClass('fa-caret-down').addClass('fa-caret-right');

					if ( !taxList.find('.wpr-inner-sub-taxonomy-2[data-term-id="great-grandchild-'+ $(this).closest('li').data('term-id') +'"]').hasClass('wpr-sub-hidden') ) {
						taxList.find('.wpr-inner-sub-taxonomy-2[data-term-id="great-grandchild-'+ $(this).closest('li').data('term-id') +'"]').addClass('wpr-sub-hidden');
					}
				});

				taxList.find('.wpr-sub-taxonomy i.wpr-tax-dropdown').on('click', function(e) {

					e.preventDefault();

					if ( taxList.find('.wpr-inner-sub-taxonomy[data-parent-id="'+ $(this).closest('li').data('id') +'"]').hasClass('wpr-sub-hidden') ) {
						$(this).removeClass('fa-caret-right').addClass('fa-caret-down');
						// $scope.find('.fa-caret-down').css('margin-left', -($scope.find('.fa-caret-down').width()));
						taxList.find('.wpr-inner-sub-taxonomy[data-parent-id="'+ $(this).closest('li').data('id') +'"]').removeClass('wpr-sub-hidden');
					} else {
						$(this).removeClass('fa-caret-down').addClass('fa-caret-right');
						// taxList.find('.wpr-sub-taxonomy i').removeClass('fa-caret-down').addClass('fa-caret-right');
						// $scope.find('.fa-caret-right').css('margin-left', -($scope.find('.fa-caret-right').width()));
						taxList.find('.wpr-inner-sub-taxonomy[data-parent-id="'+ $(this).closest('li').data('id') +'"]').addClass('wpr-sub-hidden');
					}

					taxList.find('.wpr-inner-sub-taxonomy[data-parent-id="'+ $(this).closest('li').data('id') +'"] i.wpr-tax-dropdown').removeClass('fa-caret-down').addClass('fa-caret-right');

					if ( !taxList.find('.wpr-inner-sub-taxonomy-2[data-term-id="great-grandchild-'+ $(this).closest('li').data('term-id').replace('child-', '') +'"]').hasClass('wpr-sub-hidden') ) {
						taxList.find('.wpr-inner-sub-taxonomy-2[data-term-id="great-grandchild-'+ $(this).closest('li').data('term-id').replace('child-', '') +'"]').addClass('wpr-sub-hidden');
					}
				});

				taxList.find('.wpr-inner-sub-taxonomy i.wpr-tax-dropdown').on('click', function(e) {

					e.preventDefault();

					if ( taxList.find('.wpr-inner-sub-taxonomy-2[data-parent-id="'+ $(this).closest('li').data('id') +'"]').hasClass('wpr-sub-hidden') ) {
						$(this).removeClass('fa-caret-right').addClass('fa-caret-down');
						// $scope.find('.fa-caret-down').css('margin-left', -($scope.find('.fa-caret-down').width()));
						taxList.find('.wpr-inner-sub-taxonomy-2[data-parent-id="'+ $(this).closest('li').data('id') +'"]').removeClass('wpr-sub-hidden');
					} else {
						$(this).removeClass('fa-caret-down').addClass('fa-caret-right');
						// taxList.find('.wpr-sub-taxonomy i').removeClass('fa-caret-down').addClass('fa-caret-right');
						// $scope.find('.fa-caret-right').css('margin-left', -($scope.find('.fa-caret-right').width()));
						taxList.find('.wpr-inner-sub-taxonomy-2[data-parent-id="'+ $(this).closest('li').data('id') +'"]').addClass('wpr-sub-hidden');
					}
				});
			}
		}, // End of widgetTaxonomyList

		widgetPostsTimeline: function($scope) {
			var iScrollTarget = $scope.find( '.wpr-timeline-centered' ).length > 0 ? $scope.find( '.wpr-timeline-centered' ) : '',
			    element = $scope.find('.wpr-timeline-centered').length > 0 ? $scope.find('.wpr-timeline-centered') : '',
				pagination = $scope.find( '.wpr-grid-pagination' ).length > 0 ? $scope.find( '.wpr-grid-pagination' ) : '',
				middleLine = $scope.find('.wpr-middle-line').length > 0 ? $scope.find('.wpr-middle-line') : '',
				timelineFill = $scope.find(".wpr-timeline-fill").length > 0 ? $scope.find(".wpr-timeline-fill") : '',
				lastIcon = $scope.find('.wpr-main-line-icon.wpr-icon:last').length > 0 ? $scope.find('.wpr-main-line-icon.wpr-icon:last') : '',
				firstIcon = $scope.find('.wpr-main-line-icon.wpr-icon').length > 0 ? $scope.find('.wpr-main-line-icon.wpr-icon').first() : '',
				scopeClass = '.elementor-element-'+ $scope.attr( 'data-id' ),
				aosOffset = $scope.find('.wpr-story-info-vertical').attr('data-animation-offset') ? +$scope.find('.wpr-story-info-vertical').attr('data-animation-offset') : '',
				aosDuration = $scope.find('.wpr-story-info-vertical').attr('data-animation-duration') ? +$scope.find('.wpr-story-info-vertical').attr('data-animation-duration') : '';


			if ( $scope.find('.wpr-timeline-centered').length > 0 ) {
				
				$(window).resize(function() {
					removeLeftAlignedClass();
				});

				$(window).smartresize(function() {
					removeLeftAlignedClass();
				});

				setTimeout(function() {
					removeLeftAlignedClass();
					$(window).trigger('resize');
				}, 500);

				adjustMiddleLineHeight(middleLine, timelineFill, lastIcon, firstIcon, element);
				
				setTimeout(function() {
					adjustMiddleLineHeight(middleLine, timelineFill, lastIcon, firstIcon, element);
					$(window).trigger('resize');
				}, 500);

				$(window).smartresize(function() {
					adjustMiddleLineHeight(middleLine, timelineFill, lastIcon, firstIcon, element);
				});

				$(window).resize(function() {
					adjustMiddleLineHeight(middleLine, timelineFill, lastIcon, firstIcon, element);
				});
	
				if ( 'load-more' !== iScrollTarget.attr('data-pagination') ) {
					$scope.find('.wpr-grid-pagination').css('visibility', 'hidden');
				}

				AOS.init({
					offset: parseInt(aosOffset),
					duration: aosDuration,
					once: true,
				});

				postsTimelineFill(lastIcon, firstIcon);

				$(window).on('scroll',  function() {
					postsTimelineFill(lastIcon, firstIcon);
				});

				// init Infinite Scroll
				if ( !$scope.find('.elementor-repeater-items').length && !WprElements.editorCheck() && ('load-more' === $scope.find('.wpr-timeline-centered').data('pagination') || 'infinite-scroll' === $scope.find('.wpr-timeline-centered').data('pagination')) ) {
					var threshold = iScrollTarget !== undefined && 'load-more' === iScrollTarget.attr('data-pagination') ? false : 10;
					// var navClass = scopeClass +' .wpr-load-more-btn';
					
					iScrollTarget.infiniteScroll({
						path: scopeClass +' .wpr-grid-pagination a',
						hideNav: false,
						append:  scopeClass +'.wpr-timeline-entry',
						history: false,
						scrollThreshold: threshold,
						status: scopeClass + ' .page-load-status',
					});
					// Request
					iScrollTarget.on( 'request.infiniteScroll', function( event, path ) {
						$scope.find( '.wpr-load-more-btn' ).hide();
						$scope.find( '.wpr-pagination-loading' ).css( 'display', 'inline-block' );
					});
					
					var pagesLoaded = 0;

					iScrollTarget.on( 'load.infiniteScroll', function( event, response ) {
						pagesLoaded++;
						
						// get posts from response
						var items = $( response ).find(scopeClass).find( '.wpr-timeline-entry' );
						iScrollTarget.infiniteScroll( 'appendItems', items );

						if ( !$scope.find('.wpr-one-sided-timeline').length && !$scope.find('.wpr-one-sided-timeline-left').length ) {
							$scope.find('.wpr-timeline-entry').each(function(index, value){
								$(this).removeClass('wpr-right-aligned wpr-left-aligned');
								if ( 0 == index % 2 ) {
									$(this).addClass('wpr-left-aligned');
									$(this).find('.wpr-story-info-vertical').attr('data-aos', $(this).find('.wpr-story-info-vertical').attr('data-aos-left'));
								} else {
									$(this).addClass('wpr-right-aligned');
									$(this).find('.wpr-story-info-vertical').attr('data-aos', $(this).find('.wpr-story-info-vertical').attr('data-aos-right'));
								}
							});
						}

						AOS.init({
							offset: parseInt(aosOffset),
							duration: aosDuration,
							once: true,
						});

						$(window).scroll();

						$scope.find( '.wpr-pagination-loading' ).hide();
						// $scope.find( '.wpr-load-more-btn' ).fadeIn();
						if ( iScrollTarget.data('max-pages') - 1 !== pagesLoaded ) { // $pagination_max_pages
							if ( 'load-more' === iScrollTarget.attr('data-pagination') ) {
								$scope.find( '.wpr-load-more-btn' ).fadeIn();
							}
						} else {
							$scope.find( '.wpr-pagination-finish' ).fadeIn( 1000 );
							pagination.delay( 2000 ).fadeOut( 1000 );
						}

						middleLine = $scope.find('.wpr-middle-line');
						timelineFill = $scope.find(".wpr-timeline-fill");
						lastIcon = $scope.find('.wpr-main-line-icon.wpr-icon:last');
						firstIcon = $scope.find('.wpr-main-line-icon.wpr-icon').first();
						element = $scope.find('.wpr-timeline-centered');

						adjustMiddleLineHeight(middleLine, timelineFill, lastIcon, firstIcon, element);
						$(window).trigger('resize');
						postsTimelineFill(lastIcon, firstIcon);
					});

					if ( !WprElements.editorCheck() ) {
						$scope.find( '.wpr-load-more-btn' ).on( 'click', function() {
							iScrollTarget.infiniteScroll( 'loadNextPage' );
							return false;
						});

						if ( 'infinite-scroll' == iScrollTarget.attr('data-pagination') ) {
								iScrollTarget.infiniteScroll('loadNextPage');
						}
					}
				}
			}

			if ( $scope.find('.swiper-wrapper').length ) {

				var swiperLoader = function swiperLoader(swiperElement, swiperConfig) {
					// if ('undefined' === typeof Swiper) {
					// 	var asyncSwiper = elementorFrontend.utils.swiper;     
					// 	return new asyncSwiper(swiperElement, swiperConfig).then( function (newSwiperInstance) {
					// 		return newSwiperInstance;
					// 	});
					//  } else {
					// 	console.log(Swiper);
					// 	return swiperPromise(swiperElement, swiperConfig);  
					// }

					// Check if swiperPromise is necessary
					var asyncSwiper = elementorFrontend.utils.swiper;     
					return new asyncSwiper(swiperElement, swiperConfig).then( function (newSwiperInstance) {
						return newSwiperInstance;
					});
				};
				
				var swiperPromise = function swiperPromise(swiperElement, swiperConfig) {    
					return new Promise(function (resolve, reject) {  
							var swiperInstance = new Swiper(swiperElement, swiperConfig); 
							resolve(swiperInstance); 
					}); 
				};
			
				var horizontal = $scope.find('.wpr-horizontal-bottom').length ? '.wpr-horizontal-bottom' : '.wpr-horizontal';
				var swiperSlider = $scope.find(horizontal +".swiper-container");
							
				var slidestoshow = swiperSlider.data("slidestoshow");

				swiperLoader(swiperSlider, {
					spaceBetween: +swiperSlider.data('swiper-space-between'),
					loop: swiperSlider.data('loop') === 'yes' ? true : false,
					autoplay: swiperSlider.data("autoplay") !== 'yes' ? false : {
						delay: +swiperSlider.attr('data-swiper-delay'),
						disableOnInteraction: false,
						pauseOnMouseEnter: swiperSlider.data('swiper-poh') === 'yes' ? true : false,
					},
					on: {
						init: function () {
							if ( $scope.find('.wpr-timeline-outer-container').length > 0 ) {
								$scope.find('.wpr-timeline-outer-container').css('opacity', 1);
							}
						},
					},
					speed: +swiperSlider.attr('data-swiper-speed'),
					slidesPerView: swiperSlider.data("slidestoshow"),
					direction: 'horizontal',
					pagination: {
					  el: '.wpr-swiper-pagination',
					  type: 'progressbar',
					},
					navigation: {
					  nextEl: '.wpr-button-next',
					  prevEl: '.wpr-button-prev',
					},
					// Responsive breakpoints
					breakpoints: {
					  // when window width is >= 320px
					  320: {
						slidesPerView: 1,
					  },
					  // when window width is >= 480px
					  480: {
						slidesPerView: 2,
					  },
					  // when window width is >= 640px
					  769: { // 640
						slidesPerView: slidestoshow,
					  }
					},
				  });

				//   swiperSlider.data('pause-on-hover') === 'yes' && swiperSlider.hover(function() {
				// 	  (this).swiper.autoplay.stop();
				//   }, function() {
				// 	  (this).swiper.autoplay.start();
				//   });

			} else {
				$(document).ready(function() {
					// Handler when all assets (including images) are loaded
					if ( $scope.find('.wpr-timeline-outer-container').length ) {
						$scope.find('.wpr-timeline-outer-container').css('opacity', 1);
					}
				});
			}

			function removeLeftAlignedClass() {
				if ( $scope.find('.wpr-centered').length ) {
					if ( window.innerWidth <= 767 ) {
						$scope.find('.wpr-wrapper .wpr-timeline-centered').removeClass('wpr-both-sided-timeline').addClass('wpr-one-sided-timeline').addClass('wpr-remove-one-sided-later');
						$scope.find('.wpr-wrapper .wpr-left-aligned').removeClass('wpr-left-aligned').addClass('wpr-right-aligned').addClass('wpr-remove-right-aligned-later');
					} else {
						$scope.find('.wpr-wrapper .wpr-timeline-centered.wpr-remove-one-sided-later').removeClass('wpr-one-sided-timeline').addClass('wpr-both-sided-timeline').removeClass('wpr-remove-one-sided-later');
						$scope.find('.wpr-wrapper .wpr-remove-right-aligned-later').removeClass('wpr-right-aligned').addClass('wpr-left-aligned').removeClass('wpr-remove-right-aligned-later');
					}
				}
			}

		  function postsTimelineFill(lastIcon, firstIcon) {
			if ( !$scope.find('.wpr-timeline-fill').length ) {
				return;
			}

			if ( $scope.find('.wpr-timeline-entry:eq(0)').prev('.wpr-year-wrap').length > 0 ) {
				firstIcon = $scope.find('.wpr-year-label').eq(0);
			}

			  if ( timelineFill.length ) {
				var fillHeight = timelineFill.css('height').slice(0, -2),
					docScrollTop = document.documentElement.scrollTop,
					clientHeight = document.documentElement.clientHeight/2;
				  
				if ( !((docScrollTop + clientHeight - (firstIcon.offset().top)) > lastIcon.offset().top - firstIcon.offset().top + parseInt(lastIcon.css('height').slice(0, -2))) ) {
					timelineFill.css('height', (docScrollTop  + clientHeight - (firstIcon.offset().top)) + 'px');
				}

				$scope.find('.wpr-main-line-icon.wpr-icon').each(function () {
					if ( $(this).offset().top < parseInt( firstIcon.offset().top + parseInt(fillHeight) ) ) {
						$(this).addClass('wpr-change-border-color');
					} else {
						$(this).removeClass('wpr-change-border-color');
					}
				});
			  }
		  }

		  function adjustMiddleLineHeight(middleLine, timelineFill, lastIcon, firstIcon, element) {
			  	element = $scope.find('.wpr-timeline-centered');
				if ( !$scope.find('.wpr-both-sided-timeline').length && !$scope.find('.wpr-one-sided-timeline').length && !$scope.find('.wpr-one-sided-timeline-left').length ) {
					return;
				}

				if ( $scope.find('.wpr-timeline-entry:eq(0)').prev('.wpr-year-wrap').length > 0 ) {
					firstIcon = $scope.find('.wpr-year-label').eq(0);
				}
				
				var firstIconOffset = firstIcon.offset().top;
				var lastIconOffset = lastIcon.offset().top;
				var middleLineTop = (firstIconOffset - element.offset().top) + 'px';
				// var middleLineHeight = (lastIconOffset - (lastIcon.css('height').slice(0, -2)/2 + (firstIconOffset - firstIcon.css('height').slice(0, -2)))) + 'px';
				var middleLineHeight = lastIconOffset - firstIconOffset + parseInt(lastIcon.css('height').slice(0, -2));
				var middleLineMaxHeight = firstIconOffset - lastIconOffset + 'px !important';

				middleLine.css('top', middleLineTop);
				middleLine.css('height', middleLineHeight);
				// middleLine.css('maxHeight', middleLineMaxHeight);
				timelineFill !== '' ? timelineFill.css('top', middleLineTop) : '';
		  }
		}, // end widgetPostsTimeline

        widgetSharingButtons: function($scope) {
			$scope.find('.wpr-sharing-print').on('click', function(e) {
				e.preventDefault();
				window.print();
			});
        }, // end widgetSharingButtons

		widgetTwitterFeed: function($scope) {

			if ($scope.find('.wpr-twitter-feed').attr( 'data-settings' )) {
				var settings = JSON.parse( $scope.find('.wpr-twitter-feed').attr( 'data-settings' ) );
			} else {
				return;
			}

			let twitterFeed = $scope.find('.wpr-twitter-feed');
			
			var settings = JSON.parse( twitterFeed.attr( 'data-settings' ) );
			var loadMoreSettings = settings.twitter_load_more_settings;

			var nextPostsIndex = loadMoreSettings.number_of_posts;
			var pagination = $scope.find( '.wpr-grid-pagination' );

			if ( $scope.hasClass('wpr-twitter-feed-masonry') ) {
				// Init Functions
				isotopeLayout( settings );
				setTimeout(function() {
					isotopeLayout( settings );
				}, 100 );

				if ( WprElements.editorCheck() ) {
					setTimeout(function() {
						isotopeLayout( settings );
					}, 500 );
					setTimeout(function() {
						isotopeLayout( settings );
					}, 1000 );
				}

				$( window ).on( 'load', function() {
					setTimeout(function() {
						isotopeLayout( settings );
					}, 100 );
				});

				$(window).smartresize(function(){
					setTimeout(function() {
						isotopeLayout( settings );
					}, 200 );
				});
			}

			function isotopeLayout( settings ) {
				var twitterFeed = $scope.find( '.wpr-twitter-feed' ),
					item = twitterFeed.find( '.wpr-tweet' ),
					layout = settings.layout_select,
					columns = 3,
					gutterHr = settings.gutter_hr,
					gutterVr = settings.gutter_vr,
					contWidth = twitterFeed.width() + gutterHr - 0.3,
					viewportWidth = $(window).outerWidth(),
					transDuration = 400;

					var MobileResp = +elementorFrontend.config.responsive.breakpoints.mobile.value;
					var MobileExtraResp = +elementorFrontend.config.responsive.breakpoints.mobile_extra.value;
					var TabletResp = +elementorFrontend.config.responsive.breakpoints.tablet.value;
					var TabletExtraResp = +elementorFrontend.config.responsive.breakpoints.tablet_extra.value;
					var LaptopResp = +elementorFrontend.config.responsive.breakpoints.laptop.value;
					var wideScreenResp = +elementorFrontend.config.responsive.breakpoints.widescreen.value;

				// Mobile
				if (MobileResp >= viewportWidth ) {
					columns = (settings.columns_mobile) ? (settings.columns_mobile) : 1;
				// Mobile Extra
				} else if ( MobileExtraResp >= viewportWidth ) {
					columns = (settings.columns_mobile_extra) ? settings.columns_mobile_extra : settings.columns_tablet ? settings.columns_tablet : settings.columns;
				// Tablet
				} else if ( TabletResp >= viewportWidth ) {
					columns = (settings.columns_tablet) ? settings.columns_tablet : 2;
				// Tablet Extra
				} else if ( TabletExtraResp >= viewportWidth ) {
					columns = (settings.columns_tablet_extra) ? settings.columns_tablet_extra : settings.columns_tablet ? settings.columns_tablet : settings.columns;

				// Laptop
				} else if (  LaptopResp >= viewportWidth ) {
					columns = (settings.columns_laptop) ? settings.columns_laptop : settings.columns;

				// Desktop
				} else if ( wideScreenResp - 1 >= viewportWidth ) {
					columns = settings.columns;

				// Larger Screens
				} else if ( wideScreenResp <= viewportWidth ) {
					columns = (settings.columns_widescreen) ? settings.columns_widescreen : settings.columns;
				} else {
					columns = settings.columns
				}

				// Limit Columns for Higher Screens
				if ( columns > 8 ) {
					columns = 8;
				}

				columns = parseInt(columns);

				if ( 'string' == typeof(columns) && -1 !== columns.indexOf('pro') ) {
					columns = 3;
				}

				// Calculate Item Width
				item.outerWidth( Math.floor( contWidth / columns - gutterHr ) );

				// Set Vertical Gutter
				item.css( 'margin-bottom', gutterVr +'px' );

				// Reset Vertical Gutter for 1 Column Layout
				if ( 1 === columns ) {
					item.last().css( 'margin-bottom', '0' );
				}

				// add last row & make all post equal height
				var maxTop = -1;

				// Run Isotope
				var twitterFeedMasonry = twitterFeed.isotopewpr({
					layoutMode: layout,
					masonry: {
						comlumnWidth: contWidth / columns,
						gutter: gutterHr
					},
					transitionDuration: transDuration,
  					percentPosition: true
				});

				if ( '1' !== twitterFeed.css( 'opacity' ) ) {
					twitterFeed.css( 'opacity', '1' );
				}

				// return instagramFeed;//tmp
			}

			if ( !WprElements.editorCheck() ) {
				$scope.find('.wpr-load-more-twitter-posts').on('click', function() {
					pagination.find( '.wpr-load-more-btn' ).hide();
					pagination.find( '.wpr-pagination-loading' ).css( 'display', 'inline-block' );
					// pagination.find( '.wpr-pagination-finish' ).fadeIn(  );
					// pagination.delay( 2000 ).fadeOut( 1000 );
					// setTimeout(function() {
						$.ajax({
							type: 'POST',
							url: WprConfig.ajaxurl,
							data: { 
								action: 'wpr_load_more_tweets',
								nonce: WprConfig.nonce,
								wpr_load_more_settings: loadMoreSettings,
								next_post_index: nextPostsIndex,
							},
							success: function(data) {
								var $data = $(data);

								$data.each(function() {
									$(this).addClass('wpr-twitter-hidden-item');
								});

								
								$scope.find('.wpr-twitter-feed').append( $data );

								setTimeout(function() {
					
									if ( $scope.hasClass('wpr-twitter-feed-masonry') ) {
										twitterFeed.isotopewpr( 'appended', $data );
								
										twitterFeed.isotopewpr( 'reloadItems' ); // https://isotope.metafizzy.co/methods.html#reloaditems
										
										twitterFeed.isotopewpr('layout'); // https://isotope.metafizzy.co/methods.html#layout
						
										$(window).trigger('resize');
									}

									$data.each(function(index) {
										var item = $(this);
										setTimeout(function() {
											item.removeClass('wpr-twitter-hidden-item');
										}, 300);
									});
		
									// Loading
									pagination.find( '.wpr-pagination-loading' ).hide();
	
									if (data.includes('wpr-tweet')) { // replaceclassname
										pagination.find( '.wpr-load-more-btn' ).fadeIn();
									} else {
										pagination.find( '.wpr-pagination-finish' ).fadeIn( 1000 );
									}
								}, 400);

								nextPostsIndex =  nextPostsIndex + loadMoreSettings.number_of_posts;
							},
							error: function(error) {
								console.log(error);
							}
						});

					// }, 1000);
				});
			}

			twitterFeedCarousel();
				
			$scope.find('.wpr-grid').css('opacity', 1);

			function twitterFeedCarousel() {
				if ( $scope.hasClass('wpr-twitter-feed-carousel') ) {
					var swiperLoader = function swiperLoader(swiperElement, swiperConfig) {
						// if ('undefined' === typeof Swiper) {
						// 	var asyncSwiper = elementorFrontend.utils.swiper;     
						// 	return new asyncSwiper(swiperElement, swiperConfig).then( function (newSwiperInstance) {     
						// 		return newSwiperInstance;
						// 	});  
						// } else {     
						// 	return swiperPromise(swiperElement, swiperConfig);  
						// }

						var asyncSwiper = elementorFrontend.utils.swiper;     
						return new asyncSwiper(swiperElement, swiperConfig).then( function (newSwiperInstance) {     
							return newSwiperInstance;
						});
					};
					
					var swiperPromise = function swiperPromise(swiperElement, swiperConfig) {    
						return new Promise(function (resolve, reject) {  
								var swiperInstance = new Swiper(swiperElement, swiperConfig);     
								resolve(swiperInstance); 
						}); 
					};

					$scope.find('.wpr-twitter-feed').css('flexWrap', 'nowrap');

					var sliderSettings = settings.carousel;
					
					$scope.find('.wpr-twitter-feed-cont').addClass('swiper');
					$scope.find('.wpr-twitter-feed').addClass('swiper-wrapper');
					$scope.find('.wpr-tweet').addClass('swiper-slide');
					$scope.find('.wpr-twitter-feed-cont').css('overflow', 'hidden');
					// $scope.find('.elementor-container').css('margin', '0');
					var swiperSlider = $scope.find('.wpr-twitter-feed-cont');

					var aboveMobileResp = +elementorFrontend.config.responsive.breakpoints.mobile.value + 1;
					var aboveMobileExtraResp = +elementorFrontend.config.responsive.breakpoints.mobile_extra.value + 1;
					var aboveTabletResp = +elementorFrontend.config.responsive.breakpoints.tablet.value + 1;
					var aboveTabletExtraResp = +elementorFrontend.config.responsive.breakpoints.tablet_extra.value + 1;
					var aboveLaptopResp = +elementorFrontend.config.responsive.breakpoints.laptop.value + 1;
					var wideScreenResp = +elementorFrontend.config.responsive.breakpoints.widescreen.value;

					swiperLoader(swiperSlider, {
						hashNavigation: sliderSettings.wpr_cs_navigation === 'yes' ? true : false,
						autoplay: sliderSettings.wpr_cs_autoplay === 'yes' ? {
							delay: +sliderSettings.wpr_cs_delay,
						} : false,
						loop: sliderSettings.wpr_cs_loop === 'yes' ? true : false,
						slidesPerView: +sliderSettings.wpr_cs_slides_to_show,
						spaceBetween: +sliderSettings.wpr_cs_space_between,
						speed: +sliderSettings.wpr_cs_speed,
						pagination: sliderSettings.wpr_cs_pagination === 'yes' ? {
						el: '.swiper-pagination',
						type: sliderSettings.wpr_cs_pagination_type,
						} : false,
						navigation: {
						prevEl: '.wpr-swiper-button-prev',
						nextEl: '.wpr-swiper-button-next',
						},
						// Responsive breakpoints - direction min
						breakpoints: {
						320: {
							slidesPerView: +sliderSettings.wpr_cs_slides_to_show_mobile,
							// spaceBetween: +sliderSettings.wpr_cs_space_between_mobile,
						},
						[aboveMobileResp]: {
							slidesPerView: +sliderSettings.wpr_cs_slides_to_show_mobile_extra,
							// spaceBetween: +sliderSettings.wpr_cs_space_between_mobile_extra,
						},
						[aboveMobileExtraResp]: {
							slidesPerView: +sliderSettings.wpr_cs_slides_to_show_tablet,
							spaceBetween: +sliderSettings.wpr_cs_space_between_tablet,
						},
						[aboveTabletResp]: {
							slidesPerView: +sliderSettings.wpr_cs_slides_to_show_tablet_extra,
							spaceBetween: +sliderSettings.wpr_cs_space_between_tablet_extra,
						},
						[aboveTabletExtraResp]: {
							slidesPerView: +sliderSettings.wpr_cs_slides_to_show_laptop,
							spaceBetween: +sliderSettings.wpr_cs_space_between_laptop,
						},
						[aboveLaptopResp]: {
							slidesPerView: +sliderSettings.wpr_cs_slides_to_show,
							spaceBetween: +sliderSettings.wpr_cs_space_between,
						},
						[wideScreenResp]: {
							slidesPerView: +sliderSettings.wpr_cs_slides_to_show_widescreen,
							spaceBetween: +sliderSettings.wpr_cs_space_between_widescreen,
						}
						},
					
					});

					$scope.css('opacity', 1);
				}
			}

		}, // end widgetTwitterFeed

        widgetInstagramFeed: function($scope) {

			if ( $scope.find('.wpr-insta-feed-content-wrap').length  > 0 ) {

				let instaFeed = $scope.find('.wpr-instagram-feed');
			
				if ( instaFeed.attr( 'data-settings' ) ) {
					var settings = JSON.parse( instaFeed.attr( 'data-settings' ) );
					var loadMoreSettings = settings.insta_load_more_settings;
				}
				
				var widgetID = $scope.attr('data-id');
				
				// if ( loadMoreSettings.is_mobile === 'mobile') {
				// 	var nextPostsIndex = loadMoreSettings.limit_mobile;
				// 	console.log(nextPostsIndex);
				// } else {
				// 	var nextPostsIndex = loadMoreSettings.limit;
				// }
				var nextPostsIndex = loadMoreSettings.limit;
				var pagination = $scope.find( '.wpr-grid-pagination' ); // Isotope Layout

				if ( $scope.hasClass('wpr-insta-feed-layout-full-width') ) {
					if ( loadMoreSettings.limit > $scope.find('.wpr-insta-feed-content-wrap').length ) {
						$scope.find('.wpr-layout-full-width').css('grid-template-columns', "repeat("+ $scope.find('.wpr-insta-feed-content-wrap').length +", minmax(0, 1fr))");
					}
				}
	
				if ( $scope.hasClass('wpr-insta-feed-masonry') ) {
					// Init Functions
					isotopeLayout( settings );
					setTimeout(function() {
						isotopeLayout( settings );
					}, 100 );
	
					if ( WprElements.editorCheck() ) {
						setTimeout(function() {
							isotopeLayout( settings );
						}, 500 );
						setTimeout(function() {
							isotopeLayout( settings );
						}, 1000 );
					}
	
					$( window ).on( 'load', function() {
						setTimeout(function() {
							isotopeLayout( settings );
						}, 100 );
					});
	
					$(window).smartresize(function(){
						setTimeout(function() {
							isotopeLayout( settings );
						}, 200 );
					});
				}
	
				if ( $scope.hasClass('wpr-insta-feed-layout-list') ) {
					var mediaAlign = settings.media_align,
					mediaWidth = settings.media_width,
					mediaDistance = settings.media_distance;
					$scope.find( '.wpr-insta-feed-item-below-content' ).css({
						'float' : mediaAlign,
						'width' : 'calc((100% - '+ mediaWidth +'%) - '+ mediaDistance +'px)',
					});
	
					$(window).smartresize(function() {
						mediaAlign = settings.media_align,
						mediaWidth = settings.media_width,
						mediaDistance = settings.media_distance;
						$scope.find( '.wpr-insta-feed-item-below-content' ).css({
							'float' : mediaAlign,
							'width' : 'calc((100% - '+ mediaWidth +'%) - '+ mediaDistance +'px)',
						});
					});
				}
	
				function isotopeLayout( settings ) {
					var instaFeed = $scope.find( '.wpr-instagram-feed' ),
						item = instaFeed.find( '.wpr-insta-feed-content-wrap' ),
						layout = settings.insta_layout_select,
						columns = 3,
						gutterHr = settings.gutter_hr,
						gutterVr = settings.gutter_vr,
						contWidth = instaFeed.width() + gutterHr - 0.3,
						viewportWidth = $(window).outerWidth(),
						transDuration = 400;
	
						var MobileResp = +elementorFrontend.config.responsive.breakpoints.mobile.value;
						var MobileExtraResp = +elementorFrontend.config.responsive.breakpoints.mobile_extra.value;
						var TabletResp = +elementorFrontend.config.responsive.breakpoints.tablet.value;
						var TabletExtraResp = +elementorFrontend.config.responsive.breakpoints.tablet_extra.value;
						var LaptopResp = +elementorFrontend.config.responsive.breakpoints.laptop.value;
						var wideScreenResp = +elementorFrontend.config.responsive.breakpoints.widescreen.value;
	
					// Mobile
					if (MobileResp >= viewportWidth ) {
						columns = (settings.columns_mobile) ? (settings.columns_mobile) : 1;
					// Mobile Extra
					} else if ( MobileExtraResp >= viewportWidth ) {
						columns = (settings.columns_mobile_extra) ? settings.columns_mobile_extra : settings.columns_tablet ? settings.columns_tablet : settings.columns;
					// Tablet
					} else if ( TabletResp >= viewportWidth ) {
						columns = (settings.columns_tablet) ? settings.columns_tablet : 2;
					// Tablet Extra
					} else if ( TabletExtraResp >= viewportWidth ) {
						columns = (settings.columns_tablet_extra) ? settings.columns_tablet_extra : settings.columns_tablet ? settings.columns_tablet : settings.columns;
	
					// Laptop
					} else if (  LaptopResp >= viewportWidth ) {
						columns = (settings.columns_laptop) ? settings.columns_laptop : settings.columns;
	
					// Desktop
					} else if ( wideScreenResp - 1 >= viewportWidth ) {
						columns = settings.columns;
	
					// Larger Screens
					} else if ( wideScreenResp <= viewportWidth ) {
						columns = (settings.columns_widescreen) ? settings.columns_widescreen : settings.columns;
					} else {
						columns = settings.columns
					}
	
					// Limit Columns for Higher Screens
					if ( columns > 8 ) {
						columns = 8;
					}
	
					columns = parseInt(columns);
					if ( 'string' == typeof(columns) && -1 !== columns.indexOf('pro') ) {
						columns = 3;
					}
	
					// Calculate Item Width
					item.outerWidth( Math.floor( contWidth / columns - gutterHr ) );
	
					// Set Vertical Gutter
					item.css( 'margin-bottom', gutterVr +'px' );
	
					// Reset Vertical Gutter for 1 Column Layout
					if ( 1 === columns ) {
						item.last().css( 'margin-bottom', '0' );
					}
	
					// Run Isotope
					var instagramFeed = instaFeed.isotopewpr({
						layoutMode: layout,
						masonry: {
							comlumnWidth: contWidth / columns,
							gutter: gutterHr
						},
						transitionDuration: transDuration,
						  percentPosition: true
					});
					// return instagramFeed;//tmp
				}
	
				if ( !WprElements.editorCheck() ) {
					$scope.find('.wpr-load-more-insta-posts').on('click', function() {
						pagination.find( '.wpr-load-more-btn' ).hide();
						pagination.find( '.wpr-pagination-loading' ).css( 'display', 'inline-block' );
						// pagination.find( '.wpr-pagination-finish' ).fadeIn(  );
						// pagination.delay( 2000 ).fadeOut( 1000 );
						// setTimeout(function() {
							$.ajax({
								type: 'POST',
								url: WprConfig.ajaxurl,
								data: { 
									action: 'wpr_load_more_instagram_posts',
									nonce: WprConfig.nonce,
									wpr_load_more_settings: loadMoreSettings,
									wpr_insta_feed_widget_id: widgetID,
									next_post_index: nextPostsIndex,
								},
								success: function(data) {
									var $data = $(data);
	
									$data.each(function() {
										$(this).addClass('wpr-instagram-hidden-item');
									});
	
									$scope.find('.wpr-instagram-feed').append( $data );
										
	
									if ( $scope.hasClass('wpr-insta-feed-layout-list') ) {
										mediaAlign = settings.media_align,
										mediaWidth = settings.media_width,
										mediaDistance = settings.media_distance;
										$scope.find( '.wpr-insta-feed-item-below-content' ).css({
											'float' : mediaAlign,
											'width' : 'calc((100% - '+ mediaWidth +'%) - '+ mediaDistance +'px)',
										});
									}
	
									if ( $scope.hasClass('wpr-insta-feed-masonry') ) {
										instaFeed.isotopewpr( 'appended', $data );
									
										instaFeed.isotopewpr( 'reloadItems' ); // https://isotope.metafizzy.co/methods.html#reloaditems
										
										instaFeed.isotopewpr('layout'); // https://isotope.metafizzy.co/methods.html#layout
		
										$(window).trigger('resize');
									}
	
									setTimeout(function() {
	
										$data.each(function(index) {
											var item = $(this);
											setTimeout(function() {
												item.removeClass('wpr-instagram-hidden-item');
											}, 100);
										});
		
										// Loading
										pagination.find( '.wpr-pagination-loading' ).hide();
		
										if (data.includes('wpr-insta-feed-content-wrap')) {
											setTimeout(function() {
												pagination.find( '.wpr-load-more-btn' ).fadeIn();
											}, 400);
										} else {
											pagination.find( '.wpr-pagination-finish' ).fadeIn( 1000 );
											pagination.delay( 2000 ).fadeOut( 1000 );
											setTimeout(function() {
												pagination.find( '.wpr-pagination-loading' ).hide();
											}, 500 );
										}
	
									}, 400);
									
									// if ( loadMoreSettings.is_mobile === 'mobile' ) {
									// 	nextPostsIndex =  nextPostsIndex + loadMoreSettings.limit_mobile;
									// } else {
									// 	nextPostsIndex =  nextPostsIndex + loadMoreSettings.limit;
									// }
									nextPostsIndex =  nextPostsIndex + loadMoreSettings.limit;
	
									if ( instaFeed.data('lightGallery') ) {
										// Fix Lightbox
										instaFeed.data( 'lightGallery' ).destroy( true );
									}
		
									mediaHoverLink();
								},
								error: function(error) {
									console.log(error);
								}
							});
						// }, 1000);
					});
				}
	
				if ( $scope.find('.wpr-layout-carousel') ) {
					instaFeedCarousel();
				}
				
				$(document).ready(function() {
					$scope.find('.wpr-grid-pagination').removeClass('wpr-pagination-hidden'); 
				});

				$(document).ready(function() {
					// Handler when all assets (including images) are loaded
					if ( instaFeed.length ) {
						instaFeed.css('opacity', 1);
					}
				});
	
				if ( WprElements.editorCheck() ) {
					// Handler when all assets (including images) are loaded
					if ( instaFeed.length ) {
						instaFeed.css('opacity', 1);
					}
				}
	
				// Init Media Hover Link
				mediaHoverLink();
	
				// Init Lightbox
				lightboxPopup( settings );
	
				// Init Post Sharing
				postSharing();
	
				var mutationObserver = new MutationObserver(function(mutations) {
					// Init Media Hover Link
					mediaHoverLink();
	
					lightboxPopup( settings );
				});
	
				mutationObserver.observe($scope[0], {
					childList: true,
					subtree: true,
				});
	
				// Post Sharing
				function postSharing() {
					if ( $scope.find( '.wpr-sharing-trigger' ).length ) {
						var sharingTrigger = $scope.find( '.wpr-sharing-trigger' ),
							sharingInner = $scope.find( '.wpr-post-sharing-inner' ),
							sharingWidth = 5;
	
						// Calculate Width
						sharingInner.first().find( 'a' ).each(function() {
							sharingWidth += $(this).outerWidth() + parseInt( $(this).css('margin-right'), 10 );
						});
	
						// Calculate Margin
						var sharingMargin = parseInt( sharingInner.find( 'a' ).css('margin-right'), 10 );
	
						// Set Positions
						if ( 'left' === sharingTrigger.attr( 'data-direction') ) {
							// Set Width
							sharingInner.css( 'width', sharingWidth +'px' );
	
							// Set Position
							sharingInner.css( 'left', - ( sharingMargin + sharingWidth ) +'px' );
						} else if ( 'right' === sharingTrigger.attr( 'data-direction') ) {
							// Set Width
							sharingInner.css( 'width', sharingWidth +'px' );
	
							// Set Position
							sharingInner.css( 'right', - ( sharingMargin + sharingWidth ) +'px' );
						} else if ( 'top' === sharingTrigger.attr( 'data-direction') ) {
							// Set Margins
							sharingInner.find( 'a' ).css({
								'margin-right' : '0',
								'margin-top' : sharingMargin +'px'
							});
	
							// Set Position
							sharingInner.css({
								'top' : -sharingMargin +'px',
								'left' : '50%',
								'-webkit-transform' : 'translate(-50%, -100%)',
								'transform' : 'translate(-50%, -100%)'
							});
						} else if ( 'right' === sharingTrigger.attr( 'data-direction') ) {
							// Set Width
							sharingInner.css( 'width', sharingWidth +'px' );
	
							// Set Position
							sharingInner.css({
								'left' : sharingMargin +'px',
								// 'bottom' : - ( sharingInner.outerHeight() + sharingTrigger.outerHeight() ) +'px',
							});
						} else if ( 'bottom' === sharingTrigger.attr( 'data-direction') ) {
							// Set Margins
							sharingInner.find( 'a' ).css({
								'margin-right' : '0',
								'margin-bottom' : sharingMargin +'px'
							});
	
							// Set Position
							sharingInner.css({
								'bottom' : -sharingMargin +'px',
								'left' : '50%',
								'-webkit-transform' : 'translate(-50%, 100%)',
								'transform' : 'translate(-50%, 100%)'
							});
						}
	
						if ( 'click' === sharingTrigger.attr( 'data-action' ) ) {
							sharingTrigger.on( 'click', function() {
								var sharingInner = $(this).next();
	
								if ( 'hidden' === sharingInner.css( 'visibility' ) ) {
									sharingInner.css( 'visibility', 'visible' );
									sharingInner.find( 'a' ).css({
										'opacity' : '1',
										'top' : '0'
									});
	
									setTimeout( function() {
										sharingInner.find( 'a' ).addClass( 'wpr-no-transition-delay' );
									}, sharingInner.find( 'a' ).length * 100 );
								} else {
									sharingInner.find( 'a' ).removeClass( 'wpr-no-transition-delay' );
	
									sharingInner.find( 'a' ).css({
										'opacity' : '0',
										'top' : '-5px'
									});
									setTimeout( function() {
										sharingInner.css( 'visibility', 'hidden' );
									}, sharingInner.find( 'a' ).length * 100 );
								}
							});
						} else {
							sharingTrigger.on( 'mouseenter', function() {
								var sharingInner = $(this).next();
	
								sharingInner.css( 'visibility', 'visible' );
								sharingInner.find( 'a' ).css({
									'opacity' : '1',
									'top' : '0',
								});
								
								setTimeout( function() {
									sharingInner.find( 'a' ).addClass( 'wpr-no-transition-delay' );
								}, sharingInner.find( 'a' ).length * 100 );
							});
							$scope.find( '.wpr-insta-feed-item-sharing' ).on( 'mouseleave', function() {
								var sharingInner = $(this).find( '.wpr-post-sharing-inner' );
	
								sharingInner.find( 'a' ).removeClass( 'wpr-no-transition-delay' );
	
								sharingInner.find( 'a' ).css({
									'opacity' : '0',
									'top' : '-5px'
								});
								setTimeout( function() {
									sharingInner.css( 'visibility', 'hidden' );
								}, sharingInner.find( 'a' ).length * 100 );
							});
						}
					}				
				}
	
				// Remove if not necessary - GOGA
				$scope.find('.elementor-widget-wrap').removeClass('e-swiper-container');
	
				function instaFeedCarousel() {
					if ( $scope.hasClass('wpr-insta-feed-layout-carousel') ) {
						var swiperLoader = function swiperLoader(swiperElement, swiperConfig) {
							// if ('undefined' === typeof Swiper) {     
							// 	var asyncSwiper = elementorFrontend.utils.swiper;     
							// 	return new asyncSwiper(swiperElement, swiperConfig).then( function (newSwiperInstance) {     
							// 		return newSwiperInstance;
							// 	});  
							// } else {     
							// 	return swiperPromise(swiperElement, swiperConfig);  
							// }
							     
							var asyncSwiper = elementorFrontend.utils.swiper;     
							return new asyncSwiper(swiperElement, swiperConfig).then( function (newSwiperInstance) {     
								return newSwiperInstance;
							}); 
						};
						
						var swiperPromise = function swiperPromise(swiperElement, swiperConfig) {    
							return new Promise(function (resolve, reject) {  
									var swiperInstance = new Swiper(swiperElement, swiperConfig);     
									resolve(swiperInstance); 
							}); 
						};
	
						$scope.find('.wpr-instagram-feed').css('flexWrap', 'nowrap');
	
						var sliderSettings = settings.carousel;
						
						$scope.find('.wpr-instagram-feed-cont').addClass('swiper');
						$scope.find('.wpr-instagram-feed').addClass('swiper-wrapper');
						$scope.find('.wpr-insta-feed-content-wrap').addClass('swiper-slide');
						$scope.find('.wpr-instagram-feed-cont').css('overflow', 'hidden');
						// $scope.find('.elementor-container').css('margin', '0');
						var swiperSlider = $scope.find('.wpr-instagram-feed-cont');
	
						var aboveMobileResp = +elementorFrontend.config.responsive.breakpoints.mobile.value + 1;
						var aboveMobileExtraResp = +elementorFrontend.config.responsive.breakpoints.mobile_extra.value + 1;
						var aboveTabletResp = +elementorFrontend.config.responsive.breakpoints.tablet.value + 1;
						var aboveTabletExtraResp = +elementorFrontend.config.responsive.breakpoints.tablet_extra.value + 1;
						var aboveLaptopResp = +elementorFrontend.config.responsive.breakpoints.laptop.value + 1;
						var wideScreenResp = +elementorFrontend.config.responsive.breakpoints.widescreen.value;
	
						swiperLoader(swiperSlider, {
							hashNavigation: sliderSettings.wpr_cs_navigation === 'yes' ? true : false,
							autoplay: sliderSettings.wpr_cs_autoplay === 'yes' ? {
								delay: +sliderSettings.wpr_cs_delay,
							} : false,
							loop: sliderSettings.wpr_cs_loop === 'yes' ? true : false,
							slidesPerView: +sliderSettings.wpr_cs_slides_to_show,
							spaceBetween: +sliderSettings.wpr_cs_space_between,
							speed: +sliderSettings.wpr_cs_speed,
							pagination: sliderSettings.wpr_cs_pagination === 'yes' ? {
								el: '.swiper-pagination',
								type: sliderSettings.wpr_cs_pagination_type,
								clickable: 'bullets' === sliderSettings.wpr_cs_pagination_type ? true : false,
							} : false,
							navigation: {
								prevEl: '.wpr-swiper-button-prev',
								nextEl: '.wpr-swiper-button-next',
							},
							// Responsive breakpoints - direction min
							breakpoints: {
							320: {
								slidesPerView: +sliderSettings.wpr_cs_slides_to_show_mobile,
								// spaceBetween: +sliderSettings.wpr_cs_space_between_mobile,
							},
							[aboveMobileResp]: {
								slidesPerView: +sliderSettings.wpr_cs_slides_to_show_mobile_extra,
								// spaceBetween: +sliderSettings.wpr_cs_space_between_mobile_extra,
							},
							[aboveMobileExtraResp]: {
								slidesPerView: +sliderSettings.wpr_cs_slides_to_show_tablet,
								spaceBetween: +sliderSettings.wpr_cs_space_between_tablet,
							},
							[aboveTabletResp]: {
								slidesPerView: +sliderSettings.wpr_cs_slides_to_show_tablet_extra,
								spaceBetween: +sliderSettings.wpr_cs_space_between_tablet_extra,
							},
							[aboveTabletExtraResp]: {
								slidesPerView: +sliderSettings.wpr_cs_slides_to_show_laptop,
								spaceBetween: +sliderSettings.wpr_cs_space_between_laptop,
							},
							[aboveLaptopResp]: {
								slidesPerView: +sliderSettings.wpr_cs_slides_to_show,
								spaceBetween: +sliderSettings.wpr_cs_space_between,
							},
							[wideScreenResp]: {
								slidesPerView: +sliderSettings.wpr_cs_slides_to_show_widescreen,
								spaceBetween: +sliderSettings.wpr_cs_space_between_widescreen,
							}
							},
						
						});
	
						$scope.css('opacity', 1);
						
					}
				}
	
				function lightboxPopup( settings ) {
					if ( -1 === $scope.find( '.wpr-insta-feed-item-lightbox' ).length ) {
						return;
					}
	
					var lightbox = $scope.find( '.wpr-insta-feed-item-lightbox' ),
						lightboxOverlay = lightbox.find( '.wpr-insta-feed-lightbox-overlay' );
	
					// Set Src Attributes
					lightbox.each(function() {
						var source = $(this).find('.inner-block > span').attr( 'data-src' ),
							instaFeedItem = $(this).closest( '.wpr-insta-feed-content-wrap' );
	
							instaFeedItem.find( '.wpr-insta-feed-image-wrap' ).attr( 'data-src', source );
	
						var dataSource = instaFeedItem.find( '.wpr-insta-feed-image-wrap' ).attr( 'data-src' );
					});
	
					// Init Lightbox
					instaFeed.lightGallery( settings.lightbox );
	
					// Fix LightGallery Thumbnails
					instaFeed.on('onAfterOpen.lg', function() {
						if ( $('.lg-outer').find('.lg-thumb-item').length ) {
							$('.lg-outer').find('.lg-thumb-item').each(function() {
								var imgSrc = $(this).find('img').attr('src'),
									newImgSrc = imgSrc,
									extIndex = imgSrc.lastIndexOf('.'),
									imgExt = imgSrc.slice(extIndex),
									cropIndex = imgSrc.lastIndexOf('-'),
									cropSize = /\d{3,}x\d{3,}/.test(imgSrc.substring(extIndex,cropIndex)) ? imgSrc.substring(extIndex,cropIndex) : false;
								
								if ( 42 <= imgSrc.substring(extIndex,cropIndex).length ) {
									cropSize = '';
								}
	
								if ( cropSize !== '' ) {
									if ( false !== cropSize ) {
										newImgSrc = imgSrc.replace(cropSize, '-150x150');
									} else {
										newImgSrc = [imgSrc.slice(0, extIndex), '-150x150', imgSrc.slice(extIndex)].join('');
									}
								}
	
								// Change SRC
								$(this).find('img').attr('src', newImgSrc);
		
								if ( false == cropSize ) {
									$(this).find('img').attr('src', imgSrc);
								}
							});
						}
					});
	
					// Show/Hide Controls
					$scope.find( '.wpr-insta-feed' ).on( 'onAferAppendSlide.lg, onAfterSlide.lg', function( event, prevIndex, index ) {
						var lightboxControls = $( '#lg-actual-size, #lg-zoom-in, #lg-zoom-out, #lg-download' ),
							lightboxDownload = $( '#lg-download' ).attr( 'href' );
	
						if ( $( '#lg-download' ).length ) {
							if ( -1 === lightboxDownload.indexOf( 'wp-content' ) ) {
								lightboxControls.addClass( 'wpr-hidden-element' );
							} else {
								lightboxControls.removeClass( 'wpr-hidden-element' );
							}
						}
	
						// Autoplay Button
						if ( '' === settings.lightbox.autoplay ) {
							$( '.lg-autoplay-button' ).css({
								 'width' : '0',
								 'height' : '0',
								 'overflow' : 'hidden'
							});
						}
					});
	
					// Overlay
					if ( lightboxOverlay.length ) {
						$scope.find( '.wpr-insta-feed-media-hover-bg' ).after( lightboxOverlay.remove() );
	
						$scope.find( '.wpr-insta-feed-lightbox-overlay' ).on( 'click', function() {
							if ( ! WprElements.editorCheck() ) {
								$(this).closest( '.wpr-insta-feed-content-wrap' ).find( '.wpr-insta-feed-image-wrap' ).trigger( 'click' );
							} else {
								alert( 'Lightbox is Disabled in the Editor!' );
							}
						});
					} else {
						lightbox.find( '.inner-block > span' ).on( 'click', function() {
							if ( ! WprElements.editorCheck() ) {
								var imageWrap = $(this).closest( '.wpr-insta-feed-content-wrap' ).find( '.wpr-insta-feed-image-wrap' );
									imageWrap.trigger( 'click' );
							} else {
								alert( 'Lightbox is Disabled in the Editor!' );
							}
						});
					}
				}
	
				// Media Hover Link
				function mediaHoverLink() {
					if ( 'yes' === instaFeed.find( '.wpr-insta-feed-media-wrap' ).attr( 'data-overlay-link' ) && ! WprElements.editorCheck() ) {
							instaFeed.find( '.wpr-insta-feed-media-wrap' ).css('cursor', 'pointer');
	
							instaFeed.find( '.wpr-insta-feed-media-wrap' ).on( 'click', function( event ) {
							var targetClass = event.target.className;
	
							if ( -1 !== targetClass.indexOf( 'inner-block' ) || -1 !== targetClass.indexOf( 'wpr-cv-inner' ) || 
								 -1 !== targetClass.indexOf( 'wpr-insta-feed-media-hover' ) ) {
								event.preventDefault();
	
								var itemUrl = $(this).find( '.wpr-insta-feed-media-hover-bg' ).attr( 'data-url' ),
									itemUrl = itemUrl.replace('#new_tab', '');
	
								if ( '_blank' === instaFeed.find( '.wpr-insta-feed-media-hover-bg' ).data('target') ) {
									window.open(itemUrl, '_blank').focus();
								} else {
									window.location.href = itemUrl;
								}
							}
						});
					}				
				}
			}

        }, // end widgetInstagramFeed

        widgetFacebookFeed: function($scope) {
			window.fbAsyncInit = function() {
				FB.init({
				  appId      : '1184287221975469',
				  xfbml      : true,
				  version    : 'v13.0'
				});
				FB.AppEvents.logPageView();
			  };
			
			  (function(d, s, id){
				 var js, fjs = d.getElementsByTagName(s)[0];
				 if (d.getElementById(id)) {return;}
				 js = d.createElement(s); js.id = id;
				 js.src = "https://connect.facebook.net/en_US/sdk.js";
				 fjs.parentNode.insertBefore(js, fjs);
			   }(document, 'script', 'facebook-jssdk'));
        }, // end widgetFacebookFeed
		
		widgetFlipCarousel: function($scope) {
			var flipsterSettings = JSON.parse($scope.find('.wpr-flip-carousel').attr('data-settings'));

			$scope.find('.wpr-flip-carousel').css('opacity', 1);
			
			$scope.find('.wpr-flip-carousel').flipster({
				itemContainer: 'ul',
				itemSelector: 'li',
				fadeIn: 400,
				start: flipsterSettings.starts_from_center === 'yes' ? 'center' : 0,
				style: flipsterSettings.carousel_type,
				loop: flipsterSettings.loop === 'yes' ? true : false,
				autoplay: flipsterSettings.autoplay === 'no' ? false : flipsterSettings.autoplay_milliseconds,
				pauseOnHover: flipsterSettings.pause_on_hover === 'yes' ? true : false,
				click: flipsterSettings.play_on_click === 'yes' ? true : false,
				scrollwheel: flipsterSettings.play_on_scroll === 'yes' ? true : false,
				touch: true,
				nav: 'true' === flipsterSettings.pagination_position ? true : flipsterSettings.pagination_position ? flipsterSettings.pagination_position : false,
				spacing: flipsterSettings.spacing,
				buttons: 'custom',
				buttonPrev: flipsterSettings.button_prev,
				buttonNext: flipsterSettings.button_next
			});
			
			var paginationButtons = $scope.find('.wpr-flip-carousel').find('.flipster__nav__item').find('.flipster__nav__link');
			
			paginationButtons.each(function() {
				$(this).text(parseInt($(this).text()) + 1);
			});
		}, // end widgetFlipCarousel

		widgetFeatureList: function($scope) {
			$scope.find('.wpr-feature-list-item:not(:last-of-type)').find('.wpr-feature-list-icon-wrap').each(function(index) {
				var offsetTop = $scope.find('.wpr-feature-list-item').eq(index + 1).find('.wpr-feature-list-icon-wrap').offset().top;
				
				$(this).find('.wpr-feature-list-line').height(offsetTop - $(this).offset().top + 'px');
			});

			$(window).resize(function() {
				$scope.find('.wpr-feature-list-item:not(:last-of-type)').find('.wpr-feature-list-icon-wrap').each(function(index) {
					var offsetTop = $scope.find('.wpr-feature-list-item').eq(index + 1).find('.wpr-feature-list-icon-wrap').offset().top;
					
					$(this).find('.wpr-feature-list-line').height(offsetTop - $(this).offset().top + 'px');
				});
			})
		}, // end widgetFeatureList
		
		widgetAdvancedAccordion: function($scope) {
            var acc = $scope.find('.wpr-acc-button');
            var accItemWrap = $scope.find('.wpr-accordion-item-wrap');
			var accordionType = $scope.find('.wpr-advanced-accordion').data('accordion-type');
			var activeIndex = +$scope.find('.wpr-advanced-accordion').data('active-index') - 1;
			var accordionTrigger = $scope.find('.wpr-advanced-accordion').data('accordion-trigger');
			var interactionSpeed = +$scope.find('.wpr-advanced-accordion').data('interaction-speed') * 1000;

			// ?active_panel=panel-index#your-id
			var activeTabIndexFromLocation = window.location.href.indexOf("active_panel=");

			if (activeTabIndexFromLocation > -1) {
				activeIndex = +window.location.href.substring(activeTabIndexFromLocation,  window.location.href.lastIndexOf("#")).replace("active_panel=", '') - 1;
			}

			if ('click' === accordionTrigger) {

				if ( accordionType == 'accordion' ) {
					acc.on("click", function() {
						var thisIndex = acc.index(this);
						acc.each(function(index){
							index != thisIndex ? $(this).removeClass('wpr-acc-active') : '';
						});
						$scope.find('.wpr-acc-panel').each(function(index) {
							index != thisIndex ? $(this).removeClass('wpr-acc-panel-active') && $(this).slideUp(interactionSpeed) : '';
						});
						$(this).toggleClass("wpr-acc-active");
						var panel = $(this).next();
						if ( !panel.hasClass('wpr-acc-panel-active') ) {
							panel.slideDown(interactionSpeed);
							panel.addClass('wpr-acc-panel-active');
						} else {
							panel.slideUp(interactionSpeed);
							panel.removeClass('wpr-acc-panel-active');
						}
					});
				} else {
					acc.each(function() {
						$(this).on("click", function() {
							$(this).toggleClass("wpr-acc-active");
							var panel = $(this).next();
							if ( !panel.hasClass('wpr-acc-panel-active') ) {
								panel.slideDown(interactionSpeed);
								panel.addClass('wpr-acc-panel-active');
							} else {
								panel.slideUp(interactionSpeed);
								panel.removeClass('wpr-acc-panel-active');
							}
						});
					});
				}

				acc && (activeIndex > -1 && acc.eq(activeIndex).trigger('click'));
			} else if ( accordionTrigger == 'hover' ) {
				accItemWrap.on("mouseenter", function() {
						var thisIndex = accItemWrap.index(this);

						$(this).find('.wpr-acc-button').addClass("wpr-acc-active");

						var panel = $(this).find('.wpr-acc-panel');
							panel.slideDown(interactionSpeed);
							panel.addClass('wpr-acc-panel-active');

						accItemWrap.each(function(index) {
							if (index != thisIndex) {
								$(this).find('.wpr-acc-button').removeClass("wpr-acc-active");
								var panel = $(this).find('.wpr-acc-panel');
								panel.slideUp(interactionSpeed);
								panel.removeClass('wpr-acc-panel-active');
							}
						});
				});
				
				accItemWrap &&  (activeIndex > -1 && accItemWrap.eq(activeIndex).trigger('mouseenter'));
			}

			$scope.find('.wpr-acc-search-input').on( {
				focus: function() {
					$scope.addClass( 'wpr-acc-search-input-focus' );
				},
				blur: function() {
					$scope.removeClass( 'wpr-search-form-input-focus' );
				}
			} );
			
			let allInAcc = $scope.find('.wpr-advanced-accordion > *');

			$scope.find('i.fa-times').on('click', function() {
				$scope.find('.wpr-acc-search-input').val('');
				$scope.find('.wpr-acc-search-input').trigger('keyup');
			});

			var iconBox = $scope.find('.wpr-acc-icon-box');

			iconBox.each(function() {
				$(this).find('.wpr-acc-icon-box-after').css({
					'border-top': $(this).height()/2 + 'px solid transparent', 
					'border-bottom': $(this).height()/2 + 'px solid transparent'
				});	
			});

			$(window).resize(function() {
				iconBox.each(function() {
					$(this).find('.wpr-acc-icon-box-after').css({
						'border-top': $(this).height()/2 + 'px solid transparent', 
						'border-bottom': $(this).height()/2 + 'px solid transparent'
					});	
				});
			});

			$scope.find('.wpr-acc-search-input').on('keyup', function() {
				setTimeout( () => {
					let thisValue = $(this).val();
					if ( thisValue.length > 0 ) {
						$scope.find('.wpr-acc-search-input-wrap').find('i.fa-times').css('display', 'inline-block');
						allInAcc.each(function() {
							if ( $(this).hasClass('wpr-accordion-item-wrap') ) {
								var itemWrap = $(this);
								if ( itemWrap.text().toUpperCase().indexOf(thisValue.toUpperCase()) == -1 ) {
									itemWrap.hide();
									if ( itemWrap.find('.wpr-acc-button').hasClass('wpr-acc-active') && itemWrap.find('.wpr-acc-panel').hasClass('wpr-acc-panel-active') ) {
										itemWrap.find('.wpr-acc-button').removeClass('wpr-acc-active');
										itemWrap.find('.wpr-acc-panel').removeClass('wpr-acc-panel-active');
									}
								} else {
									itemWrap.show();
									if ( !itemWrap.find('.wpr-acc-button').hasClass('wpr-acc-active') && !itemWrap.find('.wpr-acc-panel').hasClass('wpr-acc-panel-active') ) {
										itemWrap.find('.wpr-acc-button').addClass('wpr-acc-active');
										itemWrap.find('.wpr-acc-panel').addClass('wpr-acc-panel-active');
										itemWrap.find('.wpr-acc-panel').slideDown(interactionSpeed);
									}
								}
							}
						});
					} else {
						$scope.find('.wpr-acc-search-input-wrap').find('i.fa-times').css('display', 'none');
						allInAcc.each(function() {
							if ( $(this).hasClass('wpr-accordion-item-wrap') ) {
								$(this).show();
								if ( $(this).find('.wpr-acc-panel').hasClass('wpr-acc-panel-active') ) {
									$(this).find('.wpr-acc-panel').removeClass('wpr-acc-panel-active');
								}
								if ( $(this).find('.wpr-acc-button').hasClass('wpr-acc-active') ) {
									$(this).find('.wpr-acc-button').removeClass('wpr-acc-active')
								}
								$(this).find('.wpr-acc-panel').slideUp(interactionSpeed);
							}
						});
						// if ('click' === accordionTrigger) {
						// 	acc && (activeIndex > -1 && acc.eq(activeIndex).trigger('click'));
						// } else if ( 'hover' === accordionTrigger ) {
						// 	accItemWrap &&  (activeIndex > -1 && accItemWrap.eq(activeIndex).trigger('mouseenter'));
						// }
					}
				}, 1000);
			});
			
		}, // end widgetAdvancedAccordion

        widgetImageAccordion: function($scope) {
			var settings = JSON.parse($scope.find( '.wpr-img-accordion-media-hover' ).attr( 'data-settings' ));

			// var MediaWrap = $scope.find( '.wpr-img-accordion-media-hover' );
			var MediaWrap = $scope.find( '.wpr-image-accordion' );
			// var	lightboxSettings = settings.lightbox;
			var lightboxSettings = $scope.find('.wpr-image-accordion').attr('lightbox') ? JSON.parse($scope.find('.wpr-image-accordion').attr('lightbox')) : '';

			var thisTargetHasClass = false;

			if ( $scope.find('.wpr-image-accordion-wrap').hasClass('wpr-acc-no-column') ) {
				if ( !$scope.hasClass('wpr-image-accordion-row') );
				$scope.removeClass('wpr-image-accordion-column').addClass('wpr-image-accordion-row');
				$scope.find('.wpr-image-accordion').css('flex-direction', 'row');
			}

			if ( '' !== lightboxSettings ) {
			
				// Init Lightbox
				MediaWrap.lightGallery( lightboxSettings );
	
				// Fix LightGallery Thumbnails
				MediaWrap.on('onAfterOpen.lg',function() {
					if ( $('.lg-outer').find('.lg-thumb-item').length ) {
						$('.lg-outer').find('.lg-thumb-item').each(function() {
							var imgSrc = $(this).find('img').attr('src'),
								newImgSrc = imgSrc,
								extIndex = imgSrc.lastIndexOf('.'),
								imgExt = imgSrc.slice(extIndex),
								cropIndex = imgSrc.lastIndexOf('-'),
								cropSize = /\d{3,}x\d{3,}/.test(imgSrc.substring(extIndex,cropIndex)) ? imgSrc.substring(extIndex,cropIndex) : false;
							
							if ( 42 <= imgSrc.substring(extIndex,cropIndex).length ) {
								cropSize = '';
							}
	
							if ( cropSize !== '' ) {
								if ( false !== cropSize ) {
									newImgSrc = imgSrc.replace(cropSize, '-150x150');
								} else {
									newImgSrc = [imgSrc.slice(0, extIndex), '-150x150', imgSrc.slice(extIndex)].join('');
								}
							}
	
							// Change SRC
							$(this).find('img').attr('src', newImgSrc);
	
							if ( false == cropSize || '-450x450' === cropSize ) {
								$(this).find('img').attr('src', imgSrc);
							}
						});
					}
				});
	
				// Show/Hide Controls
				$scope.find( '.wpr-image-accordion' ).on( 'onAferAppendSlide.lg, onAfterSlide.lg', function( event, prevIndex, index ) {
					var lightboxControls = $( '#lg-actual-size, #lg-zoom-in, #lg-zoom-out, #lg-download' ),
						lightboxDownload = $( '#lg-download' ).attr( 'href' );
	
					if ( $( '#lg-download' ).length ) {
						if ( -1 === lightboxDownload.indexOf( 'wp-content' ) ) {
							lightboxControls.addClass( 'wpr-hidden-element' );
						} else {
							lightboxControls.removeClass( 'wpr-hidden-element' );
						}
					}
	
					// Autoplay Button
					if ( '' === lightboxSettings.autoplay ) {
						$( '.lg-autoplay-button' ).css({
							 'width' : '0',
							 'height' : '0',
							 'overflow' : 'hidden'
						});
					}
				});

			}

			MediaWrap.css('cursor', 'pointer');

			// Init Media Hover Link

			var accordionItem = $scope.find('.wpr-image-accordion-item');

			// Media Hover Link
			function mediaHoverLink() {
				if ( ! WprElements.editorCheck() ) {

					$scope.find('.wpr-img-accordion-media-hover').on( 'click', function( event ) {
						var thisSettings = event.target.className.includes('wpr-img-accordion-media-hover') ? JSON.parse($(this).attr('data-settings')) : JSON.parse($(this).closest('.wpr-img-accordion-media-hover').attr( 'data-settings' ));
						
						if ( !$(event.target).hasClass('wpr-img-accordion-item-lightbox') && 0 === $(event.target).closest('.wpr-img-accordion-item-lightbox').length ) {
							var itemUrl = thisSettings.activeItem.overlayLink;
							if (itemUrl != '') {
	
								if ( '_blank' === thisSettings.activeItem.overlayLinkTarget ) {
									window.open(itemUrl, '_blank').focus();
								} else {
									window.location.href = itemUrl;
								}
	
							}
						}
					});
				}				
			}

			if ( 'hover' === settings.activeItem.interaction) {

				mediaHoverLink();
				
				accordionItem.on('mouseenter', function() {
					accordionItem.removeClass('wpr-image-accordion-item-grow');
					accordionItem.find('.wpr-animation-wrap').removeClass('wpr-animation-wrap-active');
					$(this).addClass('wpr-image-accordion-item-grow');
					$(this).find('.wpr-animation-wrap').addClass('wpr-animation-wrap-active');
				});

				accordionItem.on('mouseleave', function() {
					$(this).removeClass('wpr-image-accordion-item-grow');
					$(this).find('.wpr-animation-wrap').removeClass('wpr-animation-wrap-active');
				});

			} else if ( 'click' === settings.activeItem.interaction ) {
				$scope.find('.wpr-img-accordion-media-hover').removeClass('wpr-animation-wrap');
				accordionItem.on('click', '.wpr-img-accordion-media-hover', function(event) {
					thisTargetHasClass = event.target.className.includes('wpr-img-accordion-media-hover') ? event.target.className.includes('wpr-animation-wrap-active') : $(this).closest('.wpr-img-accordion-media-hover').hasClass('wpr-animation-wrap-active');
					if (thisTargetHasClass && !WprElements.editorCheck()) {
						var thisSettings = event.target.className.includes('wpr-img-accordion-media-hover') ? JSON.parse($(this).attr('data-settings')) : JSON.parse($(this).closest('.wpr-img-accordion-media-hover').attr( 'data-settings' ));
						
						if ( !$(event.target).hasClass('wpr-img-accordion-item-lightbox') && 0 === $(event.target).closest('.wpr-img-accordion-item-lightbox').length ) {
							var itemUrl = thisSettings.activeItem.overlayLink;
							if (itemUrl != '') {
								if ( '_blank' === thisSettings.activeItem.overlayLinkTarget ) {
									window.open(itemUrl, '_blank').focus();
								} else {
									window.location.href = itemUrl;
								}
							}
						}
					} else {
						$scope.find('.wpr-img-accordion-media-hover').removeClass('wpr-animation-wrap').removeClass('wpr-animation-wrap-active');
						accordionItem.removeClass('wpr-image-accordion-item-grow');
						$(this).closest('.wpr-image-accordion-item').addClass('wpr-image-accordion-item-grow');
						$(this).closest('.wpr-img-accordion-media-hover').addClass('wpr-animation-wrap-active');
					}
				});
			} else {
				$scope.find('.wpr-img-accordion-media-hover').removeClass('wpr-animation-wrap');
			}

			accordionItem.each(function() {
				if ( $(this).index() === settings.activeItem.defaultActive - 1 ) {
					if ( 'click' === settings.activeItem.interaction) {
						setTimeout(() => {
							$(this).find('.wpr-img-accordion-media-hover').trigger('click');
						}, 400);
					} else {
						setTimeout(() => {
							$(this).find('.wpr-img-accordion-media-hover').trigger('mouseenter');
						}, 400);
					}
				}
			});
			
			$scope.find('.wpr-image-accordion-wrap').css('opacity', 1); 
        }, // end widgetImageAccordion

		widgetOffcanvas: function($scope) {
			let animationDuration;

			if ( $scope.hasClass('wpr-offcanvas-entrance-animation-pro-sl') ) {
				$scope.removeClass('wpr-offcanvas-entrance-animation-pro-sl').addClass('wpr-offcanvas-entrance-animation-fade');
			} else if ( $scope.hasClass('wpr-offcanvas-entrance-animation-pro-gr') ) {
				$scope.removeClass('wpr-offcanvas-entrance-animation-pro-gr').addClass('wpr-offcanvas-entrance-animation-fade');
			}

			if ( $scope.hasClass('wpr-offcanvas-entrance-type-pro-ps') ) {
				$scope.removeClass('wpr-offcanvas-entrance-type-pro-ps').addClass('wpr-offcanvas-entrance-type-cover');
			}

			function openOffcanvas(offcanvasSelector) {
				if ( !$scope.hasClass('wpr-offcanvas-entrance-type-push') && !$scope.find('.wpr-offcanvas-content').hasClass('wpr-offcanvas-content-relative') ) {
					$('body').addClass('wpr-offcanvas-body-overflow');
				}
				animationDuration = +offcanvasSelector.find('.wpr-offcanvas-content').css('animation-duration').replace('s', '') * 1000;
				offcanvasSelector.fadeIn(animationDuration);
				offcanvasSelector.addClass('wpr-offcanvas-wrap-active');
				if ( $scope.hasClass('wpr-offcanvas-entrance-animation-slide') ) {
					if ( offcanvasSelector.find('.wpr-offcanvas-content').hasClass('wpr-offcanvas-slide-in') ) {
						offcanvasSelector.find('.wpr-offcanvas-content').removeClass('wpr-offcanvas-slide-in').addClass('wpr-offcanvas-slide-out');
					} else {
						offcanvasSelector.find('.wpr-offcanvas-content').removeClass('wpr-offcanvas-slide-out').addClass('wpr-offcanvas-slide-in');
					}
				} else if ( $scope.hasClass('wpr-offcanvas-entrance-animation-grow') ) {
					if ( offcanvasSelector.find('.wpr-offcanvas-content').hasClass('wpr-offcanvas-grow-in') ) {
						offcanvasSelector.find('.wpr-offcanvas-content').removeClass('wpr-offcanvas-grow-in').addClass('wpr-offcanvas-grow-out');
					} else {
						offcanvasSelector.find('.wpr-offcanvas-content').removeClass('wpr-offcanvas-grow-out').addClass('wpr-offcanvas-grow-in');
					}
				} else if ( $scope.hasClass('wpr-offcanvas-entrance-animation-fade') ) {
					if ( offcanvasSelector.find('.wpr-offcanvas-content').hasClass('wpr-offcanvas-fade-in') ) {
						offcanvasSelector.find('.wpr-offcanvas-content').removeClass('wpr-offcanvas-fade-in').addClass('wpr-offcanvas-fade-out');
					} else {
						offcanvasSelector.find('.wpr-offcanvas-content').removeClass('wpr-offcanvas-fade-out').addClass('wpr-offcanvas-fade-in');
					}
				}

				$(window).trigger('resize');
			}

			function closeOffcanvas(offcanvasSelector) {
				if ( !$scope.hasClass('wpr-offcanvas-entrance-type-push') && !$scope.find('.wpr-offcanvas-content').hasClass('wpr-offcanvas-content-relative') ) {
					$('body').removeClass('wpr-offcanvas-body-overflow');
				}
				if ( $scope.hasClass('wpr-offcanvas-entrance-animation-slide') ) {
					offcanvasSelector.find('.wpr-offcanvas-content').removeClass('wpr-offcanvas-slide-in').addClass('wpr-offcanvas-slide-out');
				} else if ( $scope.hasClass('wpr-offcanvas-entrance-animation-grow') ) {
					offcanvasSelector.find('.wpr-offcanvas-content').removeClass('wpr-offcanvas-grow-in').addClass('wpr-offcanvas-grow-out');
				} else if ( $scope.hasClass('wpr-offcanvas-entrance-animation-fade') ) {
					offcanvasSelector.find('.wpr-offcanvas-content').removeClass('wpr-offcanvas-fade-in').addClass('wpr-offcanvas-fade-out');
				}

				offcanvasSelector.fadeOut(animationDuration);
				offcanvasSelector.removeClass('wpr-offcanvas-wrap-active');
				// setTimeout(function() {
				// }, 600);
			}

			if ( $scope.hasClass('wpr-offcanvas-entrance-type-push') ) {

				function growBodyWidth() {
					openOffcanvas(offcanvasSelector);

					$('body').addClass('wpr-offcanvas-body-overflow');
	
					if ( offcanvasSelector.find('.wpr-offcanvas-content').hasClass('wpr-offcanvas-content-left') ) {
						// bodyInnerWrap.animate({'margin-left': offcanvasSelector.find('.wpr-offcanvas-content').width() + 'px'}, 'slow');
						bodyInnerWrap.css({
							'transition-duration': offcanvasSelector.find('.wpr-offcanvas-content').css('animation-duration'),
							'transform': 'translateX('+ offcanvasSelector.find('.wpr-offcanvas-content').outerWidth() +'px)',
						});
					} else if ( offcanvasSelector.find('.wpr-offcanvas-content').hasClass('wpr-offcanvas-content-right') ) {
						// bodyInnerWrap.animate({'margin-right': offcanvasSelector.find('.wpr-offcanvas-content').width() + 'px'}, 'slow');
						bodyInnerWrap.css({
							'transition-duration': offcanvasSelector.find('.wpr-offcanvas-content').css('animation-duration'),
							'transform': 'translateX(-'+ offcanvasSelector.find('.wpr-offcanvas-content').outerWidth() +'px)',
						});
					} else if ( offcanvasSelector.find('.wpr-offcanvas-content').hasClass('wpr-offcanvas-content-top') ) {
						// bodyInnerWrap.animate({'margin-top': offcanvasSelector.find('.wpr-offcanvas-content').outerHeight() + 'px'}, 'slow');
						bodyInnerWrap.css({
							'transition-duration': offcanvasSelector.find('.wpr-offcanvas-content').css('animation-duration'),
							'margin-top': offcanvasSelector.find('.wpr-offcanvas-content').outerHeight() + 'px',
						});
					}
				}
	
				function reduceBodyWidth() {
					closeOffcanvas(offcanvasSelector);

					if ( offcanvasSelector.find('.wpr-offcanvas-content').hasClass('wpr-offcanvas-content-left') ) {
						// bodyInnerWrap.animate({'margin-left': 0}, 'slow');
						bodyInnerWrap.css({'transform': 'translateX(0px)'});
					} else if ( offcanvasSelector.find('.wpr-offcanvas-content').hasClass('wpr-offcanvas-content-right') ) {
						// bodyInnerWrap.animate({'margin-right': 0}, 'slow');
						bodyInnerWrap.css({'transform': 'translateX(0px)'});
					} else if ( offcanvasSelector.find('.wpr-offcanvas-content').hasClass('wpr-offcanvas-content-top') ) {
						// bodyInnerWrap.animate({'margin-top': 0}, 'slow');
						bodyInnerWrap.css({'margin-top': 0});
					}

					$('body').removeClass('wpr-offcanvas-body-overflow');
				}
	
				function closeTriggers() {
					offcanvasSelector.on('click', function(e){
						if ( !e.target.classList.value.includes('wpr-offcanvas-content') && !e.target.closest('.wpr-offcanvas-content') ) {
							reduceBodyWidth();
						}
					});
					
					$(document).on('keyup', function(event) {
						if (event.key == "Escape") {
							reduceBodyWidth();
						}
					});
		
					offcanvasSelector.find('.wpr-close-offcanvas').on('click', function() {
						reduceBodyWidth();
					});
				}

				if ( !($('.wpr-offcanvas-body-inner-wrap-' + $scope.data('id')).length > 0) ) {
					$("body").wrapInner('<div class="wpr-offcanvas-body-inner-wrap-' + $scope.data('id') + '" />');
				}

				var bodyInnerWrap = $('.wpr-offcanvas-body-inner-wrap-' + $scope.data('id'));

				bodyInnerWrap.css('position', 'relative');

				if ( !(bodyInnerWrap.prev('.wpr-offcanvas-wrap').length > 0) ) {
					$scope.find('.wpr-offcanvas-wrap').addClass('wpr-offcanvas-wrap-'+ $scope.data('id'));

					document.querySelector('body').insertBefore($scope.find('.wpr-offcanvas-wrap')[0], document.querySelector('.wpr-offcanvas-body-inner-wrap-' + $scope.data('id')));
				}

				var offcanvasSelector = $('.wpr-offcanvas-wrap-'+ $scope.data('id'));

				$scope.find('.wpr-offcanvas-trigger').on('click', function() {
					if ( $('.wpr-offcanvas-wrap-'+ $scope.data('id')).length > 0 && $scope.find('.wpr-offcanvas-wrap').length > 0 ) {
						$('.wpr-offcanvas-wrap-'+ $scope.data('id')).remove();
						$scope.find('.wpr-offcanvas-wrap').addClass('wpr-offcanvas-wrap-'+ $scope.data('id'));
						document.querySelector('body').insertBefore($scope.find('.wpr-offcanvas-wrap')[0], document.querySelector('.wpr-offcanvas-body-inner-wrap-' + $scope.data('id')));
						offcanvasSelector = $('.wpr-offcanvas-wrap-'+ $scope.data('id'));
					}

					if (offcanvasSelector.hasClass('wpr-offcanvas-wrap-active')) {
						reduceBodyWidth();
					} else {
						growBodyWidth();
					}
				});
	
				if ( 'yes' === $scope.find('.wpr-offcanvas-container').data('offcanvas-open') ) {
					$scope.find('.wpr-offcanvas-trigger').trigger('click');
				}

				closeTriggers();

				var mutationObserver = new MutationObserver(function(mutations) {
					closeTriggers();
				});

				// Listen to Mini Cart Changes
				mutationObserver.observe($scope[0], {
					childList: true,
					subtree: true,
				});

			} else {

				$scope.find('.wpr-offcanvas-trigger').on('click', function() {
					if ( !$scope.find('.wpr-offcanvas-wrap').hasClass('wpr-offcanvas-wrap-active') ) {
						openOffcanvas($scope.find('.wpr-offcanvas-wrap'));
					} else if ( $scope.find('.wpr-offcanvas-wrap').hasClass('wpr-offcanvas-wrap-active') && $scope.find('.wpr-offcanvas-wrap').hasClass('wpr-offcanvas-wrap-relative') ) {
						closeOffcanvas($scope.find('.wpr-offcanvas-wrap'));
					}
				});
	
				$scope.find('.wpr-offcanvas-wrap').on('click', function(e){
					if ( !e.target.classList.value.includes('wpr-offcanvas-content') && !e.target.closest('.wpr-offcanvas-content') ) {
						closeOffcanvas($scope.find('.wpr-offcanvas-wrap'));
					}
				});
	
				if ( 'yes' === $scope.find('.wpr-offcanvas-container').data('offcanvas-open') ) {
					$scope.find('.wpr-offcanvas-trigger').trigger('click');
				}
				
				$(document).on('keyup', function(event) {
					if (event.key == "Escape") {
						closeOffcanvas($scope.find('.wpr-offcanvas-wrap'));
					}
				});
	
				$scope.find('.wpr-close-offcanvas').on('click', function() {
					closeOffcanvas($scope.find('.wpr-offcanvas-wrap'));
				});
				
			}

		}, // end widgetOffcanvas

		widgetWishlist: function($scope) {

			$.ajax({
				url: WprConfig.ajaxurl,
				type: 'POST',
				data: {
					action: 'count_wishlist_items',
					element_addcart_simple_txt: $scope.find('.wpr-wishlist-products').attr('element_addcart_simple_txt'),
					element_addcart_grouped_txt: $scope.find('.wpr-wishlist-products').attr('element_addcart_grouped_txt'),
					element_addcart_variable_txt: $scope.find('.wpr-wishlist-products').attr('element_addcart_variable_txt')
				},
				success: function(response) { 
					// Get all elements with the class 'wpr-wishlist-product' and their product IDs
					var productElements = $scope.find('.wpr-wishlist-product');
					var productIds = productElements.map(function() {
						return $(this).data('product-id');
					}).get();
					
					// Filter out the items in the response that match the product IDs
					var newWishlistItems = response.wishlist_items.filter(function(item) {
						return !productIds.includes(item.product_id);
					});

					// Convert the wishlist_items to an array of product_ids for easier searching
					var wishlistProductIds = response.wishlist_items.map(function(item) {
						return item.product_id;
					});

					productElements.each(function() {
						var productId = $(this).data('product-id');
					
						// If the product ID is not in the wishlistProductIds array, remove the element
						if (!wishlistProductIds.includes(productId)) {
						$(this).remove();
						}
					});

					newWishlistItems.forEach(function(item) {
						var html = '<tr class="wpr-wishlist-product" data-product-id="' + item.product_id + '">';
							html += '<td><span class="wpr-wishlist-remove" data-product-id="' + item.product_id + '"></span></td>';
							html += '<td><a class="wpr-wishlist-img-wrap" href="' + item.product_url + '">' + item.product_image + '</a></td>';
							html += '<td><a class="wpr-wishlist-product-name" href="' + item.product_url + '">' + item.product_title + '</a></td>';
							html += '<td><div class="wpr-wishlist-product-price">' + item.product_price + '</div></td>';
							html += '<td><div class="wpr-wishlist-product-status">' + item.product_stock + '</div></td>';
							html += '<td><div class="wpr-wishlist-product-atc">' + item.product_atc + '</div></td>';
						html += '</tr>';
						$scope.find('.wpr-wishlist-products tbody').append(html);
					});

					if ( 0 < +response.wishlist_count ) {
						if ( $scope.find('.wpr-wishlist-products').hasClass('wpr-wishlist-empty-hidden') ) {
							$scope.find('.wpr-wishlist-products').removeClass('wpr-wishlist-empty-hidden');
						}

						if ( !$scope.find('.wpr-wishlist-empty').hasClass('wpr-wishlist-empty-hidden') ) {
							$scope.find('.wpr-wishlist-empty').addClass('wpr-wishlist-empty-hidden');
						}
					} else {
						if ( !$scope.find('.wpr-wishlist-products').hasClass('wpr-wishlist-empty-hidden') ) {
							$scope.find('.wpr-wishlist-products').addClass('wpr-wishlist-empty-hidden');
						}

						if ( $scope.find('.wpr-wishlist-empty').hasClass('wpr-wishlist-empty-hidden') ) {
							$scope.find('.wpr-wishlist-empty').removeClass('wpr-wishlist-empty-hidden');
						}
					}
				},
				error: function(error) {
					console.log(error);
				}
			});

			$scope.on('click', '.wpr-wishlist-remove', function(e) {
				e.preventDefault();
				var product_id = $(this).data('product-id');

				$.ajax({
					url: WprConfig.ajaxurl,
					type: 'POST',
					data: {
						action: 'remove_from_wishlist',
						product_id: product_id
					},
					success: function(response) {
						if ( 1 === $scope.find('.wpr-wishlist-product').length ) {	
							if ( !$scope.find('.wpr-wishlist-products').hasClass('wpr-wishlist-empty-hidden') ) {
								$scope.find('.wpr-wishlist-products').addClass('wpr-wishlist-empty-hidden');
							}

							if ( $scope.find('.wpr-wishlist-empty').hasClass('wpr-wishlist-empty-hidden') ) {
								$scope.find('.wpr-wishlist-empty').removeClass('wpr-wishlist-empty-hidden');
							}
						}

           				$scope.find('.wpr-wishlist-product[data-product-id="' + product_id + '"]').remove();
						WprElements.changeActionTargetProductId(product_id);
						$(document).trigger('removed_from_wishlist');	
					},
					error: function(error) {
						console.log(error);
					}
				});
			});

			$( 'body' ).on( 'added_to_cart', function(ev, fragments, hash, button) {
				button.next().fadeTo( 700, 1 );

				button.css('display', 'none');
			});

		}, // end widgetWishlistTest

		//GOGA - widget wishlist count place here

		widgetWishlistButton: function($scope) {

				$.ajax({
					url: WprConfig.ajaxurl,
					type: 'POST',
					data: {
						action: 'check_product_in_wishlist',
						product_id: $scope.find('.wpr-wishlist-add').data('product-id')
					},
					success: function(response) {
						if ( true == response ) {
							if ( !$scope.find('.wpr-wishlist-add').hasClass('wpr-button-hidden') ) {
								$scope.find('.wpr-wishlist-add').addClass('wpr-button-hidden');
							}

							if ( $scope.find('.wpr-wishlist-remove').hasClass('wpr-button-hidden') ) {
								$scope.find('.wpr-wishlist-remove').removeClass('wpr-button-hidden');
							}
						}
					}
				});

				$scope.find('.wpr-wishlist-add').click(function(e) {
					e.preventDefault();
					var product_id = $(this).data('product-id');

					$(this).fadeTo(500, 0);
	
					$.ajax({
						url: WprConfig.ajaxurl,
						type: 'POST',
						data: {
							action: 'add_to_wishlist',
							product_id: product_id
						},
						success: function() {
							$scope.find('.wpr-wishlist-add[data-product-id="' + product_id + '"]').hide();
							$scope.find('.wpr-wishlist-remove[data-product-id="' + product_id + '"]').show();
							$scope.find('.wpr-wishlist-remove[data-product-id="' + product_id + '"]').fadeTo(500, 1);
							WprElements.changeActionTargetProductId(product_id);
							$(document).trigger('added_to_wishlist');
						},
						error: function(response) {
							var error_message = response.responseJSON.message;
							// Display error message
							alert(error_message);
						}
					});
				});
				$scope.find('.wpr-wishlist-remove').on('click', function(e) {
					e.preventDefault();
					var product_id = $(this).data('product-id');

					$(this).fadeTo(500, 0);

					$.ajax({
						url: WprConfig.ajaxurl,
						type: 'POST',
						data: {
							action: 'remove_from_wishlist',
							product_id: product_id
						},
						success: function() {
							$scope.find('.wpr-wishlist-remove[data-product-id="' + product_id + '"]').hide();
							$scope.find('.wpr-wishlist-add[data-product-id="' + product_id + '"]').show();
							$scope.find('.wpr-wishlist-add[data-product-id="' + product_id + '"]').fadeTo(500, 1);
							WprElements.changeActionTargetProductId(product_id);
							$(document).trigger('removed_from_wishlist');
						}
					});
				});

				$(document).on('removed_from_wishlist', function() {
					$scope.find('.wpr-wishlist-remove[data-product-id="' + actionTargetProductId + '"]').hide();
					$scope.find('.wpr-wishlist-add[data-product-id="' + actionTargetProductId + '"]').show();
					$scope.find('.wpr-wishlist-add[data-product-id="' + actionTargetProductId + '"]').fadeTo(500, 1);
				});
		
		}, // end widgetWishlistButton

		widgetCompareButton: function($scope) {

			$.ajax({
				url: WprConfig.ajaxurl,
				type: 'POST',
				data: {
					action: 'check_product_in_compare',
					product_id: $scope.find('.wpr-compare-add').data('product-id')
				},
				success: function(response) {
					if ( true == response ) {
						if ( !$scope.find('.wpr-compare-add').hasClass('wpr-button-hidden') ) {
							$scope.find('.wpr-compare-add').addClass('wpr-button-hidden');
						}

						if ( $scope.find('.wpr-compare-remove').hasClass('wpr-button-hidden') ) {
							$scope.find('.wpr-compare-remove').removeClass('wpr-button-hidden');
						}
					}
				}
			});

			// $(document).ready(function() {
				$scope.find('.wpr-compare-add').click(function(e) {
					e.preventDefault();
					var product_id = $(this).data('product-id');

					$(this).fadeTo(500, 0);

					$.ajax({
						url: WprConfig.ajaxurl,
						type: 'POST',
						data: {
							action: 'add_to_compare',
							product_id: product_id
						},
						success: function() {
							$scope.find('.wpr-compare-add[data-product-id="' + product_id + '"]').hide();
							$scope.find('.wpr-compare-remove[data-product-id="' + product_id + '"]').show();
							$scope.find('.wpr-compare-remove[data-product-id="' + product_id + '"]').fadeTo(500, 1);
							WprElements.changeActionTargetProductId(product_id);
							$(document).trigger('added_to_compare');
						},
						error: function(response) {
							var error_message = response.responseJSON.message;
							// Display error message
							alert(error_message);
						}
					});
				});
				$scope.find('.wpr-compare-remove').click(function(e) {
					e.preventDefault();
					var product_id = $(this).data('product-id');
					
					$(this).fadeTo(500, 0);

					$.ajax({
						url: WprConfig.ajaxurl,
						type: 'POST',
						data: {
							action: 'remove_from_compare',
							product_id: product_id
						},
						success: function() {
							$scope.find('.wpr-compare-remove[data-product-id="' + product_id + '"]').hide();
							$scope.find('.wpr-compare-add[data-product-id="' + product_id + '"]').show();
							$scope.find('.wpr-compare-add[data-product-id="' + product_id + '"]').fadeTo(500, 1);
							WprElements.changeActionTargetProductId(product_id);
							$(document).trigger('removed_from_compare');
						}
					});
				});

				$(document).on('removed_from_compare', function() {
					$scope.find('.wpr-compare-remove[data-product-id="' + actionTargetProductId + '"]').hide();
					$scope.find('.wpr-compare-add[data-product-id="' + actionTargetProductId + '"]').show();
					$scope.find('.wpr-compare-add[data-product-id="' + actionTargetProductId + '"]').fadeTo(500, 1);
				});

			// });

		}, // end widgetCompareButton

		widgetMiniCompare: function($scope) {
			// $scope.find('.wpr-compare-text').click(function(e) {
			// 	e.preventDefault();
			// 	alert(WprConfig.comparePageID);
			// 	$scope.find('.wpr-compare-popup').removeClass('wpr-compare-popup-hidden');
			// 	$.ajax({
			// 		// url: WprConfig.ajaxurl,
			// 		url: '/royal-wp/wp-json/wpraddons/v1/page-content/' + WprConfig.comparePageID,
			// 		type: 'GET',
			// 		// data: {
			// 		// 	action: 'wpr_get_page_content',
			// 		// 	wpr_compare_page_id: WprConfig.comparePageID // Replace with the ID of the page you want to retrieve
			// 		// },
			// 		// success: function(response) {
			// 		// 	console.log(response);
			// 		// 	// $scope.find('.wpr-compare-popup').append(response.data.content);
			// 		// 	$scope.find('.wpr-compare-popup').append(response);
			// 		// },
			// 		dataType: 'json',
			// 		success: function(response) {
			// 				$scope.find('.wpr-compare-popup').append(response);
			// 				elementorFrontend.init();
			// 		},
			// 		error: function(xhr, status, error) {
			// 			console.log(xhr.responseText);
			// 		}
			// 	});
			// });
			
			if ( !($scope.find('.wpr-compare-count').length > 0 && 0 == $scope.find('.wpr-compare-count').text()) ) {
				$scope.find('.wpr-compare-count').css('display', 'inline-flex');
			}

			// WITH AJAX
			if ( $scope.hasClass('wpr-compare-style-popup') ) {
				$scope.find('.wpr-compare-toggle-btn').on('click', function(e) {
					e.preventDefault();

					$('body').addClass('wpr-body-overflow-hidden');

					$scope.find('.wpr-compare-bg').removeClass('wpr-compare-popup-hidden');
					$scope.find('.wpr-compare-popup').removeClass('wpr-compare-fade-out').addClass('wpr-compare-fade-in');
					$scope.find('.wpr-compare-bg').removeClass('wpr-compare-fade-out').addClass('wpr-compare-fade-in');

					$scope.find('.wpr-compare-popup-inner-wrap').html('<div class="wpr-compare-loader-wrap"><div class="wpr-double-bounce"><div class="wpr-child wpr-double-bounce1"></div><div class="wpr-child wpr-double-bounce2"></div></div></div>');
					$.ajax({
						url: WprConfig.ajaxurl,
						type: 'POST',
						data: {
							action: 'wpr_get_page_content',
							wpr_compare_page_id: WprConfig.comparePageID
						},
						success: function(response) {
							$scope.find('.wpr-compare-popup-inner-wrap').html(response.data.content);
							WprElements.widgetCompare($scope);
							
							$scope.find('.wpr-compare-remove').click(function(e) {
								e.preventDefault();
								var productID = $(this).data('product-id');
							
								$.ajax({
									url: WprConfig.ajaxurl,
									type: 'POST',
									data: {
										action: 'remove_from_compare',
										product_id: productID
									},
									success: function() {
										WprElements.changeActionTargetProductId(productID);
										$scope.find('[data-product-id="' + productID + '"]').remove();
										if ( !($scope.find('.wpr-compare-popup-inner-wrap').find('.wpr-compare-remove').length > 0) ) {
											$scope.find('.wpr-compare-products').addClass('wpr-hidden-element');
											$scope.find('.wpr-compare-empty').removeClass('wpr-hidden-element');
										} else {
											$scope.find('.wpr-compare-empty').addClass('wpr-hidden-element');
											$scope.find('.wpr-compare-products').removeClass('wpr-hidden-element');
										}
										$(document).trigger('removed_from_compare');
									}
								});
							});
						},
						error: function(xhr, status, error) {
							console.log(xhr.responseText);
						}
					});
				});
	
				$scope.find('.wpr-close-compare').click(function(e) {
					$scope.find('.wpr-compare-popup').removeClass('wpr-compare-fade-in').addClass('wpr-compare-fade-out');
					$scope.find('.wpr-compare-bg').removeClass('wpr-compare-fade-in').addClass('wpr-compare-fade-out');
					setTimeout(function() {
						$scope.find('.wpr-compare-bg').addClass('wpr-compare-popup-hidden');
						$('body').removeClass('wpr-body-overflow-hidden');
					}, 600)
				});
	
				$scope.find('.wpr-compare-bg').click(function(e) {
					if ( !e.target.classList.value.includes('wpr-compare-popup') && !e.target.closest('.wpr-compare-popup') ) {
						var thisTarget = $(this);
						$scope.find('.wpr-compare-popup').removeClass('wpr-compare-fade-in').addClass('wpr-compare-fade-out');
						$scope.find('.wpr-compare-bg').removeClass('wpr-compare-fade-in').addClass('wpr-compare-fade-out');
						setTimeout(function() {
							thisTarget.addClass('wpr-compare-popup-hidden');
							$('body').removeClass('wpr-body-overflow-hidden');
						}, 600);
					}
				});

			}

			$.ajax({
				url: WprConfig.ajaxurl,
				type: 'POST',
				data: {
					action: 'count_compare_items',
				},
				success: function(response) {
					if ( $scope.find('.wpr-compare-count').css('display') == 'none' && 0 < response.compare_count ) {
						$scope.find('.wpr-compare-count').text(response.compare_count);
						$scope.find('.wpr-compare-count').css('display', 'inline-flex');
					} else if ( 0 == response.compare_count ) {
						$scope.find('.wpr-compare-count').css('display', 'none');
						$scope.find('.wpr-compare-count').text(response.compare_count);
					} else {
						$scope.find('.wpr-compare-count').text(response.compare_count);
					}
				},
				error: function(error) {
					console.log(error);
				}
			});

			$(document).on('removed_from_compare', function() {
				$.ajax({
					url: WprConfig.ajaxurl,
					type: 'POST',
					data: {
						action: 'update_mini_compare',
						product_id: actionTargetProductId,
					},
					success: function(response) {
						$scope.find('.wpr-compare-count').text(response.compare_count);
						
						if ( response.compare_count == 0 ) {
							$scope.find('.wpr-compare-count').css('display', 'none');
						} else {
							$scope.find('.wpr-compare-count').css('display', 'inline-flex');
						}
					}
				});
			});

			$(document).on('added_to_compare', function() {
				$.ajax({
					url: WprConfig.ajaxurl,
					type: 'POST',
					data: {
						action: 'update_mini_compare',
						product_id: actionTargetProductId,
					},
					success: function(response) {
						$scope.find('.wpr-compare-count').text(response.compare_count);
						$scope.find('.wpr-compare-count').css('display', 'inline-flex');
					}
				});
			});
		}, // end widgetMiniCompare

		widgetCompare: function($scope) {
			$.ajax({
				url: WprConfig.ajaxurl,
				type: 'POST',
				data: {
					action: 'count_compare_items',
					remove_text: $scope.find('.wpr-compare-table-wrap').attr('remove_from_compare_text'),
					compare_empty_text: $scope.find('.wpr-compare-table-wrap').attr('compare_empty_text'),
					element_addcart_simple_txt: $scope.find('.wpr-compare-table-wrap').attr('element_addcart_simple_txt'),
					element_addcart_grouped_txt: $scope.find('.wpr-compare-table-wrap').attr('element_addcart_grouped_txt'),
					element_addcart_variable_txt: $scope.find('.wpr-compare-table-wrap').attr('element_addcart_variable_txt')
				},
				success: function(response) {
					if ( true ) {
						$scope.find('.wpr-compare-table-wrap').html(response.compare_table);
					}
				},
				error: function(error) {
					console.log(error);
				}
			});

			$scope.on('click', '.wpr-compare-remove', function(e) {
				e.preventDefault();
				var productID = $(this).data('product-id');
			
				$.ajax({
					url: WprConfig.ajaxurl,
					type: 'POST',
					data: {
						action: 'remove_from_compare',
						product_id: productID
					},
					success: function() {
						WprElements.changeActionTargetProductId(productID);
						$scope.find('[data-product-id="' + productID + '"]').remove();
						if ( !($scope.find('.wpr-compare-remove').length > 0) ) {
							$scope.find('.wpr-compare-products').addClass('wpr-hidden-element');
							$scope.find('.wpr-compare-empty').removeClass('wpr-hidden-element');
						} else {
							$scope.find('.wpr-compare-empty').addClass('wpr-hidden-element');
							$scope.find('.wpr-compare-products').removeClass('wpr-hidden-element');
						}
						$(document).trigger('removed_from_compare');
					}
				});
			});
			
			$( 'body' ).on( 'added_to_cart', function(ev, fragments, hash, button) {
				button.next().fadeTo( 700, 1 );

				button.css('display', 'none');
			});
		}, // end widgetCompare
		
		widgetFormBuilder: function($scope) {

			var formContent = {};

			var fileUrl = {};

			if ( $('body').find('.wpr-form-field-type-recaptcha-v3').length > 0 ) {
					var script = document.createElement('script');
					script.src = 'https://www.google.com/recaptcha/api.js?render='+ $scope.find('#g-recaptcha-response').data('site-key') +'';
					document.body.appendChild(script);
			}

			var currentTab = 0; // Current tab is set to be the first tab (0)
			if ( 0 < $scope.find('.wpr-step-tab').length ) {
				showTab(currentTab); // Display the current tab

				$scope.find('.wpr-step-prev').each(function() {
					$(this).on('click', function() {
						nextPrev(-1);	
					});
				});
	
				$scope.find(".wpr-step-next").each(function() {
					$(this).on('click', function() {
						nextPrev(1);
					});
				});
			}

			var actions = $scope.find('.wpr-form-field-type-submit').data('actions');
			
			$scope.find('input[type="file"]').on('change', function(e) {
				var files = this.files;
				var thisInput = $(this);
				var eventType = e.type;
				handleFileValidityAndUpload(thisInput, files, eventType);
			});

			$scope.find('input, select, textarea').each(function() {
				$(this).on('change', function() {
					var $this = $(this);
					if ('checkbox' == $this.attr('type')) {
						var $option = $this.closest('.wpr-form-field-option');
						if ($option.hasClass('wpr-checked')) {
							$option.removeClass('wpr-checked');
						} else {
							$option.addClass('wpr-checked');
						}
					} else if ('radio' == $this.attr('type')) {
						// Find all radio buttons in the same group
						var name = $this.attr('name');
						var $group = $('input[type="radio"][name="' + name + '"]');
				
						// Remove 'wpr-checked' from all options in the group
						$group.closest('.wpr-form-field-option').removeClass('wpr-checked');
				
						// Add 'wpr-checked' to the selected option
						if ($this.is(':checked')) {
							$this.closest('.wpr-form-field-option').addClass('wpr-checked');
						}
					}
				});

				$(this).on('input change keyup', function(e) {
					if ( $(this).closest('.wpr-select-wrap').length > 0 ) {
						$(this).closest('.wpr-select-wrap').removeClass('wpr-form-error-wrap');
					}
					$(this).removeClass('wpr-form-error');
					$(this).closest('.wpr-field-group').find('.wpr-submit-error').remove();
				});
			});

			$scope.find('.wpr-button').on('click', function(e) {
				e.preventDefault();

				var eventType = e.type;

				formContent = {};
				
				// Create an array to store the promises of the file uploads
				let fileUploadPromises = [];

				if ( 0 < $scope.find('input[type="file"').length ) {
					$scope.find('input[type="file"]').each(function() {
						var files = this.files;
						var thisInput = $(this);
					
						fileUploadPromises.push(handleFileValidityAndUpload(thisInput, files, eventType));
					});
	
					// Wait for all file uploads to complete
					Promise.all(fileUploadPromises)
						.then(() => {
							createFormContent();

							// Check if the form is valid and submit the form
							if (validateForm()) {
								$(this).closest('form').trigger('submit');
							}
						})
						.catch((error) => {
							// Handle errors
							console.error(error);
						});
				} else {
					createFormContent();

					if ( validateForm() ) {
						$(this).closest('form').trigger('submit');
					}
				}
			});
			

			$scope.find('form').on('submit', function(e) {
				
				e.preventDefault();
				
				let responsesArray = [];

				$scope.find('.wpr-button>span').addClass('wpr-loader-hidden');
				$scope.find('.wpr-button').find('.wpr-double-bounce').removeClass('wpr-loader-hidden');

				if ( $scope.find('.wpr-submit-error') ) {
					$scope.find('.wpr-submit-error').remove();
				}

				if ( $scope.find('.wpr-submit-success') ) {
					$scope.find('.wpr-submit-success').remove();
				} 
				
				function processRecaptcha(callback) {
					if ($scope.find('#g-recaptcha-response').length > 0) {
						grecaptcha.ready(function() {
							grecaptcha.execute(WprConfig.site_key, {action: 'submit'}).then(function(token) {
								// Set the token value to the hidden input field
								$scope.find('#g-recaptcha-response').val(token);
			
								// Perform the AJAX call after the token is set
								$.ajax({
									type: 'POST',
									url: WprConfig.ajaxurl,
									data: {
										action: 'wpr_verify_recaptcha',
										'g-recaptcha-response': token
									},
									success: function(response) {
										if( !response.success ) {
											console.log(response);
											setTimeout(function() {
												$scope.find('.wpr-button').find('.wpr-double-bounce').addClass('wpr-loader-hidden');
												$scope.find('.wpr-button>span').removeClass('wpr-loader-hidden');
												$scope.find('form').append('<p class="wpr-submit-error">'+ WprConfig.recaptcha_error +'</p>');
											}, 500);
											callback(false); // Call the callback with failure
										} else {
											console.log(response);
											callback(true); // Call the callback with success
										}
									},
									error: function(error) {
										console.log(error);
										setTimeout(function() {
											$scope.find('.wpr-button').find('.wpr-double-bounce').addClass('wpr-loader-hidden');
											$scope.find('.wpr-button>span').removeClass('wpr-loader-hidden');
											$scope.find('form').append('<p class="wpr-submit-error">'+ WprConfig.recaptcha_error +'</p>');
										}, 500);
										callback(false); // Call the callback with failure
									}
								});
							});
						});
					} else {
						callback(true); // Call the callback if there's no reCAPTCHA
					}
				}

				// Call the processRecaptcha function and pass a callback that submits the form on success
				processRecaptcha(function(isRecaptchaSuccessful) {
					if (isRecaptchaSuccessful) {

						// Perform the form submission here
						var actionsObject = {
							emailPromise: sendEmail,
							submissionsPromise: createPost,
							mailchimpPromise: subscribeMailchimp,
							webhookPromise: sendWebhook
						}

						// Wait for all Promises to resolve
						Promise.all(
							actions.map((action) => {
								try {
									if (actionsObject[action + 'Promise']) {
										return actionsObject[action + 'Promise']();
									}
								} catch (error) {
									console.error(error);
									return Promise.reject(error);
								}
							})
						)
						.then((responses) => {
							console.log(responses);
							
							// Find the post ID from the createPost() response
							const createPostResponse = responses.find((response) => response && response.data.action === 'wpr_form_builder_submissions');

							const postId = createPostResponse ? createPostResponse.data.post_id : null;
							
								// Update post meta for each action
								var updateMetaPromises = actions.map((action) => {
									if ( action !== 'redirect' ) {
										action = 'wpr_form_builder_' + action;
										
										// Find the response object for the current action
										const response = responses.find((response) => response && response.data.action === action);
										
										// Store the message from the response object in a variable
										const message = response ? response.data.message : '';
										
										if (response && response.data.status === 'success') {
											responsesArray.push('success');
											if (postId) {
												return updateFormActionMeta(postId, action, 'success', message);
											}
										} else {
											responsesArray.push('error');
											
											if (postId) {
												return updateFormActionMeta(postId, action, 'error', message);
											}
										}
									}
								});
							
								return Promise.all(updateMetaPromises).then(() => {
									if (responsesArray.includes('error')) {
										$scope.find('form').append('<p class="wpr-submit-error">'+ $scope.data('settings').error_message +'</p>');
									} else {
										$scope.find('form').append('<p class="wpr-submit-success">'+ $scope.data('settings').success_message +'</p>');
										$scope.find('button').attr('disabled', true);
										$scope.find('button').css('opacity', 0.6);
									}
								});
							// }
						})
						.catch((error) => {
							// Handle errors
							console.error(error);
						})
						.then(() => {
							// All AJAX actions have completed
							setTimeout(function() {
								// Switch submit button from loader back to submit
								$scope.find('.wpr-button').find('.wpr-double-bounce').addClass('wpr-loader-hidden');
								$scope.find('.wpr-button>span').removeClass('wpr-loader-hidden');
								setTimeout(function() {
									if (actions.includes('redirect') && responsesArray.includes('success')) {
										// window.location.replace($scope.find('.wpr-form-field-type-submit').data('redirect-url'));
										$(location).prop('href', $scope.find('.wpr-form-field-type-submit').data('redirect-url'))
									}
								}, 500);
							}, 500);
						})
						.catch((error) => {
							// Handle errors
							console.error(error);
						});
					} else {
						// Handle the case when reCAPTCHA fails
						return false;
					}
				});

				function updateFormActionMeta(postId, actionName, status, message) {
					return $.ajax({
						type: 'POST',
						url: WprConfig.ajaxurl,
						data: {
							action: 'wpr_update_form_action_meta',
							post_id: postId,
							action_name: actionName,
							status: status,
							message: message
						},
					});
				}

				function deepCopy(obj) {
					return JSON.parse(JSON.stringify(obj));
				}
				
				function sendEmail() {
					var data = deepCopy(formContent);
					
					for (let key in data) {
						if (data[key][0] == 'radio' || data[key][0] == 'checkbox' ) {
							if (Array.isArray(data[key][1])) {
								let trueValues = data[key][1].filter(innerArray => innerArray[1] === true).map(innerArray => innerArray[0]);
								let trueValuesString = trueValues.join(', ');
								data[key][1] = trueValuesString;
							}
						}
					}

					return $.ajax({
						type: 'POST',
						url: WprConfig.ajaxurl,
						data: { 
							action: 'wpr_form_builder_email',
							nonce: WprConfig.nonce,
							form_content: data,
							wpr_form_id: $scope.find('input[name="form_id"]').val(),
						},
						success: function(response) {
							console.log(response);
							if ( !response.success ) {
								// if (WprConfig.is_admin) {
								// 	$scope.find('form').append('<p class="wpr-submit-error">'+ response.data.message +'</p>');
								// }
							} else {
								// if (WprConfig.is_admin) {
								// 	$scope.find('form').append('<p class="wpr-submit-success">'+ response.data.message +'</p>');
								// }
							}
						},
						error: function(error) {
							// if (WprConfig.is_admin) {
							// 	$scope.find('form').append('<p class="wpr-submit-error">'+ error.data.message +'</p>');
							// }
						}
					});
				}

				function sendWebhook() {
					var data = deepCopy(formContent);
					
					for (let key in data) {
						if (data[key][0] == 'radio' || data[key][0] == 'checkbox' ) {
							if (Array.isArray(data[key][1])) {
								let trueValues = data[key][1].filter(innerArray => innerArray[1] === true).map(innerArray => innerArray[0]);
								let trueValuesString = trueValues.join(', ');
								data[key][1] = trueValuesString;
							}
						}
					}

					return $.ajax({
						type: 'POST',
						url: WprConfig.ajaxurl,
						data: { 
							action: 'wpr_form_builder_webhook',
							nonce: WprConfig.nonce,
							form_content: data,
							wpr_form_id: $scope.find('input[name="form_id"]').val(),
							form_name: $scope.find('form').attr('name')
						},
						success: function(response) {
							console.log(response);
							if ( !response.success ) {
								// if (WprConfig.is_admin) {
								// 	$scope.find('form').append('<p class="wpr-submit-error">'+ response.data.message +'</p>');
								// }
							} else {
								// if (WprConfig.is_admin) {
								// 	$scope.find('form').append('<p class="wpr-submit-success">'+ response.data.message +'</p>');
								// }
							}
						},
						error: function(error) {
							console.log(error);
							// if (WprConfig.is_admin) {
							// 	$scope.find('form').append('<p class="wpr-submit-error">'+ error.data.message +'</p>');
							// }
						}
					});
				}

				function createPost() {
					
					var data = {
						action: 'wpr_form_builder_submissions',
						nonce: WprConfig.nonce,
						form_content: formContent,
						status: 'publish',
						form_name: $scope.find('form').attr('name'),
						form_id: $scope.find('input[name="form_id"]').val(),
						form_page: $scope.find('form').attr('page'),
						form_page_id: $scope.find('form').attr('page_id')
					};
					
					return $.ajax({
						type: 'POST',
						url: WprConfig.ajaxurl,
						data: data,
						success: function(response) {
							console.log(response);
							// if (WprConfig.is_admin) {
							// 	$scope.find('form').append('<p class="wpr-submit-success">'+ response.data.message +'</p>');
							// }
						},
						error: function(error) {
							console.log(error)
							// if (WprConfig.is_admin) {
							// 	$scope.find('form').append('<p class="wpr-submit-error">'+ response.data.message +'</p>');
							// }
						}
					});
				}

				function subscribeMailchimp() {

					const submitButton = $scope.find('.wpr-form-field-type-submit');
					const mailchimpFields = JSON.parse(submitButton.attr('data-mailchimp-fields'));

					let formData = {};
		
					Object.keys(mailchimpFields).forEach(function (fieldId) {
						if ( fieldId == 'group_id' ) {

							var fieldValue = Array.isArray(mailchimpFields[fieldId]) ? mailchimpFields[fieldId].join(',') : mailchimpFields[fieldId];
						} else {
							var fieldValue = $scope.find('#form-field-' + mailchimpFields[fieldId]).val();
						}
						if ( fieldValue ) {
							if ( fieldId == 'birthday_field') {
								formData[fieldId] = convertToMailchimpBirthdayFormat(fieldValue);
							} else {
								formData[fieldId] = fieldValue;
							}
						}
					});

					return $.ajax({
						url: WprConfig.ajaxurl,
						method: 'POST',
						data: {
							action: 'wpr_form_builder_mailchimp',
							nonce: WprConfig.nonce,
							form_data: formData,
							listId: submitButton.data( 'list-id' )
							// security: mailchimpSubscription.security
						},
						beforeSend: function () {
							submitButton.prop('disabled', true);
						},
						success: function (response) {
							console.log(response);
							if (!response.success) {
								// if (WprConfig.is_admin) {
								// 	$scope.find('form').append('<p class="wpr-submit-error">'+ response.data.message +'</p>');
								// }
							} else {
								// if (WprConfig.is_admin) {
								// 	$scope.find('form').append('<p class="wpr-submit-success">'+ response.data.message +'</p>');
								// }
							}
							// Handle success response, e.g., show a success message.
						},
						error: function (jqXHR, textStatus, errorThrown) {
							console.log(errorThrown);
							// if (WprConfig.is_admin) {
							// 	$scope.find('form').append('<p class="wpr-submit-error">'+ errorThrown.message +'</p>');
							// }
						},
						complete: function () {
							submitButton.prop('disabled', false);
						}
					});
				}
			});

			function createFormContent() {
				$scope.find('.wpr-form-field, .wpr-form-field-type-radio, .wpr-form-field-type-checkbox, .wpr-step-input').each(function() {

					var label = '';
					if ( $(this).prev('label') ) {
						label = $(this).prev('label').text().trim();
					} else {
						label = '';
					}
								
					if ( 'textarea' !== $(this).prop('tagName').toLowerCase() ) {
						if ( $(this).hasClass('wpr-select-wrap') ) {
							var selectValue = $(this).find('select').val();
							if ( Array.isArray($(this).find('select').val()) ) {
								selectValue = $(this).find('select').val().join(', ');
							} else {
								selectValue = $(this).find('select').val();
							}
							formContent[$(this).find('select').attr('id').replace('-', '_')] = ['select', selectValue, label];
						} else if ( $(this).hasClass('wpr-form-field-type-radio' ) || $(this).hasClass('wpr-form-field-type-checkbox') ) {
							var valuesArray = [];
							var checkedField = $(this).find('input');
							var type;
							checkedField.each(function() {
								valuesArray.push([$(this).val(), $(this).is(':checked'), $(this).attr('name'), $(this).attr('id')]);
							});

							if ( $(this).hasClass('wpr-form-field-type-radio') ) {
								type = 'radio'
							} else {
								type = 'checkbox';
							}

							var inputLabel = $(this).find('.wpr-form-field-label').text().trim();

							if (checkedField.length > 0) {
								formContent[$(this).find('.wpr-form-field-option').data('key').replace('-', '_')] = [type, valuesArray, inputLabel];
							}
						} else if ( $(this).hasClass('wpr-step-input') ) {
							formContent[$(this).attr('id').replace('-', '_')] = [$(this).attr('type'), '', $(this).val(), label];
						} else {
							if ( $(this).attr('type') == 'file' ) {
								formContent[$(this).attr('id').replace('-', '_')] = [$(this).attr('type'), fileUrl[$(this).attr('id')], label];
							} else {
								formContent[$(this).attr('id').replace('-', '_')] = [$(this).attr('type'), $(this).val(), label];
							}
						}
					} else {
						formContent[$(this).attr('id').replace('-', '_')] = [$(this).prop('tagName').toLowerCase(), $(this).val(), label];
					}
	
				});
			}

			function handleFileValidityAndUpload(thisInput, files, eventType) {
				var thisId = thisInput.attr('id');
			  
				if (0 < thisInput.closest('.wpr-field-group').find('.wpr-submit-error').length) {
				  thisInput.closest('.wpr-field-group').find('.wpr-submit-error').remove();
				}
			  
				// Get the data-maxfs value from the input.
				var maxFileSize = thisInput.data('maxfs') ? thisInput.data('maxfs') : 0;
				var allowedFileTypes = thisInput.data('allft') ? thisInput.data('allft') : 0;
			  
				// Create an array to store the upload promises
				let uploadPromises = [];
			  
				for (let i = 0; i < files.length; i++) {
				  var fileInput = files[i];
			  
				  // Create a new FormData object.
				  var formDataForFile = new FormData();
				  formDataForFile.append('action', 'wpr_addons_upload_file');
				  formDataForFile.append('uploaded_file', fileInput);
				  formDataForFile.append('max_file_size', maxFileSize);
				  formDataForFile.append('allowed_file_types', allowedFileTypes);
				  formDataForFile.append('triggering_event', eventType);
				  formDataForFile.append('wpr_addons_nonce', WprConfig.nonce);
			  
				  if ('click' == eventType) {
					if (!fileUrl[thisId]) {
					  fileUrl[thisId] = [];
					}
				  }
			  
				  // Wrap the AJAX call in a Promise and push it to the uploadPromises array
				  uploadPromises.push(
					new Promise((resolve, reject) => {
					  $.ajax({
						url: WprConfig.ajaxurl,
						type: 'POST',
						data: formDataForFile,
						processData: false,
						contentType: false,
						success: function(response) {
						  if (response.success) {
							// Do something with the uploaded file's URL (e.g., store it in a hidden input).
							console.log(response);
							if (eventType == 'click') {
							  fileUrl[thisId][i] = response.data.url;
							}
							resolve(response);
						  } else {
							console.error('Error:', response);
							if (response.data ) {
								if ( 'filesize' === response.data.cause ) {
									let maxFileNotice = thisInput.data('maxfs-notice') ? thisInput.data('maxfs-notice') : response.data.message;
									thisInput.closest('.wpr-field-group').append('<p class="wpr-submit-error">' + maxFileNotice + '</p>');
								}

								if ( 'filetype' == response.data.cause ) {
									thisInput.closest('.wpr-field-group').append('<p class="wpr-submit-error">' + response.data.message + '</p>');
								}
							}

							reject(response);
						  }
						},
						error: function(error) {
							if ( 'filesize' === error.cause ) {
								let maxFileNotice = thisInput.data('maxfs-notice') ? thisInput.data('maxfs-notice') : error.message;
								thisInput.closest('.wpr-field-group').append('<p class="wpr-submit-error">' + maxFileNotice + '</p>');
							}

							if ( 'filetype' == error.cause ) {
								thisInput.closest('.wpr-field-group').append('<p class="wpr-submit-error">' + error.message + '</p>');
							}
						  console.log(error);
						  reject(error);
						},
					  });
					}),
				  );
				}
			  
				// Return a Promise that resolves when all uploadPromises are resolved
				return Promise.all(uploadPromises);
			}			  
			
			function convertToMailchimpBirthdayFormat(dateString) {
				const date = new Date(dateString);
				const month = (date.getMonth() + 1).toString().padStart(2, '0');
				const day = date.getDate().toString().padStart(2, '0');
				return `${month}/${day}`;
			}

			function showTab(n) {
				// This function will display the specified tab of the form...
				var $stepTab = $scope.find(".wpr-step-tab");
				$stepTab.eq(n).removeClass('wpr-step-tab-hidden');
				//... and fix the Previous/Next buttons:
				if (n === 0) {
					$scope.find(".wpr-step-prev").hide();
				} else {
					$scope.find(".wpr-step-prev").show();
				}
				//... and run a function that will display the correct step indicator:
				fixStepIndicator(n);
			}

			function nextPrev(n) {
				// This function will figure out which tab to display
				var $stepTab = $scope.find(".wpr-step-tab");

				// Exit the function if any field in the current tab is invalid:
				if (n === 1 && !validateForm()) {
					return false;
				}
				// Hide the current tab:
				$stepTab.eq(currentTab).addClass('wpr-step-tab-hidden');
				// Increase or decrease the current tab by 1:
				currentTab = currentTab + n;
				// if you have reached the end of the form...
				if (currentTab >= $stepTab.length) {
					// ... the form gets submitted:
					$scope.find("form").submit();
					return false;
				}
				// Otherwise, display the correct tab:
				showTab(currentTab);
			}

			function validateForm() {
				var valid = true;
				var $stepTab = $scope.find(".wpr-step-tab");
				if ( !($stepTab.length > 0) ) {
					$stepTab = $scope.find('.wpr-form-fields-wrap');
					currentTab = 0;
				}
				var $types = ['text', 'email', 'password', 'file', 'url', 'tel', 'number', 'date', 'datetime-local', 'time', 'week', 'month', 'color']; // radio checkbox ?

				$stepTab.eq(currentTab).find('input, select, textarea').each(function() {
				  const type = $(this).attr('type');

				  var requiredField = $(this).closest('.wpr-field-group').find('.wpr-form-field').attr('required') === 'required' || $(this).closest('.wpr-field-group').find('.wpr-form-field-textual').attr('required') === 'required';

				//   if ( this.tagName === 'SELECT' ) {
				// 	requiredField = $(this).attr('required') === 'required';
				//   }

				  if ( type !== undefined && $.inArray(type, $types) !== -1 && $(this).val() === '' && requiredField ) {
					// add an "invalid" class to the field:
					$(this).addClass("wpr-form-error");
					// and set the current valid status to false
					valid = false;
				  } else if ( type === 'radio' || type === 'checkbox' ) {
					let requiredOption = $(this).closest('.wpr-field-group').find('.wpr-form-field-option input').attr('required') === 'required';

					if ( requiredOption && $stepTab.eq(currentTab).find('input[type="'+ type +'"]:checked').length === 0 ) {
						// add an "invalid" class to the field:
						$(this).addClass("wpr-form-error");
						// and set the current valid status to false
						valid = false;
					}
				  } else if ( requiredField && this.tagName === 'SELECT' && $(this).val().trim() === '' ) {
					// select error wrap
					$(this).closest('.wpr-select-wrap').addClass('wpr-form-error-wrap');
					// add an "invalid" class to the field:
					$(this).addClass("wpr-form-error");
					// and set the current valid status to false
					valid = false;
				  } else if ( requiredField && this.tagName === 'TEXTAREA' && $(this).val().trim() === '' ) {
					// add an "invalid" class to the field:
					$(this).addClass("wpr-form-error");
					// and set the current valid status to false
					valid = false;
				  }
				});

				if (!valid) {
					$stepTab.eq(currentTab).find('.wpr-form-error, .wpr-form-error-wrap').each(function() {
						if ( !($(this).closest('.wpr-field-group').find('.wpr-submit-error').length > 0) ) {
							if ( $(this).attr('type') == 'file' ) {
								$(this).closest('.wpr-field-group').append('<p class="wpr-submit-error">'+ WprConfig.file_empty +'</p>');
							} else if ( $(this).is('select') || $(this).attr('type') === 'radio' || $(this).attr('type') === 'checkbox' ) {
								$(this).closest('.wpr-field-group').append('<p class="wpr-submit-error">'+ WprConfig.select_empty +'</p>');
							} else {
								$(this).closest('.wpr-field-group').append('<p class="wpr-submit-error">'+ WprConfig.input_empty +'</p>');
							}
						}
					});
				}

				if (valid) {
					$scope.find(".wpr-step").eq(currentTab).addClass("wpr-step-finish");
				} else {
                    if ( $scope.find(".wpr-step").eq(currentTab).hasClass('wpr-step-finish') ) {
                        $scope.find(".wpr-step").eq(currentTab).removeClass('wpr-step-finish');
                    }
                }

				return valid;
			}

			function fixStepIndicator(n) {
				// This function removes the "active" class of all steps...
				var $step = $scope.find(".wpr-step");
				$step.removeClass("wpr-step-active");
				//... and adds the "active" class on the current step:
				$step.eq(n).addClass("wpr-step-active");

                if ( $scope.find('.wpr-step-active').hasClass('wpr-step-finish') ) {
                    $scope.find('.wpr-step-active').removeClass('wpr-step-finish');
                }

				const stepTabs = $scope.find('.wpr-step-tab');
				const progressBarFill = $scope.find('.wpr-step-progress-fill');
		
				let currentStep = n + 1;

				updateProgressBar()
		
				function updateProgressBar() {
					const totalSteps = stepTabs.length;
					const progressPercentage = (currentStep / totalSteps) * 100;
		
					progressBarFill.css('width', progressPercentage + '%');
					setTimeout(function() {
						progressBarFill.text(Math.round(progressPercentage) + '%');
					}, 500);
				}
			}
		}, // end widgetFormBuilder

		widgetProductAddToCart: function($scope) {
			var qtyInput = jQuery('.woocommerce .wpr-quantity-wrapper'),
				qtyInputInStock = qtyInput.find('input.qty').attr('max') ? qtyInput.find('input.qty').attr('max') : 99999999,
				qtyLayout = $scope.find('.wpr-product-add-to-cart').attr('layout-settings'),
				qtyWrapper = $scope.find('.wpr-add-to-cart-icons-wrap'),
				plusIconChild = !$scope.find('.wpr-add-to-cart-icons-wrap').length ? 'last-child' : 'first-child',
				minusIconChild = !$scope.find('.wpr-add-to-cart-icons-wrap').length ? 'first-child' : 'last-child';

			$scope.find('input.qty').each(function() {
				if (!$(this).val()) {
					$(this).val(0);
				}
			});
			
			$scope.find('.variations').find('select').on('change', function () {
				var resetButtonDisplay = false;
				$scope.find('.variations').find('select').each(function () {
					if ( 'choose an option' !== $(this).find('option:selected').text().toLowerCase() ) {
						resetButtonDisplay = true;
					}
				});

				if ( resetButtonDisplay == false ) {
					$scope.find('.reset_variations').css('display', 'none');
				} else {
					$scope.find('.reset_variations').css('display', 'inline-block');
				}
			});

			// convert to text input
			if (qtyLayout !== 'default' ) {
				qtyInput.find('input.qty').attr('type', 'text').removeAttr('step').removeAttr('min').removeAttr('max');
			}
		
			// plus
			qtyInput.on('click', 'i:'+plusIconChild, function() {

				if ( parseInt(jQuery(this).prev('.quantity').find('input.qty').val(), 10) < qtyInputInStock && qtyLayout == 'both' ) {
					jQuery(this).prev('.quantity').find('input.qty').val( parseInt(jQuery(this).prev('.quantity').find('input.qty').val(), 10) + 1);
					jQuery('input[name="update_cart"]').removeAttr('disabled');
				} else if ( parseInt(jQuery(this).parent().siblings('.quantity').find('input.qty').val(), 10) < qtyInputInStock && qtyLayout !== 'both' && qtyLayout !== 'default' ) {
					jQuery(this).parent().siblings('.quantity').find('input.qty').val( parseInt(jQuery(this).parent().siblings('.quantity').find('input.qty').val(), 10) + 1);
					jQuery('input[name="update_cart"]').removeAttr('disabled');
				}
			});
			
			// minus
			qtyInput.on('click', 'i:'+minusIconChild, function() {
				if ( parseInt(jQuery(this).next('.quantity').find('input.qty').val(), 10) > 0 && qtyLayout == 'both' ) {
					jQuery(this).next('.quantity').find('input.qty').val( parseInt(jQuery(this).next('.quantity').find('input.qty').val(), 10) - 1);
					jQuery('input[name="update_cart"]').removeAttr('disabled');
				} else if ( parseInt(jQuery(this).parent().siblings('.quantity').find('input.qty').val(), 10) > 0 && qtyLayout !== 'both' && qtyLayout !== 'default' ) {
					jQuery(this).parent().siblings('.quantity').find('input.qty').val( parseInt(jQuery(this).parent().siblings('.quantity').find('input.qty').val(), 10) - 1);
					jQuery('input[name="update_cart"]').removeAttr('disabled');
				}
			});
		
			// in stock range check
			qtyInput.find('input.qty').keyup(function() {
				if ( jQuery(this).val() > qtyInputInStock ) {
					jQuery(this).val( qtyInputInStock );
				}
			});

			if ( 'yes' === $scope.find('.wpr-product-add-to-cart').data('ajax-add-to-cart') ) {
				if ( !$('div[data-elementor-type="wpr-theme-builder"]').hasClass('product-type-external') ) {
					$scope.find('.single_add_to_cart_button').on('click', ajaxAddToCart);
				}
			}

			function ajaxAddToCart(e) {
				e.preventDefault();
			
				let $form = $( this ).closest('form');

				var $variationForm = $form.closest('.variations_form');

				let isGrouped = $form.hasClass('grouped_form');
			
				if ( ! $form[0].checkValidity() ) {
					$form[0].reportValidity();
			
					return false;
				}
			
				let $thisBtn = $( this ),
					product_id = $thisBtn.val() || '',
					cartFormData = $form.serialize();
					
					// // Get the ID of the selected variation
					// let variation_id = $scope.find('input[name="variation_id"]').val();
					// console.log(window['wc_variation_form']);
					// // Get the data of the selected variation
					// let variation_data = window['wc_variation_form'].variation_data[variation_id];
					
					// // Get the availability HTML of the selected variation
					// let availability_html = variation_data.availability_html;
					
					// // Check if the variation is in stock
					// if (availability_html.indexOf('In stock') !== -1) {
					//   console.log('Selected variation is in stock');
					// } else {
					//   console.log('Selected variation is out of stock');
					// }
				
				if (isGrouped) {
					let nonZero = false;
					$scope.find('.woocommerce-grouped-product-list-item__quantity').find('input').each(function() {
						if ($(this).val() > 0 ) {
							nonZero = true;
						}
					});

					if ( !nonZero ) {
						// The grouped product does not have the required number of items selected
						alert(WprConfig.chooseQuantityText);
						return;
					}
				}
			
				$.ajax( {
					type: 'POST',
					url: WprConfig.ajaxurl,
					data: 'action=wpr_addons_add_cart_single_product&add-to-cart=' + product_id + '&' + cartFormData,
					beforeSend: function () {
						if ( $variationForm.length > 0 && ! $variationForm.find('.variations select').val() ) {
							// Do not trigger added_to_cart event if options are not selected for variable product
							return;
						} 
						if ( $thisBtn.hasClass('disabled') ) {
							return
						}

						$thisBtn.removeClass( 'added' ).addClass( 'loading' );
					},
					complete: function () {
						if ( $variationForm.length > 0 && ! $variationForm.find('.variations select').val() ) {
							// Do not trigger added_to_cart event if options are not selected for variable product
							return;
						} 

						if ( $thisBtn.hasClass('disabled') ) {
							return
						}

						$thisBtn.addClass( 'added' ).removeClass( 'loading' );
					},
					success: function ( response ) {

						// GOGA - remove later
						if (response.notices && response.notices.length > 0) {

							// The selected variation is low in stock, display a warning message
							if (response.notices[0].type === 'wc_low_stock') {
								alert('Only ' + response.notices[0].message + ' left in stock!');
							} else {
								alert(response.notices[0].message);
							}
						}

						if ( response.error && response.product_url ) {
							window.location = response.product_url;
							return;
						}
			
						if ( typeof wc_add_to_cart_params === 'undefined' ) {
							return false;
						}
			
						if ( $variationForm.length > 0 && ! $variationForm.find('.variations select').val() ) {
							// Do not trigger added_to_cart event if options are not selected for variable product
							return;
						}
						
						if ( $thisBtn.hasClass('disabled') ) {
							return;
						}

						$( document.body ).trigger( 'wc_fragment_refresh' );
						$( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, $thisBtn ] );

						setTimeout( function () {
							$thisBtn.removeClass( 'added' );
							var currentCartCount = parseInt($('.wpr-mini-cart-icon-count').text());
							var updatedCartCount = parseInt($scope.find('.wpr-quantity-wrapper .qty').val());
							$('.wpr-mini-cart-icon-count').text(currentCartCount + updatedCartCount);
						}, 1000 );
					},
				} );
			}
		}, // End of widgetProductAddToCart

		widgetMiniWishlist: function($scope) {
			
			if ( !($scope.find('.wpr-wishlist-count').length > 0 && 0 == $scope.find('.wpr-wishlist-count').text()) ) {
				$scope.find('.wpr-wishlist-count').css('display', 'inline-flex');
			} else {

			}
			
			function wishlistRemoveHandler() {
				$scope.find('.wpr-wishlist-remove').on('click', function(e) {
					e.preventDefault();
					var product_id = $(this).data('product-id');
					$.ajax({
						url: WprConfig.ajaxurl,
						type: 'POST',
						data: {
							action: 'remove_from_wishlist',
							product_id: product_id
						},
						success: function() {
							   $scope.find('.wpr-wishlist-product[data-product-id="' + product_id + '"]').remove();
							WprElements.changeActionTargetProductId(product_id);
							$(document).trigger('removed_from_wishlist');
						}
					});
				});
			}

			wishlistRemoveHandler();
	
			var mutationObserver = new MutationObserver(function(mutations) {
				wishlistRemoveHandler();
			});

			mutationObserver.observe($scope[0], {
				childList: true,
				subtree: true,
			});

			$.ajax({
				url: WprConfig.ajaxurl,
				type: 'POST',
				data: {
					action: 'count_wishlist_items',
				},
				success: function(response) {
					if ( $scope.find('.wpr-wishlist-count').css('display') == 'none' && 0 < response.wishlist_count ) {
						$scope.find('.wpr-wishlist-count').text(response.wishlist_count);
						$scope.find('.wpr-wishlist-count').css('display', 'inline-flex');
					} else if ( 0 == response.wishlist_count ) {
						$scope.find('.wpr-wishlist-count').css('display', 'none');
						$scope.find('.wpr-wishlist-count').text(response.wishlist_count);
					} else {
						$scope.find('.wpr-wishlist-count').text(response.wishlist_count);
					}

					if ( true ) {
						// Get all elements with the class 'wpr-wishlist-product' and their product IDs
						var productElements = $scope.find('.wpr-wishlist-product');
						var productIds = productElements.map(function() {
						  return $(this).data('product-id');
						}).get();
						
						// Filter out the items in the response that match the product IDs
						var newWishlistItems = response.wishlist_items.filter(function(item) {
						  return !productIds.includes(item.product_id);
						});

						// Convert the wishlist_items to an array of product_ids for easier searching
						var wishlistProductIds = response.wishlist_items.map(function(item) {
							return item.product_id;
						});

						productElements.each(function() {
						  var productId = $(this).data('product-id');
						
						  // If the product ID is not in the wishlistProductIds array, remove the element
						  if (!wishlistProductIds.includes(productId)) {
							$(this).remove();
						  }
						});

						newWishlistItems.forEach(function(item) {
							$scope.find('.wpr-wishlist-products').append('<li class="wpr-wishlist-product" data-product-id="'+ item.product_id +'"><a class="wpr-wishlist-product-img" href="'+ item.product_url +'">'+ item.product_image +'</a><div><a href="'+ item.product_url +'">'+ item.product_title +'</a><div class="wpr-wishlist-product-price">'+ item.product_price +'</div></div><span class="wpr-wishlist-remove" data-product-id="'+ item.product_id +'"></span></li>');
						});
					}
				}
			});

			$(document).on('added_to_wishlist', function() {
				$.ajax({
					url: WprConfig.ajaxurl,
					type: 'POST',
					data: {
						action: 'update_mini_wishlist',
						product_id: actionTargetProductId,
					},
					success: function(response) {
						if ( $scope.find('.wpr-wishlist-products').find('li[data-product-id='+ response.product_id +']').length == 0 ) {
							$scope.find('.wpr-wishlist-products').append('<li class="wpr-wishlist-product" data-product-id="'+ response.product_id +'"><a class="wpr-wishlist-product-img" href="'+ response.product_url +'">'+ response.product_image +'</a><div><a href="'+ response.product_url +'">'+ response.product_title +'</a><div class="wpr-wishlist-product-price">'+ response.product_price +'</div></div><span class="wpr-wishlist-remove" data-product-id="'+ response.product_id +'"></span></li>');
						}

						$scope.find('.wpr-wishlist-count').text(response.wishlist_count);
						$scope.find('.wpr-wishlist-count').css('display', 'inline-flex');
					}
				});
			});

			$(document).on('removed_from_wishlist', function() {
				$scope.find('.wpr-wishlist-product[data-product-id="' + actionTargetProductId + '"]').remove();
				$.ajax({
					url: WprConfig.ajaxurl,
					type: 'POST',
					data: {
						action: 'update_mini_wishlist',
						product_id: actionTargetProductId,
					},
					success: function(response) {
						$scope.find('.wpr-wishlist-count').text(response.wishlist_count);
						
						if ( response.wishlist_count == 0 ) {
							$scope.find('.wpr-wishlist-count').css('display', 'none');
						} else {
							$scope.find('.wpr-wishlist-count').css('display', 'inline-flex');
						}
					}
				});
			});
			$scope.find('.wpr-wishlist').css({"display": "none"});

			var animationSpeed = $scope.find('.wpr-wishlist-wrap').data('animation');

			$('body').on('click', function(e) {
				if ( !e.target.classList.value.includes('wpr-wishlist-wrap') && !e.target.closest('.wpr-wishlist-wrap') ) {
					if ( $scope.hasClass('wpr-wishlist-slide') ) {
						$scope.find('.wpr-wishlist').slideUp(animationSpeed);
					} else {
						$scope.find('.wpr-wishlist').fadeOut(animationSpeed);
					}
				}
			});

			if ( 0 !== $scope.hasClass('wpr-wishlist-sidebar').length ) {
				if ( $('#wpadminbar').length ) {
					$scope.find('.wpr-wishlist').css({
						// 'top': $('#wpadminbar').css('height'),
						// 'height': $scope.find('.wpr-shopping-cart-wrap').css('height') -  $('#wpadminbar').css('height')
						'z-index': 999999
					});
				}

				closeSideBar();

				$scope.find('.wpr-wishlist').on('click', function(e) {
					// if ( !e.target.classList.value.includes('widget_shopping_cart_content') && !e.target.closest('.widget_shopping_cart_content') ) {
					if ( !e.target.classList.value.includes('wpr-wishlist-inner-wrap') && !e.target.closest('.wpr-wishlist-inner-wrap') ) {
						// $scope.find('.widget_shopping_cart_content').addClass('wpr-mini-cart-slide-out');
						$scope.find('.wpr-wishlist-inner-wrap').addClass('wpr-wishlist-slide-out');
						$scope.find('.wpr-wishlist-slide-out').css('animation-speed', animationSpeed);
						$scope.find('.wpr-wishlist').fadeOut(animationSpeed);
						$('body').removeClass('wpr-wishlist-sidebar-body');
						setTimeout(function() {
							// $scope.find('.widget_shopping_cart_content').removeClass('wpr-mini-cart-slide-out');
							$scope.find('.wpr-wishlist-inner-wrap').removeClass('wpr-wishlist-slide-out');
							$scope.find('.wpr-wishlist').css({"display": "none"});
						}, animationSpeed + 100);
					}
				});
			}

			if ( $scope.find('.wpr-wishlist').length ) {
				if ( $scope.hasClass('wpr-wishlist-sidebar') || $scope.hasClass('wpr-wishlist-dropdown') ) {
					$scope.find('.wpr-wishlist-toggle-btn').on('click', function(e) {
						e.stopPropagation();
						e.preventDefault();
						if ( 'none' === $scope.find('.wpr-wishlist').css("display") ) {
							if ( $scope.hasClass('wpr-wishlist-slide') ) {
								$scope.find('.wpr-wishlist').slideDown(animationSpeed);
							} else {
								$scope.find('.wpr-wishlist').fadeIn(animationSpeed);
							}
							if ( $scope.hasClass('wpr-wishlist-sidebar') ) {
								$scope.find('.wpr-wishlist').fadeIn(animationSpeed);
								$scope.find('.wpr-wishlist-inner-wrap').addClass('wpr-wishlist-slide-in');
								$scope.find('.wpr-wishlist-slide-in').css('animation-speed', animationSpeed);
								$('body').addClass('wpr-wishlist-sidebar-body');
							}
							setTimeout(function() {
								// $scope.find('.widget_shopping_cart_content').removeClass('wpr-mini-cart-slide-in');
								$scope.find('.wpr-wishlist').removeClass('wpr-wishlist-slide-in');
								if ( $scope.hasClass('wpr-wishlist-sidebar') ) {
									$scope.find('.wpr-wishlist').trigger('resize');
								}
							}, animationSpeed + 100);
						} else {
							if ( $scope.hasClass('wpr-wishlist-slide') ) {
								$scope.find('.wpr-wishlist').slideUp(animationSpeed);
							} else {
								$scope.find('.wpr-wishlist').fadeOut(animationSpeed);
							}
						}
					});
				}
			}

			var mutationObserver = new MutationObserver(function(mutations) {
				if (  0 !== $scope.hasClass('wpr-wishlist-sidebar').length ) {
					closeSideBar();
				}
				
				$scope.find('.wpr-wishlist-product').on('click', '.wpr-wishlist-remove', function() {
					$(this).closest('li').addClass('wpr-before-remove-from-wishlist');
				});

				if ( $scope.find('.wpr-wishlist-product').length !== 0 ) {
					$scope.find('.wpr-wishlist-empty').addClass('wpr-wishlist-empty-hidden');
					$scope.find('.wpr-view-wishlist').removeClass('wpr-hidden-element');
				} else {
					$scope.find('.wpr-wishlist-empty').removeClass('wpr-wishlist-empty-hidden');
					$scope.find('.wpr-view-wishlist').addClass('wpr-hidden-element');
				}
			});

			// Listen to Mini Cart Changes
			mutationObserver.observe($scope[0], {
				childList: true,
				subtree: true,
			});

			function closeSideBar() {
				$scope.find('.wpr-close-wishlist span').on('click', function(e) {
					// $scope.find('.widget_shopping_cart_content').addClass('wpr-mini-cart-slide-out');
					$scope.find('.wpr-wishlist-inner-wrap').addClass('wpr-wishlist-slide-out');
					$scope.find('.wpr-wishlist-slide-out').css('animation-speed', animationSpeed);
					$scope.find('.wpr-wishlist').fadeOut(animationSpeed);
					$('body').removeClass('wpr-wishlist-sidebar-body');
					setTimeout(function() {
						// $scope.find('.widget_shopping_cart_content').removeClass('wpr-mini-cart-slide-out');
						$scope.find('.wpr-wishlist-inner-wrap').removeClass('wpr-wishlist-slide-out');
						$scope.find('.wpr-wishlist').css({"display": "none"});
					}, animationSpeed + 100);
				});
			}
		}, // end widgetMiniWishlist

		widgetProductMiniCart: function($scope) {
				$scope.find('.wpr-mini-cart').css({"display": "none"});
			
				// $( document.body ).trigger( 'wc_fragment_refresh' );

				var animationSpeed = $scope.find('.wpr-mini-cart-wrap').data('animation');

				$('body').on('click', function(e) {
					if ( !e.target.classList.value.includes('wpr-mini-cart') && !e.target.closest('.wpr-mini-cart') ) {
						if ( $scope.hasClass('wpr-mini-cart-slide') ) {
							$scope.find('.wpr-mini-cart').slideUp(animationSpeed);
						} else {
							$scope.find('.wpr-mini-cart').fadeOut(animationSpeed);
						}
					}
				});

				if ( $scope.hasClass('wpr-mini-cart-sidebar') ) {
					if ( $('#wpadminbar').length ) {
						$scope.find('.wpr-mini-cart').css({
							// 'top': $('#wpadminbar').css('height'),
							// 'height': $scope.find('.wpr-shopping-cart-wrap').css('height') -  $('#wpadminbar').css('height')
							'z-index': 999999
						});
					}

					closeSideBar();

					$scope.find('.wpr-shopping-cart-wrap').on('click', function(e) {
						// if ( !e.target.classList.value.includes('widget_shopping_cart_content') && !e.target.closest('.widget_shopping_cart_content') ) {
						if ( !e.target.classList.value.includes('wpr-shopping-cart-inner-wrap') && !e.target.closest('.wpr-shopping-cart-inner-wrap') ) {
							// $scope.find('.widget_shopping_cart_content').addClass('wpr-mini-cart-slide-out');
							$scope.find('.wpr-shopping-cart-inner-wrap').addClass('wpr-mini-cart-slide-out');
							$scope.find('.wpr-mini-cart-slide-out').css('animation-speed', animationSpeed);
							$scope.find('.wpr-shopping-cart-wrap').fadeOut(animationSpeed);
							$('body').removeClass('wpr-mini-cart-sidebar-body');
							setTimeout(function() {
								// $scope.find('.widget_shopping_cart_content').removeClass('wpr-mini-cart-slide-out');
								$scope.find('.wpr-shopping-cart-inner-wrap').removeClass('wpr-mini-cart-slide-out');
								$scope.find('.wpr-mini-cart').css({"display": "none"});
							}, animationSpeed + 100);
						}
					});
				}

				if ( $scope.find('.wpr-mini-cart').length ) {
					if ( $scope.hasClass('wpr-mini-cart-sidebar') || $scope.hasClass('wpr-mini-cart-dropdown') ) {
						$scope.find('.wpr-mini-cart-toggle-btn').on('click', function(e) {
							e.stopPropagation();
							e.preventDefault();
							if ( 'none' === $scope.find('.wpr-mini-cart').css("display") ) {
								if ( $scope.hasClass('wpr-mini-cart-slide') ) {
									$scope.find('.wpr-mini-cart').slideDown(animationSpeed);
								} else {
									$scope.find('.wpr-mini-cart').fadeIn(animationSpeed);
								}
								if ( $scope.hasClass('wpr-mini-cart-sidebar') ) {
									$scope.find('.wpr-shopping-cart-wrap').fadeIn(animationSpeed);
									// $scope.find('.widget_shopping_cart_content').addClass('wpr-mini-cart-slide-in');
									$scope.find('.wpr-shopping-cart-inner-wrap').addClass('wpr-mini-cart-slide-in');
									$scope.find('.wpr-mini-cart-slide-in').css('animation-speed', animationSpeed);
									$('body').addClass('wpr-mini-cart-sidebar-body');
								}
								setTimeout(function() {
									// $scope.find('.widget_shopping_cart_content').removeClass('wpr-mini-cart-slide-in');
									$scope.find('.wpr-shopping-cart-inner-wrap').removeClass('wpr-mini-cart-slide-in');
									if ( $scope.hasClass('wpr-mini-cart-sidebar') ) {
										$scope.find('.wpr-woo-mini-cart').trigger('resize');
									}
								}, animationSpeed + 100);
							} else {
								if ( $scope.hasClass('wpr-mini-cart-slide') ) {
									$scope.find('.wpr-mini-cart').slideUp(animationSpeed);
								} else {
									$scope.find('.wpr-mini-cart').fadeOut(animationSpeed);
								}
							}
						});
					}
				}

				var mutationObserver = new MutationObserver(function(mutations) {
					if (  $scope.hasClass('wpr-mini-cart-sidebar') ) {
						closeSideBar();

						// if ( $scope.find('.wpr-mini-cart').data('close-cart-heading') ) {
						// 	$scope.find('.wpr-close-cart h2').text($scope.find('.wpr-mini-cart').data('close-cart-heading').replace(/-/g, ' '));
						// }
					}
					
					$scope.find('.woocommerce-mini-cart-item').on('click', '.wpr-remove-item-from-mini-cart', function() {
						$(this).closest('li').addClass('wpr-before-remove-from-mini-cart');
					});
				});

				// Listen to Mini Cart Changes
				mutationObserver.observe($scope[0], {
					childList: true,
					subtree: true,
				});

				function closeSideBar() {
					$scope.find('.wpr-close-cart span').on('click', function(e) {
						// $scope.find('.widget_shopping_cart_content').addClass('wpr-mini-cart-slide-out');
						$scope.find('.wpr-shopping-cart-inner-wrap').addClass('wpr-mini-cart-slide-out');
						$scope.find('.wpr-mini-cart-slide-out').css('animation-speed', animationSpeed);
						$scope.find('.wpr-shopping-cart-wrap').fadeOut(animationSpeed);
						$('body').removeClass('wpr-mini-cart-sidebar-body');
						setTimeout(function() {
							// $scope.find('.widget_shopping_cart_content').removeClass('wpr-mini-cart-slide-out');
							$scope.find('.wpr-shopping-cart-inner-wrap').removeClass('wpr-mini-cart-slide-out');
							$scope.find('.wpr-mini-cart').css({"display": "none"});
						}, animationSpeed + 100);
					});
				}

		}, // End of widgetProductMiniCart

		widgetProductFilters: function($scope) {
			if ( 0 !== $scope.find('.wpr-search-form-input').length ) {
				$scope.find('.wpr-search-form-input').on( {
					focus: function() {
						$scope.addClass( 'wpr-search-form-input-focus' );
					},
					blur: function() {
						$scope.removeClass( 'wpr-search-form-input-focus' );
					}
				} );
			}
		}, // End of widgetProductFilters

		widgetPageCart: function($scope) {
			// $scope.find('.shipping-calculator-button').trigger('click');
		}, // End of widgetPageCart

		widgetPageMyAccount: function($scope) {

			if ( WprElements.editorCheck() ) {
			
				$scope.find(".woocommerce-MyAccount-content").each(function() {
					if ( $(this).index() !== 1 ) {
						$(this).css('display', 'none');
					}
				});

				$scope.find('.woocommerce-MyAccount-navigation-link').on('click', function() {
					var tabContent, tabLinks, pageName;

					tabContent = $scope.find(".woocommerce-MyAccount-content");
					tabContent.each(function() {
							$(this).css('display', 'none');
					});

					tabLinks = $scope.find(".woocommerce-MyAccount-navigation-link");
					tabLinks.each(function() {
						$(this).removeClass('is-active');
					});

					pageName = $(this).attr('class').slice($(this).attr('class').indexOf('--') + 2);
					$(this).addClass('is-active');

					$scope.find('[wpr-my-account-page="'+ pageName +'"]').css('display', 'block');

				});
			}
			
			if ( $scope.find('.wpr-wishlist-remove').length ) {
				$scope.find('.wpr-wishlist-remove').on('click', function(e) {
					e.preventDefault();
					var product_id = $(this).data('product-id');
					$.ajax({
						url: WprConfig.ajaxurl,
						type: 'POST',
						data: {
							action: 'remove_from_wishlist',
							product_id: product_id,
						},
						success: function() {
							$scope.find('.wpr-wishlist-product[data-product-id="' + product_id + '"]').remove();
							WprElements.changeActionTargetProductId(product_id);
							$(document).trigger('removed_from_wishlist');
						}
					});
				});

				$(document).on('removed_from_wishlist', function() {
					$scope.find('.wpr-wishlist-product[data-product-id="' + actionTargetProductId + '"]').remove();
				});

			}
			
		}, // End of widgetPageMyAccount

		widgetReadingProgressBar: function($scope) {

			if ( $scope.find('.wpr-reading-progress-bar-container').length != 0 ) {
				var rpbContainer = $scope.find('.wpr-reading-progress-bar-container');
				readingProgressBar($scope, rpbContainer);
			}

			function readingProgressBar($scope, rpbContainer) {

				var initialPaddingTop = $('body').css('paddingTop');
				var initialPaddingBottom = $('body').css('paddingBottom');

				if ( '0px' === rpbContainer.css('top') ) {
					if ( 'colored' == rpbContainer.data('background-type') ) {
						$('body').css('paddingTop', $scope.find('.wpr-reading-progress-bar').css('height'));
					}
					if ( $('#wpadminbar').length ) {
						rpbContainer.css('top', $('#wpadminbar').height());
					}
					$('body').css('paddingBottom', initialPaddingBottom);
				} else if ( '0px' === rpbContainer.css('bottom') && 'colored' == rpbContainer.data('background-type') ) {
					$('body').css('paddingBottom', $scope.find('.wpr-reading-progress-bar').css('height'));
					$('body').css('paddingTop', initialPaddingTop);
				}

				readingProgressBarFill($scope);
				window.onscroll = function() {
					readingProgressBarFill($scope);
				};

			}

			function readingProgressBarFill($scope) {
				if ( $scope.find('.wpr-reading-progress-bar').length ) {
					var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
					var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
					var scrolled = (winScroll / height) * 100;
					$scope.find(".wpr-reading-progress-bar").css('width', scrolled + "%");
				}
			}

		},

		widgetDataTable: function($scope) {
			
			var beforeFilter = $scope.find("tbody .wpr-table-row"),
				itemsPerPage = +$scope.find('.wpr-table-inner-container').attr('data-rows-per-page'),
				paginationListItems = $scope.find('.wpr-table-custom-pagination-list-item'),
				initialRows = $scope.find('.wpr-table-inner-container tbody tr'),
				table = $scope.find('.wpr-table-inner-container tbody'),
				pageIndex, value, paginationIndex;

			// Table Custom Pagination
			if ( 'yes' === $scope.find('.wpr-table-inner-container').attr('data-custom-pagination') ) {

				var tableRows = initialRows.filter(function(index) {
					return index < $scope.find('.wpr-table-inner-container').attr('data-rows-per-page');
				});

				table.html(tableRows);

				adjustPaginationList();

				$scope.on('click', '.wpr-table-custom-pagination-list-item', function() {
						paginationListItems.removeClass('wpr-active-pagination-item');
						$(this).addClass('wpr-active-pagination-item');
						adjustPaginationList();
						table.hide();
						pageIndex = +$(this).text();
						itemsPerPage = +$scope.find('.wpr-table-inner-container').attr('data-rows-per-page');

						table.html(initialRows.filter(function(index) {
								index++;
								return index > itemsPerPage * (pageIndex - 1) && index <= itemsPerPage * pageIndex;
						}));

						table.show();
						beforeFilter = $scope.find("tbody .wpr-table-row");
						beforeFilter.find('.wpr-table-tr-before-remove').each(function() {
							$(this).removeClass('wpr-table-tr-before-remove');
						});

						entryInfo();
				});

				$scope.find('.wpr-table-prev-next').each(function() {
					pageIndex = +$scope.find('.wpr-active-pagination-item').text();

					if ( $(this).hasClass('wpr-table-custom-pagination-prev')) {

						$(this).on('click', function() {

							if ( 1 < pageIndex ) {
								paginationListItems.removeClass('wpr-active-pagination-item');
								pageIndex--;

								paginationListItems.each(function(index) {
									index++;
									if ( index === pageIndex) {
										$(this).addClass('wpr-active-pagination-item');
										pageIndex = +$(this).text();
									}
								});
								adjustPaginationList();
	
								table.html(initialRows.filter(function(index) {
									index++;
									return index > itemsPerPage * (pageIndex - 1) && index <= itemsPerPage * pageIndex;
								}));

								beforeFilter = $scope.find("tbody .wpr-table-row");

								if ( '' == value ) {
									table.html(beforeFilter);
								}
							}

							entryInfo();
						});

					} else {

						$(this).on('click', function() {

							if (  paginationListItems.length > pageIndex ) {
								paginationListItems.removeClass('wpr-active-pagination-item');
								pageIndex++;
								
								paginationListItems.each(function(index) {
									index++;
									if ( index === pageIndex) {
										$(this).addClass('wpr-active-pagination-item');
										pageIndex = +$(this).text();
									}
								});
								adjustPaginationList();
	
								table.html(initialRows.filter(function(index) {
									index++;
									return index > itemsPerPage * (pageIndex - 1) && index <= itemsPerPage * pageIndex;
								}));

								beforeFilter = $scope.find("tbody .wpr-table-row");
													
								if ( '' == value ) {
									table.html(beforeFilter);
								}
							}

							entryInfo();
						});
					}
	
					beforeFilter.find('.wpr-table-tr-before-remove').each(function() {
						$(this).removeClass('wpr-table-tr-before-remove');
					});

				});

			}

			$scope.find('.wpr-table-inner-container').removeClass('wpr-hide-table-before-arrange');

			entryInfo();

			// Table Live Search
			beforeFilter = $scope.find("tbody .wpr-table-row");
			$scope.find(".wpr-table-live-search").keyup(function () {
				if ( this.value !== '' ) {
					$scope.find('.wpr-table-pagination-cont').addClass('wpr-hide-pagination-on-search');
				} else {
					$scope.find('.wpr-table-pagination-cont').removeClass('wpr-hide-pagination-on-search');
				}
				value = this.value.toLowerCase().trim();

				var afterFilter = [];

				initialRows.each(function (index) {
					// if (!index) return; // TODO: restore if better
					$(this).find("td").each(function () {
						var id = $(this).text().toLowerCase().trim();
						var not_found = (id.indexOf(value) == -1);
						// $(this).closest('tr').toggle(!not_found);
						// return not_found;
						if ( !not_found ) {
							afterFilter.push($(this).closest('tr'));
						}
					});
				});

				table.html(afterFilter);

				if ( '' == value ) {
					table.html(beforeFilter);
				}

				entryInfo();
			});

			// Table Sorting
			if ( 'yes' === $scope.find('.wpr-table-inner-container').attr('data-table-sorting') ) {
				$(window).click(function(e) {
					if ( !$(e.target).hasClass('wpr-table-th') && 0 === $(e.target).closest('.wpr-table-th').length ) {
						if ( !$(e.target).hasClass('wpr-active-td-bg-color') && 0 === $(e.target).closest('.wpr-active-td-bg-color').length ) {
							$scope.find('td').each(function() {
								if($(this).hasClass('wpr-active-td-bg-color')) {
									$(this).removeClass('wpr-active-td-bg-color');
								}
							});
						}
					}
				});

				$scope.find('th').click(function(){

					var indexOfTr = $(this).index();

					$scope.find('td').each(function() {
						if($(this).index() === indexOfTr) {
							$(this).addClass('wpr-active-td-bg-color');
						} else {
							$(this).removeClass('wpr-active-td-bg-color');
						}
					});

					$scope.find('th').each(function() {
						$(this).find('.wpr-sorting-icon').html('<i class="fas fa-sort" aria-hidden="true"></i>');
					});

					var table = $(this).parents('table').eq(0);
					var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))

					this.asc = !this.asc
					if ($scope.hasClass('wpr-data-table-type-custom') ? !this.asc : this.asc) {
						if ($scope.hasClass('wpr-data-table-type-custom')) {
							$(this).find('.wpr-sorting-icon').html('<i class="fas fa-sort-down" aria-hidden="true"></i>');
						} else {
							$(this).find('.wpr-sorting-icon').html('<i class="fas fa-sort-up" aria-hidden="true"></i>');
						}
						rows = rows.reverse() 
					} 
	
					if($scope.hasClass('wpr-data-table-type-custom') ? this.asc : !this.asc) {
						
						if ($scope.hasClass('wpr-data-table-type-custom')) {
							$(this).find('.wpr-sorting-icon').html('<i class="fas fa-sort-up" aria-hidden="true"></i>');
						} else {

							$(this).find('.wpr-sorting-icon').html('<i class="fas fa-sort-down" aria-hidden="true"></i>');
						}
					}
	
					for (var i = 0; i < rows.length; i++) {
						table.append(rows[i])
					}
	
					beforeFilter.find('.wpr-table-tr-before-remove').each(function() {
						$(this).closest('.wpr-table-row').next('.wpr-table-appended-tr').remove();
						$(this).removeClass('wpr-table-tr-before-remove');
					});
				});
			}

			if ( $scope.find('.wpr-table-inner-container').attr('data-row-pagination') === 'yes' ) {
				$scope.find('.wpr-table-head-row').prepend('<th class="wpr-table-th-pag" style="vertical-align: middle;">' + '#' + '</th>')
				initialRows.each(function(index) {
						$(this).prepend('<td class="wpr-table-td-pag" style="vertical-align: middle;"><span style="vertical-align: middle;">'+ (index + 1) +'</span></td>')
				})	
			}

			if ( $scope.find('.wpr-table-export-button-cont').length ) {
				var exportBtn = $scope.find('.wpr-table-export-button-cont .wpr-button');;
				exportBtn.each(function() {
					if ( $(this).hasClass('wpr-xls')) {
						$(this).on('click', function() {    
							let table = $scope.find('table');
							TableToExcel.convert(table[0], { // html code may contain multiple tables so here we are refering to 1st table tag
								name: `export.xlsx`, // fileName you could use any name
								sheet: {
									name: 'Sheet 1' // sheetName
								}
							});
						});
					} else if ( $(this).hasClass('wpr-csv')) {
						$(this).on('click', function() {
							htmlToCSV('why-this-arg?', "placeholder.csv", $scope.find('.wpr-data-table'));
						});
					}
				});
			}

			// if('yes' === $scope.find('.wpr-table-inner-container').attr('data-enable-tr-link')) {
			// 	$scope.find('tbody tr:eq('+ $scope.find('.wpr-table-inner-container').attr('data-tr-index') +')').click(function() {
			// 		window.location.href = 'https://stackoverflow.com/questions/503093/how-do-i-redirect-to-another-webpage';
			// 		// window.open('https://stackoverflow.com/questions/503093/how-do-i-redirect-to-another-webpage', '_blank');
			// 	});
			// }

			function entryInfo() {

				if ( 'yes' !== $scope.find('.wpr-table-inner-container').attr('data-entry-info') ) {
					return;
				}

				var entryPage = +$scope.find('.wpr-active-pagination-item').text(),
					lastEntry = itemsPerPage * entryPage - (itemsPerPage - $scope.find('tbody tr').length),
					firstEntry = lastEntry - $scope.find('tbody tr').length + 1;

				$scope.find('.wpr-entry-info').html('Showing ' + firstEntry + ' to ' + lastEntry + ' of ' + initialRows.length + ' Entries.');
			}

			function adjustPaginationList() {
				
				paginationIndex = $scope.find('.wpr-active-pagination-item').index();
				paginationListItems.each(function(index) {
					if (index == 0 || index == paginationListItems.length - 1 || index <= paginationIndex && index >= paginationIndex - 2) {
						$(this).css('display', 'flex');
					} else {
						$(this).css('display', 'none');
					}
				});
			}
			
			function comparer(index) {
				return function(a, b) {
					var valA = getCellValue(a, index), valB = getCellValue(b, index)
					return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
				}
			}

			function getCellValue (row, index) { 
				return $(row).children('td').eq(index).text() 
			}

			function htmlToCSV(html, filename, view) {
				var data = [];
				var rows = view.find(".wpr-table-row");
						
				for (var i = 0; i < rows.length; i++) {
					var row = [], cols = rows[i].querySelectorAll(".wpr-table-text");
							
					for (var j = 0; j < cols.length; j++) {
							row.push(cols[j].innerText);
					}
					
					data.push(row.join(",")); 		
				}
			
				downloadCSVFile(data.join("\n"), filename);
			}
		
			function downloadCSVFile(csv, filename) {
				var csv_file, download_link;
			
				csv_file = new Blob([csv], {type: "text/csv"});
			
				download_link = document.createElement("a");
			
				download_link.download = filename;
			
				download_link.href = window.URL.createObjectURL(csv_file);
			
				download_link.style.display = "none";
			
				document.body.appendChild(download_link);
			
				download_link.click();
			} // Data Table CSV export

		}, // End widgetDataTable

		// Editor Check
		editorCheck: function() {
			return $( 'body' ).hasClass( 'elementor-editor-active' ) ? true : false;
		},

		// Edith with Elementor - Admin Bar Menu
		changeAdminBarMenu: function() {
			let editLinks = $('#wp-admin-bar-elementor_edit_page-default');

			editLinks.children('li').each(function(){
				let $this = $(this),
					template = $this.children('a').children('span').first().text();

				if ( 0 === template.indexOf('wpr-mega-menu-item') ) {
					$this.remove();
				}
			});
		},

		changeActionTargetProductId: function(productId) {
			actionTargetProductId = productId;
		}
	
	} // End WprElements

	$( window ).on( 'elementor/frontend/init', WprElements.init );

}( jQuery, window.elementorFrontend ) );

// Resize Function - Debounce
(function($,sr){

  var debounce = function (func, threshold, execAsap) {
      var timeout;

      return function debounced () {
          var obj = this, args = arguments;
          function delayed () {
              if (!execAsap)
                  func.apply(obj, args);
              timeout = null;
          };

          if (timeout)
              clearTimeout(timeout);
          else if (execAsap)
              func.apply(obj, args);

          timeout = setTimeout(delayed, threshold || 100);
      };
  }
  // smartresize 
  jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };

})(jQuery,'smartresize');