<?php
namespace WprAddons\Admin\Templates\Library;
use WprAddons\Classes\Utilities;
use WprAddons\Admin\Templates\Library\WPR_Templates_Data;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Templates_Library_Blocks setup
 *
 * @since 1.0
 */
class WPR_Templates_Library_Blocks {

	/**
	** Constructor
	*/
	public function __construct() {

		// Template Library Popup
		add_action( 'wp_ajax_render_library_templates_blocks', [ $this, 'render_library_templates_blocks' ] );

	}

	/**
	** Template Library Popup
	*/
	public static function render_library_templates_blocks() {
		$license = !wpr_fs()->can_use_premium_code() ? 'free' : 'premium';

		?>

		<div class="wpr-tplib-sidebar" data-license="<?php echo esc_attr($license); ?>">
			<div class="wpr-tplib-filters-wrap">
				<div class="wpr-tplib-filters">
					<h3>
						<span data-filter="all"><?php esc_html_e( 'Category', 'wpr-addons' ); ?></span>
						<i class="fas fa-angle-down"></i>
					</h3>

					<div class="wpr-tplib-filters-list">
						<ul>

							<li data-filter="all"><?php esc_html_e( 'All', 'wpr-addons' ) ?></li>

							<?php

							$modules = Utilities::get_available_modules( Utilities::get_registered_modules() );

							$exclude_widgets = [
								'logo',
								'mega-menu',
								'forms',
								'phone-call',
								'back-to-top',
								'popup-trigger',
								'lottie-animations',
								'taxonomy-list',
								'page-list',
								'elementor-template',
								'flip-carousel',
								'feature-list',
								'dual-color-heading',
								'reading-progress-bar',
								'image-accordion',
								'advanced-accordion',
								'charts',
							];
							
							foreach ($modules as $title => $slug) {
								if ( ! in_array($slug[0], $exclude_widgets) ) {
									echo '<li data-filter="'. esc_attr($slug[0]) .'">'. esc_html($title) .'</li>';
								}
							}

							?>
						</ul>
					</div>
				</div>

				<div class="wpr-tplib-sub-filters">
					<ul>
						<li data-sub-filter="all" class="wpr-tplib-activ-filter"><?php esc_html_e( 'All', 'wpr-addons' ); ?></li>
						<li data-sub-filter="grid"><?php esc_html_e( 'Grid', 'wpr-addons' ) ?></li>
						<li data-sub-filter="slider"><?php esc_html_e( 'Slider', 'wpr-addons' ) ?></li>
						<li data-sub-filter="carousel"><?php esc_html_e( 'Carousel', 'wpr-addons' ) ?></li>
					</ul>
				</div>
			</div>
			<div class="wpr-tplib-search">
				<input type="text" placeholder="Search Template">
				<i class="eicon-search"></i>
			</div>
		</div>

		<div class="wpr-tplib-template-gird elementor-clearfix">
			<div class="wpr-tplib-template-gird-inner">

			<?php

			$modules['Footer'] = [
				0 => 'footer',
				1 => '#',
			];

			$modules['Header'] = [
				0 => 'header',
				1 => '#',
			];

			foreach ($modules as $title => $data) :
				$module_slug = $data[0];
				$blocks = WPR_Templates_Data::get_available_blocks();

				if ( !isset($blocks[$module_slug]) ) {
					continue;
				}

				for ( $i=0; $i < count($blocks[$module_slug]); $i++ ) :

					$template_slug 	= array_keys($blocks[$module_slug])[$i];
					$template_sub 	= isset($blocks[$module_slug][$template_slug]['sub']) ? $blocks[$module_slug][$template_slug]['sub'] : '';
					$template_title = $title .' '. $template_slug;
					$preview_type 	= $blocks[$module_slug][$template_slug]['type'];
					$preview_url 	= $blocks[$module_slug][$template_slug]['url'];
					$template_class = (strpos($template_slug, 'pro') && !wpr_fs()->can_use_premium_code()) || (strpos($template_slug, 'zzz') && !wpr_fs()->can_use_premium_code()) ? ' wpr-tplib-pro-wrap' : '';

					if (defined('WPR_ADDONS_PRO_VERSION') && wpr_fs()->can_use_premium_code()) {
						$template_class .= ' wpr-tplib-pro-active';
					}

					$template_slug_for_image = strpos($template_slug, 'zzz') ? substr($template_slug, 0, -4) : $template_slug;

					// Add Extra Keywords for Search
					$data_template_title = $template_title;
					if ( false !== strpos($title, 'Form Builder') ) {
						$data_template_title .= ' contact';
					} else if ( false !== strpos($title, 'Nav Menu') ) {
						$data_template_title .= ' header';
					}

			?>

				<div class="wpr-tplib-template-wrap<?php echo esc_attr($template_class); ?>" data-title="<?php echo esc_attr(strtolower($data_template_title)); ?>">
					<div class="wpr-tplib-template" data-slug="<?php echo esc_attr($template_slug); ?>" data-filter="<?php echo esc_attr($module_slug); ?>" data-sub-filter="<?php echo esc_attr($template_sub); ?>" data-preview-type="<?php echo esc_attr($preview_type); ?>" data-preview-url="<?php echo esc_attr($preview_url); ?>">
						<div class="wpr-tplib-template-media">
							<img src="<?php echo esc_url('https://royal-elementor-addons.com/library/premade-styles/'. $module_slug .'/'. $template_slug_for_image .'.jpg'); ?>">
							<div class="wpr-tplib-template-media-overlay">
								<i class="eicon-eye"></i>
							</div>
						</div>
						<div class="wpr-tplib-template-footer elementor-clearfix">
							<?php if ( !defined('WPR_ADDONS_PRO_VERSION') && ! wpr_fs()->can_use_premium_code() ) : ?>
								<h3><?php echo strpos($template_slug, 'pro') ? esc_html(str_replace('-pro', ' Pro', $template_title)) : esc_html(str_replace('-zzz', ' Pro', $template_title)); ?></h3>
							<?php else : ?>
								<h3><?php echo strpos($template_slug, 'pro') ? esc_html(str_replace('-pro', '', $template_title)) : esc_html(str_replace('-zzz', '', $template_title)); ?></h3>
							<?php endif; ?>

							<?php if ( ( strpos($template_slug, 'pro') && !wpr_fs()->can_use_premium_code() ) || ( strpos($template_slug, 'zzz') ) && !wpr_fs()->can_use_premium_code() ) : ?>
								<span class="wpr-tplib-insert-template wpr-tplib-insert-pro"><i class="eicon-star"></i> <span><?php esc_html_e( 'Go Pro', 'wpr-addons' ); ?></span></span>
							<?php else : ?>
								<span class="wpr-tplib-insert-template"><i class="eicon-file-download"></i> <span><?php esc_html_e( 'Insert', 'wpr-addons' ); ?></span></span>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<?php endfor; ?>
			<?php endforeach;?>

			</div>
		</div>

		<?php

		$current_screen = get_current_screen();

		if ( !(isset($current_screen) && 'royal-addons_page_wpr-premade-blocks' === $current_screen->id) ) {
			exit;
		}
	}

}
