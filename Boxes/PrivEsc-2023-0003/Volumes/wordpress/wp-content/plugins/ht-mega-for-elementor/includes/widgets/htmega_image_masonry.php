<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Image_Masonry extends Widget_Base {

    public function get_name() {
        return 'htmega-imagemasonryd-addons';
    }
    
    public function get_title() {
        return __( 'Image Masonry', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-posts-masonry';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_style_depends(){
        return [
            'htmega-widgets',
        ];
    }

    public function get_script_depends() {
        return [
            'masonry',
            'isotope',
            'htmega-widgets-scripts',
            'imagesloaded',
        ];
    }

    public function get_keywords() {
        return [ 'image masonary', 'image gallery','masonry','gallery image','htmega','ht mega' ];
    }

    public function get_help_url() {
		return 'https://wphtmega.com/docs/general-widgets/image-masonry-widget/';
	}

    protected function register_controls() {

        $this->start_controls_section(
            'imagemasonry_content',
            [
                'label' => __( 'Image Masonry', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'imagemasonry_style',
                [
                    'label' => __( 'Style', 'htmega-addons' ),
                    'type' => 'htmega-preset-select',
                    'default' => '1',
                    'options' => [
                        '1'   => __( 'Style One', 'htmega-addons' ),
                        '2'   => __( 'Style Two', 'htmega-addons' ),
                        '3'   => __( 'Style Three', 'htmega-addons' ),
                        '4'   => __( 'Style Four', 'htmega-addons' ),
                        '5'   => __( 'Style Five', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'imagemasonrycolumn',
                [
                    'label' => __( 'Column', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '3',
                    'options' => [
                        '1'   => __( 'One', 'htmega-addons' ),
                        '2'   => __( 'Two', 'htmega-addons' ),
                        '3'   => __( 'Three', 'htmega-addons' ),
                        '4'   => __( 'Four', 'htmega-addons' ),
                        '5'   => __( 'Five', 'htmega-addons' ),
                        '6'   => __( 'Six', 'htmega-addons' ),
                    ],
                ]
            );
            
            $repeater = new Repeater();

            $repeater->add_control(
                'masonryimage_title',
                [
                    'label'   => __( 'Title', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'placeholder' => __('Image Masonry Title.','htmega-addons'),
                ]
            );

            $repeater->add_control(
                'masonryimage_description',
                [
                    'label'   => __( 'Description', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXTAREA,
                    'placeholder' => __('Image Masonry Description.','htmega-addons'),
                ]
            );

            $repeater->add_control(
                'masonryimage_image',
                [
                    'label' => __( 'Image', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                ]
            );

            $repeater->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'masonryimage_imagesize',
                    'default' => 'large',
                    'separator' => 'none',
                ]
            );

            $repeater->add_control(
                'masonryimage_btntxt',
                [
                    'label'   => __( 'Read More Text', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'placeholder' => __('Read More','htmega-addons'),
                ]
            );

            $repeater->add_control(
                'masonryimage_btnlink',
                [
                    'label' => __( 'Read More Link', 'htmega-addons' ),
                    'type' => Controls_Manager::URL,
                    'placeholder' => __( 'https://your-link.com', 'htmega-addons' ),
                    'show_external' => false,
                    'default' => [
                        'url' => '#',
                        'is_external' => false,
                        'nofollow' => false,
                    ],
                ]
            );

            $this->add_control(
                'masonrygrid_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => $repeater->get_controls(),
                    'default' => [

                        [
                            'masonryimage_title'        => __('Image Masonry Title','htmega-addons'),
                            'masonryimage_description'  => __( 'Image Masonry Description','htmega-addons' ),
                            'masonryimage_btntxt'       => __( 'Read More', 'htmega-addons' ),
                            'masonryimage_btnlink'       => __( '#', 'htmega-addons' ),
                        ],

                    ],
                    'title_field' => '{{{ masonryimage_title }}}',
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'imagemasonry_style_section',
            [
                'label' => __( 'Box Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'imagemasonry_image_overlay_color',
                [
                    'label' => __( 'Overlay Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => 'rgba(0, 0, 0, 0.5)',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-singleimage-grid .thumb a::before,
                        {{WRAPPER}} .htmega-singleimage-gridstyle-5 .image-grid-content,
                        {{WRAPPER}} .htmega-singleimage-gridstyle-4 .image-grid-content' => 'background: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'imagemasonry_image_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-singleimage-grid' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'imagemasonry_image_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        //'{{WRAPPER}} .htmega-singleimage-grid' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .masonary-item,
                        {{WRAPPER}} .htmega-masonry-activation.htmega-image-gridstyle-5 .htb-row > [class*="col"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .masonry-wrap' => 'margin: -{{TOP}}{{UNIT}} -{{RIGHT}}{{UNIT}} -{{BOTTOM}}{{UNIT}} -{{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} [class*="htmega-singleimage-gridstyle-"]' => 'margin-top:0',
                        // '{{WRAPPER}} .masonary-item' => 'padding-left:{{LEFT}}{{UNIT}};padding-right:{{RIGHT}}{{UNIT}}',
                        // '{{WRAPPER}} .masonry-wrap' => 'margin-left: -{{LEFT}}{{UNIT}}; margin-right: -{{RIGHT}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'imagemasonry_image_area_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-singleimage-grid',
                ]
            );

            $this->add_responsive_control(
                'imagemasonry_image_area_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-singleimage-grid' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} .htmega-singleimage-gridstyle-3 .thumb' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} .htmega-singleimage-gridstyle-4 .thumb' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'imagemasonry_image_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-singleimage-grid',
                    'separator' => 'before',
                ]
            );

        $this->end_controls_section();

        // Style tab title section
        $this->start_controls_section(
            'imagemasonry_title_style_section',
            [
                'label' => __( 'Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'imagemasonry_title_align',
                [
                    'label' => __( 'Alignment', 'htmega-addons' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-right',
                        ],
                        'justify' => [
                            'title' => __( 'Justified', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content h2' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'imagemasonry_title_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content h2' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'imagemasonry_title_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content h2',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'imagemasonry_title_typography',
                    'selector' => '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content h2',
                ]
            );

            $this->add_responsive_control(
                'imagemasonry_title_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'imagemasonry_title_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

        // Style tab Desciption section
        $this->start_controls_section(
            'imagemasonry_desciption_style_section',
            [
                'label' => __( 'Description', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'imagemasonry_desciption_align',
                [
                    'label' => __( 'Alignment', 'htmega-addons' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-right',
                        ],
                        'justify' => [
                            'title' => __( 'Justified', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content p' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'separator' =>'before',
                ]
            );

            $this->add_control(
                'imagemasonry_desciption_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#18012c',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content p' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'imagemasonry_desciption_typography',
                    'selector' => '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content p',
                ]
            );

            $this->add_responsive_control(
                'imagemasonry_desciption_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'imagemasonry_desciption_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

        // Style tab read more button section
        $this->start_controls_section(
            'imagemasonry_readmorebtn_style_section',
            [
                'label' => __( 'Read More Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs( 'readmorebtn_style_tabs' );

                $this->start_controls_tab(
                    'readmorebtn_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'imagemasonry_readmorebtn_typography',
                            'selector' => '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn',
                        ]
                    );

                    $this->add_control(
                        'imagegrid_readmorebtn_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'imagemasonry_readmorebtn_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'imagemasonry_readmorebtn_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn',
                        ]
                    );

                    $this->add_responsive_control(
                        'imagemasonry_readmorebtn_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'imagemasonry_readmorebtn_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn',
                        ]
                    );

                    $this->add_responsive_control(
                        'imagemasonry_readmorebtn_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Read More button normal tab end

                $this->start_controls_tab(
                    'readmorebtn_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'imagemasonry_readmorebtn_hover_typography',
                            'selector' => '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn:hover',
                        ]
                    );

                    $this->add_control(
                        'imagemasonry_readmorebtn_hover_color',
                        [
                            'label' => __( 'Color', 'htmega-addons' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'imagemasonry_readmorebtn_hover_background',
                            'label' => __( 'Background', 'htmega-addons' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'imagemasonry_readmorebtn_hover_box_shadow',
                            'label' => __( 'Box Shadow', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'imagemasonry_readmorebtn_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'imagemasonry_readmorebtn_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htmega-singleimage-grid .image-grid-content a.read-btn:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Read More button hover tab end

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $sectionid = "sid". $this-> get_id();
        $this->add_render_attribute( 'imagemasonry_attr', 'class', 'htmega-masonry-activation htmega-gridimage-area htmega-image-gridstyle-'.$settings['imagemasonry_style'] );
        $this->add_render_attribute( 'imagemasonry_item_attr', 'class', 'htmega-singleimage-grid htmega-singleimage-gridstyle-'.$settings['imagemasonry_style'] );

        $columns = $settings['imagemasonrycolumn'];
        $collumval = 'htb-col-md-4 htb-col-sm-6 htb-col-12 masonary-item';
        $collumval_sizer = 'htb-col-1';
        if( $columns != 5 ){
            $colwidth = round(12/$columns);
            $collumval = 'htb-col-md-'.$colwidth.' htb-col-sm-6 htb-col-12 masonary-item';
            $collumval_sizer ='htb-col-1';
        }else{
            $collumval = 'custom-col-5 masonary-item';
            $collumval_sizer ='custom-col-5';
        }
       
        ?>
            <div <?php echo $this->get_render_attribute_string( 'imagemasonry_attr' ); ?> style="display:none;">
                <div class="htb-row masonry-wrap" id="<?php echo $sectionid; ?>">
                    <div class='masonary-sizer <?php echo esc_attr( $collumval_sizer );?>'></div>
                    <?php
                        foreach ( $settings['masonrygrid_list'] as $key=> $imagegrid ):
                        ?>
                            <div class="<?php echo esc_attr( $collumval );?>">
                                <div <?php echo $this->get_render_attribute_string( 'imagemasonry_item_attr' ); ?> >
                                    <div class="thumb">
                                        <?php
                                            if ( !empty($imagegrid['masonryimage_btnlink']['url']) && $imagegrid['masonryimage_btnlink']['url'] ){
                                                $this->add_link_attributes( $key, $imagegrid['masonryimage_btnlink'] );

                                                echo '<a '.$this->get_render_attribute_string( $key ).'>'.Group_Control_Image_Size::get_attachment_image_html( $imagegrid, 'masonryimage_imagesize', 'masonryimage_image' ).'</a>';
                                            }else{
                                                echo Group_Control_Image_Size::get_attachment_image_html( $imagegrid, 'masonryimage_imagesize', 'masonryimage_image' ); 
                                            }
                                        ?>
                                    </div>
                                    <?php if( !empty( $imagegrid['masonryimage_title'] ) || !empty( $imagegrid['masonryimage_description'] ) || ! empty( $imagegrid['masonryimage_btntxt'] ) ): ?>
                                        <div class="image-grid-content">
                                            <div class="hover-action">
                                                <?php 
                                                    if( !empty( $imagegrid['masonryimage_title'] )){
                                                        echo '<h2>'.esc_html( $imagegrid['masonryimage_title'] ).'</h2>';
                                                    }

                                                    if( !empty( $imagegrid['masonryimage_description'] )){
                                                        echo '<p>'.esc_html( $imagegrid['masonryimage_description'] ).'</p>';
                                                    }

                                                    if ( !empty($imagegrid['masonryimage_btnlink']['url']) && $imagegrid['masonryimage_btnlink']['url'] ){
                                                        
                                                        echo sprintf( '<a class="read-btn" %1$s>%2$s</a>', $this->get_render_attribute_string( $key ), htmega_kses_title( $imagegrid['masonryimage_btntxt'] ));

                                                    }


                                                ?>
                                            </div>
                                        </div>
                                    <?php endif;?>
                                </div>
                            </div>

                        <?php
                        endforeach;
                    ?>
                </div>
            </div>
 
        <?php

    }

}