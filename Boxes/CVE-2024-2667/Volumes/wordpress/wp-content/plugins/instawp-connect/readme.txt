=== InstaWP Connect - 1-click WP Staging & Migration ===
Contributors: instawp
Tags: clone, migrate, staging, backup, restore
Requires at least: 5.6
Tested up to: 6.5
Requires PHP: 5.6
Stable tag: 0.1.0.22
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html

Create a staging WordPress site from production (live site). Ideal for testing updates, version change or re-write. Sync back only the changes.

== Description ==
InstaWP Connect is a WordPress staging and migration plugin developed by the InstaWP team. It works as a companion tool for InstaWP - a famous tool for creating WordPress websites to test plugin or theme, product demo creation, client project delivery, and everything that can improve your WordPress workflows.

The aim of this plugin is to speed up your WordPress staging process and complete it in seconds without you putting in any effort.

Once you activate InstaWP Connect, you can click Create Site and connect your site to your InstaWP account. Once it’s connected, you can select to:

- Create quick staging [media folder will not be copied over].
- Create custom staging [skip plugins, themes, media, or any specific files or folders]
- Create full staging [everything from your live site will be added to the staging site]

One-click, and the staging creation process will begin!

Once the process is complete (it takes a few minutes depending on the site’s size), you will be able to find the staging of your live site in the InstaWP dashboard. Now, it’s possible to test, develop, edit, clone, migrate, or log in to your staging site in seconds.

## PLUGIN FEATURES
Using this companion plugin, you can connect your existing WordPress sites anywhere on the internet to your InstaWP. After your site is connected, you can create 1-click staging websites from within the WP admin panel.

- Create full, custom, or quick staging websites in your InstaWP dashboard
- Auto-login to your Connected sites from InstaWP (https://app.instawp.io/connects)
- 2-way sync (push from staging to production and production to staging)
- Check the status of actions performed during 2-way sync
- Connect and disconnect your WordPress live site
- Keep all info about your site’s health.
- Do everything related to Remote Management securely (File Manager, Database Manager, Plugin / Themes Installer) - Beta
- Utilize dozens of InstaWP features by connecting your site to it.

## BENEFITS OF USING INSTAWP CONNECT
- Staging in seconds
- Zero mess in your hosting account
- Comes with a FREE Staging Environment
- Simplified migration and WordPress website backup
- Connect multiple staging sites to your live site.

## WHAT IS INSTAWP?
InstaWP is an online WordPress development environment to get you started with a new WP site within seconds. It allows you to create WordPress websites for testing, development, and staging in seconds.

You can get a free account here – [https://app.instawp.io/onboard](https://app.instawp.io/onboard).

### Key features include:
- Instant site creation
- Code Editor, DB Editor, and Logs viewer.
- Magic login to the WP admin panel.
- Migrate to any hosting provider that hosts your server (e.g., RunCloud, CloudWays, ServerAvatar, Pressable, SpinupWP, and WP Bolt)
- Access SFTP/SSH (pro).
- Drag-and-drop plugin installation
- Bulk theme/plugin installation from WP Repo using slugs
- Map a custom domain (pro) and host your site with InstaWP.
- Save your site as a template (snapshot of the site that can be reused).
- Shared templates for a public sandbox.
- Invite team members (pro).
- Git integration and GitHub actions support.
- Preset configurations.
- 3rd party services integrations (e.g., Mailchimp, active Campaign, Atarim, etc.)
- Core Faker

### Useful Resources

- [Visit Website](http://instawp.com/)
- [Read the InstaWP Documentation](https://docs.instawp.com/)
- [Know more about our API](https://docs.instawp.com/en/category/api-docs-12yssdo/)
- Read on How To Setup [WordPress Staging Site](https://instawp.com/set-up-wordpress-staging-site/)

Need support or want to partner with us? Go to our [website](http://instawp.com/) and use the live chat feature. You can also email us at support@instawp.com. We will be happy to assist.

== Installation ==

1. Generate a new API key from your InstaWP Account from https://app.instawp.io/user/api-tokens
2. Paste the key into the InstaWP Connect Settings. 
3. Go to "Create New" and connect the InstaWP Connect to the cloud. 
4. Now enjoy creating staging websites. 
5. Go to https://app.instawp.io/connects to see list of all your connected sites. 

== Screenshots ==

1. Staging Process - Select Staging Type.
2. Staging Process - Select Options.
3. Staging Process - Confirm Selection.
4. Staging Process - Monitor Progress.
5. All Staging Sites
6. Update API Key and Settings.

== Changelog ==

= 0.1.0.22 - 26 Mar 2024 =
- NEW - Added Magic Login support for Solid Security plugin.
- FIX - 2 way sync issues.

= 0.1.0.21 - 22 Mar 2024 =
- NEW - Added full support for PHP 5.6.
- NEW - Added User Create/Update/Delete APIs.
- FIX - Migration exclude screen undefined path issue.
- FIX - Magic login errors.
- FIX - Fix heartbeat actions ordering.
- FIX - Fix Total site calculation for migration.
- FIX - Only changed post object parameters will be logged in 2 way sync.
- FIX - 2 way sync optimizations.
- FIX - Magic login support for maintenance mode plugin by webfactory.
- FIX - Rest api blocking issue by security plugins.

= 0.1.0.20 - 15 Mar 2024 =
- NEW - Added support for PHP 5.6.
- FIX - Improve plugin performance.
- FIX - Increase security in 2-way-sync.
- FIX - 2 way sync issues.
- FIX - Added 2 way sync from local site to staging.

= 0.1.0.19 - 5 Mar 2024 =
- NEW - Added Activity Log.
- NEW - Added CLI command for reset staging.
- NEW - Added migration support for Elementor Cloud.
- NEW - Added option to hide shortcut and hide flashing.
- FIX - Improve plugin overall performance every dashboard page loading.
- FIX - Fixed config issues after staging creation from local environment.
- FIX - Fixed support for COOKIE_DOMAIN constant in wp-config.
- FIX - Stabilize the staging site creation process.
- FIX - 2 way sync event date timezone issue.
- FIX - 2 way sync duplicate event issue.
- FIX - 2 way sync performance optimizations.
- FIX - Added animation on staging site refresh button.
- FIX - Staging site list remove automatically due to transient

= 0.1.0.18 - 20 Feb 2024 =
- NEW - Added option to filter sync changes based on the status.
- FIX - Missing query var error.
- FIX - Handle database host/port/IPv6 support for push.
- FIX - File Manager auto login issue.
- FIX - Database Manager DB Host issue.
- FIX - Data missing for Heartbeat.
- FIX - Staging sites list not updating upon clicking refresh button.
- FIX - Fixed connect id issue.
- FIX - URL replace not working for Elementor.
- FIX - 2 way sync Issues.

= 0.1.0.17 - 15 Feb 2024 =
- FIX - Fixed iwpdb database table structure.
- FIX - Fixed connect id issue.

= 0.1.0.16 - 15 Feb 2024 =
- NEW - Deselect search engine visibility option after go live.
- NEW - Exclude cache folders by default on the migration.
- FIX - Fixed regular expression for missing images issue in migration.
- FIX - Fixed iwpdb database table structure for missing file issue in migration.
- FIX - Minor updates on UI of the plugin page.
- FIX - Fixed 2-way-sync for Elementor plugin.
- FIX - Comment unnecessary lines from htaccess during the migration for staging websites.
- FIX - Fixed htaccess rule for images from both source and current website.
- FIX - Fixed root path finding logic in push/go live case.

= 0.1.0.15 - 9 Feb 2024 =
- NEW - Added cleanup on uninstalling the plugin.
- NEW - Added admin bar navigation on InstaWP icon.
- NEW - Added connect dashboard link to the connect message.
- FIX - Fixed screen reload after migration finished.
- FIX - Fixed unnecessary API hit to staging-sites endpoint.
- FIX - Fixed search engine visibility issue.
- FIX - Fixed redirect issue after connect from authentication screen.
- FIX - Fixed 2-ways-sync issues.
- FIX - Fixed migration issues during staging website creation.
- FIX - Fixed issue with missing database column.

= 0.1.0.14 - 30 Jan 2024 =
- NEW - Added support for Core, Theme and Plugin Update via REST API.
- NEW - Added support for Theme and Plugin Activation and Deactivation via REST API.
- NEW - Added support for Enable or Disable Theme and Plugin Auto Update via REST API.
- FIX - Auto Disconnect from InstaWP API.
- FIX - Auto login method updated and fixed multiple issues regarding the magic login.

= 0.1.0.13 - 25 Jan 2024 =
- NEW - Added support for WordPress Customizer.
- NEW - Added support for RunCloud Hub cache purge support.
- FIX - Changed image upload mechanism for 2 way sync.
- FIX - Added check for heartbeat run 10 times.
- FIX - Install sync tables only when needed.
- FIX - Fixed mysql allocated length issue in IWPDB.
- FIX - Migration Issues.

= 0.1.0.12 - 19 Jan 2024 =
- NEW - Added support for Flywheel.
- NEW - Added support for WooCommerce in 2 ways sync.
- FIX - Fixed file mimetype issue during the migration process.

= 0.1.0.11 - 15 Jan 2024 =
- NEW - Added support for Malcare security plugin.
- FIX - Fixed migration issues while checking usages.

= 0.1.0.10 - 12 Jan 2024 =
- FIX - Fixed vulnerability issues.
- FIX - Fixed issues of event tracking in 2 way sync.

= 0.1.0.9 - 10 Jan 2024 =

- NEW - Added support for local websites using WP CLI.
- FIX - Updated Composer libraries.
- FIX - File and Database Manager auto login screen loader direction.
- FIX - Missing Nonce checking and access control.
- FIX - Fixed 2 ways sync limit issue.

= 0.1.0.8 - 4 Jan 2024 =

- NEW - Added support for custom name of the staging website.
- FIX - Fixed 2 way sync event tracking issue.

= 0.1.0.7 - 28 Dec 2023 =

- NEW - Really Simple SSL Redirect plugin support in htaccess file.
- FIX - Quick staging actions now skip everything possible to make the migration real quick.
- FIX - Display InstaWP icon at admin bar in frontend as well.
- FIX - Fixed heartbeat sending issue.
- FIX - Fixed migration issues with wp-config file.


= 0.1.0.6 - 22 Dec 2023 =

- NEW - Added row level database tracking progress on the migration.
- FIX - Fixed heartbeat sending issue.

= 0.1.0.5 - 20 Dec 2023 =

- NEW - Added option to enable or disable 2 way sync events.
- NEW - Added option to individually select events for sync.
- NEW - Send heartbeat immediately after connect a website.
- FIX - File Permission issue if the file name has any unwanted characters.
- FIX - Fixed migration issue with wp-config file on parent directory.

= 0.1.0.4 - 14 Dec 2023 =

- FIX - Revert support for PHP 7.4.
- FIX - Fixed issue with 2 way sync recording.

= 0.1.0.3 - 14 Dec 2023 =

- NEW - Added a way to see debug log from inside our plugin.
- NEW - Taxonomy and WP Option support for 2 way sync.
- FIX - Fixed issue with 2 way sync specially for Posts (CPT), Plugin and Theme changes.
- FIX - Stabilize the staging site creation process.

= 0.1.0.2 - 12 Dec 2023 =

- NEW - Added file permission issue during precheck.
- FIX - Stabilize the staging site creation process.

= 0.1.0.1 =

- NEW - Added hard disable search engine visibility for staging sites.
- FIX - Fixed CSS Loading in plugin due to http instead of https.

= 0.1.0.0 - 1 Dec 2023 =

- FIX - Incorrect versioning, updated to 0.1.0.0 instead of 0.0.1.0

= 0.1.0.0 - 1 Dec 2023 =

- FIX - Fixed 2 migrate v3 issues.
- FIX - Fixed auto login issue.
- FIX - Stabilize the staging site creation process.
- FIX - Remove SQLite PHP extension or PDO SQLite PHP check.

= 0.0.9.51 - 28 Nov 2023 =

- FIX - Fixed 2 Way Sync not working.
- FIX - Fixed Site Dropdown CSS.
- FIX - Fixed some PHP errors.
- FIX - Optimized 2 Way Sync Codebase.
- FIX - Heartbeat event logic.
- FIX - Fixed auto login issue.
- FIX - Fixed migration issue in destination script.
- FIX - Stabilize the staging site creation process.

= 0.0.9.50 - 22 Nov 2023 =

- NEW - Added connect logs to app.
- FIX - Fixed get_source_site_detail api.
- FIX - Removed SqlLite support.
- FIX - Fix Download log button in failed screen.
- FIX - Stabilize the staging site creation process.

= 0.0.9.49 - 14 Nov 2023 =

- FIX - Fixed migration issues and stabilize the process.
- FIX - Table export logic.
- FIX - Removed URL extra slash.

= 0.0.9.48 - 11 Nov 2023 =

- FIX - Skip database tables was not sending create table schema.
- FIX - Skip known log tables during the migration.
- FIX - Removed action scheduler.
- FIX - Changed recording blink for 2 way sync.
- FIX - Fixed displaying sites url on staging sites list.

= 0.0.9.47 - 8 Nov 2023 =

- FIX - Implemented new migration method and stabilize the process.
- FIX - Fixed .htaccess issue during the migration.
- FIX - Added pre-check before the migration start.

= 0.0.9.46 - 1 Nov 2023 =

- FIX - PharData library is now working properly.
- FIX - Improvement in Migrate v3 Push to make it stable.
- FIX - Improvement in Migrate v3 Pull to make it stable.
- FIX - Show Inventory toggle option and make it checked by default.
- FIX - Config API Key issue.
- FIX - Calculation of total sizes to check usages limit before migration start.
- FIX - Fixed the issue of automatically changing of api domain and connect id.
- FIX - WordFence whitelist IP address dynamically.

= 0.0.9.45 - 27 Oct 2023 =

- FIX - Provide an alternative of Phardata library when ZipArchive is not present
- FIX - Stop staging process if SQLite library is not present
- FIX - Improvement in staging process for stability
- FIX - Removed unused files, making plugin .zip less than 500kb.

= 0.0.9.44 =
- 26/10/2023 - FIX - Skip Large files option not working.
- 26/10/2023 - FIX - Collapsible Files & Table skip screen.
- 26/10/2023 - FIX - Confirmation of files & database size.
- 26/10/2023 - FIX - Removed Reset option.
- 26/10/2023 - NEW - Migration (v3) functionality added (beta).
- 26/10/2023 - FIX - Include Parent theme if 'active_themes_only' is selected.
- 26/10/2023 - NEW - Site usage API for Remote Management.
- 26/10/2023 - FIX - File and Database manager not working for Remote Management.
- 26/10/2023 - FIX - Several 2 Way Sync fixes.
- 26/10/2023 - FIX - 2 Way Sync - Pagination UI.
- 26/10/2023 - FIX - 2 Way Sync - Event type count fixes for each batch.
- 26/10/2023 - FIX - 2 Way Sync - Fixed event pagination issue.

= 0.0.9.43 =
- 18/10/2023 - FIX - Add supported plugins check.
- 18/10/2023 - FIX - Fix character check issue.
- 18/10/2023 - FIX - Stabilise migrations/staging process.

= 0.0.9.42 =- 17/10/2023 - NEW - Add support for non-zip and non-pdo servers.
- 17/10/2023 - FIX - Stabilise migrations/staging process.
- 17/10/2023 - FIX - Improve 2 way sync functionalities.

= 0.0.9.41 =
- 11/10/2023 - FIX - Fix folder creation issue.

= 0.0.9.40 =
- 11/10/2023 - NEW - Re-write of entire staging process from ground up, Goal: Stable staging process.
- 11/10/2023 - FIX - Removed un-used files.

= 0.0.9.33 =
- 29/09/2023 - NEW - Added option to whitelist InstaWP IP during staging creation.
- 29/09/2023 - FIX - Fixed repeat staging site creation issues.
- 29/09/2023 - FIX - Implemented excluded file and folders size check.
- 29/09/2023 - FIX - Stabilise migrations/staging process.
- 29/09/2023 - FIX - Replaced Tailwind CDN with NPM Package.

= 0.0.9.32 =
- 25/09/2023 - NEW - Introduced batch-based sync system.
- 25/09/2023 - FIX - Fixed repeat staging site creation issues.
- 25/09/2023 - FIX - Stabilise migrations/staging process.

= 0.0.9.31 =
- 14/09/2023 - NEW - Exclude selected files from the migration process.
- 14/09/2023 - FIX - Bath syncing change events for 2 way sync.
- 12/09/2023 - FIX - Deployer mode updated with new WaaS flow.
- 14/09/2023 - FIX - Stabilise migrations/staging process.

= 0.0.9.30 =
- 08/09/2023 - NEW - Split uploading backup files to cloud part based.
- 08/09/2023 - NEW - Exclude unnecessary backup files created from other plugins during the migration process
- 08/09/2023 - FIX - Fixed abort migration issues and duplicate migration starting.

= 0.0.9.29 =
- 04/09/2023 - FIX - Fixed critical error on staging sites loading.
- 04/09/2023 - FIX - Fixed disconnect API with a warning about site deletion.
- 04/09/2023 - FIX - Fixed two-way sync sites listing issue.

= 0.0.9.28 =
- 02/09/2023 - FIX - Fixed database restore timeout issue.
- 02/09/2023 - FIX - Fixed Radis Object Cache plugin conflict.

= 0.0.9.27 =
- 31/08/2023 - NEW - Added disconnect button in plugin screen.
- 31/08/2023 - FIX - Fixed loading media from parent site when doing quick migration.
- 31/08/2023 - FIX - Fixed flush permalink issue through while happening through CLI.

= 0.0.9.26 =
- 28/08/2023 - FIX - Fixed repetitive migration process.

= 0.0.9.25 =
- 24/08/2023 - NEW - Added plugin installation/update/delete features in two-way sync.
- 24/08/2023 - NEW - Added media transferring features in two-way sync.
- 24/08/2023 - FIX - Fixed migration abort issues from external sources.
- 24/08/2023 - FIX - Fixed starting of repetitive migration after completing one.
- 24/08/2023 - FIX - Fixed over populating native debug.log files.
- 24/08/2023 - FIX - Stabilise migrations/staging process.

= 0.0.9.24 =
- 21/08/2023 - NEW - Remote Management features added on the Beta programs.
- 21/08/2023 - FIX - Stabilising the migrations process.
- 21/08/2023 - FIX - Auto login issue with magic login button.

= 0.0.9.23 =
- 07/08/2023 - FIX - Fix reset permalink after successful migration.
- 07/08/2023 - FIX - Stabilise migrations/staging process.

= 0.0.9.22 =
- 05/08/2023 - FIX - Stabilise migrations/staging process.

= 0.0.9.21 =
- 02/08/2023 - NEW - Automatically hide Staging Sites from search engines.
- 02/08/2023 - FIX - Restrict staging site creation for a site already marked as staging.
- 02/08/2023 - FIX - Stabilise migrations/staging process.

= 0.0.9.20 =
- 28/07/2023 - FIX - Fix limit checking warning screen issues.
- 28/07/2023 - FIX - Allow migration from local WordPress installations.
- 28/07/2023 - FIX - Fixed some UI issues regarding user experience.

= 0.0.9.19 =
- 24/07/2023 - FIX - Fix Sync API vulnerability issue, disclosure by WordFence.

= 0.0.9.18 =
- 20/07/2023 - NEW - [ BETA ] Implemented two-way sync. Production to staging and vice versa. Includes Compatibility with: Posts, Media, Pages, Custom Post Types
- 20/07/2023 - NEW - Implement new migration algorithm, So website migration is now much more stable and faster.
- 20/07/2023 - NEW - Added remote management features.
- 20/07/2023 - FIX - Fix WaaS and Migrate V2 migration flows.
- 20/07/2023 - FIX - Minor changes on the migration user interface.

= 0.0.9.17 =
- 06/07/2023 - FIX - Fix site usage checking issues.
- 06/07/2023 - FIX - Fix WaaS and Migrate V2 migration flows.

= 0.0.9.16 =
- 04/07/2023 - FIX - Fix minor migration issues. Website migration is now much more stable and faster.

= 0.0.9.15 =
- 15/06/2023 - FIX - Fix migration issues on wizard go live flow.
- 15/06/2023 - FIX - Update user interface to display limit issues before starting migration.
- 15/06/2023 - FIX - Added a "Start Over" button in the limit warning screen.

= 0.0.9.14 =
- 31/05/2023 - FIX - Fix some migration issues on staging to production flow.

= 0.0.9.13 =
- 30/05/2023 - NEW - New user interface of the plugin.
- 30/05/2023 - FIX - Fix many errors in the migration process.
- 30/05/2023 - FIX - Fix migration issues on staging to production flow.

= 0.0.9.12 =
- 24/05/2023 - FIX - Wizard Mode Migration errors fixed.

= 0.0.9.11 =
- 11/05/2023 - FIX - Wizard Mode Migration is now updated.
- 11/05/2023 - FIX - Migration Process errors fixed, migration is now more stable.
- 15/05/2023 - FIX - Added Migration Status Timelapsed.

= 0.0.9.10 =
- 28/04/2023 - UPDATE - Improve calculating migration progress status.

= 0.0.9.9 =
- 18/04/2023 - FIX - Fix many errors while restoring process.

= 0.0.9.8 =
- 13/04/2023 - UPDATE - Heartbeat interval default changed to 15 minutes.
- 13/04/2023 - UPDATE - API key manual input allowed in the settings page.
- 13/04/2023 - FIX - API key is not setting properly after returning from auth screen.

= 0.0.9.7 =
- 11/04/2023 - NEW - Added plugin reset functionality.

= 0.0.9.6 =
- 11/04/2023 - NEW - Parallel approaches for site migration.
- 11/04/2023 - Improved performance and updated user interface.
- 11/04/2023 - FIX - Many bug fixes.

= 0.0.9.3 =
- Still in beta version, but more stable
- 2 way Sync option enabled (from staging to prod)
- Many bug fixes

= 0.0.7 =
- Major bug fixes and quick backup option.

= 0.0.1 =
- Initial release of the plugin.
