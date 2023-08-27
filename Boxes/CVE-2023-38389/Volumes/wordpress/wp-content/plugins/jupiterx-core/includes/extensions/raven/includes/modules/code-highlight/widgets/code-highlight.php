<?php
namespace JupiterX_Core\Raven\Modules\Code_Highlight\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Modules\DynamicTags\Module as Tags;
use JupiterX_Core\Raven\Modules\Code_Highlight\Module;
use Elementor\Plugin;

defined( 'ABSPATH' ) || die();

class Code_Highlight extends Base_Widget {
	public function get_name() {
		return 'raven-code-highlight';
	}

	public function get_title() {
		return esc_html__( 'Code Highlight', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-code-highlight';
	}

	public function get_script_depends() {
		$plugins = [
			'prismjs_core' => true,
			'prismjs_clike' => true,
			'prismjs_normalize' => true,
			'prismjs_line_numbers' => true,
			'prismjs_line_highlight' => true,
			'prismjs_toolbar' => true,
			'prismjs_copy_to_clipboard' => true,
			'prismjs_markup' => true,
		];

		if ( ! Plugin::$instance->preview->is_preview_mode() ) {
			$settings = $this->get_settings_for_display();

			if ( ! $settings['show_line_number'] ) {
				unset( $plugins['prismjs_line_numbers'] );
			}

			if ( ! $settings['highlight'] ) {
				unset( $plugins['prismjs_line_highlight'] );
			}

			if ( ! $settings['clipboard'] ) {
				unset( $plugins['prismjs_copy_to_clipboard'] );
			}
		}

		return array_keys( $plugins );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content',
			[
				'label' => esc_html__( 'Code Highlight', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'src',
			[
				'label' => esc_html__( 'Language', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => Module::languages(),
				'default' => 'javascript',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'code',
			[
				'label' => esc_html__( 'Code', 'jupiterx-core' ),
				'type' => Controls_Manager::CODE,
				'default' => 'console.log( \'Hello World\' );',
				'dynamic' => [
					'active' => true,
					'categories' => [
						Tags::TEXT_CATEGORY,
					],
				],
			]
		);

		$this->add_control(
			'show_line_number',
			[
				'label' => esc_html__( 'Line Numbers', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'line-numbers',
				'default' => 'line-numbers',
			]
		);

		$this->add_control(
			'clipboard',
			[
				'label' => esc_html__( 'Copy to Clipboard', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Off', 'jupiterx-core' ),
				'return_value' => 'copy-to-clipboard',
				'default' => 'copy-to-clipboard',
			]
		);

		$this->add_control(
			'highlight',
			[
				'label' => esc_html__( 'Highlight Lines', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => '1, 3-6',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'word_wrap',
			[
				'label' => esc_html__( 'Word Wrap', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Off', 'jupiterx-core' ),
				'return_value' => 'jupiterx-ch-word-wrap',
				'default' => 'no',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'theme',
			[
				'label' => esc_html__( 'Theme', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default'  => 'Solid',
					'dark' => 'Dark',
					'okaidia' => 'Okaidia',
					'solarizedlight' => 'Solarizedlight',
					'tomorrow' => 'Tomorrow',
					'twilight' => 'Twilight',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' => esc_html__( 'Height', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh', 'em' ],
				'range' => [
					'px' => [
						'min' => 60,
						'max' => 1000,
					],
					'em' => [
						'min' => 6,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} #jupiterx-code-highlight pre' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'font_size',
			[
				'label' => esc_html__( 'Font Size', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'vw' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
					],
					'vw' => [
						'min' => 0.1,
						'max' => 10,
						'step' => 0.1,
					],
				],
				'responsive' => true,
				'selectors' => [
					'{{WRAPPER}} pre, {{WRAPPER}} code, {{WRAPPER}} .line-numbers .line-numbers-rows' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$language = $settings['src'];
		$markups  = [ 'markup', 'html', 'svg', 'xml' ];

		if ( in_array( $language, $markups, true ) ) {
			$language = 'markup';
		}

		$this->add_render_attribute( 'code', 'class', 'language-' . $language );

		// Wrapper.
		$wrapper_classes = [ 'jupiterx-code-highlight-wrapper', 'prismjs-' . $settings['theme'] ];

		if ( isset( $settings['clipboard'] ) && 'copy-to-clipboard' === $settings['clipboard'] ) {
			$wrapper_classes[] = 'copy-to-clipboard';
		}

		if ( isset( $settings['word_wrap'] ) && 'jupiterx-ch-word-wrap' === $settings['word_wrap'] ) {
			$wrapper_classes[] = 'jupiterx-ch-word-wrap';
		}

		$this->add_render_attribute(
			'wrapper',
			[
				'class' => $wrapper_classes,
				'id'    => 'jupiterx-code-highlight',
			]
		);

		// Pre.
		$pre_classes = [ 'jupiterx-code-highlight-pre' ];

		if ( isset( $settings['show_line_number'] ) && 'line-numbers' === $settings['show_line_number'] ) {
			$pre_classes[] = 'line-numbers';
		} else {
			$pre_classes[] = 'no-line-numbers';
		}

		$this->add_render_attribute(
			'pre',
			[
				'data-line'   => $settings['highlight'],
				'class'       => $pre_classes,
				'data-indent' => 8,
			]
		);

		?>
			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<pre <?php echo $this->get_render_attribute_string( 'pre' ); ?>>
					<code <?php echo $this->get_render_attribute_string( 'code' ); ?>><xmp><?php $this->print_unescaped_setting( 'code' ); ?></xmp></code>
				</pre>
			</div>
		<?php

		do_action( 'jupiterx-raven-code-highlight-after-rendering', $settings, $this->get_id() );
	}

	protected function content_template() {
		?>
			<#
				let language = settings.src;
				let markups = [ 'markup', 'html', 'svg', 'xml' ];
				if ( markups.includes( language ) ) {
					language = 'markup';
				}
			#>
			<div id="jupiterx-code-highlight" data-language="{{{ settings.src }}}" class="whitespace-normalization jupiterx-code-highlight-wrapper prismjs-{{{ settings.theme }}} {{{settings.clipboard}}} {{{settings.word_wrap}}}">
				<pre data-line="{{{settings.highlight }}}" class="whitespace-normalization language-{{{ settings.src }}} {{{ settings.show_line_number || 'no-line-numbers' }}}">
					<code readonly="true" class="language-{{{ settings.src }}}"><xmp>{{{ settings.code }}}</xmp></code>
				</pre>
			</div>
			<div class="jx-related-scripts" ></div>
			<script type="text/javascript" src="{{ elementor.config.jx_assets_url }}/lib/code-highlight/prism-{{{ language || 'javascript' }}}.min.js"></script><?php //phpcs:ignore ?>
		<?php
	}
}
