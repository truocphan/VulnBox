<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Brand extends Widget_Base {

    public function get_name() {
        return 'htmega-brand-addons';
    }
    
    public function get_title() {
        return __( 'Brands', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-image';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_style_depends(){
        return [
            'htmega-widgets',
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'brand_content',
            [
                'label' => __( 'Brands', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'htmega_brand_style',
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
                        '6'   => __( 'Style Six', 'htmega-addons' ),
                        '7'   => __( 'Style Seven', 'htmega-addons' ),
                    ],
                ]
            );

            $repeater = new Repeater();

            $repeater->add_control(
                'htmega_brand_title',
                [
                    'label'   => __( 'Title', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => 'Brand Logo',
                ]
            );

            $repeater->add_control(
                'htmega_brand_logo',
                [
                    'label' => __( 'Partner Logo', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                ]
            );

            $repeater->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'htmega_brand_logo_size',
                    'default' => 'large',
                    'separator' => 'none',
                ]
            );

            $repeater->add_control(
                'htmega_brand_link',
                [
                    'label'   => __( 'Partner Link', 'htmega-addons' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __( '#', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'htmega_brand_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => $repeater->get_controls(),
                    'default' => [

                        [
                            'htmega_brand_title'      => 'Brand Logo',
                            'htmega_brand_link'       => __( '#', 'htmega-addons' ),
                        ],
                    ],
                    'title_field' => '{{{ htmega_brand_title }}}',
                ]
            );


        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'htmega_brand_style_section',
            [
                'label' => __( 'Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'htmega_brand_section_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-brands-area' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_brand_section_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-brands-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            ); 

        $this->end_controls_section(); // Brand section style end

        // Style tab brand logo section
        $this->start_controls_section(
            'htmega_brand_logo_style',
            [
                'label' => __( 'Brand Logo', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );



        $this->start_controls_tabs('brand_style_tabs');

            // Brand Normal tab Start
            $this->start_controls_tab(
                'brand_style_normal_tab',
                [
                    'label' => __( 'Normal', 'htmega-addons' ),
                ]
            );

                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'htmega_brand_logo_background',
                        'label' => __( 'Background', 'htmega-addons' ),
                        'types' => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} .single-partner',
                        'condition' =>[
                            'htmega_brand_style' => array( '1','5','7' ),
                        ]
                    ]
                );

                $this->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    [
                        'name' => 'htmega_brand_logo_box_shadow',
                        'label' => __( 'Box Shadow', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .single-partner',
                        'condition' =>[
                            'htmega_brand_style' => array( '1','5','7' ),
                        ]
                    ]
                );

                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'htmega_brand_logo_background_2',
                        'label' => __( 'Background', 'htmega-addons' ),
                        'types' => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} ul.brand-list li',
                        'condition' =>[
                            'htmega_brand_style' => array( '2','3','4','6' ),
                        ]
                    ]
                );

                $this->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    [
                        'name' => 'htmega_brand_logo_box_shadow_2',
                        'label' => __( 'Box Shadow', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} ul.brand-list li',
                        'condition' =>[
                            'htmega_brand_style' => array( '2','3','4','6' ),
                        ]
                    ]
                );


            $this->end_controls_tab(); // Brand Normal tab end

            // Brand Hover tab start
            $this->start_controls_tab(
                'brand_style_hover_tab',
                [
                    'label' => __( 'Hover', 'htmega-addons' ),
                ]
            );

                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'htmega_brand_logo_background_hover',
                        'label' => __( 'Background', 'htmega-addons' ),
                        'types' => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} .single-partner:hover',
                        'condition' =>[
                            'htmega_brand_style' => array( '1','5','7' ),
                        ]
                    ]
                );

                $this->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    [
                        'name' => 'htmega_brand_logo_box_shadow_hover',
                        'label' => __( 'Box Shadow', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .single-partner:hover',
                        'condition' =>[
                            'htmega_brand_style' => array( '1','5','7' ),
                        ]
                    ]
                );

                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'htmega_brand_logo_background_2_hover',
                        'label' => __( 'Background', 'htmega-addons' ),
                        'types' => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} ul.brand-list li:hover',
                        'condition' =>[
                            'htmega_brand_style' => array( '2','3','4','6' ),
                        ]
                    ]
                );

                $this->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    [
                        'name' => 'htmega_brand_logo_box_shadow_2_hover',
                        'label' => __( 'Box Shadow', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} ul.brand-list li:hover',
                        'condition' =>[
                            'htmega_brand_style' => array( '2','3','4','6' ),
                        ]
                    ]
                );

                $this->add_control(
                    'htmega_brand_logo_duration',
                    [
                        'label' => __( 'Transition Duration', 'htmega-addons' ),
                        'type'  => Controls_Manager::SLIDER,
                        'range' => [
                            'px' => [
                                'min' => 0.1,
                                'max' => 3,
                                'step' => 0.1,
                            ],
                        ],
                        'default' => [
                            'size' => 0.3,
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .single-partner' => 'transition: all {{SIZE}}s cubic-bezier(0.645, 0.045, 0.355, 1);',
                            '{{WRAPPER}} ul.brand-list li' => 'transition: all {{SIZE}}s cubic-bezier(0.645, 0.045, 0.355, 1);',
                        ],
                    ]
                );

            $this->end_controls_tab(); // Brand Hover tab end

        $this->end_controls_tabs();

            $this->add_responsive_control(
                'htmega_brand_logo_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .single-partner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} ul.brand-list li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'htmega_brand_logo_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .single-partner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} ul.brand-list li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'htmega_brand_logo_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .single-partner',
                    'condition' =>[
                        'htmega_brand_style' => array( '1','5','7' ),
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'htmega_brand_logo_border_2',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} ul.brand-list li',
                    'condition' =>[
                        'htmega_brand_style' => array( '2','3','4','6' ),
                    ]
                ]
            );

            $this->add_responsive_control(
                'htmega_brand_logo_borderradius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .single-partner' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} ul.brand-list li' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section(); // Brand Logo style end

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute( 'brands_area_attr', 'class', 'htmega-brands-area' );
        $this->add_render_attribute( 'brands_area_attr', 'class', 'htmega-brands-style-'.$settings['htmega_brand_style'] );

        ?>
            <div <?php echo $this->get_render_attribute_string( 'brands_area_attr' ); ?> >

                <?php if( $settings['htmega_brand_style'] == 2 || $settings['htmega_brand_style'] == 3 || $settings['htmega_brand_style'] == 4 || $settings['htmega_brand_style'] == 6 ): ?>
                    <ul class="brand-list">
                        <?php foreach ( $settings['htmega_brand_list'] as $brandimage ): ?>
                            <li>
                                <?php
                                    if( !empty($brandimage['htmega_brand_link']) ){
                                        printf('<a href="%1$s">%2$s</a>', esc_url( $brandimage['htmega_brand_link'] ),Group_Control_Image_Size::get_attachment_image_html( $brandimage, 'htmega_brand_logo_size', 'htmega_brand_logo' ) );
                                    }else{
                                        echo Group_Control_Image_Size::get_attachment_image_html( $brandimage, 'htmega_brand_logo_size', 'htmega_brand_logo' ); 
                                    }
                                ?>
                            </li>
                        <?php endforeach;?>
                    </ul>

                <?php elseif( $settings['htmega_brand_style'] == 5 || $settings['htmega_brand_style'] == 7):?>
                    <div class="brand-list-area">
                        <?php foreach ( $settings['htmega_brand_list'] as $brandimage ): ?>
                            <div class="brand-logo-col">
                                <div class="single-partner">
                                    <?php
                                        if( !empty($brandimage['htmega_brand_link']) ){
                                            printf('<a href="%1$s">%2$s</a>', esc_url( $brandimage['htmega_brand_link'] ),Group_Control_Image_Size::get_attachment_image_html( $brandimage, 'htmega_brand_logo_size', 'htmega_brand_logo' ) );
                                        }else{
                                            echo Group_Control_Image_Size::get_attachment_image_html( $brandimage, 'htmega_brand_logo_size', 'htmega_brand_logo' ); 
                                        }
                                    ?>
                                </div>
                            </div>
                        <?php endforeach;?>
                    </div>

                <?php else:?>
                    <?php foreach ( $settings['htmega_brand_list'] as $brandimage ): ?>
                        <div class="single-partner">
                            <?php
                                if( !empty($brandimage['htmega_brand_link']) ){
                                    printf('<a href="%1$s">%2$s</a>', esc_url( $brandimage['htmega_brand_link'] ) ,Group_Control_Image_Size::get_attachment_image_html( $brandimage, 'htmega_brand_logo_size', 'htmega_brand_logo' ) );
                                }else{
                                    echo Group_Control_Image_Size::get_attachment_image_html( $brandimage, 'htmega_brand_logo_size', 'htmega_brand_logo' ); 
                                }
                            ?>
                        </div>
                    <?php endforeach;?>
                <?php endif;?>
            </div>
        <?php
    }
}

