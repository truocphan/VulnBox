<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Gallery_Justify extends Widget_Base {

    public function get_name() {
        return 'htmega-galleryjustify-addons';
    }
    
    public function get_title() {
        return __( 'Gallery Justify', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-gallery-justified';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_style_depends() {
        return [
            'justify-gallery',
            'magnific-popup',
            'htmega-widgets',
        ];
    }

    public function get_script_depends() {
        return [
            'justified-gallery',
            'magnific-popup',
            'imagesloaded'
        ];
    }

    public function get_keywords() {
        return [ 'image justify', 'justify gallery','image gallery','gallery image','htmega','ht mega' ];
    }

    public function get_help_url() {
		return 'https://wphtmega.com/docs/general-widgets/image-justify-widget/';
	}
    protected function register_controls() {
        $this->start_controls_section(
            'gallery_content',
            [
                'label' => __( 'Gallery Justify', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'gallery_images',
                [
                    'label' => __( 'Add Images', 'htmega-addons' ),
                    'type' => Controls_Manager::GALLERY,
                ]
            );

            $this->add_control(
                'row_height',
                [
                    'label' => __( 'Row Height', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 300,
                ]
            );

            $this->add_control(
                'space_margin',
                [
                    'label' => __( 'Space', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 20,
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'justify_image_area_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-justify-single-image',
                ]
            );

            $this->add_responsive_control(
                'justify_image_area_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-justify-single-image' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'justify_image_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-justify-single-image',
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'popup_options',
                [
                    'label' => __( 'Popup options', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '2',
                    'options' => [
                        '1'   => __( 'Popup None', 'htmega-addons' ),
                        '2'   => __( 'Default Popup', 'htmega-addons' ),
                        '3'   => __( 'Gallery Popup ', 'htmega-addons' ),
                    ],
                ]
            );
            $this->add_control(
                'gallery_title_on_off', 
                [
                    'label'         => __( 'Show Gallery Title', 'htmega-addons' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'return_value'  => 'yes',
                    'default'       => 'no',
                    'condition'     => [
                        'popup_options' =>'3',
                    ]
                ]
            );
            $this->add_control(
                'gallery_counter_on_off', 
                [
                    'label'         => __( 'Show Image Counter', 'htmega-addons' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'return_value'  => 'yes',
                    'default'       => 'no',
                    'condition'     => [
                        'popup_options' =>'3',
                    ]
                ]
            );
            $this->add_control(
                'title_color',
                [
                    'label' => __( 'Title Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.htmega.mfp-title' => 'color: {{VALUE}};',
                    ],
                    'condition'     => [
                        'gallery_title_on_off' =>'yes',
                        'popup_options' =>'3',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'selector' => '.htmega.mfp-title',
                    'condition'     => [
                        'gallery_title_on_off' =>'yes',
                        'popup_options' =>'3',
                    ]
                ]
            );
            $this->add_control(
                'counter_color',
                [
                    'label' => __( 'Counter Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.htmega.mfp-counter' => 'color: {{VALUE}};',
                    ],
                    'condition'     => [
                        'gallery_counter_on_off' =>'yes',
                        'popup_options' =>'3',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'counter_typography',
                    'selector' => '.htmega.mfp-counter',
                    'condition'     => [
                        'gallery_counter_on_off' =>'yes',
                        'popup_options' =>'3',
                    ]
                ]
            );
        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $id         = $this->get_id();
        $this->add_render_attribute( 'justify_gallery_attr', 'id', 'npgallery'.$id );
        $this->add_render_attribute( 'justify_gallery_attr', 'class', 'npgallery_active'.$id );


        // Remove Elementor Lightbox
 
    
        if( isset( $settings['gallery_images'] ) ):
           
            echo '<div '.$this->get_render_attribute_string( 'justify_gallery_attr' ).'style="display:none;">';
                foreach ( $settings['gallery_images'] as $image ) {
                    $image_src = wp_get_attachment_image_url( $image['id'], 'full' );

                    if( '1' == $settings['popup_options'] ){
                        $this->add_render_attribute( $image["id"], 'data-elementor-open-lightbox', 'no' );
                        $this->add_render_attribute( $image["id"], 'href', '#' );

                    } else if( '2' == $settings['popup_options'] ){
                        $this->add_render_attribute( $image["id"], 'href', esc_url( $image['url'] ) );
                    }  else if( '3' == $settings['popup_options'] ){

                        $this->add_render_attribute( $image["id"], 'data-elementor-open-lightbox', 'no' );
                        $this->add_render_attribute( $image["id"], 'href', esc_url( $image['url'] ) );
                        if ( 'yes' == $settings['gallery_title_on_off'] ){
                            $this->add_render_attribute( $image["id"], 'title', esc_attr( get_the_title($image["id"])) );  
                        }
                    }
                                    
                    ?>
                        <div class="htmega-justify-single-image">
                            <div class="thumb">
                                <a <?php echo $this->get_render_attribute_string( $image["id"] ); ?> rel="npgallery">
                                    <img src="<?php echo esc_url( $image_src );?>" alt="<?php echo( esc_attr( get_post_meta( $image['id'], '_wp_attachment_image_alt', true) ) );?>">
                                </a>
                            </div>
                        </div>

                    <?php
                }
            echo '</div>';
        endif;
        ?>
        <script>
            jQuery(document).ready(function($) {

                'use strict';                
                $('#npgallery<?php echo esc_js( $id ); ?>').imagesLoaded( function() {
                    $('#npgallery<?php echo esc_js( $id ); ?>')[0].style.display='block';
                    $('#npgallery<?php echo esc_js( $id ); ?>').justifiedGallery({
                        rowHeight: <?php echo esc_js( $settings['row_height'] ); ?>,
                        maxRowHeight: null,
                        margins: <?php echo esc_js(  $settings['space_margin'] ); ?>,
                        border: 0,
                        rel: 'npgallery<?php echo esc_js( $id ); ?>',
                        lastRow: 'nojustify',
                        captions: true,
                        randomize: false,
                        sizeRangeSuffixes: {
                            lt100: '_t',
                            lt240: '_m',
                            lt320: '_n',
                            lt500: '',
                            lt640: '_z',
                            lt1024: '_b'
                        }
                    });
                });
                <?php
                if( '3' == $settings['popup_options'] ){
                    ?>
                    $('.npgallery_active<?php echo esc_js( $id ); ?>').magnificPopup({
                        delegate: 'a',
                        type: 'image',
                        tLoading: 'Loading image #%curr%...',
                        mainClass: 'mfp-img-mobile',
                        gallery: {
                            enabled: true,
                            navigateByImgClick: true,
                            preload: [0,1], // Will preload 0 - before current, and 1 after the current image

                            <?php  if( 'yes' !== $settings['gallery_counter_on_off']) { ?>
                            tCounter: '',
                            <?php } ?>
                        },
                        image: {
                            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',

                            markup: '<div class="mfp-figure">'+
                                        '<div class="mfp-close"></div>'+
                                        '<div class="mfp-img"></div>'+
                                        '<div class="mfp-bottom-bar">'+
                                        '<div class="htmega mfp-title"></div>'+
                                        '<div class="htmega mfp-counter"></div>'+
                                        '</div>'+
                                    '</div>', // Popup HTML markup. `.mfp-img` div will be replaced with img tag, `.mfp-close` by close button

                            titleSrc: function(item) {
                                return item.el.attr('title');
                            }
                        },
                    });

               <?php } ?>
            });
        </script>
        <?php

    }

}

