<?php

/**
 * Implement plugin menu.
 *
 * @package CoverNews
 */

//Exit if directly acess
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Add new registration menu items.
 */
function awpa_settings_menu($hook)
{
    add_submenu_page(
        'wp-post-author',
        __('Registration Forms', 'wp-post-author'),
        __('Registration Forms', 'wp-post-author'),
        'manage_options',
        'wp-post-author',
        'awpa_user_registration_page'
    );

    add_submenu_page(
        'wp-post-author',
        __('Registered Authors', 'wp-post-author'),
        __('Registered  Authors', 'wp-post-author'),
        'manage_options',
        'awpa-members',
        'awpa_members_page'
    );

    add_submenu_page(
        'wp-post-author',
        __('Guest Authors', 'wp-post-author'),
        __('Guest Authors', 'wp-post-author'),
        'manage_options',
        'awpa-multi-authors',
        'awpa_multi_authors_page'
    );

    add_submenu_page(
        'wp-post-author',
        __('Settings', 'wp-post-author'),
        __('Settings', 'wp-post-author'),
        'manage_options',
        'awpa-settings',
        'awpa_settings_page'
    );
}
add_action('admin_menu', 'awpa_settings_menu', 60);

function awpa_user_registration_page()
{
    $addon_state = awpa_addon_state();
?>
    <div id="awpa-actions" data-for="textarea"></div>
    <div id="awpa-form-listing" class="awpa-all-forms-container" addon_state=<?php echo json_encode($addon_state); ?>>
    </div>
<?php
}

function awpa_add_registration_page()
{
    $addon_state = awpa_addon_state();
?>
    <div id="awpa-form-builder-container" class="awpa-form-builder-container" addon_state=<?php echo json_encode($addon_state); ?>>
        <p>This should not render!!!</p>
    </div>
<?php
}

function awpa_settings_page()
{
    $addon_state = awpa_addon_state();
?><br />
    <div id="afwrap-react" addon_state=<?php echo json_encode($addon_state); ?>></div>
<?php
}

function awpa_members_page()
{

?><br />
    <div id="afwrap-membership-dashboard"></div>
<?php
}

function awpa_multi_authors_page()
{
    if (class_exists('AWPA_Multi_Authors_Addon')) {
    ?>
        <div id="awpa-multi-authors-addon" class="awpa-multi-authors">
        </div>
    <?php
    } else {
    ?>
        <div id="awpa-multi-authors" class="awpa-multi-authors">
        </div>
<?php
    }
}
function awpa_addon_state()
{
    $addon_state = array(
        'is_active_membership_addon' => class_exists('WP_Post_Author_Membership_Plans_Addon'),
        'is_active_user_dashboard_addon' => class_exists('WP_Post_Author_User_Dashboard_Addon'),
        'is_active_advanced_fields_addon' => class_exists('WP_Post_Author_Advanced_Fields_Addon'),
        'is_active_content_restrict_addon' => class_exists('WP_Post_Author_Content_Restrict_Addon'),
        'is_active_newsletter_addon' => class_exists('WP_Post_Author_Newsletter_Addon'),
    );
    return $addon_state;
}
