<?php
namespace Elementor;

// Elementor Classes

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_SinglePost extends Widget_Base {

    public function get_name() {
        return 'htmega-singlepost-addons';
    }
    
    public function get_title() {
        return __( 'Single Post', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-posts-group';
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
            'single_post_content',
            [
                'label' => __( 'Single Post', 'htmega-addons' ),
            ]
        );
            $this->add_control(
                'post_style',
                [
                    'label' => esc_html__( 'Style', 'htmega-addons' ),
                    'type' => 'htmega-preset-select',
                    'default' => '1',
                    'options' => [
                        '1' => esc_html__( 'Style One', 'htmega-addons' ),
                        '2' => esc_html__( 'Style Two', 'htmega-addons' ),
                        '3' => esc_html__( 'Style Three', 'htmega-addons' ),
                        '4' => esc_html__( 'Style Four', 'htmega-addons' ),
                    ],
                ]
            );

            $this->add_control(
                'post_content_position',
                [
                    'label' => esc_html__( 'Content Position', 'htmega-addons' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'bottom',
                    'options' => [
                        'top' => esc_html__( 'Top', 'htmega-addons' ),
                        'bottom' => esc_html__( 'Bottom', 'htmega-addons' ),
                    ],
                    'condition'=>[
                        'post_style' => '1',
                    ]
                ]
            );
            $this->get_posts_by_post_type();

        $this->end_controls_section();

        $this->start_controls_section(
            'single_post_additional',
            [
                'label' => __( 'Additional Option', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'show_title',
                [
                    'label' => esc_html__( 'Title', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'single_post_title_length',
                [
                    'label' => __( 'Title Length', 'htmega-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'step' => 1,
                    'default' => 5,
                    'condition'=>[
                        'show_title'=>'yes',
                    ],
                ]
            );


            $this->add_control(
                'show_category',
                [
                    'label' => esc_html__( 'Category', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_author',
                [
                    'label' => esc_html__( 'Author', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_date',
                [
                    'label' => esc_html__( 'Date', 'htmega-addons' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

        $this->end_controls_section();

        // Style Title tab section
        $this->start_controls_section(
            'single_post_title_style_section',
            [
                'label' => __( 'Title', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_title'=>'yes',
                ]
            ]
        );
            $this->add_control(
                'title_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post .content h2 a' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_control(
                'title_color_hover',
                [
                    'label' => __( 'Hover Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post .content h2 a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-single-post .content h2',
                ]
            );

            $this->add_responsive_control(
                'title_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post .content h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'title_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post .content h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'title_align',
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
                        '{{WRAPPER}} .htmega-single-post .content h2' => 'text-align: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Category tab section
        $this->start_controls_section(
            'single_post_category_style_section',
            [
                'label' => __( 'Category', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_category'=>'yes',
                ]
            ]
        );
            $this->add_control(
                'category_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'default'=>'#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post .post-category a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'category_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-single-post .post-category a',
                ]
            );

            $this->add_responsive_control(
                'category_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post .post-category a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'category_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post .post-category a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'category_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-single-post .post-category a',
                ]
            );
            $this->add_responsive_control(
                'category_align',
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
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post .post-category' => 'text-align: {{VALUE}};',
                    ],
                    
                ]
            );
        $this->end_controls_section();

        // Style Date tab section
        $this->start_controls_section(
            'single_post_date_style_section',
            [
                'label' => __( 'Meta', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_date'=>'yes',
                ]
            ]
        );
        $this->start_controls_tabs(
            'style_tabs'
        );
            // Normal Style Tab
            $this->start_controls_tab(
                'style_normal_tab',
                [
                    'label' => __( 'Normal', 'htmega-addons' ),
                ]
            );
            $this->add_control(
                'date_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post ul.meta li a,
                        {{WRAPPER}} .htmega-single-post ul.meta li' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_control(
                'date_icon_color',
                [
                    'label' => __( 'Icon Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post ul.meta li a i,
                        {{WRAPPER}} .htmega-single-post ul.meta li i' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'date_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-single-post ul.meta li a,
                    {{WRAPPER}} .htmega-single-post ul.meta li',
                ]
            );

            $this->add_responsive_control(
                'date_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post ul.meta li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .htmega-single-post ul.meta' => 'margin-left:-{{LEFT}}{{UNIT}};margin-right:-{{RIGHT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'date_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post ul.meta li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    
                ]
            );

            $this->add_responsive_control(
                'date_align',
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
                        'space-between' => [
                            'title' => __( 'Justified', 'htmega-addons' ),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post ul.meta' => 'justify-content: {{VALUE}};',
                    ],
                    
                ]
            );

            $this->end_controls_tab();

            // Hover Style Tab
            $this->start_controls_tab(
                'filter_menu_hover_tab',
                [
                    'label' => __( 'Hover', 'htmega-addons' ),
                ]
            );
            $this->add_control(
                'meta_hover_color_',
                [
                    'label' => __( 'Meta Hover Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post ul.meta li a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        // Style content box section
        $this->start_controls_section(
            'single_post_content_box_style_section',
            [
                'label' => __( 'Content Box', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'box_bg_color',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htmega-single-post .content',
                ]
            );
            $this->add_responsive_control(
                'box_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post .content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    ],
                ]
            );
            $this->add_responsive_control(
                'box_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-single-post .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        $this->end_controls_section();
    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $sectionid =  $this-> get_id();
        $sectionid = 'secid'.$sectionid;

        $post_content_position = isset( $settings['post_content_position'] ) ? $settings['post_content_position'] : 'bottom';
        $title_length = $settings['single_post_title_length'];
        $this->add_render_attribute( 'htmega_single_post_attr', 'class', 'htmega-single-post htmega-single-post-style-'.$settings['post_style']." ". $sectionid );


        $select_post_type = $settings['select_post_type'];
        if( 'post'== $select_post_type ){
            $get_post_name = $settings['post_name'];
        } else {
            $get_post_name = $settings[$select_post_type.'_post_name'];
        }
        $category_name =  get_object_taxonomies($select_post_type);

        if( $get_post_name >= 1 ) { 
            $posts_ids = implode(', ', $get_post_name); 
        } else { $posts_ids = ''; }
        $post_names = explode(',', $posts_ids);

        $args = array(
            'post_type'             => $select_post_type,
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page'        => -1,
        );
        if ( "0" != $get_post_name ) {
            $args['post__in'] = $post_names;
        }
        $single_post = new \WP_Query( $args );

        ?>
            <?php
                if( $single_post->have_posts() ):
                    while( $single_post->have_posts() ): $single_post->the_post();
            ?>
                <div <?php echo $this->get_render_attribute_string( 'htmega_single_post_attr' ); ?>>
                    <div class="thumb">
                        <a href="<?php the_permalink();?>">
                            <?php
                                if ( has_post_thumbnail() ){
                                    the_post_thumbnail( 'full' ); 
                                }else{
                                    echo '<img src="'.HTMEGA_ADDONS_PL_URL.'/assets/images/image-placeholder.png" alt="'.get_the_title().'" />';
                                }
                            ?>
                        </a>
                    </div>
                    <div class="content">
                        <?php if($settings['show_category'] == 'yes' ):?>
                            <div class="post-category">
                                <?php
                                if( $category_name ){
                                    $get_terms = get_the_terms($single_post->ID, $category_name[0] );
                                    if($select_post_type == 'product'){
                                        $get_terms = get_the_terms($single_post->ID, 'product_cat');
                                    }
                                    if( $get_terms ){
                                        foreach ( $get_terms as $category ) {
                                            $term_link = get_term_link( $category );
                                            ?>
                                                <a href="<?php echo esc_url( $term_link ); ?>" class="category <?php echo esc_attr( $category->slug ); ?>"><?php echo esc_html( $category->name );?></a>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </div>
                        <?php endif; if($settings['show_title'] == 'yes' ):
                        
                            if ( 0 > $title_length ) { ?>
                                <h2><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h2>
                            <?php
                            } else { ?>
                                <h2><a href="<?php the_permalink();?>"><?php echo wp_trim_words( get_the_title(), $title_length, '' ); ?></a></h2>
                            <?php
                             }
                        ?>
                        <?php endif; if( $settings['show_author'] == 'yes' || $settings['show_date'] == 'yes'):?>
                            <ul class="meta">
                                <?php if( $settings['show_author'] == 'yes' ):?>
                                    <li><i class="fa fa-user-circle"></i><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php the_author();?></a></li>
                                <?php endif; if($settings['show_date'] == 'yes' ):?>
                                    <li><i class="fa fa-clock-o"></i><?php the_time( 'd F Y' );?></li>
                                <?php endif; ?>
                            </ul>
                        <?php endif;?>
                    </div>
                </div>

            <?php endwhile; wp_reset_postdata(); wp_reset_query(); 
                else:
                    echo esc_html__( 'No selected post', 'htmega-addons' );
                endif;
            if( 'top' == $post_content_position ){
                 $htmega_print_css = ".{$sectionid}.htmega-single-post-style-1 .content {
                        bottom: auto;
                        top: 0;
                    }
                    ";
                ?>
                <style>
                    <?php echo esc_html( $htmega_print_css ); ?>
                </style>
                <script>
                    var marginLeft = jQuery(".<?php echo esc_attr( $sectionid ); ?>.htmega-single-post-style-1 .content").css("margin-left");
                    var marginRight = jQuery(".<?php echo esc_attr( $sectionid ); ?>.htmega-single-post-style-1 .content").css("margin-right");
                    var marginLeft2 = parseInt( marginLeft.slice(0, -2) );
                    var marginRight2 = parseInt( marginRight.slice(0, -2));
                    if( marginLeft2 || marginRight2 ){
                        var totalMargin = marginLeft2+marginRight2+'px';
                        var calcevalue = 'calc(100% - '+totalMargin+')';
                        jQuery(".<?php echo esc_attr( $sectionid ); ?>.htmega-single-post-style-1 .content").css("width", calcevalue);
                    }
                </script>
            <?php
            }
    }
    public function get_posts_by_post_type(){

        $this->add_control(
            'select_post_type',
            [
                'label' => esc_html__( 'Select Post Type', 'htmega-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'bottom',
                'options' => htmega_get_post_types(),
                'default' => 'post',
            ]
        );

        $post_list = htmega_get_post_types();       
        foreach( $post_list as $post_key => $post_value ){
            if ( 'post' == $post_key ){
            $this->add_control(
                'post_name',
                    [
                        'label' => esc_html__( 'Post Name', 'htmega-addons' ),
                        'type' => Controls_Manager::SELECT2,
                        'label_block' => true,
                        'multiple' => true,
                        'options' =>  htmega_post_name('post', -1),
                        'condition' => [
                                'select_post_type' => 'post',
                            ],
                    ]
                );

            } else {

                $this->add_control(
                    "{$post_key}_post_name",
                    [
                        'label' => esc_html__( 'Post Name', 'htmega-pro' ),
                        'type' => Controls_Manager::SELECT2,
                        'label_block' => true,
                        'options' => htmega_post_name($post_key, -1),
                        'multiple' => true,
                        'condition' => [
                            'select_post_type' => $post_key,
                        ],
                    ]
                );
            }
        }
    }
}