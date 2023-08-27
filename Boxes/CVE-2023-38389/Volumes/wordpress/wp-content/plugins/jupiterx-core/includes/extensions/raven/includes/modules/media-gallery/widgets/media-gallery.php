<?php
namespace JupiterX_Core\Raven\Modules\Media_Gallery\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Plugin;
use Elementor\Repeater;
use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Modules\Media_Gallery\Submodules\Hosted_Audio;
use JupiterX_Core\Raven\Modules\Media_Gallery\Submodules\Hosted_Video;
use JupiterX_Core\Raven\Modules\Media_Gallery\SubModules\Image;
use JupiterX_Core\Raven\Modules\Media_Gallery\Submodules\SoundCloud;
use JupiterX_Core\Raven\Modules\Media_Gallery\Submodules\Spotify;
use JupiterX_Core\Raven\Modules\Media_Gallery\Submodules\Vimeo;
use JupiterX_Core\Raven\Modules\Media_Gallery\Submodules\YouTube;

defined( 'ABSPATH' ) || die();

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Media_Gallery extends Base_Widget {

	public function get_name() {
		return 'raven-media-gallery';
	}

	public function get_title() {
		return esc_html__( 'Media Gallery', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-media-gallery';
	}

	public function register_controls() {
		$this->register_content_settings_controls();
		$this->register_content_layout_controls();
		$this->register_content_filter_bar_controls();
		$this->register_style_image_controls();
		$this->register_style_content_controls();
		$this->register_style_overlay_controls();
		$this->register_style_filter_bar_controls();
		$this->register_style_video_play_icon();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_content_settings_controls() {
		$this->start_controls_section(
			'media_gallery_settings_section',
			[
				'label' => esc_html__( 'Settings', 'jupiterx-core' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_type',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Type', 'jupiterx-core' ),
				'default' => 'image',
				'options' => [
					'image' => esc_html__( 'Image', 'jupiterx-core' ),
					'video' => esc_html__( 'Video', 'jupiterx-core' ),
					'audio' => esc_html__( 'Audio', 'jupiterx-core' ),
					'category' => esc_html__( 'Category', 'jupiterx-core' ),
				],
			]
		);

		$repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'jupiterx-core' ),
				'type' => Controls_Manager::MEDIA,
				'media_types' => [ 'image' ],
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'item_type' => 'image',
				],
			]
		);

		$repeater->add_control(
			'link_to',
			[
				'label' => esc_html__( 'Link', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'file',
				'options' => [
					'none' => esc_html__( 'None', 'jupiterx-core' ),
					'file' => esc_html__( 'Media File', 'jupiterx-core' ),
					'custom' => esc_html__( 'Custom URL', 'jupiterx-core' ),
				],
				'condition' => [
					'item_type' => 'image',
				],
			]
		);

		$repeater->add_control(
			'url_link_to',
			[
				'label' => esc_html__( 'URL', 'jupiterx-core' ),
				'type' => Controls_Manager::URL,
				'condition' => [
					'link_to' => 'custom',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'video_source',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Source', 'jupiterx-core' ),
				'default' => 'youtube',
				'options' => [
					'youtube' => esc_html__( 'YouTube', 'jupiterx-core' ),
					'vimeo' => esc_html__( 'Vimeo', 'jupiterx-core' ),
					'hosted' => esc_html__( 'Self Hosted', 'jupiterx-core' ),
				],
				'condition' => [
					'item_type' => 'video',
				],
			]
		);

		$repeater->add_control(
			'youtube_poster',
			[
				'label' => esc_html__( 'Image', 'jupiterx-core' ),
				'type' => Controls_Manager::MEDIA,
				'media_types' => [ 'image' ],
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'description' => sprintf(
					/* translators: %1$s: Open html tag | %2$s: Close html tag */
					esc_html__( 'To display this image, please go to Layout > Video Preview and select %1$s Poster %2$s option.', 'jupiterx-core' ),
					'<b>',
					'</b>'
				),
				'condition' => [
					'item_type' => 'video',
					'video_source' => 'youtube',
				],
			]
		);

		$repeater->add_control(
			'youtube_url',
			[
				'label' => esc_html__( 'Link', 'jupiterx-core' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => esc_html__( 'Enter your URL', 'jupiterx-core' ) . ' (YouTube)',
				'label_block' => true,
				'options' => false,
				'default' => [
					'url' => 'https://www.youtube.com/watch?v=GuAL8OhcbNk',
				],
				'condition' => [
					'item_type' => 'video',
					'video_source' => 'youtube',
				],
			]
		);

		$repeater->add_control(
			'vimeo_poster',
			[
				'label' => esc_html__( 'Image', 'jupiterx-core' ),
				'type' => Controls_Manager::MEDIA,
				'media_types' => [ 'image' ],
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'item_type' => 'video',
					'video_source' => 'vimeo',
				],
			]
		);

		$repeater->add_control(
			'vimeo_url',
			[
				'label' => esc_html__( 'Link', 'jupiterx-core' ),
				'type' => Controls_Manager::URL,
				'options' => false,
				'default' => [
					'url' => 'https://vimeo.com/235215203',
				],
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => esc_html__( 'Enter your URL', 'jupiterx-core' ) . ' (Vimeo)',
				'label_block' => true,
				'condition' => [
					'item_type' => 'video',
					'video_source' => 'vimeo',
				],
			]
		);

		$repeater->add_control(
			'video_insert_url',
			[
				'label' => esc_html__( 'External URL', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'item_type' => 'video',
					'video_source' => 'hosted',
				],
			]
		);

		$repeater->add_control(
			'video_hosted_url',
			[
				'label' => esc_html__( 'Choose File', 'jupiterx-core' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::MEDIA_CATEGORY,
					],
				],
				'media_type' => 'video',
				'condition' => [
					'item_type' => 'video',
					'video_source' => 'hosted',
					'video_insert_url' => '',
				],
			]
		);

		$repeater->add_control(
			'video_external_url',
			[
				'label' => esc_html__( 'URL', 'jupiterx-core' ),
				'type' => Controls_Manager::URL,
				'autocomplete' => false,
				'options' => false,
				'label_block' => true,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'media_type' => 'video',
				'placeholder' => esc_html__( 'Enter your URL', 'jupiterx-core' ),
				'condition' => [
					'item_type' => 'video',
					'video_source' => 'hosted',
					'video_insert_url' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'video_external_url_poster',
			[
				'label' => esc_html__( 'Poster', 'jupiterx-core' ),
				'type' => Controls_Manager::MEDIA,
				'media_types' => [ 'image' ],
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'item_type' => 'video',
					'video_source' => 'hosted',
				],
			]
		);

		$repeater->add_control(
			'audio_source',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Source', 'jupiterx-core' ),
				'default' => 'spotify',
				'options' => [
					'spotify' => esc_html__( 'Spotify', 'jupiterx-core' ),
					'soundcloud' => esc_html__( 'Soundcloud', 'jupiterx-core' ),
				],
				'condition' => [
					'item_type' => 'audio',
				],
			]
		);

		$repeater->add_control(
			'spotify_url',
			[
				'label' => esc_html__( 'Link', 'jupiterx-core' ),
				'type' => Controls_Manager::URL,
				'options' => false,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => esc_html__( 'Enter your URL', 'jupiterx-core' ) . ' (Spotify)',
				'label_block' => true,
				'condition' => [
					'item_type' => 'audio',
					'audio_source' => 'spotify',
				],
			]
		);

		$repeater->add_control(
			'spotify_poster',
			[
				'label' => esc_html__( 'Poster', 'jupiterx-core' ),
				'type' => Controls_Manager::MEDIA,
				'media_types' => [ 'image' ],
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'item_type' => 'audio',
					'audio_source' => 'spotify',
				],
			]
		);

		$repeater->add_control(
			'soundcloud_url',
			[
				'label' => esc_html__( 'Link', 'jupiterx-core' ),
				'type' => Controls_Manager::URL,
				'options' => false,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => esc_html__( 'Enter your URL', 'jupiterx-core' ) . ' (Soundcloud)',
				'label_block' => true,
				'condition' => [
					'item_type' => 'audio',
					'audio_source' => 'soundcloud',
				],
			]
		);

		$repeater->add_control(
			'soundcloud_poster',
			[
				'label' => esc_html__( 'Poster', 'jupiterx-core' ),
				'type' => Controls_Manager::MEDIA,
				'media_types' => [ 'image' ],
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'item_type' => 'audio',
					'audio_source' => 'soundcloud',
				],
			]
		);

		$repeater->add_control(
			'category_label',
			[
				'type' => Controls_Manager::TEXT,
				'label' => esc_html__( 'Label', 'jupiterx-core' ),
				'default' => esc_html__( 'Category', 'jupiterx-core' ),
				'condition' => [
					'item_type' => 'category',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'fields',
			[
				'type' => Controls_Manager::REPEATER,
				'label' => esc_html__( 'Galleries', 'jupiterx-core' ),
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ item_type }}}',
				'default' => [
					[
						'item_type' => 'image',
					],
				],
			]
		);

		$this->add_control(
			'order_by',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Order By', 'jupiterx-core' ),
				'options' => [
					'' => esc_html__( 'Default', 'jupiterx-core' ),
					'random' => esc_html__( 'Random', 'jupiterx-core' ),
				],
				'default' => '',
			]
		);

		$this->add_control(
			'lazy_load',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Lazy Load', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_content_layout_controls() {
		$this->start_controls_section(
			'layout',
			[
				'label' => esc_html__( 'Layout', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'gallery_layout',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Layout', 'jupiterx-core' ),
				'default' => 'grid',
				'options' => [
					'grid' => esc_html__( 'Grid', 'jupiterx-core' ),
					'justified' => esc_html__( 'Justified', 'jupiterx-core' ),
					'masonry' => esc_html__( 'Masonry', 'jupiterx-core' ),
				],
				'prefix_class' => 'gallery-layout-',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'content_visibility',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Content Visibility', 'jupiterx-core' ),
				'default' => 'visible',
				'render_type' => 'template',
				'options' => [
					'none' => esc_html__( 'No Content', 'jupiterx-core' ),
					'hover-visible' => esc_html__( 'Visible on Hover', 'jupiterx-core' ),
					'visible' => esc_html__( 'Always Visible', 'jupiterx-core' ),
				],
				'prefix_class' => 'content-visibility-',
			]
		);

		$this->add_control(
			'content_layout_hover_visible',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Content Layout', 'jupiterx-core' ),
				'default' => 'overlay',
				'options' => [
					'overlay' => esc_html__( 'Content Overlay', 'jupiterx-core' ),
				],
				'prefix_class' => 'content-layout-',
				'frontend_available' => true,
				'render_type' => 'template',
				'condition' => [
					'content_visibility' => 'hover-visible',
				],
			]
		);

		$this->add_control(
			'content_layout_visible',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Content Layout', 'jupiterx-core' ),
				'default' => 'under-image',
				'options' => [
					'overlay' => esc_html__( 'Content Overlay', 'jupiterx-core' ),
					'under-image' => esc_html__( 'Content Under Image', 'jupiterx-core' ),
				],
				'prefix_class' => 'content-layout-',
				'render_type' => 'template',
				'frontend_available' => true,
				'condition' => [
					'content_visibility' => 'visible',
				],
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => esc_html__( 'Columns', 'jupiterx-core' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
				'tablet_default' => 2,
				'mobile_default' => 1,
				'min' => 1,
				'max' => 10,
				'render_type' => 'template',
				'condition' => [
					'gallery_layout!' => 'justified',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--mg-columns: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'ideal_row_height',
			[
				'label' => esc_html__( 'Row Height', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 500,
					],
				],
				'default' => [
					'size' => 200,
				],
				'condition' => [
					'gallery_layout' => 'justified',
				],
				'render_type' => 'template',
				'required' => true,
				'selectors' => [
					'{{WRAPPER}}.gallery-layout-justified .content.active .gallery-item [class*="type-"]' => 'height: {{SIZE}}px',
					'{{WRAPPER}}.gallery-layout-justified .content.active .gallery-item img' => 'height: {{SIZE}}px',
					'{{WRAPPER}}.gallery-layout-justified .content.active .gallery-item iframe' => 'height: {{SIZE}}px',
					'{{WRAPPER}}.gallery-layout-justified .content.active .gallery-item video' => 'height: {{SIZE}}px',
				],
			]
		);

		$this->add_responsive_control(
			'gap',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'required' => true,
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} ' => '--mg-gap: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'title',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Title', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'render_type' => 'ui',
				'selectors_dictionary' => [
					'yes' => 'display: block;',
					'' => 'display: none;',
				],
				'selectors' => [
					'{{WRAPPER}} .overlay .title' => '{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'caption',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Caption', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'render_type' => 'template',
				'selectors_dictionary' => [
					'yes' => 'display: block;',
					'' => 'display: none;',
				],
				'selectors' => [
					'{{WRAPPER}} .overlay .caption' => '{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'description',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Description', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'render_type' => 'template',
				'selectors_dictionary' => [
					'yes' => 'display: block;',
					'' => 'display: none;',
				],
				'selectors' => [
					'{{WRAPPER}} .overlay .description' => '{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'alt',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Alt', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'render_type' => 'template',
				'selectors_dictionary' => [
					'yes' => 'display: block;',
					'' => 'display: none;',
				],
				'selectors' => [
					'{{WRAPPER}} .overlay .alt' => '{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'video_play_icon',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Video Play Icon', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'selectors' => [
					'{{WRAPPER}} .play-icon' => 'visibility: visible; scale: 1;',
				],
			]
		);

		$this->add_control(
			'video_preview',
			[
				'label' => esc_html__( 'Video Preview', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'player',
				'render_type' => 'template',
				'options' => [
					'player' => esc_html__( 'Video Player', 'jupiterx-core' ),
					'poster' => esc_html__( 'Poster', 'jupiterx-core' ),
				],
				'prefix_class' => 'video-preview-',
			]
		);

		$this->add_responsive_control(
			'aspect_ratio',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Aspect Ratio', 'jupiterx-core' ),
				'default' => '1 / 1',
				'options' => [
					'1 / 1' => '1:1',
					'2 / 3' => '3:2',
					'3 / 4' => '4:3',
					'16 / 9' => '9:16',
					'9 / 16' => '16:9',
					'9 / 21' => '21:9',
				],
				'condition' => [
					'gallery_layout' => 'grid',
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tab-contents .gallery-item [class^="type-"]' => 'padding-bottom: calc({{VALUE}} * 100%) !important;',
				],
			]
		);

		$this->add_responsive_control(
			'media_position',
			[
				'label' => esc_html__( 'Media Position', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'center center',
				'options' => [
					'center center' => esc_html__( 'Center Center', 'jupiterx-core' ),
					'center left' => esc_html__( 'Center Left', 'jupiterx-core' ),
					'center right' => esc_html__( 'Center Right', 'jupiterx-core' ),
					'top center' => esc_html__( 'Top Center', 'jupiterx-core' ),
					'top left' => esc_html__( 'Top Left', 'jupiterx-core' ),
					'top right' => esc_html__( 'Top Right', 'jupiterx-core' ),
					'bottom center' => esc_html__( 'Bottom Center', 'jupiterx-core' ),
					'bottom left' => esc_html__( 'Bottom Left', 'jupiterx-core' ),
					'bottom right' => esc_html__( 'Bottom Right', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tab-contents .gallery-item [class^="type-"] img' => '-o-object-position: {{VALUE}}; object-position: {{VALUE}};',
				],
				'condition' => [
					'gallery_layout' => 'grid',
				],
			]
		);

		$this->add_group_control(
			'image-size',
			[
				'name' => 'thumbnail_image',
				'default' => 'medium',
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_filter_bar_controls() {
		$this->start_controls_section(
			'filter_bar',
			[
				'label' => esc_html__( 'Filter Bar', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'all_filter',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( '"All" Filter', 'jupiterx-core' ),
				'return_value' => 'show',
				'default' => 'show',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'all_filter_label',
			[
				'type' => Controls_Manager::TEXT,
				'label' => esc_html__( '"All" Filter Label', 'jupiterx-core' ),
				'default' => esc_html__( 'All', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'all_filter' => 'show',
				],
			]
		);

		$this->add_control(
			'filter_bar_animation',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Animation', 'jupiterx-core' ),
				'default' => '',
				'options' => [
					'' => esc_html__( 'None', 'jupiterx-core' ),
					'fade' => esc_html__( 'Fade', 'jupiterx-core' ),
					'grow' => esc_html__( 'Grow', 'jupiterx-core' ),
				],
				'prefix_class' => 'gallery-item-animation-',
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_image_controls() {
		$this->start_controls_section(
			'image_style',
			[
				'label' => esc_html__( 'Image', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'image_tabs' );

		$this->start_controls_tab(
			'image_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'image_border_color',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#111111',
				'selectors' => [
					'{{WRAPPER}} .gallery-item [class*="type-"]' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'image_border_width',
			[
				'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'px' => [
						'size' => 0,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .gallery-item [class*="type-"]' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
				],
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gallery-item [class*="type-"]' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .gallery-item .poster:before' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'image_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'image_border_color_hover',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gallery-item:hover [class*="type-"]' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'image_border_radius_hover',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gallery-item:hover [class*="type-"]' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'image_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'None', 'jupiterx-core' ),
					'3d-zoom' => esc_html__( '3D Zoom', 'jupiterx-core' ),
					'zoom' => esc_html__( 'Zoom', 'jupiterx-core' ),
					'zoom-in' => esc_html__( 'Zoom In', 'jupiterx-core' ),
					'zoom-out' => esc_html__( 'Zoom Out', 'jupiterx-core' ),
					'move-left' => esc_html__( 'Move Left', 'jupiterx-core' ),
					'move-right' => esc_html__( 'Move Right', 'jupiterx-core' ),
					'v-move-up' => esc_html__( 'V Move Up', 'jupiterx-core' ),
					'move-down' => esc_html__( 'Move Down', 'jupiterx-core' ),
				],
				'separator' => 'before',
				'default' => '',
				'prefix_class' => 'image-hover-animation-',
				'frontend_available' => true,
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'image_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'jupiterx-core' ) . ' (ms)',
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 800,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3000,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--image-transition-duration: {{SIZE}}ms',
				],
				'condition' => [
					'image_hover_animation!' => 'zoom',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_style_content_controls() {
		$this->start_controls_section(
			'content_style',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_alignment',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .gallery-item .overlay' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .gallery-item .overlay p' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_vertical_position',
			[
				'label' => esc_html__( 'Vertical Position', 'jupiterx-core' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'default' => 'middle',
				'selectors' => [
					'{{WRAPPER}} .gallery-item .overlay' => 'justify-content: {{VALUE}}',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'content_layout_visible',
							'operator' => '===',
							'value' => 'overlay',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'content_layout_visible',
									'operator' => '===',
									'value' => 'under-image',
								],
								[
									'name' => 'content_visibility',
									'operator' => '===',
									'value' => 'hover-visible',
								],
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gallery-item .overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'title_heading',
			[
				'label' => esc_html__( 'Title', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tab-contents .title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tab-contents .title',
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tab-contents .title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'caption_heading',
			[
				'label' => esc_html__( 'Caption', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'caption_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tab-contents .caption' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'caption_typography',
				'selector' => '{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tab-contents .caption',
			]
		);

		$this->add_responsive_control(
			'caption_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tab-contents .caption' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'description_heading',
			[
				'label' => esc_html__( 'Description', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tab-contents .description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tab-contents .description',
			]
		);

		$this->add_responsive_control(
			'description_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tab-contents .description' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'alt_heading',
			[
				'label' => esc_html__( 'Alt', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'alt_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tab-contents .alt' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'alt_typography',
				'selector' => '{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tab-contents .alt',
			]
		);

		$this->add_responsive_control(
			'alt_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tab-contents .alt' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'content_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'groups' => [
					[
						'label' => esc_html__( 'None', 'jupiterx-core' ),
						'options' => [
							'' => esc_html__( 'None', 'jupiterx-core' ),
						],
					],
					[
						'label' => esc_html__( 'Entrance', 'jupiterx-core' ),
						'options' => [
							'enter-from-right' => 'Slide In Right',
							'enter-from-left' => 'Slide In Left',
							'enter-from-top' => 'Slide In Up',
							'enter-from-bottom' => 'Slide In Down',
							'enter-zoom-in' => 'Zoom In',
							'enter-zoom-out' => 'Zoom Out',
							'fade-in' => 'Fade In',
						],
					],
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'content_visibility',
							'operator' => '===',
							'value' => 'hover-visible',
						],
						[
							'name' => 'image_hover_animation',
							'operator' => '!==',
							'value' => 'zoom',
						],
					],
				],
				'default' => 'fade-in',
				'separator' => 'before',
				'render_type' => 'ui',
				'prefix_class' => 'content-animation-',
			]
		);

		$this->add_control(
			'content_hover_animation_reaction_exit',
			[
				'label' => esc_html__( 'Hover Animation', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'groups' => [
					[
						'label' => esc_html__( 'None', 'jupiterx-core' ),
						'options' => [
							'' => esc_html__( 'None', 'jupiterx-core' ),
						],
					],
					[
						'label' => esc_html__( 'Reaction', 'jupiterx-core' ),
						'options' => [
							'grow' => 'Grow',
							'shrink' => 'Shrink',
							'move-right' => 'Move Right',
							'move-left' => 'Move Left',
							'move-up' => 'Move Up',
							'move-down' => 'Move Down',
						],
					],
					[
						'label' => esc_html__( 'Exit', 'jupiterx-core' ),
						'options' => [
							'exit-to-right' => 'Slide Out Right',
							'exit-to-left' => 'Slide Out Left',
							'exit-to-top' => 'Slide Out Up',
							'exit-to-bottom' => 'Slide Out Down',
							'exit-zoom-in' => 'Zoom In',
							'exit-zoom-out' => 'Zoom Out',
							'fade-out' => 'Fade Out',
						],
					],
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'content_visibility',
							'operator' => '===',
							'value' => 'visible',
						],
						[
							'name' => 'content_layout_visible',
							'operator' => '!==',
							'value' => 'under-image',
						],
						[
							'name' => 'image_hover_animation',
							'operator' => '!==',
							'value' => 'zoom',
						],
					],
				],
				'default' => '',
				'separator' => 'before',
				'render_type' => 'ui',
				'prefix_class' => 'content-animation-',
			]
		);

		$this->add_control(
			'content_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'jupiterx-core' ) . ' (ms)',
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 800,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3000,
					],
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'image_hover_animation',
							'operator' => '!==',
							'value' => 'zoom',
						],
						[
							'name' => 'content_layout_visible',
							'operator' => '!==',
							'value' => 'under-image',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--content-transition-duration: {{SIZE}}ms;',
				],
			]
		);

		$this->add_control(
			'content_sequenced_animation',
			[
				'label' => esc_html__( 'Sequenced Animation', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'image_hover_animation',
							'operator' => '!==',
							'value' => 'zoom',
						],
						[
							'name' => 'content_layout_visible',
							'operator' => '!==',
							'value' => 'under-image',
						],
					],
				],
				'render_type' => 'ui',
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_overlay_controls() {
		$this->start_controls_section(
			'style_overlay',
			[
				'label' => esc_html__( 'Overlay', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'content_layout_visible!' => 'under-image',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_overlay' );

		$this->start_controls_tab(
			'tabs_overlay_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'background',
			[
				'name' => 'overlay_normal_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .gallery-item [class*="type-"]:after, {{WRAPPER}} .gallery-item .poster:before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_overlay_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'background',
			[
				'name' => 'overlay_hover_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .gallery-item:hover [class*="type-"]:after, {{WRAPPER}} .gallery-item:hover .poster:before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_style_filter_bar_controls() {
		$this->start_controls_section(
			'style_filter_bar',
			[
				'label' => esc_html__( 'Filter Bar', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'filter_bar_container_heading',
			[
				'label' => esc_html__( 'Filter Bar Container', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'filter_bar_container_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'filter_bar_gap',
			[
				'label' => esc_html__( 'Gap', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'size' => 20,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'filter_bar_container_background',
				'label' => esc_html__( 'Background', 'jupiterx-core' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs',
			]
		);

		$this->add_control(
			'sticky_filter_bar',
			[
				'label' => esc_html__( 'Sticky Bar', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'prefix_class' => 'sticky-filter-bar-',
				'return_value' => 'stick',
				'default' => 'false',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'filter_bar_container_border',
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs',
			]
		);

		$this->add_control(
			'filter_bar_container_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'filter_bar_container_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs',
			]
		);

		$this->add_control(
			'filter_bar_space_between_and_container_border_heading',
			[
				'label' => esc_html__( 'Category Items', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'filter_bar_space_between',
			[
				'label' => esc_html__( 'Space Between', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs' => 'gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'filter_bar_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs button.tab-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'filter_bar_alignment',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_filter_bar' );

		$this->start_controls_tab(
			'tab_filter_bar_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'filter_bar_normal_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs button.tab-item:not(.active)' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'filter_bar_normal_typography',
				'selector' => '{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs button.tab-item:not(.active)',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'filter_bar_normal_background',
				'label' => esc_html__( 'Background', 'jupiterx-core' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs button.tab-item:not(.active)',
			]
		);

		$this->add_control(
			'filter_bar_normal_heading',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'filter_bar_normal_border',
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs button.tab-item:not(.active)',
			]
		);

		$this->add_control(
			'filter_bar_normal_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs button.tab-item:not(.active)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'filter_bar_normal_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs button.tab-item:not(.active)',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_filter_bar_active',
			[
				'label' => esc_html__( 'Active', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'filter_bar_active_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs button.tab-item.active' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'filter_bar_active_typography',
				'selector' => '{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs button.tab-item.active',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'filter_bar_active_background',
				'label' => esc_html__( 'Background', 'jupiterx-core' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs button.tab-item.active',
			]
		);

		$this->add_control(
			'filter_bar_active_heading',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'filter_bar_active_border',
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs button.tab-item.active',
			]
		);

		$this->add_control(
			'filter_bar_active_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs button.tab-item.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'filter_bar_active_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs button.tab-item.active',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_filter_bar_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'filter_bar_hover_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs button.tab-item:not(.active):hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'filter_bar_hover_typography',
				'selector' => '{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs button.tab-item:not(.active):hover',
			]
		);

		$this->add_group_control(
			'background',
			[
				'name' => 'filter_bar_hover_background',
				'label' => esc_html__( 'Background', 'jupiterx-core' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs button.tab-item:not(.active):hover',
			]
		);

		$this->add_control(
			'filter_bar_hover_heading',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'filter_bar_hover_border',
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs button.tab-item:not(.active):hover',
			]
		);

		$this->add_control(
			'filter_bar_hover_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs button.tab-item:not(.active):hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'filter_bar_hover_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tabs button.tab-item:not(.active):hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_video_play_icon() {
		$this->start_controls_section(
			'style_video_play_icon',
			[
				'label' => esc_html__( 'Video Play Icon', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'play_icon',
			[
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-play',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'video_play_icon_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}}' => '--play-icon-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'video_play_icon_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tab-contents .content .gallery-item [class^="type-"] .poster .play-icon i' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-media-gallery-wrapper .raven-media-gallery-tab-contents .content .gallery-item [class^="type-"] .poster .play-icon svg' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Add iFrame, Source and custom attributes on div and a tag to allowed wp_kses_post tags.
	 *
	 * It will allow every attribute that user add to the `url_link_to` control and
	 * delete the allowance when render is done.
	 *
	 * @param array  $tags Allowed tags, attributes, and/or entities.
	 * @param string $context Context to judge allowed tags by. Allowed values are 'post'.
	 *
	 * @return array
	 * @since 3.0.0
	 */
	public function allow_tags_on_wp_kses_post( $tags, $context ) {
		$custom_attributes = $this->get_all_custom_attributes();

		if ( 'post' === $context ) {
			$tags['iframe'] = [
				'src' => true,
				'height' => true,
				'width' => true,
				'frameborder' => true,
				'allowfullscreen' => true,
				'loading' => true,
			];

			$tags['source'] = [
				'src' => true,
			];

			$tags['div'] = array_merge(
				[
					'class' => true,
					'data-raven-content-index' => true,
					'data-elementor-open-lightbox' => true,
					'data-elementor-lightbox-slideshow' => true,
					'data-elementor-lightbox-video' => true,
					'data-elementor-lightbox' => true,
				],
				$custom_attributes
			);

			$tags['a'] = array_merge(
				[
					'class' => true,
					'href' => true,
					'data-elementor-open-lightbox' => true,
					'data-elementor-lightbox-slideshow' => true,
					'data-elementor-lightbox-video' => true,
					'data-elementor-lightbox' => true,
					'target' => true,
					'rel' => true,
				],
				$custom_attributes
			);

			$tags['svg'] = [
				'xmlns' => true,
				'fill' => true,
				'viewbox' => true,
				'role' => true,
				'aria-hidden' => true,
				'focusable' => true,
			];

			$tags['path'] = [
				'd' => true,
				'fill' => true,
			];
		}

		return $tags;
	}

	/**
	 * Get all custom attributes and return the array for wp_kses_post.
	 *
	 * @return array
	 */
	private function get_all_custom_attributes() {
		$settings          = $this->get_settings();
		$custom_attributes = [];
		$separated         = [];

		foreach ( $settings['fields'] as $field ) {
			if ( 'image' !== $field['item_type'] ) {
				continue;
			}

			if ( ! empty( $field['url_link_to']['custom_attributes'] ) ) {
				$custom_attributes[] = $field['url_link_to']['custom_attributes'];
			}
		}

		if ( ! empty( $custom_attributes ) ) {
			foreach ( $custom_attributes as $attribute ) {
				$attribute = explode( ',', $attribute );

				foreach ( $attribute as $attr ) {
					$separated[ explode( '|', $attr )[0] ] = true;
				}
			}
		}

		return $separated;
	}

	protected function render() {
		$settings = $this->get_settings();

		if ( 'zoom' === $settings['image_hover_animation'] ) {
			wp_enqueue_script( 'zoom' );
		}

		add_filter( 'wp_kses_allowed_html', [ $this, 'allow_tags_on_wp_kses_post' ], 10, 2 );

		$tabs                = $this->get_items_categories( $settings['fields'] );
		$items_with_category = $this->separate_items_by_category( $settings['fields'] );

		echo '<div class="raven-media-gallery-wrapper">';

		if ( ! empty( $tabs ) ) {
			echo $this->render_gallery_tabs( $tabs, $settings );
		}

		echo $this->render_tab_contents( $items_with_category, $settings );

		echo '</div>';

		remove_filter( 'wp_kses_allowed_html', [ $this, 'allow_tags_on_wp_kses_post' ] );
	}

	protected function separate_items_by_category( $fields ) {
		$chunked_array = [];
		$count         = - 1;

		foreach ( $fields as $item ) {
			if ( 'category' === $item['item_type'] ) {
				$count ++;
				$chunked_array[ $count ] = [];
			}

			$chunked_array[ $count ][] = $item;
		}

		return $chunked_array;
	}

	protected function get_items_categories( $fields ) {
		$categories = [];

		foreach ( $fields as $field ) {
			if ( 'category' === $field['item_type'] ) {
				$categories[] = $field;
			}
		}

		return $categories;
	}

	protected function render_gallery_tabs( $tabs, $settings ) {
		$html             = '<div class="raven-media-gallery-tabs">';
		$active_tab_class = 'active';
		$has_category     = $this->gallery_has_category( $settings );

		if ( 'show' === $settings['all_filter'] || ! $has_category ) {
			$active_tab_class = '';
			$html            .= sprintf(
				'<button data-raven-tab-index="0" class="tab-item active">%s</button>',
				$settings['all_filter_label']
			);
		}

		foreach ( $tabs as $index => $tab ) :
			$html .= sprintf(
				'<button data-raven-tab-index="%1$d" class="tab-item %2$s">%3$s</button>',
				esc_attr( $index + 1 ),
				0 === $index ? esc_attr( $active_tab_class ) : '',
				esc_html( $tab['category_label'] )
			);
		endforeach;

		$html .= '</div>';

		return wp_kses_post( $html );
	}

	protected function render_tab_contents( $items, $settings ) {
		$widget_id        = $this->get_id();
		$active_tab_class = 'active';
		$has_category     = $this->gallery_has_category( $settings );

		$html = '<div class="raven-media-gallery-tab-contents">';

		if ( 'show' === $settings['all_filter'] || ! $has_category ) {
			$active_tab_class = '';
			$html            .= '<div data-raven-content-index="0" class="content active">' . $this->render_all_tab( $items, $settings ) . '</div>';
		}

		if ( count( $items ) < 2 ) {
			$html .= '</div>';

			return wp_kses_post( $html );
		}

		foreach ( $items as $index => $chunks ) {
			$html .= sprintf(
				'<div data-raven-content-index="%1$d" class="content %2$s">',
				$index + 1,
				0 === $index ? $active_tab_class : ''
			);

			$lightbox_id = 'tab-' . $index . '-' . esc_attr( $widget_id );

			if ( 'random' === $settings['order_by'] ) {
				shuffle( $chunks );
			}

			foreach ( $chunks as $item ) {
				if ( 'category' === $item['item_type'] ) {
					continue;
				}
				$item['lightbox_id'] = $lightbox_id;
				$html               .= $this->render_item( $item, $settings );
			}

			$html .= '</div>';
		}

		$html .= '</div>';

		return wp_kses_post( $html );
	}

	/**
	 * @suppressWarnings(PHPMD.NPathComplexity)
	 */
	protected function render_item( $item, $settings ) {
		$video_modules = [
			'youtube' => YouTube::class,
			'vimeo'   => Vimeo::class,
			'hosted'  => Hosted_Video::class,
		];

		$audio_modules = [
			'spotify'    => Spotify::class,
			'soundcloud' => SoundCloud::class,
		];

		if ( 'image' === $item['item_type'] ) {
			return Image::render_item( $item, $settings, $this );
		}

		if ( 'video' === $item['item_type'] ) {
			$class = '\\' . $video_modules[ $item['video_source'] ];

			return $class::render_item( $item, $settings );
		}

		if ( 'audio' === $item['item_type'] ) {
			$class = '\\' . $audio_modules[ $item['audio_source'] ];

			return $class::render_item( $item, $settings );
		}
	}

	protected function render_all_tab( $data, $settings ) {
		$widget_id   = $this->get_id();
		$html        = '';
		$lightbox_id = 'all-' . esc_attr( $widget_id );

		foreach ( $data as $chunks ) {
			if ( 'random' === $settings['order_by'] ) {
				shuffle( $chunks );
			}

			foreach ( $chunks as $item ) {
				if ( 'category' === $item['item_type'] ) {
					continue;
				}

				$item['lightbox_id'] = $lightbox_id;
				$html               .= $this->render_item( $item, $settings );
			}
		}

		return $html;
	}

	protected function gallery_has_category( $settings ) {
		if ( ! empty( $this->get_items_categories( $settings['fields'] ) ) ) {
			return true;
		}

		return false;
	}
}
