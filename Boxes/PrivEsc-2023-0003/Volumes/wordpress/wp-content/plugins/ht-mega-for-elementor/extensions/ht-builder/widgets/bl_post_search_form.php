<?php
namespace HTMega_Builder\Elementor\Widget;

// Elementor Classes
use Elementor\Plugin as Elementor;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\HTMega_Icon_manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bl_Post_Search_Form_ELement extends Widget_Base {

    public function get_name() {
        return 'bl-post-search-form';
    }

    public function get_title() {
        return __( 'BL: Post Search Form', 'htmega-addons' );
    }

    public function get_icon() {
        return 'eicon-search';
    }

    public function get_categories() {
        return ['htmega_builder'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'post_search_form_section',
            [
                'label' => __( 'Search Form', 'htmega-addons' ),
            ]
        );

            $this->add_control(
                'placeholdertxt',
                [
                    'label' => __( 'Placeholder', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Search ...', 'htmega-addons' ),
                ]
            );

            $this->add_control(
                'button_type',
                [
                    'label' => __( 'Button Type', 'htmega-addons' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'text' => [
                            'title' => __( 'Text', 'htmega-addons' ),
                            'icon' => 'eicon-t-letter',
                        ],
                        'icon' => [
                            'title' => __( 'Icon', 'htmega-addons' ),
                            'icon' => 'eicon-editor-italic',
                        ],
                    ],
                    'default' => 'icon',
                    'toggle' => true,
                ]
            );

            $this->add_control(
                'button_icon',
                [
                    'label' => __( 'Icon', 'htmega-addons' ),
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'fa fa-search',
                        'library' => 'solid',
                    ],
                    'recommended' => [
                        'fa-solid' => [
                            'search',
                            'search-dollar',
                            'search-location',
                            'search-minus',
                            'search-plus',
                        ],
                        'fa-regular' => [
                            'circle',
                            'dot-circle',
                            'square-full',
                        ],
                    ],                    
                    'condition' => [
                        'button_type' => 'icon',
                    ]
                ]
            );

            $this->add_control(
                'button_text',
                [
                    'label' => __( 'Button Text', 'htmega-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Search', 'htmega-addons' ),
                    'placeholder' => __( 'Enter you text', 'htmega-addons' ),
                    'condition' => [
                        'button_type' => 'text',
                    ]
                ]
            );

        $this->end_controls_section();

        // Input Box Style
        $this->start_controls_section(
            'post_search_inputbox_style_section',
            array(
                'label' => __( 'Input Box', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_control(
                'post_search_inputbox_color',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htbuilder-search-form input.htbuilder-search-form-input' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'post_search_inputbox_typography',
                    'label'     => __( 'Typography', 'htmega-addons' ),
                    'selector'  => '{{WRAPPER}} .htbuilder-search-form input.htbuilder-search-form-input',
                )
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'post_search_inputbox_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htbuilder-search-form input.htbuilder-search-form-input',
                ]
            );

            $this->add_responsive_control(
                'post_search_inputbox_border_radius',
                [
                    'label' => __( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htbuilder-search-form input.htbuilder-search-form-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'post_search_inputbox_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htbuilder-search-form input.htbuilder-search-form-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Submit Button
        $this->start_controls_section(
            'post_search_button_style_section',
            array(
                'label' => __( 'Button', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->start_controls_tabs('search_button_style_tabs');

                // Submit Button Normal
                $this->start_controls_tab(
                    'search_button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'post_search_button_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-search-form button.htbuilder-submit' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'post_search_button_bg_color',
                        [
                            'label'     => __( 'Background Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-search-form button.htbuilder-submit' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'      => 'post_search_button_typography',
                            'label'     => __( 'Typography', 'htmega-addons' ),
                            'selector'  => '{{WRAPPER}} .htbuilder-search-form button.htbuilder-submit',
                        )
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'post_search_button_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htbuilder-search-form button.htbuilder-submit',
                        ]
                    );

                    $this->add_responsive_control(
                        'post_search_button_border_radius',
                        [
                            'label' => __( 'Border Radius', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-search-form button.htbuilder-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'post_search_button_padding',
                        [
                            'label' => __( 'Padding', 'htmega-addons' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-search-form button.htbuilder-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Submit Button Hover
                $this->start_controls_tab(
                    'search_button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'htmega-addons' ),
                    ]
                );
                    
                    $this->add_control(
                        'post_search_button_hover_color',
                        [
                            'label'     => __( 'Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-search-form button.htbuilder-submit:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'post_search_button_hover_bg_color',
                        [
                            'label'     => __( 'Background Color', 'htmega-addons' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htbuilder-search-form button.htbuilder-submit:hover' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'post_search_button_hover_border',
                            'label' => __( 'Border', 'htmega-addons' ),
                            'selector' => '{{WRAPPER}} .htbuilder-search-form button.htbuilder-submit:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute(
            'inputattr', [
                'placeholder' => $settings['placeholdertxt'],
                'class' => 'htbuilder-search-form-input',
                'type' => 'search',
                'name' => 's',
                'title' => __( 'Search', 'htmega-addons' ),
                'value' => get_search_query(),
            ]
        );

        
        ?>
            <form class="htbuilder-search-form" role="search" action="<?php echo home_url(); ?>" method="get">
                <input <?php echo $this->get_render_attribute_string( 'inputattr' ); ?>>
                <button class="htbuilder-submit" type="submit">
                    <?php
                        if( $settings['button_type'] == 'text' ){
                            echo esc_html( $settings['button_text']);
                        }else{
                            echo HTMega_Icon_manager::render_icon($settings['button_icon'], ['aria-hidden' => 'true']);
                        }
                    ?>
                </button>
            </form>
        <?php

    }


}
