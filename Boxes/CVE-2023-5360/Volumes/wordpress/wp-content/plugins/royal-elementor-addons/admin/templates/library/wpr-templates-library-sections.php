<?php
namespace WprAddons\Admin\Templates\Library;
use WprAddons\Classes\Utilities;
use WprAddons\Admin\Templates\Library\WPR_Templates_Data;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Templates_Library_Sections setup
 *
 * @since 1.0
 */
class WPR_Templates_Library_Sections {

	/**
	** Constructor
	*/
	public function __construct() {

		// Template Library Popup
		add_action( 'wp_ajax_render_library_templates_sections', [ $this, 'render_library_templates_sections' ] );

	}

	/**
	** Template Library Popup
	*/
	public static function render_library_templates_sections() {
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

                            $sections = WPR_Templates_Data::get_available_sections();
                            
							foreach ($sections as $title => $data) {
                                $slug = self::create_slug($title);
								echo '<li data-filter="'. esc_attr($slug) .'">'. esc_html($title) .'</li>';
							}

							?>
						</ul>
					</div>
				</div>
			</div>
			<div class="wpr-tplib-search">
				<input type="text" placeholder="Search Template">
				<i class="eicon-search"></i>
			</div>
		</div>

		<div class="wpr-tplib-template-gird wpr-tplib-sections-grid elementor-clearfix">
			<div class="wpr-tplib-template-gird-inner">

			<?php

			foreach ($sections as $title => $data) :
                $slug = self::create_slug($title);

				for ( $i=0; $i < count($data); $i++ ) :

					$template_slug 	= $slug .'-'. $data[$i];
					$template_class = strpos($template_slug, 'pro') && !wpr_fs()->can_use_premium_code() ? ' wpr-tplib-pro-wrap' : '';

					if (defined('WPR_ADDONS_PRO_VERSION') && wpr_fs()->can_use_premium_code()) {
						$template_class .= ' wpr-tplib-pro-active';
					}

			?>

				<div class="wpr-tplib-template-wrap<?php echo esc_attr($template_class); ?>" data-title="<?php echo esc_attr(strtolower($title)); ?>">
					<div class="wpr-tplib-template" data-slug="<?php echo esc_attr($data[$i]); ?>" data-filter="<?php echo esc_attr($slug); ?>" data-preview-type="image">
						<div class="wpr-tplib-template-media">
							<img src="<?php echo esc_url('https://royal-elementor-addons.com/library/premade-sections/'. $slug .'/'. $data[$i] .'.jpg'); ?>">
							<div class="wpr-tplib-template-media-overlay">
								<i class="eicon-eye"></i>
							</div>
						</div>
						<div class="wpr-tplib-template-footer elementor-clearfix">
							<?php if ( !defined('WPR_ADDONS_PRO_VERSION') && ! wpr_fs()->can_use_premium_code() ) : ?>
								<h3><?php echo strpos($template_slug, 'pro') ? esc_html(str_replace('-pro', ' Pro', $title)) : esc_html(str_replace('-zzz', ' Pro', $title)); ?></h3>
							<?php else : ?>
								<h3><?php echo strpos($template_slug, 'pro') ? esc_html(str_replace('-pro', '', $title)) : esc_html(str_replace('-zzz', '', $title)); ?></h3>
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

		if ( !(isset($current_screen) && 'royal-addons_page_wpr-premade-sections' === $current_screen->id) ) {
			exit;
		}
	}

    public static function create_slug($str, $delimiter = '-'){

        $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
        return $slug;
    
    } 

}
