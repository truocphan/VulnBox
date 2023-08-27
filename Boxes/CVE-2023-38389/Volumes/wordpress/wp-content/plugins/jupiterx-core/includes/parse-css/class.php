<?php
/**
 * This class parse CSS.
 *
 * @package JupiterX_Core\Parse_CSS
 *
 * @since   1.0.0
 */

/**
 * Parse CSS to remove properties without values and format RTL.
 *
 * @since   1.0.0
 * @ignore
 * @access  private
 *
 * @package JupiterX_Core\Parse_CSS
 */
final class _JupiterX_Parse_CSS {

	/**
	 * CSS content.
	 *
	 * @var string
	 */
	private $content;

	/**
	 * CSS parser.
	 *
	 * @var object
	 */
	private $parser;

	/**
	 * All CSS rule sets.
	 *
	 * @var string
	 */
	private $all_rule_sets;

	/**
	 * CSS rule.
	 *
	 * @var string
	 */
	private $rule;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param array $content CSS content for the parser.
	 */
	public function __construct( $content ) {
		$this->content = $content;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function parse() {
		$parser       = new Sabberworm\CSS\Parser( $this->content );
		$this->parser = @$parser->parse();

		$this->format();
		$this->format_rtl();

		return $this->parser;
	}

	/**
	 * Format.
	 *
	 * @since 1.0.0
	 */
	private function format() {
		foreach ( $this->parser->getAllRuleSets() as $all_rule_sets ) {
			$this->all_rule_sets = $all_rule_sets;

			foreach ( $this->all_rule_sets->getRules() as $rule ) {
				$this->rule = $rule;

				$this->remove_rule();
				$this->add_prefix();
			}
		}
	}

	/**
	 * Remove rule.
	 *
	 * @since 1.0.0
	 */
	private function remove_rule() {
		if ( ! $this->rule->getValue() ) {
			$this->all_rule_sets->removeRule( $this->rule );
		}
	}

	/**
	 * Add prefix.
	 *
	 * @since 1.0.0
	 */
	private function add_prefix() {
		$vendors = [
			'align-self'                => [ '-ms-flex-item-align', '-ms-grid-row-align' ],
			'align-content'             => [ '-ms-flex-line-pack' ],
			'align-items'               => [ '-webkit-box-align', '-ms-flex-align' ],
			'animation'                 => [ '-webkit-animation' ],
			'animation-name'            => [ '-webkit-animation-name' ],
			'animation-duration'        => [ '-webkit-animation-duration' ],
			'animation-timing-function' => [ '-webkit-animation-timing-function' ],
			'animation-delay'           => [ '-webkit-animation-delay' ],
			'animation-iteration-count' => [ '-webkit-animation-iteration-count' ],
			'animation-direction'       => [ '-webkit-animation-direction' ],
			'animation-fill-mode'       => [ '-webkit-animation-fill-mode' ],
			'appearance'                => [ '-webkit-appearance', '-moz-appearance' ],
			'backface-visibility'       => [ '-webkit-backface-visibility' ],
			'box-shadow'                => [ '-webkit-box-shadow' ],
			'box-sizing'                => [ '-webkit-box-sizing' ],
			'column-count'              => [ '-webkit-column-count' ],
			'column-gap'                => [ '-webkit-column-gap' ],
			'display:flex'              => [ '-webkit-box', '-ms-flexbox' ],
			'display:inline-flex'       => [ '-webkit-inline-box', '-ms-inline-flexbox' ],
			'hyphens'                   => [ '-webkit-hyphens', '-ms-hyphens' ],
			'flex'                      => [ '-ms-flex', '-webkit-flex' ],
			'flex-basis'                => [ '-ms-flex-preferred-size' ],
			'flex-direction'            => [ '-ms-flex-direction' ],
			'flex-flow'                 => [ '-ms-flex-flow' ],
			'flex-grow'                 => [ '-webkit-box-flex', '-ms-flex-positive' ],
			'flex-wrap'                 => [ '-ms-flex-wrap' ],
			'flex-shrink'               => [ '-ms-flex-negative' ],
			'justify-content'           => [ '-webkit-box-pack', '-ms-flex-pack' ],
			'order'                     => [ '-webkit-box-ordinal-group', '-ms-flex-order' ],
			'perspective'               => [ '-webkit-perspective' ],
			'perspective-origin'        => [ '-webkit-perspective-origin' ],
			'transform'                 => [ '-webkit-transform' ],
			'transform-origin'          => [ '-webkit-transform-origin' ],
			'user-select'               => [ '-webkit-user-select', '-moz-user-select', '-ms-user-select' ],
		];

		foreach ( $vendors as $key => $value ) {
			if ( strpos( $key, ':' ) !== false ) {
				$key = explode( ':', $key );

				if ( $key[1] !== $this->rule->getValue() ) {
					continue;
				}

				foreach ( $value as $vendor ) {
					$this->add_rule( $key[0], $vendor );
				}

				continue;
			}

			if ( $key !== $this->rule->getRule() ) {
				continue;
			}

			foreach ( $value as $vendor ) {
				$this->add_rule( $vendor );
			}
		}
	}

	/**
	 * Add rule.
	 *
	 * @since 1.0.0
	 *
	 * @param string $property The CSS rule property.
	 * @param string $value    The CSS rule value.
	 */
	private function add_rule( $property, $value = null ) {
		if ( is_null( $value ) ) {
			$value = $this->rule->getValue();
		}

		$new_rule = new Sabberworm\CSS\Rule\Rule( $property );
		$new_rule->setIsImportant( $this->rule->getIsImportant() );
		$new_rule->setValue( $value );
		$this->all_rule_sets->addRule( $new_rule, $this->rule );
	}

	/**
	 * Format RTL.
	 *
	 * @since 1.0.0
	 */
	private function format_rtl() {
		if ( ! is_rtl() ) {
			return;
		}

		$parser_rtl = new PrestaShop\RtlCss\RtlCss( $this->parser );

		return $parser_rtl->flip();
	}

}
