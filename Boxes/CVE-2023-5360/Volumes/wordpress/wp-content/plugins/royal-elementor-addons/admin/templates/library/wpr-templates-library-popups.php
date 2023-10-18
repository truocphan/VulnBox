<?php
namespace WprAddons\Admin\Templates\Library;
use WprAddons\Classes\Utilities;
use WprAddons\Admin\Templates\Library\WPR_Templates_Data;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Templates_Library_Popups setup
 *
 * @since 1.0
 */
class WPR_Templates_Library_Popups {

	/**
	** Constructor
	*/
	public function __construct() {

		// Template Library Popup
		add_action( 'wp_ajax_render_library_templates_popups', [ $this, 'render_library_templates_popups' ] );

	}

	/**
	** Template Library Popup
	*/
	public function render_library_templates_popups() {
		$license = !wpr_fs()->can_use_premium_code() ? 'free' : 'premium';

		?>

		<div class="wpr-tplib-sidebar" data-license="<?php echo esc_attr($license); ?>">
			<div class="wpr-tplib-search">
				<input type="text" placeholder="Search Template">
				<i class="eicon-search"></i>
			</div>

			<div class="wpr-tplib-filters-wrap">
				<div class="wpr-tplib-filters">
					<h3>
						<span><?php esc_html_e( 'Category', 'wpr-addons' ); ?></span>
						<i class="fas fa-angle-down"></i>
					</h3>

					<div class="wpr-tplib-filters-list">
						<ul>
							<li data-filter="all"><?php esc_html_e( 'All', 'wpr-addons' ) ?></li>
							<li data-filter="cookie"><?php esc_html_e( 'Cookie', 'wpr-addons' ) ?></li>
							<li data-filter="discount"><?php esc_html_e( 'Discount', 'wpr-addons' ) ?></li>
							<li data-filter="subscribe"><?php esc_html_e( 'Subscribe', 'wpr-addons' ) ?></li>
							<li data-filter="yesno"><?php esc_html_e( 'Yes/No', 'wpr-addons' ) ?></li>
						</ul>
					</div>
				</div>
			</div>

		</div>

		<div class="wpr-tplib-template-gird elementor-clearfix">
			<div class="wpr-tplib-template-gird-inner">

			<?php

			$popups = WPR_Templates_Data::get_available_popups();

			foreach ($popups as $type => $data) :

				for ( $i=0; $i < count($popups[$type]); $i++ ) :

					$template_slug 	= array_keys($popups[$type])[$i];
					$template_title = ucfirst($type) .' '. $template_slug;
					$preview_type 	= $popups[$type][$template_slug]['type'];
					$preview_url 	= $popups[$type][$template_slug]['url'];
					$template_class = ( strpos($template_slug, 'pro') && ! wpr_fs()->can_use_premium_code() ) ? ' wpr-tplib-pro-wrap' : '';

			?>

			<div class="wpr-tplib-template-wrap<?php echo esc_attr($template_class); ?>">
				<div class="wpr-tplib-template" data-slug="<?php echo esc_attr($template_slug); ?>" data-filter="<?php echo esc_attr($type); ?>" data-preview-type="<?php echo esc_attr($preview_type); ?>" data-preview-url="<?php echo esc_attr($preview_url); ?>">
					<div class="wpr-tplib-template-media">
						<img src="<?php echo esc_url('https://royal-elementor-addons.com/library/premade-styles/popups/'. $type .'/'. $template_slug .'.jpg'); ?>">
						<div class="wpr-tplib-template-media-overlay">
							<i class="eicon-eye"></i>
						</div>
					</div>
					<div class="wpr-tplib-template-footer elementor-clearfix">
						<h3><?php echo esc_html(str_replace('-pro', ' Pro', $template_title)); ?></h3>

						<?php if ( strpos($template_slug, 'pro') && ! wpr_fs()->can_use_premium_code() ) : ?>
							<span class="wpr-tplib-insert-template wpr-tplib-insert-pro"><i class="eicon-star"></i> <span><?php esc_html_e( 'Go Pro', 'wpr-addons' ); ?></span></span>
						<?php else : ?>
							<span class="wpr-tplib-insert-template"><i class="eicon-file-download"></i> <span><?php esc_html_e( 'Insert', 'wpr-addons' ); ?></span></span>
						<?php endif; ?>
					</div>
				</div>
			</div>

				<?php endfor; ?>
			<?php endforeach; ?>

			</div>
		</div>

		<?php exit();
	}

}