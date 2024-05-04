<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Piotnetforms_Field extends Base_Widget_Piotnetforms {

	protected $is_add_conditional_logic = false;

	public function get_type() {
		return 'field';
	}

	public function get_class_name() {
		return 'Piotnetforms_Field';
	}

	public function get_title() {
		return 'Field';
	}

	public function get_icon() {
		return [
			'type' => 'image',
			'value' => plugin_dir_url( __FILE__ ) . '../../assets/icons/i-field.svg',
		];
	}

	public function get_categories() {
		return [ 'piotnetforms' ];
	}

	public function get_keywords() {
		return [ 'field' ];
	}

	public function register_controls() {
		$this->start_tab( 'settings', 'Settings' );

		$this->start_section( 'piotnetforms_field', 'Settings' );
		$this->add_setting_controls();

		$this->start_section(
			'input_mask_section',
			'Input Mask (Pro)',
			[
				'conditions' => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => '!in',
							'value'    => [
								'checkbox',
								'acceptance',
								'radio',
							],
						],
					],
				],
			]
		);
		$this->add_input_mask_controls();

		$this->start_section( 'section_conditional_logic', 'Conditional Logic (Pro)' );
		$this->add_conditional_logic_controls();

		$this->start_tab( 'style', 'Style' );
		$this->start_section(
			'text_styles_section',
			'Checkbox',
			[
				'conditions' => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'checkbox',
								'acceptance',
							],
						],
					],
				],
			]
		);
		$this->add_checkbox_style_controls();

		$this->start_section(
			'section_style_spiner',
			'(-/+) Button (Pro)',
			[
				'condition' => [
					'field_type'     => 'number',
					'number_spiner!' => '',
				],
			]
		);
		$this->add_number_style_controls();

		$this->start_section(
			'section_style_image_select',
			'Image Select',
			[
				'condition' => [
					'field_type' => 'image_select',
				],
			]
		);
		$this->add_image_select_style_controls();

		$this->start_section( 'section_style_piotnet_form_label', 'Label' );
		$this->add_label_style_controls();

		$this->start_section( 'section_style_piotnet_form_field', 'Field' );
		$this->add_field_style_controls();

		$this->add_advanced_tab();

		return $this->structure;
	}

	private function add_setting_controls() {
		$field_types = [
			'text'              => __( 'Text', 'piotnetforms' ),
			'email'             => __( 'Email', 'piotnetforms' ),
			'textarea'          => __( 'Textarea', 'piotnetforms' ),
			'url'               => __( 'URL', 'piotnetforms' ),
			'tel'               => __( 'Tel', 'piotnetforms' ),
			'radio'             => __( 'Radio', 'piotnetforms' ),
			'select'            => __( 'Select', 'piotnetforms' ),
			'terms_select'      => __( 'Terms Select (Pro)', 'piotnetforms' ),
			'image_select'      => __( 'Image Select (Pro)', 'piotnetforms' ),
			'checkbox'          => __( 'Checkbox', 'piotnetforms' ),
			'acceptance'        => __( 'Acceptance', 'piotnetforms' ),
			'number'            => __( 'Number', 'piotnetforms' ),
			'date'              => __( 'Date (Pro)', 'piotnetforms' ),
			'time'              => __( 'Time (Pro)', 'piotnetforms' ),
			'image_upload'      => __( 'Image Upload (Pro)', 'piotnetforms' ),
			'upload'            => __( 'File Upload', 'piotnetforms' ),
			'password'          => __( 'Password', 'piotnetforms' ),
			'html'              => __( 'HTML', 'piotnetforms' ),
			'hidden'            => __( 'Hidden', 'piotnetforms' ),
			'range_slider'      => __( 'Range Slider (Pro)', 'piotnetforms' ),
			'coupon_code'       => __( 'Coupon Code (Pro)', 'piotnetforms' ),
			'calculated_fields' => __( 'Calculated Fields (Pro)', 'piotnetforms' ),
			'stripe_payment'    => __( 'Stripe Payment (Pro)', 'piotnetforms' ),
			'honeypot'          => __( 'Honeypot (Pro)', 'piotnetforms' ),
			'color'             => __( 'Color Picker (Pro)', 'piotnetforms' ),
		];

		$field_types['tinymce'] = __( 'TinyMCE (Pro)', 'piotnetforms' );

		$field_types['select_autocomplete'] = __( 'Select Autocomplete (Pro)', 'piotnetforms' );

		$field_types['address_autocomplete'] = __( 'Address Autocomplete (Pro)', 'piotnetforms' );

		$field_types['signature'] = __( 'Signature (Pro)', 'piotnetforms' );

		$this->add_control(
			'form_id',
			[
				'label'       => __( 'Form ID* (Required)', 'piotnetforms' ),
				'type'        => 'text',
				'description' => __( 'Enter the same form id for all fields in a form, with latin character and no space. E.g order_form', 'piotnetforms' ),
			]
		);

		$this->add_control(
			'field_id',
			[
				'label'       => __( 'Field ID* (Required)', 'piotnetforms' ),
				'type'        => 'text',
				'description' => __( 'Field ID have to be unique in a form, with latin character and no space, no number. Please do not enter Field ID = product. E.g your_field_id', 'piotnetforms' ),
			]
		);

		$this->add_control(
			'shortcode',
			[
				'label'   => __( 'Shortcode', 'piotnetforms' ),
				'type'    => 'html',
				'classes' => 'forms-field-shortcode',
				'raw'     => '<input class="piotnetforms-field-shortcode" readonly />',
			]
		);

		$this->add_control(
			'field_type',
			[
				'label'       => __( 'Type', 'piotnetforms' ),
				'type'        => 'select',
				'options'     => $field_types,
				'default'     => 'text',
				'description' => 'TinyMCE only works on the frontend.',
			]
		);

		$this->add_control(
			'google_maps',
			[
				'label'       => __( 'Google Maps', 'piotnetforms' ),
				'type'        => 'switch',
				'description' => __( 'This feature only works on the frontend.', 'piotnetforms' ),
				'label_on'    => __( 'Show', 'piotnetforms' ),
				'label_off'   => __( 'Hide', 'piotnetforms' ),
				'default'     => '',
				'conditions'  => [
					[
						'name'     => 'field_type',
						'operator' => '==',
						'value'    => 'address_autocomplete',
					],
				],
			]
		);

		$this->add_control(
			'country',
			[
				'label'       => __( 'Country', 'piotnetforms' ),
				'type'        => 'select',
				'description' => __( 'Choose your country.', 'piotnetforms' ),
				'default'     => 'All',
				'options'     => [
					'All' => __( 'All', 'piotnetforms' ),
					'AF'  => 'Afghanistan',
					'AX'  => 'Åland Islands',
					'AL'  => 'Albania',
					'DZ'  => 'Algeria',
					'AS'  => 'American Samoa',
					'AD'  => 'Andorra',
					'AO'  => 'Angola',
					'AI'  => 'Anguilla',
					'AQ'  => 'Antarctica',
					'AG'  => 'Antigua and Barbuda',
					'AR'  => 'Argentina',
					'AM'  => 'Armenia',
					'AW'  => 'Aruba',
					'AU'  => 'Australia',
					'AT'  => 'Austria',
					'AZ'  => 'Azerbaijan',
					'BS'  => 'Bahamas',
					'BH'  => 'Bahrain',
					'BD'  => 'Bangladesh',
					'BB'  => 'Barbados',
					'BY'  => 'Belarus',
					'BE'  => 'Belgium',
					'BZ'  => 'Belize',
					'BJ'  => 'Benin',
					'BM'  => 'Bermuda',
					'BT'  => 'Bhutan',
					'BO'  => 'Bolivia, Plurinational State of',
					'BQ'  => 'Bonaire, Sint Eustatius and Saba',
					'BA'  => 'Bosnia and Herzegovina',
					'BW'  => 'Botswana',
					'BV'  => 'Bouvet Island',
					'BR'  => 'Brazil',
					'IO'  => 'British Indian Ocean Territory',
					'BN'  => 'Brunei Darussalam',
					'BG'  => 'Bulgaria',
					'BF'  => 'Burkina Faso',
					'BI'  => 'Burundi',
					'KH'  => 'Cambodia',
					'CM'  => 'Cameroon',
					'CA'  => 'Canada',
					'CV'  => 'Cape Verde',
					'KY'  => 'Cayman Islands',
					'CF'  => 'Central African Republic',
					'TD'  => 'Chad',
					'CL'  => 'Chile',
					'CN'  => 'China',
					'CX'  => 'Christmas Island',
					'CC'  => 'Cocos (Keeling) Islands',
					'CO'  => 'Colombia',
					'KM'  => 'Comoros',
					'CG'  => 'Congo',
					'CD'  => 'Congo, the Democratic Republic of the',
					'CK'  => 'Cook Islands',
					'CR'  => 'Costa Rica',
					'CI'  => "Côte d'Ivoire",
					'HR'  => 'Croatia',
					'CU'  => 'Cuba',
					'CW'  => 'Curaçao',
					'CY'  => 'Cyprus',
					'CZ'  => 'Czech Republic',
					'DK'  => 'Denmark',
					'DJ'  => 'Djibouti',
					'DM'  => 'Dominica',
					'DO'  => 'Dominican Republic',
					'EC'  => 'Ecuador',
					'EG'  => 'Egypt',
					'SV'  => 'El Salvador',
					'GQ'  => 'Equatorial Guinea',
					'ER'  => 'Eritrea',
					'EE'  => 'Estonia',
					'ET'  => 'Ethiopia',
					'FK'  => 'Falkland Islands (Malvinas)',
					'FO'  => 'Faroe Islands',
					'FJ'  => 'Fiji',
					'FI'  => 'Finland',
					'FR'  => 'France',
					'GF'  => 'French Guiana',
					'PF'  => 'French Polynesia',
					'TF'  => 'French Southern Territories',
					'GA'  => 'Gabon',
					'GM'  => 'Gambia',
					'GE'  => 'Georgia',
					'DE'  => 'Germany',
					'GH'  => 'Ghana',
					'GI'  => 'Gibraltar',
					'GR'  => 'Greece',
					'GL'  => 'Greenland',
					'GD'  => 'Grenada',
					'GP'  => 'Guadeloupe',
					'GU'  => 'Guam',
					'GT'  => 'Guatemala',
					'GG'  => 'Guernsey',
					'GN'  => 'Guinea',
					'GW'  => 'Guinea-Bissau',
					'GY'  => 'Guyana',
					'HT'  => 'Haiti',
					'HM'  => 'Heard Island and McDonald Islands',
					'VA'  => 'Holy See (Vatican City State)',
					'HN'  => 'Honduras',
					'HK'  => 'Hong Kong',
					'HU'  => 'Hungary',
					'IS'  => 'Iceland',
					'IN'  => 'India',
					'ID'  => 'Indonesia',
					'IR'  => 'Iran, Islamic Republic of',
					'IQ'  => 'Iraq',
					'IE'  => 'Ireland',
					'IM'  => 'Isle of Man',
					'IL'  => 'Israel',
					'IT'  => 'Italy',
					'JM'  => 'Jamaica',
					'JP'  => 'Japan',
					'JE'  => 'Jersey',
					'JO'  => 'Jordan',
					'KZ'  => 'Kazakhstan',
					'KE'  => 'Kenya',
					'KI'  => 'Kiribati',
					'KP'  => "Korea, Democratic People's Republic of",
					'KR'  => 'Korea, Republic of',
					'KW'  => 'Kuwait',
					'KG'  => 'Kyrgyzstan',
					'LA'  => "Lao People's Democratic Republic",
					'LV'  => 'Latvia',
					'LB'  => 'Lebanon',
					'LS'  => 'Lesotho',
					'LR'  => 'Liberia',
					'LY'  => 'Libya',
					'LI'  => 'Liechtenstein',
					'LT'  => 'Lithuania',
					'LU'  => 'Luxembourg',
					'MO'  => 'Macao',
					'MK'  => 'Macedonia, the former Yugoslav Republic of',
					'MG'  => 'Madagascar',
					'MW'  => 'Malawi',
					'MY'  => 'Malaysia',
					'MV'  => 'Maldives',
					'ML'  => 'Mali',
					'MT'  => 'Malta',
					'MH'  => 'Marshall Islands',
					'MQ'  => 'Martinique',
					'MR'  => 'Mauritania',
					'MU'  => 'Mauritius',
					'YT'  => 'Mayotte',
					'MX'  => 'Mexico',
					'FM'  => 'Micronesia, Federated States of',
					'MD'  => 'Moldova, Republic of',
					'MC'  => 'Monaco',
					'MN'  => 'Mongolia',
					'ME'  => 'Montenegro',
					'MS'  => 'Montserrat',
					'MA'  => 'Morocco',
					'MZ'  => 'Mozambique',
					'MM'  => 'Myanmar',
					'NA'  => 'Namibia',
					'NR'  => 'Nauru',
					'NP'  => 'Nepal',
					'NL'  => 'Netherlands',
					'NC'  => 'New Caledonia',
					'NZ'  => 'New Zealand',
					'NI'  => 'Nicaragua',
					'NE'  => 'Niger',
					'NG'  => 'Nigeria',
					'NU'  => 'Niue',
					'NF'  => 'Norfolk Island',
					'MP'  => 'Northern Mariana Islands',
					'NO'  => 'Norway',
					'OM'  => 'Oman',
					'PK'  => 'Pakistan',
					'PW'  => 'Palau',
					'PS'  => 'Palestinian Territory, Occupied',
					'PA'  => 'Panama',
					'PG'  => 'Papua New Guinea',
					'PY'  => 'Paraguay',
					'PE'  => 'Peru',
					'PH'  => 'Philippines',
					'PN'  => 'Pitcairn',
					'PL'  => 'Poland',
					'PT'  => 'Portugal',
					'PR'  => 'Puerto Rico',
					'QA'  => 'Qatar',
					'RE'  => 'Réunion',
					'RO'  => 'Romania',
					'RU'  => 'Russian Federation',
					'RW'  => 'Rwanda',
					'BL'  => 'Saint Barthélemy',
					'SH'  => 'Saint Helena, Ascension and Tristan da Cunha',
					'KN'  => 'Saint Kitts and Nevis',
					'LC'  => 'Saint Lucia',
					'MF'  => 'Saint Martin (French part)',
					'PM'  => 'Saint Pierre and Miquelon',
					'VC'  => 'Saint Vincent and the Grenadines',
					'WS'  => 'Samoa',
					'SM'  => 'San Marino',
					'ST'  => 'Sao Tome and Principe',
					'SA'  => 'Saudi Arabia',
					'SN'  => 'Senegal',
					'RS'  => 'Serbia',
					'SC'  => 'Seychelles',
					'SL'  => 'Sierra Leone',
					'SG'  => 'Singapore',
					'SX'  => 'Sint Maarten (Dutch part)',
					'SK'  => 'Slovakia',
					'SI'  => 'Slovenia',
					'SB'  => 'Solomon Islands',
					'SO'  => 'Somalia',
					'ZA'  => 'South Africa',
					'GS'  => 'South Georgia and the South Sandwich Islands',
					'SS'  => 'South Sudan',
					'ES'  => 'Spain',
					'LK'  => 'Sri Lanka',
					'SD'  => 'Sudan',
					'SR'  => 'Suriname',
					'SJ'  => 'Svalbard and Jan Mayen',
					'SZ'  => 'Swaziland',
					'SE'  => 'Sweden',
					'CH'  => 'Switzerland',
					'SY'  => 'Syrian Arab Republic',
					'TW'  => 'Taiwan, Province of China',
					'TJ'  => 'Tajikistan',
					'TZ'  => 'Tanzania, United Republic of',
					'TH'  => 'Thailand',
					'TL'  => 'Timor-Leste',
					'TG'  => 'Togo',
					'TK'  => 'Tokelau',
					'TO'  => 'Tonga',
					'TT'  => 'Trinidad and Tobago',
					'TN'  => 'Tunisia',
					'TR'  => 'Turkey',
					'TM'  => 'Turkmenistan',
					'TC'  => 'Turks and Caicos Islands',
					'TV'  => 'Tuvalu',
					'UG'  => 'Uganda',
					'UA'  => 'Ukraine',
					'AE'  => 'United Arab Emirates',
					'GB'  => 'United Kingdom',
					'US'  => 'United States',
					'UM'  => 'United States Minor Outlying Islands',
					'UY'  => 'Uruguay',
					'UZ'  => 'Uzbekistan',
					'VU'  => 'Vanuatu',
					'VE'  => 'Venezuela, Bolivarian Republic of',
					'VN'  => 'Viet Nam',
					'VG'  => 'Virgin Islands, British',
					'VI'  => 'Virgin Islands, U.S.',
					'WF'  => 'Wallis and Futuna',
					'EH'  => 'Western Sahara',
					'YE'  => 'Yemen',
					'ZM'  => 'Zambia',
					'ZW'  => 'Zimbabwe',
				],
				'conditions'  => [
					[
						'name'     => 'field_type',
						'operator' => '==',
						'value'    => 'address_autocomplete',
					],
				],
			]
		);

		$this->add_control(
			'google_maps_lat',
			[
				'label'       => __( 'Latitude', 'piotnetforms' ),
				'type'        => 'text',
				'placeholder' => '21.028511',
				'description' => __( 'Latitude and Longitude Finder https://www.latlong.net/', 'piotnetforms' ),
				'default'     => '21.028511',
				'condition'   => [
					'field_type'   => 'address_autocomplete',
					'google_maps!' => '',
				],

			]
		);

		$this->add_control(
			'google_maps_lng',
			[
				'label'       => __( 'Longitude', 'piotnetforms' ),
				'type'        => 'text',
				'placeholder' => '105.804817',
				'description' => __( 'Latitude and Longitude Finder https://www.latlong.net/', 'piotnetforms' ),
				'default'     => '105.804817',
				'separator'   => true,
				'condition'   => [
					'field_type'   => 'address_autocomplete',
					'google_maps!' => '',
				],

			]
		);

		$this->add_control(
			'google_maps_zoom',
			[
				'label'     => __( 'Zoom', 'piotnetforms' ),
				'type'      => 'slider',
				'default'   => [
					'size' => 15,
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 25,
					],
				],
				'condition' => [
					'field_type'   => 'address_autocomplete',
					'google_maps!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'google_maps_height',
			[
				'label'      => __( 'Height', 'piotnetforms' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 200,
				],
				'selectors'  => [
					'{{WRAPPER}} .piotnetforms-address-autocomplete-map' => 'height:{{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'field_type'   => 'address_autocomplete',
					'google_maps!' => '',
				],
			]
		);

		$this->add_control(
			'signature_clear_text',
			[
				'label'     => __( 'Clear Text', 'piotnetforms' ),
				'type'      => 'text',
				'default'   => __( 'Clear', 'piotnetforms' ),
				'condition' => [
					'field_type' => 'signature',
				],
			]
		);

		$this->add_responsive_control(
			'signature_max_width',
			[
				'label'      => __( 'Max Width', 'piotnetforms' ),
				'type'       => 'slider',
				'size_units' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 400,
				],
				'selectors'  => [
					'{{WRAPPER}} canvas' => 'max-width:{{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'field_type' => 'signature',
				],
			]
		);

		$this->add_responsive_control(
			'signature_height',
			[
				'label'      => __( 'Height', 'piotnetforms' ),
				'type'       => 'slider',
				'size_units' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 200,
				],
				'selectors'  => [
					'{{WRAPPER}} canvas' => 'height:{{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'field_type' => 'signature',
				],
			]
		);

		$this->add_control(
			'field_label',
			[
				'label'   => __( 'Label', 'piotnetforms' ),
				'type'    => 'text',
				'default' => '',
			]
		);

		$this->add_control(
			'field_label_show',
			[
				'label'        => __( 'Show Label', 'piotnetforms' ),
				'type'         => 'switch',
				'label_on'     => __( 'Show', 'piotnetforms' ),
				'label_off'    => __( 'Hide', 'piotnetforms' ),
				'return_value' => 'true',
				'default'      => 'true',
				'condition'    => [
					'field_type!' => 'html',
				],
			]
		);

		$this->add_control(
			'field_placeholder',
			[
				'label'      => __( 'Placeholder', 'piotnetforms' ),
				'type'       => 'text',
				'default'    => '',
				'conditions' => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'tel',
								'text',
								'email',
								'textarea',
								'number',
								'url',
								'password',
								'select_autocomplete',
								'address_autocomplete',
								'date',
								'time',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'field_icon_enable',
			[
				'label'        => __( 'Icon (Pro)', 'piotnetforms' ),
				'type'         => 'switch',
				'label_on'     => __( 'On', 'piotnetforms' ),
				'label_off'    => __( 'Off', 'piotnetforms' ),
				'return_value' => 'true',
				'default'      => '',
			]
		);

		$this->add_control(
			'field_pattern',
			[
				'label'      => __( 'Pattern', 'piotnetforms' ),
				'type'       => 'text',
				'default'    => '[0-9()#&+*-=.]+',
				'conditions' => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'tel',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'field_pattern_not_tel',
			[
				'label'      => __( 'Pattern', 'piotnetforms' ),
				'type'       => 'text',
				'conditions' => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'text',
								'email',
								'textarea',
								'url',
								'number',
								'password',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'field_autocomplete',
			[
				'label'        => __( 'Autocomplete', 'piotnetforms' ),
				'type'         => 'switch',
				'label_on'     => __( 'On', 'piotnetforms' ),
				'label_off'    => __( 'Off', 'piotnetforms' ),
				'return_value' => 'true',
				'default'      => 'true',
				'condition'    => [
					'field_type!' => 'html',
				],
			]
		);

		$this->add_control(
			'file_sizes',
			[
				'label'       => __( 'Max. File Size', 'piotnetforms' ),
				'type'        => 'select',
				'condition'   => [
					'field_type' => 'upload',
				],
				'options'     => $this->get_upload_file_size_options(),
				'description' => __( 'If you need to increase max upload size please contact your hosting.', 'piotnetforms' ),
			]
		);

		$this->add_control(
			'file_sizes_message',
			[
				'label'       => __( 'Max. File Size Error Message', 'piotnetforms' ),
				'label_block' => true,
				'type'        => 'text',
				'default'     => __( 'File size must be less than 1MB', 'piotnetforms' ),
				'condition'   => [
					'field_type' => 'upload',
				],
			]
		);

		$this->add_control(
			'file_types',
			[
				'label'       => __( 'Allowed File Types', 'piotnetforms' ),
				'type'        => 'text',
				'condition'   => [
					'field_type' => 'upload',
				],
				'description' => __( 'Enter the allowed file types, separated by a comma (jpg, gif, pdf, etc).', 'piotnetforms' ),
			]
		);

		$this->add_control(
			'file_types_message',
			[
				'label'       => __( 'Allowed File Types Error Message', 'piotnetforms' ),
				'label_block' => true,
				'type'        => 'text',
				'default'     => __( 'Please enter a value with a valid mimetype.', 'piotnetforms' ),
				'condition'   => [
					'field_type' => 'upload',
				],
			]
		);

		$this->add_control(
			'allow_multiple_upload',
			[
				'label'        => __( 'Multiple Files', 'piotnetforms' ),
				'type'         => 'switch',
				'return_value' => 'true',
				'conditions'   => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'upload',
								'image_upload',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'max_files',
			[
				'label'     => __( 'Max Files', 'piotnetforms' ),
				'type'      => 'number',
				'condition' => [
					'field_type'            => 'image_upload',
					'allow_multiple_upload' => 'true',
				],
			]
		);

		// $this->add_control(
		// 	'max_files' => [
		// 		'label' => __( 'Max. Files', 'piotnetforms' ),
		// 		'type' => 'number',
		// 		'condition' => [
		// 			'field_type' => 'upload',
		// 			'allow_multiple_upload' => 'yes',
		// 		],
		// 		'tab' => 'content',
		// 		'inner_tab' => 'form_fields_content_tab',
		// 		'tabs_wrapper' => 'form_fields_tabs',
		// 	],
		// );

		$this->add_control(
			'attach_files',
			[
				'label'     => __( 'Attach files to email, not upload to uploads folder', 'piotnetforms' ),
				'type'      => 'switch',
				'condition' => [
					'field_type' => 'upload',
				],
			]
		);

		$this->add_control(
			'field_required',
			[
				'label'        => __( 'Required', 'piotnetforms' ),
				'type'         => 'switch',
				'return_value' => 'true',
				'default'      => '',
				'conditions'   => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => '!in',
							'value'    => [
								'recaptcha',
								'hidden',
								'html',
								'honeypot',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'invalid_message',
			[
				'label'      => __( 'Invalid Message', 'piotnetforms' ),
				'type'       => 'text',
				'conditions' => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => '!in',
							'value'    => [
								'recaptcha',
								'hidden',
								'html',
								'honeypot',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'max_length',
			[
				'label'      => __( 'Max Length', 'piotnetforms' ),
				'type'       => 'number',
				'conditions' => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'text',
								'email',
								'textarea',
								'url',
								'tel',
								'number',
								'password',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'mark_required',
			[
				'label'     => __( 'Required Mark', 'piotnetforms' ),
				'type'      => 'switch',
				'label_on'  => __( 'Show', 'piotnetforms' ),
				'label_off' => __( 'Hide', 'piotnetforms' ),
				'default'   => '',
				'condition' => [
					'field_label!' => '',
				],
			]
		);

		$this->add_control(
			'field_options',
			[
				'label'       => __( 'Options', 'piotnetforms' ),
				'type'        => 'textarea',
				'default'     => '',
				'dynamic'     => [
					'active' => true,
				],
				'description' => __( 'Enter each option in a separate line. To differentiate between label and value, separate them with a pipe char ("|"). For example: First Name|f_name.<br>Select option group:<br>[optgroup label="Swedish Cars"]<br>Volvo|volvo<br>Saab|saab<br>[/optgroup]<br>[optgroup label="German Cars"]<br>Mercedes|mercedes<br>Audi|audi<br>[/optgroup]', 'piotnetforms' ),
				'conditions'  => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'select',
								'select_autocomplete',
								'image_select',
								'checkbox',
								'radio',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'send_data_by_label',
			[
				'label'        => __( 'Send data by Label', 'piotnetforms' ),
				'type'         => 'switch',
				'return_value' => 'true',
				'conditions'   => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'select',
								'image_select',
								'terms_select',
								'checkbox',
								'radio',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'payment_methods_select_field_enable',
			[
				'label'        => __( 'Payment Methods Select Field', 'piotnetforms' ),
				'type'         => 'switch',
				'default'      => '',
				'description'  => __( 'If you have multiple payment methods', 'piotnetforms' ),
				'label_on'     => 'Yes',
				'label_off'    => 'No',
				'return_value' => 'yes',
				'conditions'   => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'select',
								'image_select',
								'terms_select',
								'checkbox',
								'radio',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'payment_methods_select_field_value_for_stripe',
			[
				'label'       => __( 'Payment Methods Field Value For Stripe', 'piotnetforms' ),
				'label_block' => true,
				'type'        => 'text',
				'description' => 'E.g Stripe',
				'conditions'  => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'select',
								'image_select',
								'terms_select',
								'checkbox',
								'radio',
							],
						],
						[
							'name'     => 'payment_methods_select_field_enable',
							'operator' => '==',
							'value'    => 'yes',
						],
					],
				],
			]
		);

		$this->add_control(
			'payment_methods_select_field_value_for_paypal',
			[
				'label'       => __( 'Payment Methods Field Value For Paypal', 'piotnetforms' ),
				'label_block' => true,
				'type'        => 'text',
				'description' => 'E.g Paypal',
				'conditions'  => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'select',
								'image_select',
								'terms_select',
								'checkbox',
								'radio',
							],
						],
						[
							'name'     => 'payment_methods_select_field_enable',
							'operator' => '=',
							'value'    => 'yes',
						],
					],
				],
			]
		);

		$this->add_control(
			'remove_this_field_from_repeater',
			[
				'label'        => __( 'Remove this field from Repeater', 'piotnetforms' ),
				'type'         => 'switch',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'field_taxonomy_slug',
			[
				'label'       => __( 'Taxonomy Slug', 'piotnetforms' ),
				'label_block' => true,
				'type'        => 'text',
				'default'     => __( 'category', 'piotnetforms' ),
				'description' => __( 'E.g: category, post_tag', 'piotnetforms' ),
				'condition'   => [
					'field_type' => 'terms_select',
				],
			]
		);

		$this->add_control(
			'terms_select_type',
			[
				'label'       => __( 'Terms Select Type', 'piotnetforms' ),
				'label_block' => true,
				'type'        => 'select',
				'default'     => 'select',
				'options'     => [
					'select'   => __( 'Select', 'piotnetforms' ),
					'checkbox' => __( 'Checkbox', 'piotnetforms' ),
					'radio'    => __( 'Radio', 'piotnetforms' ),
				],
				'condition'   => [
					'field_type' => 'terms_select',
				],
			]
		);

		$this->add_control(
			'allow_multiple',
			[
				'label'        => __( 'Multiple Selection', 'piotnetforms' ),
				'type'         => 'switch',
				'return_value' => 'true',
				'conditions'   => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'select',
								'image_select',
								'terms_select',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'limit_multiple',
			[
				'label'     => __( 'Limit Multiple Selects', 'piotnetforms' ),
				'type'      => 'number',
				'default'   => 0,
				'condition' => [
					'field_type'     => 'image_select',
					'allow_multiple' => 'true',
				],
			]
		);

		$this->add_control(
			'select_size',
			[
				'label'      => __( 'Rows', 'piotnetforms' ),
				'type'       => 'number',
				'min'        => 2,
				'step'       => 1,
				'conditions' => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'select',
								'select_autocomplete',
								'terms_select',
								'image_select',
							],
						],
						[
							'name'  => 'allow_multiple',
							'value' => 'true',
						],
					],
				],
			]
		);

		$this->add_control(
			'inline_list',
			[
				'label'        => __( 'Inline List', 'piotnetforms' ),
				'type'         => 'switch',
				'return_value' => 'piotnetforms-subgroup-inline',
				'default'      => '',
				'conditions'   => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'checkbox',
								'radio',
								'terms_select',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'field_html',
			[
				'label'      => __( 'HTML', 'piotnetforms' ),
				'type'       => 'textarea',
				'dynamic'    => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'field_type',
							'value' => 'html',
						],
					],
				],
			]
		);

		$this->add_control(
			'rows',
			[
				'label'      => __( 'Rows', 'piotnetforms' ),
				'type'       => 'number',
				'default'    => 4,
				'conditions' => [
					'terms' => [
						[
							'name'  => 'field_type',
							'value' => 'textarea',
						],
					],
				],
			]
		);

		$this->add_control(
			'recaptcha_size',
			[
				'label'      => __( 'Size', 'piotnetforms' ),
				'type'       => 'select',
				'default'    => 'normal',
				'options'    => [
					'normal'  => __( 'Normal', 'piotnetforms' ),
					'compact' => __( 'Compact', 'piotnetforms' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'field_type',
							'value' => 'recaptcha',
						],
					],
				],
			]
		);

		$this->add_control(
			'recaptcha_style',
			[
				'label'      => __( 'Style', 'piotnetforms' ),
				'type'       => 'select',
				'default'    => 'light',
				'options'    => [
					'light' => __( 'Light', 'piotnetforms' ),
					'dark'  => __( 'Dark', 'piotnetforms' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name'  => 'field_type',
							'value' => 'recaptcha',
						],
					],
				],
			]
		);

		// $this->add_control(
		// 	'css_classes',
		// 	[
		// 		'label' => __( 'CSS Classes', 'piotnetforms' ),
		// 		'type' => \Elementor\Controls_Manager::HIDDEN,
		// 		'default' => '',
		// 		'title' => __( 'Add your custom class WITHOUT the dot. e.g: my-class', 'piotnetforms' ),
		// 	]
		// );

		$this->add_control(
			'field_value',
			[
				'label'      => __( 'Default Value', 'piotnetforms' ),
				'type'       => 'text',
				'default'    => '',
				'dynamic'    => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'text',
								'email',
								'textarea',
								'url',
								'tel',
								'radio',
								'checkbox',
								'select',
								'select_autocomplete',
								'terms_select',
								'image_select',
								'number',
								'date',
								'time',
								'hidden',
								'address_autocomplete',
								'color',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'field_value_color_note',
			[
				'type'       => 'html',
				'class'      => 'piotnetforms-control-field-description',
				'raw'        => __( 'E.g: #000000. The value must be in seven-character hexadecimal notation.', 'piotnetforms' ),
				'conditions' => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'color',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'field_min',
			[
				'name'       => 'field_min',
				'label'      => __( 'Min. Value', 'piotnetforms' ),
				'type'       => 'number',
				'conditions' => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'number',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'field_max',
			[
				'label'      => __( 'Max. Value', 'piotnetforms' ),
				'type'       => 'number',
				'conditions' => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'number',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'number_spiner',
			[
				'label'      => __( 'Add (-/+) button (Pro)', 'piotnetforms' ),
				'type'       => 'switch',
				'default'    => '',
				'label_on'   => 'Yes',
				'label_off'  => 'No',
				'conditions' => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'number',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'acceptance_text',
			[
				'label'      => __( 'Acceptance Text', 'piotnetforms' ),
				'type'       => 'textarea',
				'conditions' => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'acceptance',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'checked_by_default',
			[
				'label'      => __( 'Checked by Default', 'piotnetforms' ),
				'type'       => 'switch',
				'conditions' => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'acceptance',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'flatpickr_custom_options_enable',
			[
				'label'        => __( 'Flatpickr Custom Options', 'piotnetforms' ),
				'type'         => 'switch',
				'label_on'     => __( 'Yes', 'piotnetforms' ),
				'label_off'    => __( 'No', 'piotnetforms' ),
				'return_value' => 'yes',
				'description'  => 'https://flatpickr.js.org/examples/',
				'default'      => '',
				'conditions'   => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'date',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'flatpickr_custom_options',
			[
				'label'      => __( 'Flatpickr Options', 'piotnetforms' ),
				'type'       => 'textarea',
				'conditions' => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'date',
							],
						],
						[
							'name'     => 'flatpickr_custom_options_enable',
							'operator' => '==',
							'value'    => 'yes',
						],
					],
				],
			]
		);

		$this->add_control(
			'date_range',
			[
				'label'        => __( 'Date Range', 'piotnetforms' ),
				'type'         => 'switch',
				'label_on'     => __( 'Yes', 'piotnetforms' ),
				'label_off'    => __( 'No', 'piotnetforms' ),
				'return_value' => 'true',
				'default'      => '',
				'conditions'   => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'date',
							],
						],
						[
							'name'     => 'flatpickr_custom_options_enable',
							'operator' => '==',
							'value'    => '',
						],
					],
				],
			]
		);

		$this->add_control(
			'min_date',
			[
				'label'          => __( 'Min. Date', 'piotnetforms' ),
				'type'           => 'date',
				'label_block'    => false,
				'picker_options' => [
					'enableTime' => false,
				],
				'conditions'     => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'date',
							],
						],
						[
							'name'     => 'flatpickr_custom_options_enable',
							'operator' => '==',
							'value'    => '',
						],
					],
				],
			]
		);

		$this->add_control(
			'min_date_current',
			[
				'label'        => __( 'Set Current Date for Min. Date', 'piotnetforms' ),
				'type'         => 'switch',
				'default'      => '',
				'label_on'     => 'Yes',
				'label_off'    => 'No',
				'return_value' => 'yes',
				'conditions'   => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'date',
							],
						],
						[
							'name'     => 'flatpickr_custom_options_enable',
							'operator' => '==',
							'value'    => '',
						],
					],
				],
			]
		);

		$this->add_control(
			'max_date',
			[
				'name'           => 'max_date',
				'label'          => __( 'Max. Date', 'piotnetforms' ),
				'type'           => 'date',
				'label_block'    => false,
				'picker_options' => [
					'enableTime' => false,
				],
				'conditions'     => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'date',
							],
						],
						[
							'name'     => 'flatpickr_custom_options_enable',
							'operator' => '==',
							'value'    => '',
						],
					],
				],
			]
		);

		$this->add_control(
			'max_date_current',
			[
				'label'        => __( 'Set Current Date for Max. Date', 'piotnetforms' ),
				'type'         => 'switch',
				'default'      => '',
				'label_on'     => 'Yes',
				'label_off'    => 'No',
				'return_value' => 'yes',
				'conditions'   => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'date',
							],
						],
						[
							'name'     => 'flatpickr_custom_options_enable',
							'operator' => '==',
							'value'    => '',
						],
					],
				],
			]
		);

		$date_format = esc_attr( get_option( 'date_format' ) );

		$this->add_control(
			'date_format',
			[
				'label'       => __( 'Date Format', 'piotnetforms' ),
				'type'        => 'text',
				'label_block' => false,
				'default'     => $date_format,
				'dynamic'     => [
					'active' => true,
				],
				'conditions'  => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'date',
							],
						],
						[
							'name'     => 'flatpickr_custom_options_enable',
							'operator' => '==',
							'value'    => '',
						],
					],
				],
			]
		);

		$this->add_control(
			'date_language',
			[
				'label'       => __( 'Date Language', 'piotnetforms' ),
				'type'        => 'select',
				'label_block' => false,
				'description' => __( 'This feature only works on the frontend.', 'piotnetforms' ),
				'options'     => [
					'ar'      => 'Arabic',
					'at'      => 'Austria',
					'az'      => 'Azerbaijan',
					'be'      => 'Belarusian',
					'bg'      => 'Bulgarian',
					'bn'      => 'Bangla',
					'bs'      => 'Bosnian',
					'cat'     => 'Catalan',
					'cs'      => 'Czech',
					'cy'      => 'Welsh',
					'da'      => 'Danish',
					'de'      => 'German',
					'english' => 'English',
					'eo'      => 'Esperanto',
					'es'      => 'Spanish',
					'et'      => 'Estonian',
					'fa'      => 'Persian',
					'fi'      => 'Finnish',
					'fo'      => 'Faroese',
					'fr'      => 'French',
					'ga'      => 'Irish',
					'gr'      => 'Greek',
					'he'      => 'Hebrew',
					'hi'      => 'Hindi',
					'hr'      => 'Croatian',
					'hu'      => 'Hungarian',
					'id'      => 'Indonesian',
					'is'      => 'Icelandic',
					'it'      => 'Italian',
					'ja'      => 'Japanese',
					'ka'      => 'Georgian',
					'km'      => 'Khmer',
					'ko'      => 'Korean',
					'kz'      => 'Kazakh',
					'lt'      => 'Lithuanian',
					'lv'      => 'Latvian',
					'mk'      => 'Macedonian',
					'mn'      => 'Mongolian',
					'ms'      => 'Malaysian',
					'my'      => 'Burmese',
					'nl'      => 'Dutch',
					'no'      => 'Norwegian',
					'pa'      => 'Punjabi',
					'pl'      => 'Polish',
					'pt'      => 'Portuguese',
					'ro'      => 'Romanian',
					'ru'      => 'Russian',
					'si'      => 'Sinhala',
					'sk'      => 'Slovak',
					'sl'      => 'Slovenian',
					'sq'      => 'Albanian',
					'sr-cyr'  => 'SerbianCyrillic',
					'sr'      => 'Serbian',
					'sv'      => 'Swedish',
					'th'      => 'Thai',
					'tr'      => 'Turkish',
					'uk'      => 'Ukrainian',
					'vn'      => 'Vietnamese',
					'zh-tw'   => 'MandarinTraditional',
					'zh'      => 'Mandarin',
				],
				'default'     => 'english',
				'conditions'  => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'date',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'use_native_date',
			[
				'label'      => __( 'Native HTML5', 'piotnetforms' ),
				'type'       => 'switch',
				'conditions' => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'date',
							],
						],
					],
				],
			]
		);

		$time_format = esc_attr( get_option( 'time_format' ) );

		$this->add_control(
			'time_format',
			[
				'label'       => __( 'Time Format', 'piotnetforms' ),
				'type'        => 'text',
				'label_block' => false,
				'default'     => $time_format,
				'dynamic'     => [
					'active' => true,
				],
				'conditions'  => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'time',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'time_24hr',
			[
				'label'        => __( '24 hour', 'piotnetforms' ),
				'type'         => 'switch',
				'default'      => '',
				'label_on'     => 'Yes',
				'label_off'    => 'No',
				'return_value' => 'yes',
				'conditions'   => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'time',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'use_native_time',
			[
				'label'      => __( 'Native HTML5', 'piotnetforms' ),
				'type'       => 'switch',
				'conditions' => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'time',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'piotnetforms_range_slider_field_options',
			[
				'label'       => __( 'Range Slider Options', 'piotnetforms' ),
				'label_block' => true,
				'type'        => 'textarea',
				'default'     => 'skin: "round", type: "double", grid: true, min: 0, max: 1000, from: 200, to: 800, prefix: "$"',
				'description' => 'Demo: <a href="http://ionden.com/a/plugins/ion.rangeSlider/demo.html" target="_blank">http://ionden.com/a/plugins/ion.rangeSlider/demo.html</a>',
				'condition'   => [
					'field_type' => 'range_slider',
				],
			]
		);

		// $element->add_group_control(
		// 	\Elementor\Group_Control_Typography::get_type(),
		// 	[
		// 		'name' => 'piotnetforms_calculated_fields_form_typography',
		// 		'label' => __( 'Typography', 'piotnetforms' ),
		// 		'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
		// 		'selector' => '{{WRAPPER}} .piotnetforms-calculated-fields-form',
		// 	]
		// );

		$this->new_group_controls();
		$this->add_control(
			'piotnetforms_coupon_code',
			[
				'label' => __( 'Coupon Code', 'piotnetforms' ),
				'type'  => 'text',
			]
		);
		$this->add_control(
			'piotnetforms_coupon_code_discount_type',
			[
				'label'   => __( 'Discount Type', 'piotnetforms' ),
				'type'    => 'select',
				'options' => [
					'percentage'  => __( 'Percentage', 'piotnetforms' ),
					'flat_amount' => __( 'Flat Amount', 'piotnetforms' ),
				],
				'default' => 'percentage',
			]
		);
		$this->add_control(
			'piotnetforms_coupon_code_coupon_amount',
			[
				'label' => __( 'Coupon Amount', 'piotnetforms' ),
				'type'  => 'text',
			]
		);
        $this->add_control(
            'repeater_id',
            [
                'type' => 'hidden',
            ],
            [
                'overwrite' => 'true',
            ]
        );
		$repeater_items = $this->get_group_controls();

		$this->new_group_controls();
		$this->add_control(
			'',
			[
				'type'           => 'repeater-item',
				'remove_label'   => __( 'Remove Item', 'piotnetforms' ),
				'controls'       => $repeater_items,
				'controls_query' => '.piotnet-control-repeater-field',
			]
		);

		$repeater_list = $this->get_group_controls();
		$this->add_control(
			'piotnetforms_coupon_code_list',
			[
				'type'           => 'repeater',
				'label'          => __( 'Coupon Code', 'piotnetforms' ),
				'label_block'    => true,
				'add_label'      => __( 'Add Item', 'piotnetforms' ),
				'controls'       => $repeater_list,
				'controls_query' => '.piotnet-control-repeater-list',
				'condition'      => [
					'field_type' => 'coupon_code',
				],
			]
		);

		$this->add_control(
			'piotnetforms_calculated_fields_form_distance_calculation',
			[
				'label'        => __( 'Distance Calculation', 'piotnetforms' ),
				'type'         => 'switch',
				'default'      => '',
				'label_on'     => 'Yes',
				'label_off'    => 'No',
				'return_value' => 'yes',
				'condition'    => [
					'field_type' => 'calculated_fields',
				],
			]
		);

		$this->add_control(
			'piotnetforms_calculated_fields_form_distance_calculation_from_specific_location_enable',
			[
				'label'        => __( 'From Specific Location', 'piotnetforms' ),
				'type'         => 'switch',
				'default'      => '',
				'label_on'     => 'Yes',
				'label_off'    => 'No',
				'return_value' => 'yes',
				'condition'    => [
					'field_type' => 'calculated_fields',
				],
			]
		);

		$this->add_control(
			'piotnetforms_calculated_fields_form_distance_calculation_from_specific_location',
			[
				'label'       => __( 'From Location', 'piotnetforms' ),
				'label_block' => true,
				'type'        => 'text',
				'description' => __( 'Please go to https://www.google.com/maps and type your address to get exactly location', 'piotnetforms' ),
				'condition'   => [
					'field_type' => 'calculated_fields',
					'piotnetforms_calculated_fields_form_distance_calculation' => 'yes',
					'piotnetforms_calculated_fields_form_distance_calculation_from_specific_location_enable' => 'yes',
				],
			]
		);

		$this->add_control(
			'piotnetforms_calculated_fields_form_distance_calculation_from_field_shortcode',
			[
				'label'       => __( 'From Field Shortcode', 'piotnetforms' ),
				'label_block' => true,
				'type'        => 'text',
				'description' => __( 'E.g [field id="from"]', 'piotnetforms' ),
				'condition'   => [
					'field_type' => 'calculated_fields',
					'piotnetforms_calculated_fields_form_distance_calculation' => 'yes',
					'piotnetforms_calculated_fields_form_distance_calculation_from_specific_location_enable!' => 'yes',
				],
			]
		);

		$this->add_control(
			'piotnetforms_calculated_fields_form_distance_calculation_to_specific_location_enable',
			[
				'label'        => __( 'To Specific Location', 'piotnetforms' ),
				'type'         => 'switch',
				'default'      => '',
				'label_on'     => 'Yes',
				'label_off'    => 'No',
				'return_value' => 'yes',
				'condition'    => [
					'field_type' => 'calculated_fields',
				],
			]
		);

		$this->add_control(
			'piotnetforms_calculated_fields_form_distance_calculation_to_specific_location',
			[
				'label'       => __( 'To Location', 'piotnetforms' ),
				'label_block' => true,
				'type'        => 'text',
				'description' => __( 'Please go to https://www.google.com/maps and type your address to get exactly location', 'piotnetforms' ),
				'condition'   => [
					'field_type' => 'calculated_fields',
					'piotnetforms_calculated_fields_form_distance_calculation' => 'yes',
					'piotnetforms_calculated_fields_form_distance_calculation_to_specific_location_enable' => 'yes',
				],
			]
		);

		$this->add_control(
			'piotnetforms_calculated_fields_form_distance_calculation_to_field_shortcode',
			[
				'label'       => __( 'To Field Shortcode', 'piotnetforms' ),
				'label_block' => true,
				'type'        => 'text',
				'description' => __( 'E.g [field id="to"]', 'piotnetforms' ),
				'condition'   => [
					'field_type' => 'calculated_fields',
					'piotnetforms_calculated_fields_form_distance_calculation' => 'yes',
					'piotnetforms_calculated_fields_form_distance_calculation_to_specific_location_enable!' => 'yes',
				],
			]
		);

		$this->add_control(
			'piotnetforms_calculated_fields_form_distance_calculation_unit',
			[
				'label'       => __( 'Distance Unit', 'piotnetforms' ),
				'label_block' => true,
				'type'        => 'select',
				'options'     => [
					'km'   => 'Kilometer',
					'mile' => 'Mile',
				],
				'default'     => 'km',
				'condition'   => [
					'field_type' => 'calculated_fields',
					'piotnetforms_calculated_fields_form_distance_calculation' => 'yes',
				],
			]
		);

		$this->add_control(
			'piotnetforms_calculated_fields_form_calculation',
			[
				'label'       => __( 'Calculation', 'piotnetforms' ),
				'label_block' => true,
				'type'        => 'text',
				'description' => __( 'E.g [field id="quantity"]*[field id="price"]+10', 'piotnetforms' ),
				'condition'   => [
					'field_type' => 'calculated_fields',
					'piotnetforms_calculated_fields_form_distance_calculation!' => 'yes',
				],
			]
		);

		$this->add_control(
			'piotnetforms_calculated_fields_form_coupon_code',
			[
				'label'       => __( 'Coupon Code Field Shortcode', 'piotnetforms' ),
				'label_block' => true,
				'type'        => 'text',
				'description' => __( 'E.g [field id="coupon_code"]', 'piotnetforms' ),
				'condition'   => [
					'field_type' => 'calculated_fields',
				],
			]
		);

		$this->add_control(
			'piotnetforms_calculated_fields_form_calculation_rounding_decimals',
			[
				'label'     => __( 'Rounding Decimals', 'piotnetforms' ),
				'type'      => 'number',
				'default'   => 2,
				'condition' => [
					'field_type' => 'calculated_fields',
				],
			]
		);

		$this->add_control(
			'piotnetforms_calculated_fields_form_calculation_rounding_decimals_show',
			[
				'label'        => __( 'Always show decimal places', 'piotnetforms' ),
				'type'         => 'switch',
				'default'      => '',
				'label_on'     => 'Yes',
				'label_off'    => 'No',
				'return_value' => 'yes',
				'condition'    => [
					'field_type' => 'calculated_fields',
				],
			]
		);

		$this->add_control(
			'piotnetforms_calculated_fields_form_calculation_rounding_decimals_decimals_symbol',
			[
				'label'     => __( 'Decimal point character', 'piotnetforms' ),
				'type'      => 'text',
				'default'   => '.',
				'condition' => [
					'field_type' => 'calculated_fields',
				],
			]
		);

		$this->add_control(
			'piotnetforms_calculated_fields_form_calculation_rounding_decimals_seperators_symbol',
			[
				'label'     => __( 'Separator character', 'piotnetforms' ),
				'type'      => 'text',
				'default'   => ',',
				'condition' => [
					'field_type' => 'calculated_fields',
				],
			]
		);

		$this->add_control(
			'piotnetforms_calculated_fields_form_before',
			[
				'label'       => __( 'Before Content', 'piotnetforms' ),
				'label_block' => true,
				'type'        => 'text',
				'description' => __( 'E.g $', 'piotnetforms' ),
				'condition'   => [
					'field_type' => 'calculated_fields',
				],
			]
		);

		$this->add_control(
			'piotnetforms_calculated_fields_form_after',
			[
				'label'       => __( 'After Content', 'piotnetforms' ),
				'label_block' => true,
				'type'        => 'text',
				'description' => __( 'E.g $', 'piotnetforms' ),
				'condition'   => [
					'field_type' => 'calculated_fields',
				],
			]
		);

		$this->add_control(
			'piotnetforms_image_select_field_gallery',
			[
				'label'     => __( 'Add Images', 'piotnetforms' ),
				'type'      => 'gallery',
				'default'   => [],
				'condition' => [
					'field_type' => 'image_select',
				],
			]
		);

		$this->add_control(
			'multi_step_form_autonext',
			[
				'label'        => __( 'Automatically move to the next step after selecting - Multi Step Form', 'piotnetforms' ),
				'type'         => 'switch',
				'default'      => '',
				'label_on'     => 'Yes',
				'label_off'    => 'No',
				'return_value' => 'yes',
				'conditions'   => [
					'terms' => [
						[
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => [
								'select',
								'select_autocomplete',
								'image_select',
								'checkbox',
								'radio',
							],
						],
					],
				],
			]
		);

	}

	public function add_input_mask_controls() {

		$this->add_control(
			'input_mask_enable',
			[
				'label'        => __( 'Enable (Pro)', 'piotnetforms' ),
				'type'         => 'switch',
				'label_on'     => 'Yes',
				'label_off'    => 'No',
				'return_value' => 'yes',
			]
		);
	}

	private function add_checkbox_style_controls() {
		$this->add_control(
			'piotnetforms_style_checkbox_type',
			[
				'label'   => __( 'Style', 'piotnetforms' ),
				'type'    => 'select',
				'options' => [
					'native' => __( 'Native', 'piotnetforms' ),
					'square' => __( 'Square', 'piotnetforms' ),
				],
				'default' => 'native',
			]
		);

		$this->add_control(
			'piotnetforms_style_checkbox_square_size',
			[
				'label'      => __( 'Size', 'piotnetforms' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors'  => [
					'{{WRAPPER}} span.piotnetforms-field-option' => 'position: relative;',
					'{{WRAPPER}} span.piotnetforms-field-option input[type="checkbox"]' => 'position: absolute; top: 50%; left: 0px; transform: translateY(-50%); opacity: 0; z-index: 9;',
					'{{WRAPPER}} span.piotnetforms-field-option label' => 'display: block !important; cursor: pointer; margin: 0 auto; padding: 0px 0px 0px 30px;',
					'{{WRAPPER}} span.piotnetforms-field-option label:before' => 'content: ""; display: block; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; position: absolute; top: 50%; left: 0px; transform: translateY(-50%); background: #fff; border-style: solid; border-width: 1px;',
				],
				'condition'  => [
					'piotnetforms_style_checkbox_type' => 'square',
				],
			]
		);

		$this->add_control(
			'piotnetforms_style_checkbox_square_border_width',
			[
				'label'      => __( 'Border Width', 'piotnetforms' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 10,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors'  => [
					'{{WRAPPER}} span.piotnetforms-field-option label:before' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'piotnetforms_style_checkbox_type' => 'square',
				],
			]
		);

		$this->add_control(
			'piotnetforms_style_checkbox_square_border_color',
			[
				'label'     => __( 'Border Color', 'piotnetforms' ),
				'type'      => 'color',
				'default'   => '#23a455',
				'selectors' => [
					'{{WRAPPER}} span.piotnetforms-field-option label:before' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'piotnetforms_style_checkbox_type' => 'square',
				],
			]
		);

		$this->add_control(
			'piotnetforms_style_checkbox_square_background_color',
			[
				'label'     => __( 'Checked Background Color', 'piotnetforms' ),
				'type'      => 'color',
				'default'   => '#23a455',
				'selectors' => [
					'{{WRAPPER}} span.piotnetforms-field-option input[type="checkbox"]:checked ~ label:before' => 'background: {{VALUE}};',
				],
				'condition' => [
					'piotnetforms_style_checkbox_type' => 'square',
				],
			]
		);

		$this->add_control(
			'piotnetforms_style_checkbox_square_spacing',
			[
				'label'      => __( 'Spacing', 'piotnetforms' ),
				'type'       => 'slider',
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors'  => [
					'{{WRAPPER}} span.piotnetforms-field-option label' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'piotnetforms_style_checkbox_type' => 'square',
				],
			]
		);
	}

	private function add_number_style_controls() {
	}
	
	private function add_image_select_style_controls() {

		$this->add_text_typography_controls(
			'piotnetforms_image_select_field_typography',
			[
				'selectors' => '{{WRAPPER}} .image_picker_selector .thumbnail p',
			]
		);

		$this->add_responsive_control(
			'piotnetforms_image_select_field_text_align',
			[
				'type'        => 'select',
				'label'       => __( 'Text Align', 'piotnetforms' ),
				'label_block' => true,
				'value'       => '',
				'options'     => [
					''       => __( 'Default', 'piotnetforms' ),
					'left'   => __( 'Left', 'piotnetforms' ),
					'center' => __( 'Center', 'piotnetforms' ),
					'right'  => __( 'Right', 'piotnetforms' ),
				],
				'selectors'   => [
					'{{WRAPPER}} .image_picker_selector .thumbnail p' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'piotnetforms_image_select_field_item_width',
			[
				'label'     => __( 'Item Width (%)', 'piotnetforms' ),
				'type'      => 'number',
				'default'   => 25,
				'min'       => 1,
				'max'       => 100,
				'selectors' => [
					'{{WRAPPER}} ul.thumbnails.image_picker_selector li' => 'width: {{VALUE}}% !important;',
				],
			]
		);

		$columns_margin  = is_rtl() ? '-{{SIZE}}{{UNIT}} -{{SIZE}}{{UNIT}} -{{SIZE}}{{UNIT}} -{{SIZE}}{{UNIT}};' : '-{{SIZE}}{{UNIT}} -{{SIZE}}{{UNIT}} -{{SIZE}}{{UNIT}} -{{SIZE}}{{UNIT}};';
		$columns_padding = is_rtl() ? '{{SIZE}}{{UNIT}} !important;' : '{{SIZE}}{{UNIT}} !important;';

		$this->add_responsive_control(
			'piotnetforms_image_select_field_item_spacing',
			[
				'label'      => __( 'Item Spacing', 'piotnetforms' ),
				'type'       => 'slider',
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors'  => [
					'{{WRAPPER}} ul.thumbnails.image_picker_selector li' => 'padding:' . $columns_padding,
					'{{WRAPPER}} ul.thumbnails.image_picker_selector' => 'margin: ' . $columns_margin,
				],
			]
		);

		$this->add_responsive_control(
			'piotnetforms_image_select_field_item_border_radius',
			[
				'label'      => __( 'Item Border Radius', 'piotnetforms' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} ul.thumbnails.image_picker_selector .thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'piotnetforms_image_select_field_image_border_radius',
			[
				'label'      => __( 'Image Border Radius', 'piotnetforms' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} ul.thumbnails.image_picker_selector .image_picker_image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'piotnetforms_image_select_field_image_padding',
			[
				'label'      => __( 'Input Padding', 'piotnetforms' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .image_picker_image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'piotnetforms_image_select_field_label_padding',
			[
				'label'      => __( 'Input Padding', 'piotnetforms' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} ul.thumbnails p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'piotnetforms_image_select_field_normal_active',
			[
				'type' => 'heading-tab',
				'tabs' => [
					[
						'name'   => 'piotnetforms_image_select_field_normal',
						'title'  => __( 'NORMAL', 'piotnetforms' ),
						'active' => true,
					],
					[
						'name'  => 'piotnetforms_image_select_field_active',
						'title' => __( 'ACTIVE', 'piotnetforms' ),
					],
				],
			]
		);

		$normal_controls = $this->add_image_select_thumbnail_style(
			'normal',
			[
				'selectors' => '{{WRAPPER}} ul.thumbnails.image_picker_selector .thumbnail',
			]
		);
		$this->add_control(
			'piotnetforms_image_select_field_normal',
			[
				'type'           => 'content-tab',
				'label'          => __( 'Normal', 'piotnetforms' ),
				'value'          => '',
				'active'         => true,
				'controls'       => $normal_controls,
				'controls_query' => '.piotnet-start-controls-tab',
			]
		);

		$active_controls = $this->add_image_select_thumbnail_style(
			'active',
			[
				'selectors' => '{{WRAPPER}} ul.thumbnails.image_picker_selector .thumbnail.selected',
			]
		);
		$this->add_control(
			'piotnetforms_image_select_field_active',
			[
				'type'           => 'content-tab',
				'label'          => __( 'Active', 'piotnetforms' ),
				'value'          => '',
				'controls'       => $active_controls,
				'controls_query' => '.piotnet-start-controls-tab',
			]
		);

	}

	private function add_image_select_thumbnail_style( string $name, $args = [] ) {
		$wrapper           = isset( $args['selectors'] ) ? $args['selectors'] : '{{WRAPPER}}';
		$previous_controls = $this->new_group_controls();

		$this->add_control(
			'piotnetforms_image_select_field_border_' . $name,
			[
				'label'     => __( 'Item Border Type', 'piotnetforms' ),
				'type'      => 'select',
				'options'   => [
					''       => __( 'None', 'elementor' ),
					'solid'  => _x( 'Solid', 'Border Control', 'elementor' ),
					'double' => _x( 'Double', 'Border Control', 'elementor' ),
					'dotted' => _x( 'Dotted', 'Border Control', 'elementor' ),
					'dashed' => _x( 'Dashed', 'Border Control', 'elementor' ),
					'groove' => _x( 'Groove', 'Border Control', 'elementor' ),
				],
				'selectors' => [
					$wrapper => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'piotnetforms_image_select_field_border_width_' . $name,
			[
				'label'     => __( 'Item Border Width', 'piotnetforms' ),
				'type'      => 'dimensions',
				'selectors' => [
					$wrapper => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'piotnetforms_image_select_field_border_normal!' => '',
				],
			]
		);

		$this->add_control(
			'piotnetforms_image_select_field_border_color_' . $name,
			[
				'label'     => __( 'Item Border Color', 'piotnetforms' ),
				'type'      => 'color',
				'default'   => '',
				'selectors' => [
					$wrapper => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'piotnetforms_image_select_field_border_normal!' => '',
				],
			]
		);

		$this->add_control(
			'piotnetforms_image_select_field_background_color_' . $name,
			[
				'label'     => __( 'Background Color', 'piotnetforms' ),
				'type'      => 'color',
				'default'   => '',
				'selectors' => [
					$wrapper => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'piotnetforms_image_select_field_text_color_' . $name,
			[
				'label'     => __( 'Text Color', 'piotnetforms' ),
				'type'      => 'color',
				'default'   => '',
				'selectors' => [
					$wrapper . ' p' => 'color: {{VALUE}};',
				],
			]
		);

		return $this->get_group_controls( $previous_controls );
	}

	private function add_conditional_logic_controls() {
	}

	private function add_label_style_controls() {

		$this->add_control(
			'label_spacing',
			[
				'label'     => __( 'Spacing', 'piotnetforms' ),
				'type'      => 'slider',
				'default'   => [
					'size' => '',
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'body.rtl {{WRAPPER}} .piotnetforms-labels-inline .piotnetforms-field-group > label' => 'padding-left: {{SIZE}}{{UNIT}};',
					// for the label position = inline option
					'body:not(.rtl) {{WRAPPER}} .piotnetforms-labels-inline .piotnetforms-field-group > label' => 'padding-right: {{SIZE}}{{UNIT}};',
					// for the label position = inline option
					'body {{WRAPPER}} .piotnetforms-field-group > label' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					// for the label position = above option
				],
			]
		);

		$this->add_responsive_control(
			'label_text_align',
			[
				'type'        => 'select',
				'label'       => __( 'Text Align', 'piotnetforms' ),
				'label_block' => true,
				'value'       => '',
				'options'     => [
					''       => __( 'Default', 'piotnetforms' ),
					'left'   => __( 'Left', 'piotnetforms' ),
					'center' => __( 'Center', 'piotnetforms' ),
					'right'  => __( 'Right', 'piotnetforms' ),
				],
				'selectors'   => [
					'{{WRAPPER}} .piotnetforms-field-label' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label'     => __( 'Text Color', 'piotnetforms' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .piotnetforms-field-group > label, {{WRAPPER}} .piotnetforms-field-subgroup label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mark_required_color',
			[
				'label'     => __( 'Mark Color', 'piotnetforms' ),
				'type'      => 'color',
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .piotnetforms-mark-required .piotnetforms-field-label:after' => 'color: {{COLOR}};',
				],
				'condition' => [
					'mark_required' => 'yes',
				],
			]
		);

		$this->add_text_typography_controls(
			'label_typography',
			[
				'selectors' => '{{WRAPPER}} .piotnetforms-field-group > label',
			]
		);

	}

	private function add_field_style_controls() {

		$this->add_responsive_control(
			'field_text_align',
			[
				'type'        => 'select',
				'label'       => __( 'Text Align', 'piotnetforms' ),
				'label_block' => true,
				'value'       => '',
				'options'     => [
					''       => __( 'Default', 'piotnetforms' ),
					'left'   => __( 'Left', 'piotnetforms' ),
					'center' => __( 'Center', 'piotnetforms' ),
					'right'  => __( 'Right', 'piotnetforms' ),
				],
				'selectors'   => [
					'{{WRAPPER}} .piotnetforms-field-group .piotnetforms-field' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_text_color',
			[
				'label'     => __( 'Text Color', 'piotnetforms' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .piotnetforms-field-group .piotnetforms-field' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_text_typography_controls(
			'field_typography',
			[
				'selectors' => '{{WRAPPER}} .piotnetforms-field-group .piotnetforms-field, {{WRAPPER}} .piotnetforms-field-subgroup label, {{WRAPPER}} .piotnetforms-field-group .piotnetforms-select-wrapper select',
			]
		);

		$this->add_control(
			'field_background_color',
			[
				'label'     => __( 'Background Color', 'piotnetforms' ),
				'type'      => 'color',
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .piotnetforms-field-group:not(.piotnetforms-field-type-upload) .piotnetforms-field:not(.piotnetforms-select-wrapper)' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .piotnetforms-field-group:not(.piotnetforms-field-type-upload) .piotnetforms-field .piotnetforms-field-textual' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .piotnetforms-field-group .piotnetforms-select-wrapper select' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'input_max_width',
			[
				'label'      => __( 'Input Max Width', 'piotnetforms' ),
				'type'       => 'slider',
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1500,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .piotnetforms-field-group .piotnetforms-field:not(.piotnetforms-select-wrapper)' => 'max-width: {{SIZE}}{{UNIT}}!important;',
					'{{WRAPPER}} .piotnetforms-field-group .piotnetforms-field .piotnetforms-field-textual' => 'max-width: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label'      => __( 'Input Padding', 'piotnetforms' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .piotnetforms-field-group:not(.piotnetforms-field-type-upload) .piotnetforms-field:not(.piotnetforms-select-wrapper)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .piotnetforms-field-group:not(.piotnetforms-field-type-upload) .piotnetforms-field .piotnetforms-field-textual' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'field_type!' => 'checkbox',
				],
			]
		);

		$this->add_control(
			'input_placeholder_color',
			[
				'label'     => __( 'Input Placeholder Color', 'piotnetforms' ),
				'type'      => 'color',
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .piotnetforms-field-group:not(.piotnetforms-field-type-upload) .piotnetforms-field:not(.piotnetforms-select-wrapper)::placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .piotnetforms-field-group:not(.piotnetforms-field-type-upload) .piotnetforms-field:not(.piotnetforms-select-wrapper)::-webkit-input-placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .piotnetforms-field-group:not(.piotnetforms-field-type-upload) .piotnetforms-field:not(.piotnetforms-select-wrapper)::-moz-placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .piotnetforms-field-group:not(.piotnetforms-field-type-upload) .piotnetforms-field:not(.piotnetforms-select-wrapper):-ms-input-placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .piotnetforms-field-group:not(.piotnetforms-field-type-upload) .piotnetforms-field:not(.piotnetforms-select-wrapper):-moz-placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .piotnetforms-field-group:not(.piotnetforms-field-type-upload) .piotnetforms-field.piotnetforms-field-textual::placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .piotnetforms-field-group:not(.piotnetforms-field-type-upload) .piotnetforms-field.piotnetforms-field-textual::-webkit-input-placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .piotnetforms-field-group:not(.piotnetforms-field-type-upload) .piotnetforms-field.piotnetforms-field-textual::-moz-placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .piotnetforms-field-group:not(.piotnetforms-field-type-upload) .piotnetforms-field.piotnetforms-field-textual:-ms-input-placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .piotnetforms-field-group:not(.piotnetforms-field-type-upload) .piotnetforms-field.piotnetforms-field-textual:-moz-placeholder' => 'color: {{VALUE}}; opacity: 1;',
				],
			]
		);

		$this->add_control(
			'field_border_type',
			[
				'label'     => _x( 'Border Type', 'Border Control', 'elementor' ),
				'type'      => 'select',
				'options'   => [
					''       => __( 'None', 'elementor' ),
					'solid'  => _x( 'Solid', 'Border Control', 'elementor' ),
					'double' => _x( 'Double', 'Border Control', 'elementor' ),
					'dotted' => _x( 'Dotted', 'Border Control', 'elementor' ),
					'dashed' => _x( 'Dashed', 'Border Control', 'elementor' ),
					'groove' => _x( 'Groove', 'Border Control', 'elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .piotnetforms-field-group:not(.piotnetforms-field-type-upload) .piotnetforms-field:not(.piotnetforms-select-wrapper)' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .piotnetforms-field-group:not(.piotnetforms-field-type-upload) .piotnetforms-field .piotnetforms-field-textual' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .piotnetforms-signature canvas' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'field_border_width',
			[
				'label'     => _x( 'Width', 'Border Control', 'elementor' ),
				'type'      => 'dimensions',
				'selectors' => [
					'{{WRAPPER}} .piotnetforms-field-group:not(.piotnetforms-field-type-upload) .piotnetforms-field:not(.piotnetforms-select-wrapper)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .piotnetforms-field-group:not(.piotnetforms-field-type-upload) .piotnetforms-field .piotnetforms-field-textual' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .piotnetforms-signature canvas' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'field_border_type!' => '',
				],
			]
		);

		$this->add_control(
			'field_border_color',
			[
				'label'     => _x( 'Color', 'Border Control', 'elementor' ),
				'type'      => 'color',
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .piotnetforms-field-group:not(.piotnetforms-field-type-upload) .piotnetforms-field:not(.piotnetforms-select-wrapper)' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .piotnetforms-field-group:not(.piotnetforms-field-type-upload) .piotnetforms-field .piotnetforms-field-textual' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .piotnetforms-signature canvas' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'field_border_type!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'field_border_radius',
			[
				'label'      => __( 'Border Radius', 'piotnetforms' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .piotnetforms-field-group:not(.piotnetforms-field-type-upload) .piotnetforms-field:not(.piotnetforms-select-wrapper)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .piotnetforms-field-group .piotnetforms-select-wrapper select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .piotnetforms-signature canvas' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'field_box_shadow',
			[
				'type'        => 'box-shadow',
				'label'       => __( 'Box Shadow', 'piotnetforms' ),
				'value'       => '',
				'label_block' => false,
				'render_type' => 'none',
				'selectors'   => [
					'{{WRAPPER}} .piotnetforms-field-group:not(.piotnetforms-field-type-upload) .piotnetforms-field:not(.piotnetforms-select-wrapper)' => 'box-shadow: {{VALUE}};',
				],
			]
		);

	}

	protected function make_textarea_field( $item, $item_index, $form_id, $tinymce = false ) {
		$this->add_render_attribute(
			'textarea' . $item_index,
			[
				'class' => [
					'piotnetforms-field-textual',
					'piotnetforms-field',
					'piotnetforms-size-' . $item['input_size'],
				],
				'name'  => $this->get_attribute_name( $item ),
				'id'    => $this->get_attribute_id( $item ),
				'rows'  => $item['rows'],
			]
		);

		if ( $item['field_placeholder'] ) {
			$this->add_render_attribute( 'textarea' . $item_index, 'placeholder', $item['field_placeholder'] );
		}

		if ( $tinymce ) {
			$this->add_render_attribute( 'textarea' . $item_index, 'data-piotnetforms-tinymce' );
		}

		if ( $item['field_required'] ) {
			$this->add_required_attribute( 'textarea' . $item_index );
		}

		if ( ! empty( $item['max_length'] ) ) {
			$this->add_render_attribute( 'textarea' . $i, 'maxlength', $item['max_length'] );
		}

		if ( ! empty( $item['field_pattern_not_tel'] ) ) {
			$this->add_render_attribute( 'textarea' . $i, 'pattern', $item['field_pattern_not_tel'] );
		}

		if ( ! empty( $item['remove_this_field_from_repeater'] ) ) {
			$this->add_render_attribute( 'textarea' . $item_index, 'data-piotnetforms-remove-this-field-from-repeater' );
		}

		$name  = $this->get_field_name_shortcode( $this->get_attribute_name( $item ) );
		$value = $this->get_value_edit_post( $name );

		if ( empty( $value ) ) {
			$value = $item['field_value'];
			$this->add_render_attribute( 'textarea' . $item_index, 'data-piotnetforms-default-value', $item['field_value'] );
		}

		// if ( ! empty( $value ) ) {
		// 	$this->add_render_attribute( 'input' . $i, 'value', $value );
		// }
		// $value = empty( $item['field_value'] ) ? '' : $item['field_value'];

		$this->add_render_attribute( 'textarea' . $item_index, 'data-piotnetforms-id', $form_id );
		return '<textarea ' . $this->get_render_attribute_string( 'textarea' . $item_index ) . '>' . $value . '</textarea>';
	}

	protected function make_select_field( $item, $i, $form_id, $image_select = false, $terms_select = false, $select_autocomplete = false ) {
		$this->add_render_attribute(
			[
				'select-wrapper' . $i => [
					'class' => [
						'piotnetforms-field',
						'piotnetforms-select-wrapper',
					],
				],
				'select' . $i         => [
					'name'  => $this->get_attribute_name( $item ) . ( ! empty( $item['allow_multiple'] ) ? '[]' : '' ),
					'id'    => $this->get_attribute_id( $item ),
					'class' => [
						'piotnetforms-field-textual',
						'piotnetforms-size-' . $item['input_size'],
					],
				],
			]
		);

		if ( $image_select ) {
			$list           = $item['piotnetforms_image_select_field_gallery'];
			$limit_multiple = $item['limit_multiple'];
			if ( ! empty( $list ) ) {
				$this->add_render_attribute(
					[
						'select' . $i => [
							'data-piotnetforms-image-select' => json_encode( $list ),
						],
					]
				);

				if ( ! empty( $limit_multiple ) ) {
					$this->add_render_attribute(
						[
							'select' . $i => [
								'data-piotnetforms-image-select-limit-multiple' => $limit_multiple,
							],
						]
					);
				}
			}
		}

		if ( $item['field_required'] ) {
			$this->add_required_attribute( 'select' . $i );
		}

		if ( $item['allow_multiple'] ) {
			$this->add_render_attribute( 'select' . $i, 'multiple' );
			if ( ! empty( $item['select_size'] ) ) {
				$this->add_render_attribute( 'select' . $i, 'size', $item['select_size'] );
			}
		}

		if ( $item['send_data_by_label'] ) {
			$this->add_render_attribute( 'select' . $i, 'data-piotnetforms-send-data-by-label' );
		}

		if ( ! empty( $item['invalid_message'] ) ) {
			$this->add_render_attribute( 'select' . $i, 'oninvalid', "this.setCustomValidity('" . $item['invalid_message'] . "')" );
			$this->add_render_attribute( 'select' . $i, 'onchange', "this.setCustomValidity('')" );
		}

		if ( ! empty( $item['remove_this_field_from_repeater'] ) ) {
			$this->add_render_attribute( 'select' . $i, 'data-piotnetforms-remove-this-field-from-repeater' );
		}

		if ( ! empty( $item['multi_step_form_autonext'] ) ) {
			$this->add_render_attribute( 'select' . $i, 'data-piotnetforms-multi-step-form-autonext' );
		}

		if ( ! empty( $item['payment_methods_select_field_enable'] ) ) {
			$this->add_render_attribute( 'select' . $i, 'data-piotnetforms-payment-methods-select-field', '' );
			$this->add_render_attribute( 'select' . $i, 'data-piotnetforms-payment-methods-select-field-value-for-stripe', $item['payment_methods_select_field_value_for_stripe'] );
			$this->add_render_attribute( 'select' . $i, 'data-piotnetforms-payment-methods-select-field-value-for-paypal', $item['payment_methods_select_field_value_for_paypal'] );
		}

		$options = preg_split( "/\\r\\n|\\r|\\n/", $item['field_options'] );

		if ( $terms_select ) {
			if ( ! empty( $item['field_taxonomy_slug'] ) ) {
				$terms = get_terms(
					[
						'taxonomy'   => $item['field_taxonomy_slug'],
						'hide_empty' => false,
					]
				);

				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					$options = [];
					foreach ( $terms as $term ) {
						$options[] = $term->name . '|' . $term->slug;
					}
				}
			}
		}

		if ( ! $options ) {
			return '';
		}

		if ( $select_autocomplete ) {
			$this->add_render_attribute(
				[
					'select' . $i => [
						'data-piotnetforms-select-autocomplete' => '',
					],
				]
			);
		}

		ob_start();
		$this->add_render_attribute( 'select' . $i, 'data-piotnetforms-id', $form_id );

		$name  = $this->get_field_name_shortcode( $this->get_attribute_name( $item ) );
		$value = $this->get_value_edit_post( $name );

		if ( empty( $value ) ) {
			$this->add_render_attribute( 'select' . $i, 'data-piotnetforms-default-value', $item['field_value'] );
		}
		?>
		<div <?php echo $this->get_render_attribute_string( 'select-wrapper' . $i ); ?>>
			<select <?php echo $this->get_render_attribute_string( 'select' . $i ); ?> data-options='<?php echo json_encode( $options ); ?>'>
				<?php

				if ( $select_autocomplete && ! empty( $item['field_placeholder'] ) ) {
					array_unshift( $options, $item['field_placeholder'] . '|' . '' );
				}

				foreach ( $options as $key => $option ) {
					$option_id    = $key;
					$option_value = esc_attr( $option );
					$option_label = esc_html( $option );

					if ( false !== strpos( $option, '|' ) ) {
						list( $label, $value ) = explode( '|', $option );
						$option_value          = esc_attr( $value );
						$option_label          = esc_html( $label );
					}

					$this->add_render_attribute( $option_id, 'value', $option_value );

					$name  = $this->get_field_name_shortcode( $this->get_attribute_name( $item ) );
					$value = $this->get_value_edit_post( $name );

					if ( empty( $value ) ) {
						$value = $item['field_value'];
					}

					if ( ! empty( $value ) && $option_value === $value ) {
						$this->add_render_attribute( $option_id, 'selected', 'selected' );
					}

					if ( ! $select_autocomplete ) {
						$values = explode( ',', $value );
					}

					foreach ( $values as $value_item ) {
						if ( $option_value === $value_item ) {
							$this->add_render_attribute( $option_id, 'selected', 'selected' );
						}
					}

					if ( $item['send_data_by_label'] ) {
						$this->add_render_attribute( $option_id, 'data-piotnetforms-send-data-by-label', $option_label );
					}

					if ( ! empty( $item['remove_this_field_from_repeater'] ) ) {
						$this->add_render_attribute( $option_id, 'data-piotnetforms-remove-this-field-from-repeater', $option_label );
					}

					if ( $key == ( count( $options ) - 1 ) && trim( $option_value ) == '' ) {
						# code...
					} else {
						if ( false !== strpos( $option_value, '[optgroup' ) ) {
							$optgroup = str_replace( '&quot;', '', str_replace( ']', '', str_replace( '[optgroup label=', '', $option_value ) ) ); // fix alert ]
							echo '<optgroup label="' . esc_attr( $optgroup ) . '">';
						} elseif ( false !== strpos( $option_value, '[/optgroup]' ) ) {
							echo '</optgroup>';
						} else {
							echo '<option ' . $this->get_render_attribute_string( $option_id ) . '>' . $option_label . '</option>';
						}
					}
				}
				?>
			</select>
		</div>
		<?php

		$select = ob_get_clean();
		return $select;
	}

	protected function make_radio_checkbox_field( $item, $item_index, $type, $form_id, $terms_select = false ) {
		$options = preg_split( "/\\r\\n|\\r|\\n/", $item['field_options'] );

		if ( $terms_select ) {
			if ( ! empty( $item['field_taxonomy_slug'] ) ) {
				$terms = get_terms(
					[
						'taxonomy'   => $item['field_taxonomy_slug'],
						'hide_empty' => false,
					]
				);

				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					$options = [];
					foreach ( $terms as $term ) {
						$options[] = $term->name . '|' . $term->slug;
					}
				}
			}
		}

		$html = '';
		if ( $options ) {
			$html .= '<form>';
			$html .= '<div class="piotnetforms-field-subgroup ' . $item['inline_list'] . '">';
			$index = 0;
			foreach ( $options as $key => $option ) {
				$index++;
				$element_id   = $item['field_id'] . $key;
				$html_id      = $this->get_attribute_id( $item ) . '-' . $key;
				$option_label = $option;
				$option_value = $option;
				if ( false !== strpos( $option, '|' ) ) {
					list( $option_label, $option_value ) = explode( '|', $option );
				}

				$this->add_render_attribute(
					$element_id,
					[
						'type'       => $type,
						'value'      => $option_value,
						'data-value' => $option_value,
						'id'         => $html_id,
						'name'       => $this->get_attribute_name( $item ) . ( ( 'checkbox' === $type && count( $options ) > 1 ) ? '[]' : '' ),
					]
				);

				$name  = $this->get_field_name_shortcode( $this->get_attribute_name( $item ) );
				$value = $this->get_value_edit_post( $name );

				if ( empty( $value ) ) {
					$value = $item['field_value'];
					$this->add_render_attribute( $element_id, 'data-piotnetforms-default-value', $item['field_value'] );
				}

				if ( ! empty( $item['invalid_message'] ) ) {
					if ( $index == 1 ) {
						$this->add_render_attribute( $element_id, 'oninvalid', "this.setCustomValidity('" . $item['invalid_message'] . "')" );
						$this->add_render_attribute( $element_id, 'onchange', "this.setCustomValidity('')" );
					} else {
						$this->add_render_attribute( $element_id, 'onclick', 'clearValidity(this)' );
						$this->add_render_attribute( $element_id, 'oninvalid', "this.setCustomValidity('" . $item['invalid_message'] . "')" );
						$this->add_render_attribute( $element_id, 'onchange', "this.setCustomValidity('')" );
					}
				}

				if ( ! empty( $item['payment_methods_select_field_enable'] ) ) {
					$this->add_render_attribute( $element_id, 'data-piotnetforms-payment-methods-select-field', '' );
					$this->add_render_attribute( $element_id, 'data-piotnetforms-payment-methods-select-field-value-for-stripe', $item['payment_methods_select_field_value_for_stripe'] );
					$this->add_render_attribute( $element_id, 'data-piotnetforms-payment-methods-select-field-value-for-paypal', $item['payment_methods_select_field_value_for_paypal'] );
				}

				if ( ! empty( $value ) && $option_value === $value ) {
					$this->add_render_attribute( $element_id, 'checked', 'checked' );
					$this->add_render_attribute( $element_id, 'data-checked', 'checked' );
				}

				$values = explode( ',', $value );
				foreach ( $values as $value_item ) {
					if ( $option_value === $value_item ) {
						$this->add_render_attribute( $element_id, 'checked', 'checked' );
						$this->add_render_attribute( $element_id, 'data-checked', 'checked' );
					}
				}

				if ( $item['send_data_by_label'] ) {
					$this->add_render_attribute( $element_id, 'data-piotnetforms-send-data-by-label', $option_label );
				}

				if ( ! empty( $item['remove_this_field_from_repeater'] ) ) {
					$this->add_render_attribute( $element_id, 'data-piotnetforms-remove-this-field-from-repeater', $option_label );
				}

				if ( $item['field_required'] && 'radio' === $type ) {
					$this->add_required_attribute( $element_id );
				}

				if ( ! empty( $item['multi_step_form_autonext'] ) && 'radio' === $type ) {
					$this->add_render_attribute( $element_id, 'data-piotnetforms-multi-step-form-autonext' );
				}

				$this->add_render_attribute( $element_id, 'data-piotnetforms-id', $form_id );

				$html .= '<span class="piotnetforms-field-option"><input ' . $this->get_render_attribute_string( $element_id ) . '> <label for="' . esc_attr( $html_id ) . '">' . esc_html( $option_label ) . '</label></span>';
			}

			$html .= '</div>';
			$html .= '</form>';
		}

		return $html;
	}

	protected function form_fields_render_attributes( $i, $instance, $item ) {
		$this->add_render_attribute(
			[
				'field-group' . $i       => [
					'class' => [
						'piotnetforms-field-type-' . $item['field_type'],
						'piotnetforms-field-group',
						'piotnetforms-column',
						'piotnetforms-field-group-' . $item['field_id'],
					],
				],
				'input' . $i             => [
					'class' => [
						'piotnetforms-field',
						'piotnetforms-size-' . $item['input_size'],
					],
				],
				'range_slider' . $i      => [
					'type'                           => 'text',
					'name'                           => $this->get_attribute_name( $item ),
					'id'                             => $this->get_attribute_id( $item ),
					'class'                          => [
						'piotnetforms-field',
						'piotnetforms-size-' . $item['input_size'],
					],
					'data-piotnetforms-range-slider' => $item['piotnetforms_range_slider_field_options'],
				],
				'calculated_fields' . $i => [
					'type'                                => 'text',
					'name'                                => $this->get_attribute_name( $item ),
					'id'                                  => $this->get_attribute_id( $item ),
					'class'                               => [
						'piotnetforms-field',
						'piotnetforms-size-' . $item['input_size'],
					],
					'data-piotnetforms-calculated-fields' => $item['piotnetforms_calculated_fields_form_calculation'],
					'data-piotnetforms-calculated-fields-before' => $item['piotnetforms_calculated_fields_form_before'],
					'data-piotnetforms-calculated-fields-after' => $item['piotnetforms_calculated_fields_form_after'],
					'data-piotnetforms-calculated-fields-rounding-decimals' => $item['piotnetforms_calculated_fields_form_calculation_rounding_decimals'],
					'data-piotnetforms-calculated-fields-rounding-decimals-decimals-symbol' => $item['piotnetforms_calculated_fields_form_calculation_rounding_decimals_decimals_symbol'],
					'data-piotnetforms-calculated-fields-rounding-decimals-seperators-symbol' => $item['piotnetforms_calculated_fields_form_calculation_rounding_decimals_seperators_symbol'],
					'data-piotnetforms-calculated-fields-rounding-decimals-show' => $item['piotnetforms_calculated_fields_form_calculation_rounding_decimals_show'],
				],
				'label' . $i             => [
					'for'   => $this->get_attribute_id( $item ),
					'class' => 'piotnetforms-field-label',
				],
			]
		);

		if ( $item['field_type'] == 'honeypot' ) {
			$this->add_render_attribute(
				[
					'input' . $i => [
						'type' => 'text',
						'name' => 'form_fields[honeypot]',
						'id'   => 'form-field-honeypot',
					],
				]
			);
		} elseif ( $item['field_type'] == 'address_autocomplete' ) {
			$this->add_render_attribute(
				[
					'input' . $i => [
						'type' => 'text',
						'name' => $this->get_attribute_name( $item ),
						'id'   => $this->get_attribute_id( $item ),
					],
				]
			);
		} else {
			$this->add_render_attribute(
				[
					'input' . $i => [
						'type' => $item['field_type'],
						'name' => $this->get_attribute_name( $item ),
						'id'   => $this->get_attribute_id( $item ),
					],
				]
			);
		}

		if ( empty( $item['width'] ) ) {
			$item['width'] = '100';
		}

		$this->add_render_attribute( 'field-group' . $i, 'class', 'piotnetforms-col-' . $item['width'] );

		if ( ! empty( $item['width_tablet'] ) ) {
			$this->add_render_attribute( 'field-group' . $i, 'class', 'piotnetforms-md-' . $item['width_tablet'] );
		}

		if ( $item['allow_multiple'] ) {
			$this->add_render_attribute( 'field-group' . $i, 'class', 'piotnetforms-field-type-' . $item['field_type'] . '-multiple' );
		}

		if ( ! empty( $item['width_mobile'] ) ) {
			$this->add_render_attribute( 'field-group' . $i, 'class', 'piotnetforms-sm-' . $item['width_mobile'] );
		}

		if ( ! empty( $item['field_placeholder'] ) ) {
			$this->add_render_attribute( 'input' . $i, 'placeholder', $item['field_placeholder'] );
		}

		if ( ! empty( $item['max_length'] ) ) {
			$this->add_render_attribute( 'input' . $i, 'maxlength', $item['max_length'] );
		}

		if ( ! empty( $item['field_pattern_not_tel'] && $item['field_type'] != 'tel' ) ) {
			$this->add_render_attribute( 'input' . $i, 'pattern', $item['field_pattern_not_tel'] );
		}

		if ( ! empty( $item['input_mask_enable'] ) ) {
			if ( ! empty( $item['input_mask'] ) ) {
				$this->add_render_attribute( 'input' . $i, 'data-mask', $item['input_mask'] );
			}
			if ( ! empty( $item['input_mask_reverse'] ) ) {
				$this->add_render_attribute( 'input' . $i, 'data-mask-reverse', 'true' );
			}
		}

		if ( ! empty( $item['invalid_message'] ) ) {
			$this->add_render_attribute( 'input' . $i, 'oninvalid', "this.setCustomValidity('" . $item['invalid_message'] . "')" );
			$this->add_render_attribute( 'input' . $i, 'onchange', "this.setCustomValidity('')" );
		}

		if ( ! empty( $item['field_autocomplete'] ) ) {
			$this->add_render_attribute( 'input' . $i, 'autocomplete', 'on' );
		} else {
			$this->add_render_attribute( 'input' . $i, 'autocomplete', 'off' );
		}

		$name  = $this->get_field_name_shortcode( $this->get_attribute_name( $item ) );
		$value = $this->get_value_edit_post( $name );

		if ( empty( $value ) ) {
			$value = $item['field_value'];
			$this->add_render_attribute( 'input' . $i, 'data-piotnetforms-default-value', $item['field_value'] );
		}

		if ( ! empty( $value ) || $value == 0 ) {
			$this->add_render_attribute( 'input' . $i, 'value', $value );
			$this->add_render_attribute( 'range_slider' . $i, 'value', $value );
			$this->add_render_attribute( 'input' . $i, 'data-piotnetforms-value', $value );
		}

		if ( ! empty( $item['field_required'] ) ) {
			$class = 'piotnetforms-field-required';
			if ( ! empty( $item['mark_required'] ) ) {
				$class .= ' piotnetforms-mark-required';
			}
			$this->add_render_attribute( 'field-group' . $i, 'class', $class );
			$this->add_required_attribute( 'input' . $i );
		}

		if ( ! empty( $item['allow_multiple_upload'] ) ) {
			$this->add_render_attribute( 'input' . $i, 'multiple', 'multiple' );
			//$this->add_render_attribute( 'input' . $i, 'name', $this->get_attribute_name( $item ) . '[]', true );
		}

		if ( $item['field_type'] == 'upload' ) {
			$this->add_render_attribute( 'input' . $i, 'name', 'upload_field', true );
			if ( ! empty( $item['attach_files'] ) ) {
				$this->add_render_attribute( 'input' . $i, 'data-attach-files', '', true );
			}

			if ( ! empty( $item['file_sizes'] ) ) {
				$this->add_render_attribute(
					'input' . $i,
					[
						'data-maxsize'         => $item['file_sizes'],  //MB
						'data-maxsize-message' => $item['file_sizes_message'],
					]
				);
			}

			if ( ! empty( $item['file_types'] ) ) {
				$file_types   = explode( ',', $item['file_types'] );
				$file_accepts = [ 'jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'odt', 'avi', 'ogg', 'm4a', 'mov', 'mp3', 'mp4', 'mpg', 'wav', 'wmv', 'zip', 'xls', 'xlsx' ];

				if ( is_array( $file_types ) ) {
					$file_types_output = '';
					foreach ( $file_types as $file_type ) {
						$file_type = trim( $file_type );
						// if (in_array($file_type, $file_accepts)) {
						// 	$file_types_output .= '.' . $file_type . ',';
						// }
						$file_types_output .= '.' . $file_type . ',';
					}

					//$this->add_render_attribute( 'input' . $i, 'accept', rtrim($file_types_output,',') );
					$this->add_render_attribute( 'input' . $i, 'data-accept', str_replace( '.', '', rtrim( $file_types_output, ',' ) ) );
				}

				$this->add_render_attribute(
					'input' . $i,
					[
						'data-types-message' => $item['file_types_message'],
					]
				);

			}
		}

		if ( ! empty( $item['remove_this_field_from_repeater'] ) ) {
			$this->add_render_attribute( 'input' . $i, 'data-piotnetforms-remove-this-field-from-repeater', '', true );
			$this->add_render_attribute( 'range_slider' . $i, 'data-piotnetforms-remove-this-field-from-repeater', '', true );
			$this->add_render_attribute( 'calculated_fields' . $i, 'data-piotnetforms-remove-this-field-from-repeater', '', true );
		}

	}

	public function get_field_name_shortcode( $content ) {
		$field_name = str_replace( '[field id=', '', $content );
		$field_name = str_replace( ']', '', $field_name );
		$field_name = str_replace( '"', '', $field_name );
		$field_name = str_replace( 'form_fields[', '', $field_name );
		//fix alert ]
		return trim( $field_name );
	}

	public function get_value_edit_post( $name ) {
		$value = '';
		if ( ! empty( $_GET['edit'] ) ) {
			$post_id = intval( $_GET['edit'] );
			if ( is_user_logged_in() && get_post( $post_id ) != null ) {
				if ( current_user_can( 'edit_others_posts' ) || get_current_user_id() == get_post( $post_id )->post_author ) {
					$sp_post_id = get_post_meta( $post_id, '_submit_post_id', true );
					$form_id    = get_post_meta( $post_id, '_submit_button_id', true );

					if ( ! empty( $_GET['smpid'] ) ) {
						$sp_post_id = sanitize_text_field( $_GET['smpid'] );
					}

					if ( ! empty( $_GET['sm'] ) ) {
						$form_id = sanitize_text_field( $_GET['sm'] );
					}

					$form = array();

                    $data     = json_decode( get_post_meta( $sp_post_id, '_piotnetforms_data', true ), true );
                    $form['settings'] = $data['widgets'][ $form_id ]['settings'];

                    if ( ! empty( $form ) ) {

						if ( ! empty( $form['settings'] ) ) {
							$sp_post_taxonomy  = isset($form['settings']['submit_post_taxonomy']) ? $form['settings']['submit_post_taxonomy'] : 'category-post';
							$sp_title          = $this->get_field_name_shortcode( $form['settings']['submit_post_title'] );
							$sp_content        = $this->get_field_name_shortcode( $form['settings']['submit_post_content'] );
							$sp_terms          = isset($form['settings']['submit_post_terms_list']) ? $form['settings']['submit_post_terms_list'] : [];
							$sp_term           = isset($form['settings']['submit_post_term']) ? $this->get_field_name_shortcode( $form['settings']['submit_post_term'] ) : '';
							$sp_featured_image = $this->get_field_name_shortcode( $form['settings']['submit_post_featured_image'] );
							$sp_custom_fields  = isset($form['settings']['submit_post_custom_fields_list']) ? $form['settings']['submit_post_custom_fields_list'] : [];

							if ( $name == $sp_title ) {
								$value = get_the_title( $post_id );
							}

							if ( $name == $sp_content ) {
								$value = get_the_content( null, false, $post_id );
							}

							if ( $name == $sp_term ) {
								if ( ! empty( $sp_post_taxonomy ) ) {
									$sp_post_taxonomy = explode( '|', $sp_post_taxonomy );
									$sp_post_taxonomy = $sp_post_taxonomy[0];
									$terms            = get_the_terms( $post_id, $sp_post_taxonomy );
									if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
										$value = $terms[0]->slug;
									}
								}
							}

							if ( ! empty( $sp_terms ) ) {
								foreach ( $sp_terms as $sp_terms_item ) {
									$sp_post_taxonomy = explode( '|', $sp_terms_item['submit_post_taxonomy'] );
									$sp_post_taxonomy = $sp_post_taxonomy[0];
									$sp_term_slug     = $sp_terms_item['submit_post_terms_slug'];
									$sp_term          = get_field_name_shortcode( $sp_terms_item['submit_post_terms_field_id'] );

									if ( $name == $sp_term ) {
										$terms = get_the_terms( $post_id, $sp_post_taxonomy );
										if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
											foreach ( $terms as $term ) {
												$value .= $term->slug . ',';
											}
										}
									}
								}

								$value = rtrim( $value, ',' );
							}

							if ( $name == $sp_featured_image ) {
								$value = get_the_post_thumbnail_url( $post_id, 'full' );
							}

							foreach ( $sp_custom_fields as $sp_custom_field ) {
								if ( ! empty( $sp_custom_field['submit_post_custom_field'] ) ) {
									if ( $name == $this->get_field_name_shortcode( $sp_custom_field['submit_post_custom_field_id'] ) ) {

										$meta_type = $sp_custom_field['submit_post_custom_field_type'];

										if ( function_exists( 'get_field' ) && $form['settings']['submit_post_custom_field_source'] == 'acf_field' ) {
											$value = get_field( $sp_custom_field['submit_post_custom_field'], $post_id );

											if ( $meta_type == 'image' ) {
												if ( is_array( $value ) ) {
													$value = $value['url'];
												}
											}

											if ( $meta_type == 'gallery' ) {
												if ( is_array( $value ) ) {
													$images = '';
													foreach ( $value as $item ) {
														if ( is_array( $item ) ) {
															$images .= $item['url'] . ',';
														}
													}
													$value = rtrim( $images, ',' );
												}
											}

											if ( $meta_type == 'select' || $meta_type == 'checkbox' ) {
												if ( is_array( $value ) ) {
													$value_string = '';
													foreach ( $value as $item ) {
														$value_string .= $item . ',';
													}
													$value = rtrim( $value_string, ',' );
												}
											}

											if ( $meta_type == 'date' ) {
												$value = get_post_meta( $post_id, $sp_custom_field['submit_post_custom_field'], true );
												$time  = strtotime( $value );
												$value = date( get_option( 'date_format' ), $time );
											}
										} elseif ( $form['settings']['submit_post_custom_field_source'] == 'toolset_field' ) {

											$meta_key = 'wpcf-' . $sp_custom_field['submit_post_custom_field'];

											$value = get_post_meta( $post_id, $meta_key, false );

											if ( $meta_type == 'gallery' ) {
												if ( ! empty( $value ) ) {
													$images = '';
													foreach ( $value as $item ) {
														$images .= $item . ',';
													}
													$value = rtrim( $images, ',' );
												}
											} elseif ( $meta_type == 'checkbox' ) {
												if ( is_array( $value ) ) {
													$value_string = '';
													foreach ( $value as $item ) {
														foreach ( $item as $item_item ) {
															$value_string .= $item_item[0] . ',';
														}
													}
													$value = rtrim( $value_string, ',' );
												}
											} elseif ( $meta_type == 'date' ) {
												$value = date( get_option( 'date_format' ), $value[0] );
											} else {
												$value = $value[0];
											}
										} elseif ( $form['settings']['submit_post_custom_field_source'] == 'jet_engine_field' ) {
											$value = get_post_meta( $post_id, $sp_custom_field['submit_post_custom_field'], true );

											if ( $meta_type == 'image' ) {
												if ( ! empty( $value ) ) {
													$value = wp_get_attachment_url( $value );
												}
											}

											if ( $meta_type == 'gallery' ) {
												if ( ! empty( $value ) ) {
													$images    = '';
													$images_id = explode( ',', $value );
													foreach ( $images_id as $item ) {
														$images .= wp_get_attachment_url( $item ) . ',';
													}
													$value = rtrim( $images, ',' );
												}
											}

											if ( $meta_type == 'select' ) {
												if ( is_array( $value ) ) {
													$value_string = '';
													foreach ( $value as $item ) {
														$value_string .= $item . ',';
													}
													$value = rtrim( $value_string, ',' );
												}
											}

											if ( $meta_type == 'checkbox' ) {
												if ( is_array( $value ) ) {
													$value_string = '';
													foreach ( $value as $key => $item ) {
														if ( $item ) {
															$value_string .= $key . ',';
														}
													}
													$value = rtrim( $value_string, ',' );
												}
											}

											if ( $meta_type == 'date' ) {
												$value = get_post_meta( $post_id, $sp_custom_field['submit_post_custom_field'], true );
												$time  = strtotime( $value );
												$value = date( get_option( 'date_format' ), $time );
											}
										} else {
											$value = get_post_meta( $post_id, $sp_custom_field['submit_post_custom_field'], true );
										}
									}
								}
							}
						}
					}
				}
			}
		}

		return $value;

	}

	public function render_plain_content() {}

	public function get_attribute_name( $item ) {
		return 'form_fields[' . trim( $item['field_id'] ) . ']';
	}

	public function get_attribute_id( $item ) {
		return 'form-field-' . trim( $item['field_id'] );
	}

	private function add_required_attribute( $element ) {
		$this->add_render_attribute( $element, 'required', 'required' );
		$this->add_render_attribute( $element, 'aria-required', 'true' );
	}

	private function get_upload_file_size_options() {
		$max_file_size = wp_max_upload_size() / pow( 1024, 2 ); //MB

		$sizes = [];

		for ( $file_size = 1; $file_size <= $max_file_size; $file_size++ ) {
			$sizes[ $file_size ] = $file_size . 'MB';
		}

		return $sizes;
	}

	// public function render() {
	// 	$settings = $this->settings;

	// }

	public function render() {
		$settings = $this->settings;
		$item_index = 0;
		$item = $settings;
		$field_type = $settings['field_type'];
		$field_id = $settings['field_id'];
		$form_id = $settings['form_id'];
		$country = !(empty($settings['country'])) ? $settings['country']: '';
		$latitude = !(empty($settings['google_maps_lat'])) ? $settings['google_maps_lat'] : '';
        $longitude = !(empty($settings['google_maps_lng'])) ? $settings['google_maps_lng'] : '';
		$zoom = !(empty($settings['google_maps_zoom'])) ? $settings['google_maps_zoom']['size'] : '';
		$field_placeholder = $settings['field_placeholder'];
		$field_value = $settings['field_value'];
		$field_required = !(empty($settings['field_required'])) ? ' required="required" ' : '';

		$item['input_size'] = '';
		$this->form_fields_render_attributes( $item_index, '', $item );

		$this->add_render_attribute( 'wrapper', 'class', 'piotnetforms-fields-wrapper piotnetforms-labels-above' );
	?>
		
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'field-group' . $item_index ); ?>>
				<?php
				if ( $item['field_label'] && 'html' !== $item['field_type'] ) {
					echo '<label ';
					if (empty($item['field_label_show'])) {
						echo 'style="display:none" ';
					}
					echo $this->get_render_attribute_string( 'label' . $item_index );
					if ('honeypot' == $item['field_type']) {
						echo ' data-piotnetforms-honeypot';
					}
					echo '>' . $item['field_label'] . '</label>';
				}

				echo '<div data-piotnetforms-required></div>';

				echo '<div class="piotnetforms-field-container">';

				switch ( $item['field_type'] ) :
					case 'html':
						break;

					case 'textarea':
						echo $this->make_textarea_field( $item, $item_index, $form_id );
						break;

					case 'tinymce':
						break;

					case 'select':
						echo $this->make_select_field( $item, $item_index, $form_id );
						break;

					case 'select_autocomplete':
						break;

					case 'image_select':
						break;

					case 'terms_select':
						break;

					case 'radio':
					case 'checkbox':
						echo $this->make_radio_checkbox_field( $item, $item_index, $field_type, $form_id );
						break;
					case 'text':
					case 'email':
					case 'url':
					case 'password':
					case 'hidden':
					case 'color':
						$this->add_render_attribute( 'input' . $item_index, 'data-piotnetforms-id', $form_id );
						echo '<input size="1" ' . $this->get_render_attribute_string( 'input' . $item_index ) . '>';	
						break;
					case 'coupon_code':
						break;
					case 'honeypot':
						break;
					case 'address_autocomplete':
						break;
					case 'image_upload':
						break;
					case 'upload':
						echo "<form action='#' class='piotnetforms-upload' data-piotnetforms-upload enctype='multipart/form-data'>";
						$this->add_render_attribute( 'input' . $item_index, 'data-piotnetforms-id', $form_id );
						echo '<input type="file" ' . $this->get_render_attribute_string( 'input' . $item_index ) . '>';
						echo "</form>";
						break;
					case 'stripe_payment':
						break;
					case 'range_slider':
						break;
					case 'calculated_fields':
						break;
					case 'tel':
						$this->add_render_attribute( 'input' . $item_index, 'data-piotnetforms-id', $form_id );
						$this->add_render_attribute( 'input' . $item_index, 'pattern', esc_attr( $item['field_pattern'] ) );
						$this->add_render_attribute( 'input' . $item_index, 'title', __( 'Only numbers and phone characters (#, -, *, etc) are accepted.', 'piotnetforms-pro' ) );
						echo '<input size="1" '. $this->get_render_attribute_string( 'input' . $item_index ) . '>';	
						break;
					case 'number':
						$this->add_render_attribute( 'input' . $item_index, 'data-piotnetforms-id', $form_id );
						$this->add_render_attribute( 'input' . $item_index, 'class', 'piotnetforms-field-textual' );
						$this->add_render_attribute( 'input' . $item_index, 'step', 'any' );

						if ( !empty( $item['field_min'] ) || $item['field_min'] === 0 ) {
							$this->add_render_attribute( 'input' . $item_index, 'min', esc_attr( $item['field_min'] ) );
						}

						if ( !empty( $item['field_max'] ) || $item['field_max'] === 0 ) {
							$this->add_render_attribute( 'input' . $item_index, 'max', esc_attr( $item['field_max'] ) );
						}

						echo '<input ' . $this->get_render_attribute_string( 'input' . $item_index ) . '>';	
						break;
					case 'acceptance':
						$label = '';
						$this->add_render_attribute( 'input' . $item_index, 'class', 'piotnetforms-acceptance-field' );
						$this->add_render_attribute( 'input' . $item_index, 'type', 'checkbox', true );
						$this->add_render_attribute( 'input' . $item_index, 'data-piotnetforms-id', $form_id );
						$this->add_render_attribute( 'input' . $item_index, 'value', 'on' );

						if ( ! empty( $item['acceptance_text'] ) ) {
							$label = '<label for="' . $this->get_attribute_id( $item ) . '">' . esc_html( $item['acceptance_text'] ) . '</label>';
						}

						if ( ! empty( $item['checked_by_default'] ) ) {
							$this->add_render_attribute( 'input' . $item_index, 'checked', 'checked' );
						}

						echo '<div class="piotnetforms-field-subgroup"><span class="piotnetforms-field-option"><input ' . $this->get_render_attribute_string( 'input' . $item_index ) . '> ' . $label . '</span></div>';
						break;
					case 'date':
						break;
					case 'time':
						break;
					case 'signature':
						break;
					default:
						$field_type = $item['field_type'];
				endswitch;

				echo '</div>';
				?>
			</div>
		</div>
	<?php
	}

	public function live_preview() {
		?>
			<%
				view.add_attribute('wrapper', 'class', 'piotnetforms-fields-wrapper piotnetforms-labels-above');
				var s = data.widget_settings;
				var country = s['country'] ? s['country']: '';
				var latitude = s['google_maps_lat'] ? s['google_maps_lat'] : '';
		        var longitude = s['google_maps_lng'] ? s['google_maps_lng'] : '';
				var zoom = s['google_maps_zoom'] ? s['google_maps_zoom']['size'] : '';

				function replaceAll(str, find, replace) {
			    	return str.replace(new RegExp(find, 'g'), replace);
			    }

				function get_attribute_name( s ) {
					return 'form_fields[' + s.field_id + ']';
				}

				function get_attribute_id( s ) {
					return 'form-field-' + s.field_id;
				}

				function add_required_attribute( element, view ) {
					view.add_attribute( element, 'required', 'required' );
					view.add_attribute( element, 'aria-required', 'true' );
				}

				function form_fields_render_attributes( view, s ) {
					view.add_attribute('field-group', 'class', [
						'piotnetforms-field-type-' + s.field_type,
						'piotnetforms-field-group',
						'piotnetforms-column',
						'piotnetforms-field-group-' + s.field_id,
					]);

					view.add_attribute('input', 'class', [
						'piotnetforms-field',
					]);

					view.add_multi_attribute({
						range_slider: {
							type: "text",
							id: get_attribute_id( s ),
							name: get_attribute_name( s ),
							class: [
								'piotnetforms-field',
							],
							"data-piotnetforms-range-slider": s.piotnetforms_range_slider_field_options,
						}
					});

					view.add_multi_attribute({
						calculated_fields: {
							type: "text",
							id: get_attribute_id( s ),
							name: get_attribute_name( s ),
							class: [
								'piotnetforms-field',
							],
							'data-piotnetforms-calculated-fields': s['piotnetforms_calculated_fields_form_calculation'],
							'data-piotnetforms-calculated-fields-before': s['piotnetforms_calculated_fields_form_before'],
							'data-piotnetforms-calculated-fields-after': s['piotnetforms_calculated_fields_form_after'],
							'data-piotnetforms-calculated-fields-rounding-decimals': s['piotnetforms_calculated_fields_form_calculation_rounding_decimals'],
							'data-piotnetforms-calculated-fields-rounding-decimals-decimals-symbol': s['piotnetforms_calculated_fields_form_calculation_rounding_decimals_decimals_symbol'],
							'data-piotnetforms-calculated-fields-rounding-decimals-seperators-symbol': s['piotnetforms_calculated_fields_form_calculation_rounding_decimals_seperators_symbol'],
							'data-piotnetforms-calculated-fields-rounding-decimals-show': s['piotnetforms_calculated_fields_form_calculation_rounding_decimals_show'],
						},
						'label': {
							'for': get_attribute_id( s ),
							'class': 'piotnetforms-field-label',
						}
					});


					if ( s['field_type'] === 'honeypot' ) {
						view.add_multi_attribute({
							input: {
								'type': 'text',
								'name': 'form_fields[honeypot]',
								'id': 'form-field-honeypot',
							}
						});
					} else if ( s['field_type'] == 'address_autocomplete' ) {
						view.add_multi_attribute({
							input: {
								type: 'text',
								name: get_attribute_name( s ),
								id: get_attribute_id( s ),
							}
						});
					} else {
						view.add_multi_attribute({
							input: {
								type: s['field_type'],
								name: get_attribute_name( s ),
								id: get_attribute_id( s ),
							}
						});
					}

					if ( !s['width'] ) {
						s['width'] = '100';
					}

					view.add_attribute('field-group', 'class', 'piotnetforms-col-' + s['width']);

					if ( s['width_tablet'] ) {
						view.add_attribute( 'field-group', 'class', 'piotnetforms-md-' + s['width_tablet'] );
					}

					if ( s['allow_multiple'] ) {
						view.add_attribute( 'field-group', 'class', 'piotnetforms-field-type-' + s['field_type'] + '-multiple' );
					}

					if ( s['width_mobile'] ) {
						view.add_attribute( 'field-group', 'class', 'piotnetforms-sm-' + s['width_mobile'] );
					}

					if ( s['field_placeholder'] ) {
						view.add_attribute( 'input', 'placeholder', s['field_placeholder'] );
					}

					if ( s['max_length'] ) {
						view.add_attribute( 'input', 'maxlength', s['max_length'] );
					}

					if ( s['field_pattern_not_tel'] && s['field_type'] !== 'tel' ) {
						view.add_attribute( 'input', 'pattern', s['field_pattern_not_tel'] );
					}

					if ( s['input_mask_enable'] ) {
						if ( s['input_mask'] ) {
							view.add_attribute( 'input', 'data-mask', s['input_mask'] );
						}
						if ( s['input_mask_reverse'] ) {
							view.add_attribute( 'input', 'data-mask-reverse', 'true' );
						}
					}

					if ( s['invalid_message'] ) {
						view.add_attribute( 'input', 'oninvalid', "this.setCustomValidity('" + s['invalid_message'] + "')" );
						view.add_attribute( 'input', 'onchange', "this.setCustomValidity('')" );
					}

					if ( s['field_autocomplete'] ) {
						view.add_attribute( 'input', 'autocomplete', 'on' );
					} else {
						view.add_attribute( 'input', 'autocomplete', 'off' );
					}

					var value = '';

					if ( !value ) {
						value = s['field_value'];
						view.add_attribute( 'input', 'data-piotnetforms-default-value', s['field_value'] );
					}

					if ( value || value === 0 ) {
						view.add_attribute( 'input', 'value', value );
						view.add_attribute( 'range_slider', 'value', value );
						view.add_attribute( 'input', 'data-piotnetforms-value', value );
					}

					if ( s['field_required'] ) {
						var field_class = 'piotnetforms-field-required';
						if ( s['mark_required'] ) {
							field_class += ' piotnetforms-mark-required';
						}
						view.add_attribute( 'field-group', 'class', field_class );
						add_required_attribute( 'input', view );
					}

					if ( s['allow_multiple_upload'] ) {
						view.add_attribute( 'input', 'multiple', 'multiple' );
					}

					if ( s['field_type'] === 'upload' ) {
						view.add_attribute( 'input', 'name', 'upload_field', true );
						if ( s['attach_files'] ) {
							view.add_attribute( 'input', 'data-attach-files', '', true );
						}

						if ( s['file_sizes'] ) {

							view.add_multi_attribute({
								input: {
									'data-maxsize': s['file_sizes'],
									'data-maxsize-message': s['file_sizes_message'],
								}
							});
						}

						if ( s['file_types'] ) {
							var file_types   = s['file_types'].split(',');
							var file_accepts = [ 'jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'odt', 'avi', 'ogg', 'm4a', 'mov', 'mp3', 'mp4', 'mpg', 'wav', 'wmv', 'zip', 'xls', 'xlsx' ];

							if ( Array.isArray(file_types) ) {
								var file_types_output = '';

								for ( i=0; i < file_types.length; i++ ) {
									file_types_output += '.' + file_types[i];
									if( i < (file_types.length - 1)) {
										file_types_output += ',';
									} 
								}

								view.add_attribute( 'input', 'data-accept', file_types_output.replace(/\./g, "") );
							}

							view.add_attribute( 'input', 'data-types-message', s['file_types_message'] );
						}
					}

					if ( s['remove_this_field_from_repeater'] ) {
						view.add_attribute( 'input', 'data-piotnetforms-remove-this-field-from-repeater', '', true );
						view.add_attribute( 'range_slider', 'data-piotnetforms-remove-this-field-from-repeater', '', true );
						view.add_attribute( 'calculated_fields', 'data-piotnetforms-remove-this-field-from-repeater', '', true );
					}

					return view;

				}

				function make_textarea_field( s, tinymce ) {

					view.add_multi_attribute({
						textarea: {
							class: [
								'piotnetforms-field-textual',
								'piotnetforms-field',
							],
							name: get_attribute_name( s ),
							id: get_attribute_id( s ),
							rows: s['rows'],
						}
					});

					if ( s['field_placeholder'] ) {
						view.add_attribute( 'textarea', 'placeholder', s['field_placeholder'] );
					}

					if ( tinymce ) {
						view.add_attribute( 'textarea', 'data-piotnetforms-tinymce', '' );
						view.add_attribute( 'textarea', 'placeholder', 'This feature only works on the frontend' );
					}

					if ( s['field_required'] ) {
						add_required_attribute( 'textarea', view );
					}

					if ( s['max_length'] ) {
						view.add_attribute( 'textarea', 'maxlength', s['max_length'] );
					}

					if ( s['field_pattern_not_tel'] ) {
						view.add_attribute( 'textarea', 'pattern', s['field_pattern_not_tel'] );
					}

					if ( s['remove_this_field_from_repeater'] ) {
						view.add_attribute( 'textarea', 'data-piotnetforms-remove-this-field-from-repeater', '' );
					}

					var value = '';

					if ( !value ) {
						value = s['field_value'];
						view.add_attribute( 'textarea', 'data-piotnetforms-default-value', s['field_value'] );
					}

					view.add_attribute( 'textarea', 'data-piotnetforms-id', s['form_id'] );

					return {view: view, value: value};
				}

				view = form_fields_render_attributes( view, s );

				function make_select_field( view, s, image_select, terms_select, select_autocomplete ) {

					var name = get_attribute_name( s );

					if (s['allow_multiple']) {
						name += '[]';
					}

					view.add_multi_attribute({
						'select-wrapper': {
							'class' : [
								'piotnetforms-field',
								'piotnetforms-select-wrapper',
							],
						},
						select: {
							'class' : [
								'piotnetforms-field-textual',
							],
							name: name,
							id: get_attribute_id( s ),
						}
					});

					if ( image_select ) {
						var list = s['piotnetforms_image_select_field_gallery'];
						var limit_multiple = s['limit_multiple'];
						if ( list ) {
							view.add_multi_attribute({
								select: {
									'data-piotnetforms-image-select': JSON.stringify(list),
								}
							});

							if ( limit_multiple ) {
								view.add_multi_attribute({
									select: {
										'data-piotnetforms-image-select-limit-multiple': limit_multiple,
									}
								});
							}
						}
					}

					if ( s['field_required'] ) {
						add_required_attribute( 'select', view );
					}

					if ( s['allow_multiple'] ) {
						view.add_attribute( 'select', 'multiple', '' );
						if ( s['select_size'] ) {
							view.add_attribute( 'select', 'size', s['select_size'] );
						}
					}

					if ( s['send_data_by_label'] ) {
						view.add_attribute( 'select', 'data-piotnetforms-send-data-by-label', '' );
					}

					if ( s['invalid_message'] ) {
						view.add_attribute( 'select', 'oninvalid', "this.setCustomValidity('" + s['invalid_message'] + "')" );
						view.add_attribute( 'select', 'onchange', "this.setCustomValidity('')" );
					}

					if ( s['remove_this_field_from_repeater'] ) {
						view.add_attribute( 'select', 'data-piotnetforms-remove-this-field-from-repeater', '' );
					}

					if ( s['multi_step_form_autonext'] ) {
						view.add_attribute( 'select', 'data-piotnetforms-multi-step-form-autonext', '' );
					}

					if ( s['payment_methods_select_field_enable'] ) {
						view.add_attribute( 'select', 'data-piotnetforms-payment-methods-select-field', '' );
						view.add_attribute( 'select', 'data-piotnetforms-payment-methods-select-field-value-for-stripe', s['payment_methods_select_field_value_for_stripe'] );
						view.add_attribute( 'select', 'data-piotnetforms-payment-methods-select-field-value-for-paypal', s['payment_methods_select_field_value_for_paypal'] );
					}

					var options = s['field_options'] ? s['field_options'].split('\n') : [];

					if ( terms_select ) {
						if ( s['field_taxonomy_slug'] ) {
							options = [];
							var terms_select_option = 'This feature only work on the frontend|This feature only work on the frontend';
							options.push(terms_select_option);
						}
					}

					if ( select_autocomplete ) {
						view.add_attribute( 'select', 'data-piotnetforms-select-autocomplete', '' );
					}

					view.add_attribute( 'select', 'data-piotnetforms-id', s['form_id'] );
					view.add_attribute( 'select', 'data-piotnetforms-default-value', s['field_value'] );
					
					return {view: view, options: options};
				}
			%>
			<div <%= view.render_attributes('wrapper') %>>
				<div <%= view.render_attributes('field-group') %>>
					<% if(s.field_label && s.field_type !== 'html') { %>
						<label <% if(!s.field_label_show) { %>style="display:none" <% } %> class="piotnetforms-field-label"<% if(s.field_type == 'honeypot') { %> data-piotnetforms-honeypot<% } %>><%= s.field_label %></label>
					<% } %>
					<div data-piotnetforms-required></div>

					<div class="piotnetforms-field-container">
						<%
							switch ( s['field_type'] ) {
								case 'html':
									break;
								case 'textarea':
									var textarea = make_textarea_field( s, false );
									view = textarea.view;
						%>
									<textarea <%= view.render_attributes('textarea') %>><%= textarea.value %></textarea>
						<%			
									break;
								case 'tinymce':
									break;
								case 'select':
								case 'select_autocomplete':
									var image_select = false,
										terms_select = false,
										select_autocomplete = false;

									var select = make_select_field( view, s, image_select, terms_select, select_autocomplete );
									var options = select.options;
									view = select.view;

									if ( s['field-type'] === 'terms_select' && s['terms_select_type'] !== 'select') {
										break;
									}
						%>
									<div <%= view.render_attributes('select-wrapper') %>>
										<select <%= view.render_attributes('select') %> data-options='<%= JSON.stringify(options) %>'>
											<%
												if ( select_autocomplete && s['field_placeholder'] ) {
													options.unshift( s['field_placeholder'] + '|' + '' );
												}

												for ( i = 0; i < options.length; i++ ) {
													var option = options[i],
														option_id = 'option_' + i,
														option_value = option,
														option_label = option;

													if ( option.includes("|") ) {
														var option_array = option.split('|'),
															option_value = option_array[1].trim(),
															option_label = option_array[0].trim();
													}

													view.add_attribute( option_id, 'value', option_value );

													value = s['field_value'];

													if ( value && option_value === value ) {
														view.add_attribute( option_id, 'selected', 'selected' );
													}

													if ( ! select_autocomplete && value !== undefined ) {
														var values = value.split(',');
														if (values) {
															for ( j = 0; j < options.length; j++ ) {
																if ( option_value === options[j] ) {
																	view.add_attribute( option_id, 'selected', 'selected' );
																}
															}
														}
													}

													if ( s['send_data_by_label'] ) {
														view.add_attribute( option_id, 'data-piotnetforms-send-data-by-label', option_label );
													}

													if ( s['remove_this_field_from_repeater'] ) {
														view.add_attribute( option_id, 'data-piotnetforms-remove-this-field-from-repeater', option_label );
													}

													if ( i == ( options.length - 1 ) && option_value === '' ) {
														
													} else {
														if ( option_value.includes('[optgroup') ) {
															var optgroup = replaceAll(option_value, '\\]', '');
															optgroup = replaceAll(optgroup, '\\[', '');
															optgroup = replaceAll(optgroup, 'optgroup label=', '');
															optgroup = replaceAll(optgroup, '&quot;', '');
															console.log(optgroup);
															%>
																<optgroup label=<%= optgroup %>>
															<%
														} else if ( option_value.includes('[/optgroup]') ) {
															%>
																</optgroup>
															<%
														} else {
															%>
																<option <%= view.render_attributes(option_id) %>><%= option_label %></option>
															<%
														}
													}
												}
											%>
										</select>
									</div>
						<%
									break;
								case 'radio':
								case 'checkbox':

									var options = s['field_options'] ? s['field_options'].split('\n') : [];

									if ( terms_select ) {
										if ( s['field_taxonomy_slug'] ) {
											options = [];
											var terms_select_option = 'This feature only work on the frontend|This feature only work on the frontend';
											options.push(terms_select_option);
										}
									}

									if ( options ) {
								%>
									<form>
										<div class="piotnetforms-field-subgroup <%= s['inline_list'] %>">
										<%
										for ( i = 0; i < options.length; i++ ) {
											view.remove_group_attribute( 'element_id' );
											var option = options[i];
											var element_id = s['field_id'] + i;
											var html_id = get_attribute_id( s ) + '-' + i;
											var option_label = option;
											var option_value = option;

											if ( option.includes("|") ) {
												var option_array = option.split('|'),
													option_value = option_array[1],
													option_label = option_array[0];
											}

											var name = get_attribute_name( s );
											if ( s['field_type'] === 'checkbox' && options.length > 1 ) {
												name += '[]';
											} 

											view.add_multi_attribute({
												element_id: {
													'type'       : s['field_type'],
													'value'      : option_value,
													'data-value' : option_value,
													'id'         : html_id,
													'name'       : name,
												}
											});

											value = s['field_value'];
											view.add_attribute( element_id, 'data-piotnetforms-default-value', value );

											if ( s['invalid_message'] ) {
												if ( i === 1 ) {
													view.add_attribute( element_id, 'oninvalid', "this.setCustomValidity('" + s['invalid_message'] + "')" );
													view.add_attribute( element_id, 'onchange', "this.setCustomValidity('')" );
												} else {
													view.add_attribute( element_id, 'onclick', 'clearValidity(this)' );
													view.add_attribute( element_id, 'oninvalid', "this.setCustomValidity('" + s['invalid_message'] + "')" );
													view.add_attribute( element_id, 'onchange', "this.setCustomValidity('')" );
												}
											}

											if ( s['payment_methods_select_field_enable'] ) {
												view.add_attribute( element_id, 'data-piotnetforms-payment-methods-select-field', '' );
												view.add_attribute( element_id, 'data-piotnetforms-payment-methods-select-field-value-for-stripe', s['payment_methods_select_field_value_for_stripe'] );
												view.add_attribute( element_id, 'data-piotnetforms-payment-methods-select-field-value-for-paypal', s['payment_methods_select_field_value_for_paypal'] );
											}

											if ( value && option_value === value ) {
												view.add_attribute( element_id, 'checked', 'checked' );
												view.add_attribute( element_id, 'data-checked', 'checked' );
											}

											var values = value ? value.split(',') : [];
											for ( j = 0; j < values.length; j++ ) {
												if ( option_value === values[j] ) {
													view.add_attribute( element_id, 'checked', 'checked' );
													view.add_attribute( element_id, 'data-checked', 'checked' );
												}
											}

											if ( s['send_data_by_label'] ) {
												view.add_attribute( element_id, 'data-piotnetforms-send-data-by-label', option_label );
											}

											if ( s['remove_this_field_from_repeater'] ) {
												view.add_attribute( element_id, 'data-piotnetforms-remove-this-field-from-repeater', option_label );
											}

											if ( s['field_required'] && 'radio' === s['field_type'] ) {
												add_required_attribute( element_id, view );
											}

											if ( s['multi_step_form_autonext'] && 'radio' === s['field_type'] ) {
												view.add_attribute( element_id, 'data-piotnetforms-multi-step-form-autonext', '' );
											}

											view.add_attribute( element_id, 'data-piotnetforms-id', s['form_id'] );
										%>
											<span class="piotnetforms-field-option"><input <%= view.render_attributes('element_id') %>> <label for="<%= html_id %>"><%= option_label %></label></span>
										<%
										}
										%>
											</div>
										</form>
										<%
									}
									break;
								case 'text':
								case 'email':
								case 'url':
								case 'password':
								case 'hidden':
								case 'color':
									view.add_attribute( 'input', 'data-piotnetforms-id', s.form_id );
						%>
							<input size="1" <%= view.render_attributes('input') %>>
						<%
									break;
								case 'coupon_code':
									break;
								case 'image_select':
								case 'terms_select':
								case 'honeypot':
									break;
								case 'address_autocomplete':
									break;
								case 'image_upload':
									break;
								case 'upload':
								%>
									<form action='#' class='piotnetforms-upload' data-piotnetforms-upload enctype='multipart/form-data'>
								<%	
									view.add_attribute( 'input', 'data-piotnetforms-id', s.form_id );
								%>
									<input type="file" <%= view.render_attributes('input') %>>
									</form>
								<%
									break;
								case 'stripe_payment':
									break;
								case 'range_slider':
									break;
								case 'calculated_fields':
									break;
								case 'tel':
									view.add_attribute( 'input', 'data-piotnetforms-id', s.form_id );
									view.add_attribute( 'input', 'pattern', s['field_pattern'] );
									view.add_attribute( 'input', 'title', '<?php echo __( 'Only numbers and phone characters (#, -, *, etc) are accepted.', 'piotnetforms' ); ?>' );
								%>
									<input size="1" <%= view.render_attributes('input') %>>
								<%	
									break;
								case 'number':
									view.add_attribute( 'input', 'data-piotnetforms-id', s.form_id );
									view.add_attribute( 'input', 'class', 'piotnetforms-field-textual' );
									view.add_attribute( 'input', 'step', 'any' );

									if ( s['field_min'] || s['field_min'] === 0 ) {
										view.add_attribute( 'input', 'min', s['field_min'] );
									}

									if ( s['field_max'] || s['field_max'] === 0 ) {
										view.add_attribute( 'input', 'max', s['field_max'] );
									}
								%>
									<input <%= view.render_attributes('input') %>>
								<%	
									break;
								case 'acceptance':
									var label = '';
									view.add_attribute( 'input', 'class', 'piotnetforms-acceptance-field' );
									view.remove_attribute( 'input', 'type' );
									view.add_attribute( 'input', 'type', 'checkbox' );
									view.add_attribute( 'input', 'data-piotnetforms-id', s.form_id );
									view.add_attribute( 'input', 'value', 'on' );

									if ( s['checked_by_default'] ) {
										view.add_attribute( 'input', 'checked', 'checked' );
									}
								%>
									<div class="piotnetforms-field-subgroup"><span class="piotnetforms-field-option"><input <%= view.render_attributes('input') %>><% if ( s['acceptance_text'] ) { %><label for="<%= get_attribute_id( s ) %>"><%= s['acceptance_text'] %></label><% } %></span></div>
								<%
									break;
								case 'date':
									break;
								case 'time':
									break;
								case 'signature':
									break;

								default:
							}
						%>
					</div>
				</div>
			</div>
		<?php
	}
}
