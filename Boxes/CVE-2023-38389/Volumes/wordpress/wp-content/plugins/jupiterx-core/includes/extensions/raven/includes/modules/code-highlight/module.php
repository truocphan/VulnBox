<?php

namespace JupiterX_Core\Raven\Modules\Code_Highlight;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;
use JupiterX_Core\Raven\Plugin;

class Module extends Module_Base {
	const PRISMJS_VERSION = '1.29.0';

	public function __construct() {
		parent::__construct();

		add_action( 'jupiterx-raven-code-highlight-after-rendering', [ $this, 'enqueue_language_script' ], 10, 2 );
	}

	public function get_widgets() {
		return [ 'code-highlight' ];
	}

	/**
	 * Supported languages.
	 *
	 * @since 2.5.9
	 */
	public static function languages() {
		$languages = [
			'markup'       => esc_html__( 'Markup', 'jupiterx-core' ),
			'html'         => esc_html__( 'HTML', 'jupiterx-core' ),
			'css'          => esc_html__( 'CSS', 'jupiterx-core' ),
			'sass'         => esc_html__( 'Sass (Sass)', 'jupiterx-core' ),
			'scss'         => esc_html__( 'Sass (Scss)', 'jupiterx-core' ),
			'less'         => esc_html__( 'Less', 'jupiterx-core' ),
			'javascript'   => esc_html__( 'JavaScript', 'jupiterx-core' ),
			'typescript'   => esc_html__( 'TypeScript', 'jupiterx-core' ),
			'jsx'          => esc_html__( 'React JSX', 'jupiterx-core' ),
			'tsx'          => esc_html__( 'React TSX', 'jupiterx-core' ),
			'php'          => esc_html__( 'PHP', 'jupiterx-core' ),
			'ruby'         => esc_html__( 'Ruby', 'jupiterx-core' ),
			'json'         => esc_html__( 'JSON + Web App Manifest', 'jupiterx-core' ),
			'http'         => esc_html__( 'HTTP', 'jupiterx-core' ),
			'xml'          => esc_html__( 'XML', 'jupiterx-core' ),
			'svg'          => esc_html__( 'SVG', 'jupiterx-core' ),
			'rust'         => esc_html__( 'Rust', 'jupiterx-core' ),
			'csharp'       => esc_html__( 'C#', 'jupiterx-core' ),
			'dart'         => esc_html__( 'Dart', 'jupiterx-core' ),
			'git'          => esc_html__( 'Git', 'jupiterx-core' ),
			'java'         => esc_html__( 'Java', 'jupiterx-core' ),
			'sql'          => esc_html__( 'SQL', 'jupiterx-core' ),
			'go'           => esc_html__( 'Go', 'jupiterx-core' ),
			'kotlin'       => esc_html__( 'Kotlin + Kotlin Script', 'jupiterx-core' ),
			'julia'        => esc_html__( 'Julia', 'jupiterx-core' ),
			'python'       => esc_html__( 'Python', 'jupiterx-core' ),
			'swift'        => esc_html__( 'Swift', 'jupiterx-core' ),
			'bash'         => esc_html__( 'Bash + Shell', 'jupiterx-core' ),
			'scala'        => esc_html__( 'Scala', 'jupiterx-core' ),
			'haskell'      => esc_html__( 'Haskell', 'jupiterx-core' ),
			'perl'         => esc_html__( 'Perl', 'jupiterx-core' ),
			'objectivec'   => esc_html__( 'Objective-C', 'jupiterx-core' ),
			'visual-basic' => esc_html__( 'Visual Basic + VBA', 'jupiterx-core' ),
			'r'            => esc_html__( 'R', 'jupiterx-core' ),
			'c'            => esc_html__( 'C', 'jupiterx-core' ),
			'cpp'          => esc_html__( 'C++', 'jupiterx-core' ),
			'aspnet'       => esc_html__( 'ASP.NET (C#)', 'jupiterx-core' ),
		];

		return apply_filters( 'jupiterx-highlight-widget-languages', $languages );
	}

	/**
	 * Enqueue language script.
	 *
	 * @param array  $settings widget settings.
	 * @param string $id widget id.
	 * @since 2.5.9
	 */
	public function enqueue_language_script( $settings, $id ) {
		add_action( 'elementor/frontend/after_enqueue_scripts', function() use ( $settings, $id ) {
			$language = $settings['src'];

			if ( 'cpp' === $language || 'objectivec' === $language ) {
				wp_enqueue_script(
					'code-highlight-language-c-' . $id,
					Plugin::$plugin_assets_url . 'lib/code-highlight/prism-c.min.js',
					[ 'jupiterx-core-raven-frontend' ],
					self::PRISMJS_VERSION,
					true
				);
			}

			if ( 'scala' === $language ) {
				wp_enqueue_script(
					'code-highlight-language-java-' . $id,
					Plugin::$plugin_assets_url . 'lib/code-highlight/prism-java.min.js',
					[ 'jupiterx-core-raven-frontend' ],
					self::PRISMJS_VERSION,
					true
				);
			}

			if ( 'scss' === $language || 'sass' === $language ) {
				wp_enqueue_script(
					'code-highlight-language-css-' . $id,
					Plugin::$plugin_assets_url . 'lib/code-highlight/prism-css.min.js',
					[ 'jupiterx-core-raven-frontend' ],
					self::PRISMJS_VERSION,
					true
				);
			}

			$markups = [ 'markup', 'html', 'svg', 'xml' ];

			if ( in_array( $language, $markups, true ) ) {
				$language = 'markup';
			}

			wp_enqueue_script(
				'code-highlight-language-' . $id,
				Plugin::$plugin_assets_url . 'lib/code-highlight/prism-' . $language . '.min.js',
				[ 'jupiterx-core-raven-frontend' ],
				self::PRISMJS_VERSION,
				true
			);
		} );
	}
}
