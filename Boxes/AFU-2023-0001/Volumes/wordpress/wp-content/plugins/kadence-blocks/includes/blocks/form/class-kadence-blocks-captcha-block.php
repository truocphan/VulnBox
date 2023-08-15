<?php
/**
 * Class to Build the Advanced Form Captcha Block.
 *
 * @package Kadence Blocks
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Kadence_Blocks_Captcha_Block extends Kadence_Blocks_Advanced_Form_Input_Block {

	/**
	 * Instance of this class
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Block name within this namespace.
	 *
	 * @var string
	 */
	protected $block_name = 'captcha';


	/**
	 * Instance Control
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Builds CSS for block.
	 *
	 * @param array  $attributes      the blocks attributes.
	 * @param string $css             the css class for blocks.
	 * @param string $unique_id       the blocks attr ID.
	 * @param string $unique_style_id the blocks alternate ID for queries.
	 */
	public function build_css( $attributes, $css, $unique_id, $unique_style_id ) {
		$css->set_style_id( 'kb-' . $this->block_name . $unique_style_id );
		$css->set_selector( '.wp-block-kadence-advanced-form .kb-field' . $unique_style_id );

		$css->render_responsive_range( $attributes, 'maxWidth', 'max-width', 'maxWidthUnit' );
		$css->render_responsive_range( $attributes, 'minWidth', 'min-width', 'minWidthUnit' );

		return $css->css_output();
	}

	/**
	 * Return dynamically generated HTML for block
	 *
	 * @param          $attributes
	 * @param          $unique_id
	 * @param          $content
	 * @param WP_Block $block_instance The instance of the WP_Block class that represents the block being rendered.
	 *
	 * @return mixed
	 */
	public function build_html( $attributes, $unique_id, $content, $block_instance ) {

		$captcha_settings = new Kadence_Blocks_Form_Captcha_Settings( $attributes );
		$this->register_scripts_with_attrs( $attributes, $captcha_settings );

		/* We can't tell captcha type, or key isn't set */
		if ( ! $captcha_settings->is_valid ) {
			return '';
		}

		$outer_classes = array( 'kb-adv-form-field', 'kb-field' . $unique_id );
		if ( ! empty( $attributes['className'] ) ) {
			$outer_classes[] = $attributes['className'];
		}
		$wrapper_args       = array(
			'class' => implode( ' ', $outer_classes ),
		);
		$wrapper_attributes = get_block_wrapper_attributes( $wrapper_args );
		$inner_content      = '';

		switch ( $captcha_settings->service ) {
			case 'googlev2':
				$inner_content .= $this->render_google_v2( $captcha_settings );
				break;
			case 'googlev3':
				return $this->render_google_v3( $captcha_settings, $unique_id );
				break;
			case 'turnstile':
				$inner_content .= $this->render_turnstile( $captcha_settings );
				break;
			case 'hcaptcha':
				$inner_content .= $this->render_hcaptcha( $captcha_settings );
				break;
		}

		return sprintf( '<div %1$s>%2$s</div>', $wrapper_attributes, $inner_content );
	}

	private function render_google_v2( $captcha_settings ) {

		$recaptcha_v2_script = "var kbOnloadV2Callback = function(){jQuery( '.wp-block-kadence-form' ).find( '.kadence-blocks-g-recaptcha-v2' ).each( function() {grecaptcha.render( jQuery( this ).attr( 'id' ), {'sitekey' : '" . esc_attr( $captcha_settings->public_key ) . "'});});}";
		wp_add_inline_script( 'kadence-blocks-recaptcha', $recaptcha_v2_script, 'before' );
		$this->enqueue_script( 'kadence-blocks-recaptcha' );

		return '<div class="g-recaptcha" data-language="' . $captcha_settings->language . '" data-size="' . $captcha_settings->size . '" data-theme="' . $captcha_settings->theme . '" data-sitekey="' . $captcha_settings->public_key . '"></div>';
	}

	private function render_google_v3( $captcha_settings, $unique_id ) {

		$recaptcha_v3_script = "grecaptcha.ready(function () {
					var kb_recaptcha_inputs = document.getElementsByClassName('kb_recaptcha_response');

					if ( ! kb_recaptcha_inputs.length ) {
						return;
					}

					for (var i = 0; i < kb_recaptcha_inputs.length; i++) {
						const e = i; grecaptcha.execute('" . esc_attr( $captcha_settings->public_key ) . "', { action: 'kb_form' }).then(
						function (token) {
							kb_recaptcha_inputs[e].setAttribute('value', token);
						});
					}
			});";
		wp_add_inline_script( 'kadence-blocks-recaptcha', $recaptcha_v3_script, 'after' );
		$this->enqueue_script( 'kadence-blocks-recaptcha' );

		return '<input type="hidden" name="recaptcha_response" class="kb_recaptcha_response kb_recaptcha_' . $unique_id . '" />';
	}

	private function render_turnstile( $captcha_settings ) {
		$this->enqueue_script( 'kadence-blocks-turnstile' );

		return '<div class="cf-turnstile" data-language="' . $captcha_settings->language . '" data-size="' . $captcha_settings->size . '" data-theme="' . $captcha_settings->theme . '" data-sitekey="' . $captcha_settings->public_key . '"></div>';
	}

	private function render_hcaptcha( $captcha_settings ) {
		$this->enqueue_script( 'kadence-blocks-hcaptcha' );

		return '<div class="h-captcha" data-language="' . $captcha_settings->language . '" data-size="' . $captcha_settings->size . '" data-theme="' . $captcha_settings->theme . '" data-sitekey="' . $captcha_settings->public_key . '"></div>';
	}

	/**
	 * Registers scripts and styles.
	 */
	public function register_scripts_with_attrs( $attributes, $captcha_settings ) {
		// If in the backend, bail out.
		if ( is_admin() ) {
			return;
		}
		if ( apply_filters( 'kadence_blocks_check_if_rest', false ) && kadence_blocks_is_rest() ) {
			return;
		}

		wp_register_script( 'kadence-blocks-turnstile', 'https://challenges.cloudflare.com/turnstile/v0/api.js', array(), KADENCE_BLOCKS_VERSION, true );
		wp_register_script( 'kadence-blocks-hcaptcha', 'https://js.hcaptcha.com/1/api.js', array(), KADENCE_BLOCKS_VERSION, true );

		$recaptcha_url     = 'https://www.google.com/recaptcha/api.js';
		$recaptcha_net_url = 'https://www.recaptcha.net/recaptcha/api.js';

		if ( $captcha_settings->using_kadence_captcha && $captcha_settings->get_kadence_captcha_stored_value( 'recaptcha_url' ) === 'recaptcha' ) {
			$recaptcha_url = $recaptcha_net_url;
		}

		if ( $captcha_settings->language !== false ) {
			$recaptcha_url = add_query_arg( array( 'hl' => $captcha_settings->language ), $recaptcha_url );
		}

		if ( $captcha_settings->service === 'googlev3' ) {
			$recaptcha_url = add_query_arg( array( 'render' => $captcha_settings->public_key ), $recaptcha_url );
		}

		wp_register_script( 'kadence-blocks-recaptcha', $recaptcha_url, array(), KADENCE_BLOCKS_VERSION, true );
	}

}

Kadence_Blocks_Captcha_Block::get_instance();
