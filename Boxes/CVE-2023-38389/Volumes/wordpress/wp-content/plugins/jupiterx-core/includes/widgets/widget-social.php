<?php
/**
 * Jupiter Social networks Widget.
 *
 * Widget defined here and the options are loading using custom fields.
 *
 * @package JupiterX_Core\Widgets
 *
 * @since 1.0.0
 */

/**
 * Defines new widget as Social Widget.
 *
 * @since   1.0.0
 * @ignore
 * @access  private
 *
 * @package JupiterX_Core\Widgets
 */
class JupiterX_Widget_Social extends JupiterX_Widget {

	/**
	 * Setup new widget.
	 */
	public function __construct() {
		$props = [
			'name'        => esc_html__( 'Jupiter X - Social Networks', 'jupiterx-core' ),
			'description' => esc_html__( 'Social network icons.', 'jupiterx-core' ),
			'settings'    => [
				[
					'name' => 'title',
					'label' => esc_html__( 'Title', 'jupiterx-core' ),
					'type' => 'text',
				],
				[
					'label'   => esc_html__( 'Choose social networks', 'jupiterx-core' ),
					'name'    => 'networks',
					'type'    => 'flexible',
					'options' => [
						'android'        => esc_html__( 'Android', 'jupiterx-core' ),
						'apple'          => esc_html__( 'Apple', 'jupiterx-core' ),
						'behance'        => esc_html__( 'Behance', 'jupiterx-core' ),
						'bitbucket'      => esc_html__( 'Bitbucket', 'jupiterx-core' ),
						'delicious'      => esc_html__( 'Delicious', 'jupiterx-core' ),
						'dribbble'       => esc_html__( 'Dribbble', 'jupiterx-core' ),
						'facebook'       => esc_html__( 'Facebook', 'jupiterx-core' ),
						'flickr'         => esc_html__( 'Flickr', 'jupiterx-core' ),
						'foursquare'     => esc_html__( 'Foursquare', 'jupiterx-core' ),
						'github'         => esc_html__( 'Github', 'jupiterx-core' ),
						'instagram'      => esc_html__( 'Instagram', 'jupiterx-core' ),
						'jsfiddle'       => esc_html__( 'JSFiddle', 'jupiterx-core' ),
						'linkedin'       => esc_html__( 'Linkedin', 'jupiterx-core' ),
						'medium'         => esc_html__( 'Medium', 'jupiterx-core' ),
						'pinterest'      => esc_html__( 'Pinterest', 'jupiterx-core' ),
						'product-hunt'   => esc_html__( 'Product Hunt', 'jupiterx-core' ),
						'reddit'         => esc_html__( 'Reddit', 'jupiterx-core' ),
						'rss'            => esc_html__( 'RSS', 'jupiterx-core' ),
						'skype'          => esc_html__( 'Skype', 'jupiterx-core' ),
						'snapchat'       => esc_html__( 'Snapchat', 'jupiterx-core' ),
						'soundcloud'     => esc_html__( 'Soundcloud', 'jupiterx-core' ),
						'spotify'        => esc_html__( 'Spotify', 'jupiterx-core' ),
						'stack-overflow' => esc_html__( 'Stack Overflow', 'jupiterx-core' ),
						'steam'          => esc_html__( 'Steam', 'jupiterx-core' ),
						'stumbleupon'    => esc_html__( 'Stumbleupon', 'jupiterx-core' ),
						'telegram'       => esc_html__( 'Telegram', 'jupiterx-core' ),
						'tripadvisor'    => esc_html__( 'TripAdvisor', 'jupiterx-core' ),
						'tumblr'         => esc_html__( 'Tumblr', 'jupiterx-core' ),
						'twitch'         => esc_html__( 'Twitch', 'jupiterx-core' ),
						'twitter'        => esc_html__( 'Twitter', 'jupiterx-core' ),
						'vimeo'          => esc_html__( 'Vimeo', 'jupiterx-core' ),
						'vk'             => esc_html__( 'VK', 'jupiterx-core' ),
						'weibo'          => esc_html__( 'Weibo', 'jupiterx-core' ),
						'weixin'         => esc_html__( 'Weixin', 'jupiterx-core' ),
						'whatsapp'       => esc_html__( 'Whatsapp', 'jupiterx-core' ),
						'wordpress'      => esc_html__( 'WordPress', 'jupiterx-core' ),
						'xing'           => esc_html__( 'Xing', 'jupiterx-core' ),
						'yelp'           => esc_html__( 'Yelp', 'jupiterx-core' ),
						'youtube'        => esc_html__( 'Youtube', 'jupiterx-core' ),
						'500px'          => esc_html__( '500px', 'jupiterx-core' ),
					],
				],
				[
					'name'  => 'new_tab',
					'type'  => 'checkbox',
					'label' => esc_html__( 'Open links in new tab', 'jupiterx-core' ),
				],
				[
					'name' => 'divider_1',
					'type' => 'divider',
				],
				[
					'name'    => 'icon_size',
					'type'    => 'number',
					'label'   => esc_html__( 'Icon size', 'jupiterx-core' ),
					'atts'    => [ 'min' => '8' ],
					'default' => '24',
				],
				[
					'name'  => 'border_radius',
					'type'  => 'number',
					'label' => esc_html__( 'Border radius', 'jupiterx-core' ),
					'atts'  => [ 'min' => '0' ],
				],
				[
					'name'  => 'icons_space',
					'type'  => 'number',
					'label' => esc_html__( 'Space between icons', 'jupiterx-core' ),
				],
				[
					'name'  => 'custom_colors',
					'type'  => 'checkbox',
					'label' => esc_html__( 'Use custom colors', 'jupiterx-core' ),
				],
				[
					'name'      => 'icon_color',
					'type'      => 'color',
					'label'     => esc_html__( 'Icon color', 'jupiterx-core' ),
					'default'   => '#FFFFFF',
					'condition' => [
						'setting' => 'custom_colors',
					],
				],
				[
					'name'      => 'background_color',
					'type'      => 'color',
					'label'     => esc_html__( 'Background color', 'jupiterx-core' ),
					'default'   => '#000000',
					'condition' => [
						'setting' => 'custom_colors',
					],
				],
				[
					'name'      => 'icon_color_hover',
					'type'      => 'color',
					'label'     => esc_html__( 'Icon hover color', 'jupiterx-core' ),
					'default'   => '#FFFFFF',
					'condition' => [
						'setting' => 'custom_colors',
					],
				],
				[
					'name'      => 'background_color_hover',
					'type'      => 'color',
					'label'     => esc_html__( 'Background hover color', 'jupiterx-core' ),
					'default'   => '#000000',
					'condition' => [
						'setting' => 'custom_colors',
					],
				],
			],
		];

		parent::__construct(
			'jupiterx_social',
			esc_html__( 'Jupiter X - Social Networks', 'jupiterx-core' ),
			$props
		);
	}

	/**
	 * Outputs the content of the widget.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Widget instance.
	 *
	 * @return void
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function widget( $args, $instance ) {
		$defaults = [
			'title'                  => '',
			'networks'               => [],
			'new_tab'                => '',
			'icon_size'              => '',
			'border_radius'          => '',
			'icons_space'            => '',
			'custom_colors'          => '',
			'icon_color'             => '',
			'background_color'       => '',
			'icon_color_hover'       => '',
			'background_color_hover' => '',
		];

		$instance  = wp_parse_args( $instance, $defaults );
		$unique_id = uniqid( 'jupiterx-social-widget-wrapper-' );
		$title     = $instance['title'];
		$networks  = (array) $instance['networks'];
		$target    = $instance['new_tab'] ? '_blank' : '_self';

		echo $args['before_widget']; // @phpcs:ignore

		$this->render_custom_css( $instance, $unique_id );

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // @phpcs:ignore
		}

		if ( ! empty( $networks ) ) {

			jupiterx_open_markup_e( 'jupiterx_widget_social_wrapper', 'div', 'class=jupiterx-social-widget-wrapper ' . $unique_id );

			foreach ( $networks as $name => $network ) {
				// Data converts to object while importing template.
				$network = (array) $network;

				if ( empty( $network['value'] ) ) {
					continue;
				}

				$label = $network['label'];
				$url   = $network['value'];

				jupiterx_open_markup_e( 'jupiterx_widget_social_link', 'a', [
					'href'   => esc_url( $url ),
					'class'  => 'jupiterx-widget-social-share-link btn jupiterx-widget-social-icon-' . esc_attr( $name ),
					'target' => $target,
				] );

					jupiterx_open_markup_e( 'jupiterx_widget_social_icon_screen_reader', 'span', 'class=screen-reader-text' );

						echo esc_html( $label );

					jupiterx_close_markup_e( 'jupiterx_widget_social_icon_screen_reader', 'span' );

					jupiterx_open_markup_e( 'jupiterx_widget_social_icon', 'span', 'class=jupiterx-social-icon jupiterx-icon-' . esc_attr( $name ) );

					jupiterx_close_markup_e( 'jupiterx_widget_social_icon', 'span' );

				jupiterx_close_markup_e( 'jupiterx_widget_social_link', 'a' );

			}

			jupiterx_close_markup_e( 'jupiterx_widget_social_wrapper', 'div' );

		}

		echo $args['after_widget']; // @phpcs:ignore
	}

	/**
	 * Render the current widget instance custom css.
	 *
	 * @param string $instance         Widget instance.
	 * @param string $unique_id Widget instance unique ID.
	 *
	 * @return void
	 */
	public function render_custom_css( $instance, $unique_id ) {
		$icon_size     = $instance['icon_size'];
		$border_radius = $instance['border_radius'];
		$icons_space   = $instance['icons_space'];
		$custom_color  = $instance['custom_colors'];

		$unique_selector  = ".{$unique_id}";
		$wrapper_style    = '';
		$link_style       = '';
		$icon_style       = '';
		$link_hover_style = '';
		$icon_hover_style = '';

		if ( ! empty( $icon_size ) ) {
			$icon_style .= 'font-size:' . $icon_size . 'px;';
			$link_style .= 'padding:' . $icon_size * 0.5 . 'px;';
		}

		if ( is_numeric( $border_radius ) ) {
			$link_style .= 'border-radius:' . $border_radius . 'px;';
		}

		if ( ! empty( $icons_space ) ) {
			$wrapper_style .= 'margin-right:calc(-' . $icons_space . 'px/2);';
			$wrapper_style .= 'margin-left:calc(-' . $icons_space . 'px/2);';
			$link_style    .= 'margin-right:calc(' . $icons_space . 'px/2);';
			$link_style    .= 'margin-left:calc(' . $icons_space . 'px/2);';
			$link_style    .= 'margin-bottom:' . $icons_space . 'px;';
		}

		if ( $custom_color ) {
			$color          = $instance['icon_color'];
			$bg_color       = $instance['background_color'];
			$hover_color    = $instance['icon_color_hover'];
			$bg_hover_color = $instance['background_color_hover'];

			$icon_style .= 'color:' . $color . ';';
			$link_style .= 'background-color:' . $bg_color . ';';

			$icon_hover_style = 'color:' . $hover_color . ';';
			$link_hover_style = 'background-color:' . $bg_hover_color . ';';
		}

		// phpcs:disable
		jupiterx_open_markup_e( 'jupiterx_social_widget_styles', 'style' );

			$widget_styles = [
				"$unique_selector"                                                              => $wrapper_style,
				"$unique_selector .jupiterx-widget-social-share-link"                            => $link_style,
				"$unique_selector .jupiterx-widget-social-share-link:hover"                      => $link_hover_style,
				"$unique_selector .jupiterx-widget-social-share-link .jupiterx-social-icon"       => $icon_style,
				"$unique_selector .jupiterx-widget-social-share-link:hover .jupiterx-social-icon" => $icon_hover_style,
			];

			foreach ( $widget_styles as $selector => $styles ) {
				if ( ! empty( $styles ) ) {
					echo "$selector { $styles }";
				}
			}

		jupiterx_close_markup_e( 'jupiterx_social_widget_styles', 'style' );
		// phpcs:enable
	}
}
