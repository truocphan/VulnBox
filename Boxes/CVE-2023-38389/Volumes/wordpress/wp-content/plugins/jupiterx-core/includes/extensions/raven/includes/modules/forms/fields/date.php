<?php
/**
 * Add form date field.
 *
 * @package JupiterX_Core\Raven
 * @since 1.2.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Fields;

use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

/**
 * Date Field.
 *
 * Initializing the date field by extending field base abstract class.
 *
 * @since 1.2.0
 */
class Date extends Field_Base {

	public function get_type() {
		if ( $this->field['native_html5'] ) {
			return 'date';
		}

		return 'text';
	}

	public function get_class() {
		return 'raven-field flatpickr';
	}

	public function get_style_depends() {
		return [ 'flatpickr' ];
	}

	public function get_script_depends() {
		return [ 'flatpickr' ];
	}

	private function get_min_date() {
		$attr = 'data-min-date';
		$min  = empty( $this->field['min_date'] ) ? '' : $this->field['min_date'];

		if ( $this->field['native_html5'] ) {
			$attr = 'min';
		}

		return $attr . '="' . $min . '"';
	}

	private function get_max_date() {
		$attr = 'data-max-date';
		$max  = empty( $this->field['max_date'] ) ? '' : $this->field['max_date'];

		if ( $this->field['native_html5'] ) {
			$attr = 'max';
		}

		return $attr . '="' . $max . '"';
	}

	public function render_content() {
		$localization = $this->field['localization'];
		$local_attr   = '';

		if ( 'yes' === $localization ) {
			$this->load_localize_script();
			$local_attr = 'data-locale="' . $this->date_localization() . '"';
		}
		?>
		<input
			oninput="onInvalidRavenFormField(event)"
			oninvalid="onInvalidRavenFormField(event)"
			<?php echo $this->widget->get_render_attribute_string( 'field-' . $this->get_id() ); ?>
			<?php echo $this->get_min_date(); ?>
			<?php echo esc_attr( $local_attr ); ?>
			<?php echo $this->get_max_date(); ?>>
		<?php
	}

	public function update_controls( $widget ) {
		$control_data = Elementor::$instance->controls_manager->get_control_from_stack(
			$widget->get_unique_name(),
			'fields'
		);

		if ( is_wp_error( $control_data ) ) {
			return;
		}

		$field_controls = [
			'min_date' => [
				'name' => 'min_date',
				'label' => __( 'Min Date', 'jupiterx-core' ),
				'type' => 'date_time',
				'picker_options' => [
					'enableTime' => false,
					'locale' => [
						'firstDayOfWeek' => 1,
					],
				],
				'label_block' => false,
				'condition' => [
					'type' => 'date',
				],
			],
			'max_date' => [
				'name' => 'max_date',
				'label' => __( 'Max Date', 'jupiterx-core' ),
				'type' => 'date_time',
				'picker_options' => [
					'enableTime' => false,
					'locale' => [
						'firstDayOfWeek' => 1,
					],
				],
				'label_block' => false,
				'condition' => [
					'type' => 'date',
				],
			],
			'localization' => [
				'name' => 'localization',
				'label' => __( 'Localization', 'jupiterx-core' ),
				'type' => 'switcher',
				'return_value' => 'yes',
				'condition' => [
					'type' => 'date',
				],
			],
		];

		$control_data['fields'] = $this->inject_field_controls( $control_data['fields'], $field_controls );
		$widget->update_control( 'fields', $control_data );
	}

	private function date_localization() {
		$wp_locale = get_locale();
		$locales   = [
			'af'             => '',   // 'Afrikaans'
			'ar'             => 'ar', // 'Arabic'
			'ary'            => 'ar', // 'Moroccan Arabic'
			'as'             => '',   // 'Assamese'
			'azb'            => 'az', // 'South Azerbaijani'
			'az'             => 'az', // 'Azerbaijani'
			'bel'            => 'be', // 'Belarusian'
			'bg_BG'          => 'bg', // 'Bulgarian'
			'bn_BD'          => 'bn', // 'Bengali (Bangladesh)'
			'bo'             => '',   // 'Tibetan'
			'bs_BA'          => 'bs', // 'Bosnian'
			'ca'             => 'cat', // 'Catalan'
			'ceb'            => '',   // 'Cebuano'
			'cs_CZ'          => 'cs', // 'Czech'
			'cy'             => 'cy', // 'Welsh'
			'da_DK'          => 'da', // 'Danish'
			'de_CH_informal' => 'de', // 'German (Switzerland, Informal)'
			'de_CH'          => 'de', // 'German (Switzerland)'
			'de_DE'          => 'de', // 'German'
			'de_DE_formal'   => 'de', // 'German (Formal)'
			'de_AT'          => 'de', // 'German (Austria)'
			'dzo'            => '',   // 'Dzongkha'
			'el'             => 'gr', // 'Greek'
			'en_GB'          => 'en', // 'English (UK)'
			'en_AU'          => 'en', // 'English (Australia)'
			'en_CA'          => 'en', // 'English (Canada)'
			'en_ZA'          => 'en', // 'English (South Africa)'
			'en_NZ'          => 'en', // 'English (New Zealand)'
			'eo'             => 'eo', // 'Esperanto'
			'es_CL'          => 'es', // 'Spanish (Chile)'
			'es_ES'          => 'es', // 'Spanish (Spain)'
			'es_MX'          => 'es', // 'Spanish (Mexico)'
			'es_GT'          => 'es', // 'Spanish (Guatemala)'
			'es_CR'          => 'es', // 'Spanish (Costa Rica)'
			'es_CO'          => 'es', // 'Spanish (Colombia)'
			'es_PE'          => 'es', // 'Spanish (Peru)'
			'es_VE'          => 'es', // 'Spanish (Venezuela)'
			'es_AR'          => 'es', // 'Spanish (Argentina)'
			'et'             => 'et', // 'Estonian'
			'eu'             => 'es', // 'Basque'
			'fa_IR'          => 'fa', // 'Persian'
			'fi'             => 'fi', // 'Finnish'
			'fr_CA'          => 'fr', // 'French (Canada)'
			'fr_FR'          => 'fr', // 'French (France)'
			'fr_BE'          => 'fr', // 'French (Belgium)'
			'fur'            => '',   // 'Friulian'
			'gd'             => 'ga', // 'Scottish Gaelic'
			'gl_ES'          => 'es', // 'Galician'
			'gu'             => '',   // 'Gujarati'
			'haz'            => '',   // 'Hazaragi'
			'he_IL'          => 'he', // 'Hebrew'
			'hi_IN'          => 'hi', // 'Hindi'
			'hr'             => 'hr', // 'Croatian'
			'hsb'            => '',   // 'Upper Sorbian'
			'hu_HU'          => 'hu', // 'Hungarian'
			'hy'             => '',   // 'Armenian'
			'id_ID'          => 'id', // 'Indonesian'
			'is_IS'          => 'is', // 'Icelandic'
			'it_IT'          => 'it', // 'Italian'
			'ja'             => 'ja', // 'Japanese'
			'jv_ID'          => '',   // 'Javanese'
			'ka_GE'          => 'ka', // 'Georgian'
			'kab'            => '',   // 'Kabyle'
			'kk'             => 'kz', // 'Kazakh'
			'km'             => 'km', // 'Khmer'
			'kn'             => '',   // 'Kannada'
			'ko_KR'          => 'ko', // 'Korean'
			'ckb'            => '',   // 'Kurdish (Sorani)'
			'lo'             => '',   // 'Lao'
			'lt_LT'          => 'lt', // 'Lithuanian'
			'lv'             => 'lv', // 'Latvian'
			'mk_MK'          => 'mk', // 'Macedonian'
			'ml_IN'          => '',   // 'Malayalam'
			'mn'             => 'mn', // 'Mongolian'
			'mr'             => '',   // 'Marathi'
			'ms_MY'          => 'ms', // 'Malay'
			'my_MM'          => 'my', // 'Myanmar (Burmese)'
			'nb_NO'          => 'no', // 'Norwegian (Bokmål)'
			'ne_NP'          => '',   // 'Nepali'
			'nl_NL'          => 'nl', // 'Dutch'
			'nl_NL_formal'   => 'nl', // 'Dutch (Formal)'
			'nl_BE'          => 'nl', // 'Dutch (Belgium)'
			'nn_NO'          => 'no', // 'Norwegian (Nynorsk)'
			'oci'            => '',   // 'Occitan'
			'pa_IN'          => 'pa', // 'Punjabi'
			'pl_PL'          => 'pl', // 'Polish'
			'ps'             => '',   // 'Pashto'
			'pt_BR'          => 'pt', // 'Portuguese (Brazil)'
			'pt_AO'          => 'pt', // 'Portuguese (Angola)'
			'pt_PT'          => 'pt', // 'Portuguese (Portugal)'
			'pt_PT_ao90'     => 'pt', // 'Portuguese (Portugal, AO90)'
			'rhg'            => '',   // 'Rohingya'
			'ro_RO'          => 'ro', // 'Romanian'
			'ru_RU'          => 'ru', // 'Russian'
			'sah'            => '',   // 'Sakha'
			'si_LK'          => 'si', // 'Sinhala'
			'sk_SK'          => 'sk', // 'Slovak'
			'skr'            => '',   // 'Saraiki'
			'sl_SI'          => 'sl', // 'Slovenian'
			'sq'             => 'sq', // 'Albanian'
			'sr_RS'          => 'sr', // 'Serbian'
			'sv_SE'          => 'sv', // 'Swedish'
			'sw'             => '',   // 'Swahili'
			'szl'            => '',   // 'Silesian'
			'ta_IN'          => '',   // 'Tamil'
			'te'             => '',   // 'Telugu'
			'th'             => 'th', // 'Thai'
			'tl'             => '',   // 'Tagalog'
			'tr_TR'          => 'tr', // 'Turkish'
			'tt_RU'          => '',   // 'Tatar'
			'tah'            => '',   // 'Tahitian'
			'ug_CN'          => '',   // 'Uighur'
			'uk'             => 'uk', // 'Ukrainian'
			'ur'             => '',   // 'Urdu'
			'uz_UZ'          => '',   // 'Uzbek'
			'vi'             => 'vn', // 'Vietnamese'
			'zh_HK'          => 'zh', // 'Chinese (Hong Kong)'
			'zh_TW'          => 'zh-tw', // 'Chinese (Taiwan)'
			'zh_CN'          => 'zh', // 'Chinese (China)'
		];

		$locale = array_key_exists( $wp_locale, $locales ) ? $locales[ $wp_locale ] : 'default';

		if ( 'en' === $locale || '' === $locale ) {
			$locale = 'default';
		}

		return $locale;
	}

	private function load_localize_script() {
		$locale = $this->date_localization();

		if ( 'default' === $locale ) {
			return;
		}

		wp_enqueue_script('raven_flatpickr_localize',
			trailingslashit( plugin_dir_url( JUPITERX_CORE_RAVEN__FILE__ ) . 'assets/lib/flatpickr-locale/' ) . $locale . '.js',
			[ 'flatpickr' ],
			'4.1.4',
			true
		);
	}

}
