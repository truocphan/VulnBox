<?php
/**
 * Compatibility with Yoast SEO plugin.
 *
 * @since 1.6.11
 */

namespace Masteriyo\Compatibility\Seo;

class YoastSeo {
	/**
	 * Initialize.
	 *
	 * @since 1.6.11
	 *
	 * @return void
	 */
	public function init() {
		add_filter( 'wpseo_frontend_page_type_simple_page_id', array( $this, 'update_simple_page_id' ) );
	}

	/**
	 * Update simple page id.
	 *
	 * Courses page is actually an archive page, not a regular page.
	 * So, rank math seo plugin customization doesn't reflect.
	 * To make it compatible, we need to set that the current courses page is actually just a regular page,
	 * which can be achieved through the above hook.
	 *
	 * @see https://github.com/Yoast/wordpress-seo/blob/78df60708b2a56387e63cd42c0d0451a5a116e71/src/helpers/current-page-helper.php#L95
	 *
	 * @since 1.6.11
	 *
	 * @param int $page_id
	 * @return int
	 */
	public function update_simple_page_id( $page_id ) {
		if ( masteriyo_is_courses_page() ) {
			$page_id = masteriyo_get_page_id( 'courses' );
		}

		return $page_id;
	}
}
