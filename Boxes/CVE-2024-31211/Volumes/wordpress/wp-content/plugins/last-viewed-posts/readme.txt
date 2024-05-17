=== Last Viewed Posts by WPBeginner ===
Contributors: noumaan, Olaf Baumann, smub, deb255
Stable tag: 1.0.0
Requires at least: 4.9
Tested up to: 5.7
Requires PHP: 5.6
License: GPLv2
Tags: last viewed posts, recently viewed posts, visited posts

This shows your site's visitors a personalized list of posts and pages they have recently viewed.

== Description ==

Installing this will allow you to use a widget or template tag to show your site's visitors a personalized list of posts and pages they have viewed.

The list of posts is saved in each visitor's web browser, so even if you get millions of visitors a month it won't affect the performance of your website.

Note: It doesn't store a global list of recently viewed posts by all users. Nothing is stored in your site's database. Every visitor has a custom lists of posts and pages they have viewed.

If JavaScript is disabled or no single post has been clicked, no output will be displayed.

The plugin comes with a widget and a template tag.

This plugin has been adopted and maintained by [WPBeginner](http://www.wpbeginner.com "WPBeginner - WordPress Tutorials for Beginners")

= What's Next? =

To learn more about WordPress, you can visit <a href="https://www.wpbeginner.com/" rel="friend">WPBeginner</a> for tutorials on topics like:

* <a href="http://www.wpbeginner.com/wordpress-performance-speed/" rel="friend" title="Ultimate Guide to WordPress Speed and Performance">WordPress Speed and Performance</a>
* <a href="http://www.wpbeginner.com/wordpress-security/" rel="friend" title="Ultimate WordPress Security Guide">WordPress Security</a>
* <a href="http://www.wpbeginner.com/wordpress-seo/" rel="friend" title="Ultimate WordPress SEO Guide for Beginners">WordPress SEO</a>

...and many more <a href="http://www.wpbeginner.com/category/wp-tutorials/" rel="friend" title="WordPress Tutorials">WordPress tutorials</a>.

If you like our Missed Scheduled Posts Publisher plugin, then consider checking out our other projects:

* <a href="https://optinmonster.com/" rel="friend">OptinMonster</a> – Get More Email Subscribers with the most popular conversion optimization plugin for WordPress.
* <a href="https://wpforms.com/" rel="friend">WPForms</a> – #1 drag & drop online form builder for WordPress (trusted by 4 million sites).
* <a href="https://www.monsterinsights.com/" rel="friend">MonsterInsights</a> – See the Stats that Matter and Grow Your Business with Confidence. Best Google Analytics Plugin for WordPress.
* <a href="https://www.seedprod.com/" rel="friend">SeedProd</a> – Create beautiful landing pages with our powerful drag & drop landing page builder.
* <a href="https://wpmailsmtp.com/" rel="friend">WP Mail SMTP</a> – Improve email deliverability for your contact form with the most popular SMTP plugin for WordPress.
* <a href="https://rafflepress.com/" rel="friend">RafflePress</a> – Best WordPress giveaway and contest plugin to grow traffic and social followers.
* <a href="https://www.smashballoon.com/" rel="friend">Smash Balloon</a> – #1 social feeds plugin for WordPress - display social media content in WordPress without code.
* <a href="https://aioseo.com/" rel="friend">AIOSEO</a> – the original WordPress SEO plugin to help you rank higher in search results (trusted by over 2 million sites).
* <a href="https://www.pushengage.com/" rel="friend">PushEngage</a> – Connect with visitors after they leave your website with the leading web push notification plugin.
* <a href="https://trustpulse.com/" rel="friend">TrustPulse</a> – Add real-time social proof notifications to boost your store conversions by up to 15%.

Visit <a href="http://www.wpbeginner.com/" rel="friend">WPBeginner</a> to learn from our <a href="http://www.wpbeginner.com/category/wp-tutorials/" rel="friend">WordPress Tutorials</a> and find out about other <a href="http://www.wpbeginner.com/category/plugins/" rel="friend">best WordPress plugins</a>.

== Installation ==

Viewed posts are always tracked as long as the plugin is active and the visitor has enabled JavaScript and local storage.

1. Install Last Viewed Posts by uploading the `last-viewed-posts` directory to the `/wp-content/plugins/` directory. (See instructions on <a href="https://www.wpbeginner.com/beginners-guide/step-by-step-guide-to-install-a-wordpress-plugin-for-beginners/" rel="friend">how to install a WordPress plugin</a>.)
2. Activate Last Viewed Posts through the `Plugins` menu in WordPress.

To display the list, you can use the widget that comes with the plugin, or use the following code and place it anywhere you want to outside the loop, e.g. sidebar.php :

	<?php if (function_exists('zg_recently_viewed')): ?>
		<h2>Last viewed posts</h2>
		<?php zg_recently_viewed(); ?>
	<?php endif; ?>

== Changelog ==

= 1.0.0 =
* Complete rewrite to use JavaScript rather than cookies to store custom posts.
* Updated list of supported WordPress versions.
* Improved performance for sites with a large number of visitors.

= 0.7.3 =
* Updated Readme.txt.
* Checked compatibility with WordPress 5.1.1.
* Miscellaneous updates.

= 0.7.2 =
* Plugin maintainance update.

= 0.7.1 =
* Post/Page ID values in cookie are sanitized for output. Upgrade is recommend for more security.

= 0.7 =
* Pages can now be recognized (optional).
* Custom Loop is not longer used. Now we make a database query to get the post title.

== Upgrade Notice ==

= 0.7.2 =
Maintainance release please upgrade.
