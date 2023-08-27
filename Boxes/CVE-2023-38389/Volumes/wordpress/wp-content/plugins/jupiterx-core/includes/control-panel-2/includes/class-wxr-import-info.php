<?php
/**
 * WXR Import Info class.
 *
 * As an abstraction of importing site info.
 *
 * @package Jupiter
 * @subpackage Template Import
 * @since 6.0.3
 *
 * @todo Clean up.
 *
 * phpcs:ignoreFile
 * @SuppressWarnings(PHPMD)
 */

/**
 * Initialize base variable of importing info.
 *
 * @since 6.0.3
 *
 * @see https://github.com/humanmade/WordPress-Importer/blob/master/class-wxr-import-info.php
 *
 * @codingStandardsIgnoreFile
 */
class JupiterX_Core_Control_Panel_WXR_Import_Info {
	public $home;
	public $siteurl;

	public $title;

	public $users = array();
	public $post_count = 0;
	public $media_count = 0;
	public $comment_count = 0;
	public $term_count = 0;

	public $generator = '';
	public $version;
}
