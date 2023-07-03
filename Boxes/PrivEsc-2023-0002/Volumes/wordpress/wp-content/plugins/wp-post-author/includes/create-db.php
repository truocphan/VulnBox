<?php
function create_form_builder_table()
{
	global $wpdb;
	$table_name = $wpdb->prefix . "wpa_form_builder";
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		                id bigint(20) NOT NULL AUTO_INCREMENT,
		                post_author bigint(20) UNSIGNED NOT NULL,
		                post_title text NOT NULL,
		                post_content longtext NOT NULL,
		                form_settings longtext NOT NUll,
		                payment_data longtext NOT NULL,
		                social_login_setting longtext NULL,
		                other_settings longtext NULL,
		                post_status varchar(20) NOT NULL,
		                post_date datetime NOT NULL,
		                post_date_gmt datetime NOT NULL,
		                post_modified datetime NOT NULL,
		                post_modified_gmt datetime NOT NULL,
                        editable TINYINT(1) NOT NULL DEFAULT '1',
		                PRIMARY KEY id (id)
		            ) $charset_collate;";
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta($sql);
}
function create_membership_plan_table()
{
	global $wpdb;
	$table_name = $wpdb->prefix . "wpa_membership_plan";
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		            id bigint(20) NOT NULL AUTO_INCREMENT,
		            name text NOT NULL,
		            description longtext NULL,
		            price text NOT NULL,
		            currency text NOT NULL,
		            plan_id text NULL,
		            payment_gateway text NOT NULL,
		            status text NOT NULL,
		            membership_type text NOT NULL,
                    image_name text NOT NULL,
		            created_at datetime NULL,
		            updated_at datetime NULL,
		            PRIMARY KEY id (id)
		            ) $charset_collate;";
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta($sql);
}
function create_subscriptions_table()
{
	global $wpdb;
	$table_name = $wpdb->prefix . "wpa_subscriptions";
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		            id bigint(20) NOT NULL AUTO_INCREMENT,
		            user_id bigint(20) NOT NULL,
		            plan_name text NULL,
		            plan_id longtext NULL,
		            status text NOT NULL,
		            gateway text NOT NULL,
                    membership_type text NOT NULL,
		            quantity int NOT NULL,
		            starts_from datetime NOT NULL,
		            trial_ends_at datetime NULL,
		            ends_at datetime NULL,
		            created_at datetime NULL,
		            updated_at datetime NULL,
		            PRIMARY KEY id (id)
		            ) $charset_collate;";
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta($sql);
}
function create_orders_table()
{
	global $wpdb;
	$table_name = $wpdb->prefix . "wpa_orders";
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		            id bigint(20) NOT NULL AUTO_INCREMENT,
		            user_id bigint(20) NOT NULL,
		            membership_id bigint(20) NOT NULL,
		            payer_name text NOT NULL,
		            payer_email text NOT NULL,
		            payer_address text NOT NULL,
		            order_id text NOT NULL,
		            transaction_date datetime NOT NULL,
		            created_at datetime NULL,
		            updated_at datetime NULL,
		            PRIMARY KEY id (id)
		            ) $charset_collate;";
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta($sql);
}
function create_guest_authors_table()
{
	global $wpdb;
	$table_name = $wpdb->prefix . "wpa_guest_authors";
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_email text NOT NULL,
            display_name text NOT NULL,
            nice_name text NOT NULL,
            first_name text NOT NULL,
            last_name text NOT NULL,
            description text NULL,
            user_registered datetime NOT NULL,
            website text NULL,
            is_active integer NOT NULL,
            user_meta text NULL,
            is_linked tinyInt(1) NOT NULL,
            avatar_name text NULL,
            linked_user_id bigInt(20) NULL,
            PRIMARY KEY id (id)
            ) $charset_collate;";
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	$data = dbDelta($sql);

	do_action('awpa_call_seeder_function');
}
