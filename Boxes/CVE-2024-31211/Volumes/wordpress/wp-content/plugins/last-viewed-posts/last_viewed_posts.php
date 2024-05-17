<?php
/*
Plugin Name: Last Viewed Posts by WPBeginner
Plugin URI: http://www.wpbeginner.com
Description: Shows your site's visitors a personalized list of posts and pages they have recently viewed.
Author: WPBeginner
Version: 1.0.0
Author URI: https://www.wpbeginner.com/
Requires at least: 4.9
Tested up to: 5.7
Requires PHP: 5.6
License: GPLv2
Text Domain: last-viewed-posts
*/

/*
 * Copyright 2007- Olaf Baumann  (http://zeitgrund.de)
 * Copyright 2013- WPBeginner (http://www.wpbeginner.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 *
 * Use:
 * For the output use the sidebar widget OR place following code just anywhere (outside the loop) into your theme (e.g. sidebar.php).
 * Note that the output will not appear if there's no cookie set (because cookies are disabled or the user didn't view any single post).
 * -------------------------------------------

<?php if (function_exists('zg_recently_viewed')): ?>
	<h2>Last viewed posts</h2>
	<?php zg_recently_viewed(); ?>
<?php endif; ?>

 * ------------------------------------------- */

require_once __DIR__ . '/inc/class-widget.php';
require_once __DIR__ . '/inc/namespace.php';
\AM\LastViewedPosts\bootstrap();


/* Backward compatibility template tag */
function zg_recently_viewed() {
	\AM\LastViewedPosts\recently_viewed();
}
