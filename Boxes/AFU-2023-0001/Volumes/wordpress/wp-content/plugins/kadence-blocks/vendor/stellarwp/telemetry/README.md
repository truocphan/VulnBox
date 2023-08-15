# Telemetry Library

A library for Opt-in and Telemetry data to be sent to the StellarWP Telemetry server.

## Table of Contents
- [Telemetry Library](#telemetry-library)
	- [Table of Contents](#table-of-contents)
	- [Installation](#installation)
	- [Usage Prerequisites](#usage-prerequisites)
	- [Integration](#integration)
	- [Uninstall Hook](#uninstall-hook)
	- [Opt-In Modal Usage](#opt-in-modal-usage)
		- [Prompting Users on a Settings Page](#prompting-users-on-a-settings-page)
	- [Saving Opt-In Status on a Settings Page](#saving-opt-in-status-on-a-settings-page)
	- [How to Migrate Users Who Have Already Opted In](#how-to-migrate-users-who-have-already-opted-in)
	- [Filter Reference](#filter-reference)
		- [stellarwp/telemetry/{hook-prefix}/should\_show\_optin](#stellarwptelemetryhook-prefixshould_show_optin)
		- [stellarwp/telemetry/{hook-prefix}/option\_name](#stellarwptelemetryhook-prefixoption_name)
		- [stellarwp/telemetry/{hook-prefix}/optin\_status](#stellarwptelemetryhook-prefixoptin_status)
		- [stellarwp/telemetry/{hook-prefix}/optin\_status\_label](#stellarwptelemetryhook-prefixoptin_status_label)
		- [stellarwp/telemetry/{stellar\_slug}/optin\_args](#stellarwptelemetrystellar_slugoptin_args)
		- [stellarwp/telemetry/{hook-prefix}/show\_optin\_option\_name](#stellarwptelemetryhook-prefixshow_optin_option_name)
		- [stellarwp/telemetry/{hook-prefix}/register\_site\_url](#stellarwptelemetryhook-prefixregister_site_url)
		- [stellarwp/telemetry/{hook-prefix}/register\_site\_data](#stellarwptelemetryhook-prefixregister_site_data)
		- [stellarwp/telemetry/{hook-prefix}/register\_site\_user\_details](#stellarwptelemetryhook-prefixregister_site_user_details)
		- [stellarwp/telemetry/{hook-prefix}/send\_data\_args](#stellarwptelemetryhook-prefixsend_data_args)
		- [stellarwp/telemetry/{hook-prefix}/send\_data\_url](#stellarwptelemetryhook-prefixsend_data_url)
		- [stellarwp/telemetry/{hook-prefix}/last\_send\_expire\_seconds](#stellarwptelemetryhook-prefixlast_send_expire_seconds)
		- [stellarwp/telemetry/{stellar\_slug}/exit\_interview\_args](#stellarwptelemetrystellar_slugexit_interview_args)
	- [Adding Plugin Data to Site Health](#adding-plugin-data-to-site-health)
## Installation

It's recommended that you install Telemetry as a project dependency via [Composer](https://getcomposer.org/):

```bash
composer require stellarwp/telemetry
```


> We _actually_ recommend that this library gets included in your project using [Strauss](https://github.com/BrianHenryIE/strauss).
>
> Luckily, adding Strauss to your `composer.json` is only slightly more complicated than adding a typical dependency, so checkout our [strauss docs](https://github.com/stellarwp/global-docs/blob/main/docs/strauss-setup.md).

## Usage Prerequisites
To actually _use_ the telemetry library, you must have a Dependency Injection Container (DI Container) that is compatible with [di52](https://github.com/lucatume/di52) (_We recommend using di52_).

In order to keep this library as light as possible, a container is not included in the library itself. To avoid version compatibility issues, it is also not included as a Composer dependency. Instead, you must include it in your project. We recommend including it via composer [using Strauss](https://github.com/stellarwp/global-docs/blob/main/docs/strauss-setup.md), just like you have done with this library.

## Integration
Initialize the library within your main plugin file after plugins are loaded (or anywhere else you see fit). You can configure a unique prefix (we suggest you use your plugin slug) so that hooks can be uniquely called for your specific instance of the library.

```php
use StellarWP\Telemetry\Core as Telemetry;

add_action( 'plugins_loaded', 'initialize_telemetry' );

function initialize_telemetry() {
	/**
	 * Configure the container.
	 *
	 * The container must be compatible with stellarwp/container-contract.
	 * See here: https://github.com/stellarwp/container-contract#usage.
	 *
	 * If you do not have a container, we recommend https://github.com/lucatume/di52
	 * and the corresponding wrapper:
	 * https://github.com/stellarwp/container-contract/blob/main/examples/di52/Container.php
	 */
	$container = new Container();
	Config::set_container( $container );

	// Set the full URL for the Telemetry Server API.
	Config::set_server_url( 'https://telemetry.example.com/api/v1' );

	// Set a unique prefix for actions & filters.
	Config::set_hook_prefix( 'my-custom-prefix' );

	// Set a unique plugin slug.
	Config::set_stellar_slug( 'my-custom-stellar-slug' );

    // Initialize the library.
    Telemetry::instance()->init( __FILE__ );
}
```

Using a custom hook prefix provides the ability to uniquely filter functionality of your plugin's specific instance of the library.

The unique plugin slug is used by the telemetry server to identify the plugin regardless of the plugin's directory structure or slug.

## Uninstall Hook

This library provides everything necessary to uninstall itself. Depending on when your plugin uninstalls itself and cleans up the database, you can include this static method to have the library purge the options table of the necessary rows:
```php
<?php// uninstall.php

use YOUR_STRAUSS_PREFIX\StellarWP\Telemetry\Uninstall;

require_once 'vendor/strauss/autoload.php';

Uninstall::run( 'my-custom-stellar-slug' );
```
When a user deletes the plugin, WordPress runs the method from `Uninstall` and cleans up the options table. The last plugin utilizing the library will remove all options.

## Opt-In Modal Usage

### Prompting Users on a Settings Page
On each settings page you'd like to prompt the user to opt-in, add a `do_action()`. _Be sure to include your defined stellar\_slug if you are using one_.
```php
do_action( 'stellarwp/telemetry/{stellar_slug}/optin' );
```
The library calls this action to handle registering the required resources needed to render the modal. It will only display the modal for users who haven't yet opted in.

To show the modal on a settings page, add the `do_action()` to the top of your rendered page content:
```php
function my_options_page() {
    do_action( 'stellarwp/telemetry/{stellar_slug}/optin' );
    ?>
    <div>
        <h2>My Plugin Settings Page</h2>
    </div>
    <?php
}
```
_Note: When adding the `do_action`, you may pass additional arguments to the library with an array. There is no functionality at the moment, but we expect to expand the library to accept configuration through the passed array._
```php
do_action( 'stellarwp/telemetry/{stellar_slug}/optin', [ 'plugin_slug' => 'the-events-calendar' ] );
```

## Saving Opt-In Status on a Settings Page
When implementing the library, settings should be available for site administrators to change their opt-in status at any time. The value passed to `set_status()` should be a boolean.

```php
add_action( 'admin_init', 'save_opt_in_setting_field' );

/**
 * Saves the "Opt In Status" setting.
 *
 * @return void
 */
public function save_opt_in_setting_field() {
	// Return early if not saving the Opt In Status field.
	if ( ! isset( $_POST[ 'opt-in-status' ] ) ) {
		return;
	}

	// Get an instance of the Status class.
	$Status = Config::get_container()->get( Status::class );

	// Get the value submitted on the settings page as a boolean.
	$value = filter_input( INPUT_POST, 'opt-in-status', FILTER_VALIDATE_BOOL );

	$Status->set_status( $value );
}
```

## How to Migrate Users Who Have Already Opted In
If you have a system that users have already opted in to and you'd prefer not to have them opt in again, here's how you might go about it. The `opt_in()` method will set their opt-in status to `true` and send their telemetry data and user data to the telemetry server.

```php
/**
 * The library attempts to set the opt-in status for a site during 'admin_init'. Use the hook with a priority higher
 * than 10 to make sure you're setting the status after it initializes the option in the options table.
 */
add_action( 'admin_init', 'migrate_existing_opt_in', 11 );

function migrate_existing_opt_in() {

	if ( $user_has_opted_in_already ) {

		// Get the Opt_In_Subscriber object.
		$Opt_In_Subscriber = Config::get_container()->get( Opt_In_Subscriber::class );
		$Opt_In_Subscriber->opt_in();
	}
}
```

## Filter Reference

If you configured this library to use a hook prefix, note that all hooks will now use this prefix. For example:
```php
add_filter( 'stellarwp/telemetry/my-custom-prefix/should_show_optin', 'my-custom-filter', 10, 1 );
```
### stellarwp/telemetry/{hook-prefix}/should_show_optin
Filters whether the user should be shown the opt-in modal.

**Parameters**: _bool_ `$should_show`

**Default**: `true`

### stellarwp/telemetry/{hook-prefix}/option_name
Filter the option name used to store current users' optin status.

**Parameters**: _string_ `$option_name`

**Default**: `stellarwp_telemetry`

### stellarwp/telemetry/{hook-prefix}/optin_status
Filter the optin status of the current site.

**Parameters**: _integer_ `$status`

**Default**: `1`

Each status corresponds with an integer:
```php
1 = 'Active',
2 = 'Inactive',
3 = 'Mixed',
```
### stellarwp/telemetry/{hook-prefix}/optin_status_label
Filter the label used to show the current opt-in status of the site.

**Parameters**: _string_ `$optin_label`

**Default**: see: [stellarwp/telemetry/optin_status](#stellarwptelemetryoptin_status)
### stellarwp/telemetry/{stellar_slug}/optin_args
Filter the arguments passed to the opt-in modal.

**Parameters**: _array_ `$args`

**Default**:
```php
$args = [
	'plugin_logo'           => Resources::get_asset_path() . 'resources/images/stellar-logo.svg',
	'plugin_logo_width'     => 151,
	'plugin_logo_height'    => 32,
	'plugin_logo_alt'       => 'StellarWP Logo',
	'plugin_name'           => 'The Events Calendar',
	'plugin_slug'           => Config::get_container()->get( Core::PLUGIN_SLUG ),
	'user_name'             => wp_get_current_user()->display_name,
	'permissions_url'       => '#',
	'tos_url'               => '#',
	'privacy_url'           => '#',
	'opted_in_plugins_text' => __( 'See which plugins you have opted in to tracking for', 'stellarwp-telemetry' ),
	'heading'               => __( 'We hope you love {plugin_name}.', 'stellarwp-telemetry' ),
	'intro'                 => __( 'Hi, {user_name}.! This is an invitation to help our StellarWP community. If you opt-in, some data about your usage of {plugin_name} and future StellarWP Products will be shared with our teams (so they can work their butts off to improve). We will also share some helpful info on WordPress, and our products from time to time. And if you skip this, that’s okay! Our products still work just fine.', 'stellarwp-telemetry' ),
];
```
### stellarwp/telemetry/{hook-prefix}/show_optin_option_name
Filters the string used for the option that determines whether the opt-in modal should be shown.

**Parameters**: _string_ `$option_name`

**Default**: `stellarwp_telemetry_{plugin_slug}_show_optin`
### stellarwp/telemetry/{hook-prefix}/register_site_url
Filters the url of the telemetry server that will store the site data when registering a new site.

**Parameters**: _string_ `$url`

**Default**: `https://telemetry.example.com/api/v1/register-site`
### stellarwp/telemetry/{hook-prefix}/register_site_data
Filters the data that is sent to the telemetry server when registering a new site.

**Parameters**: _array_ `$site_data`

**Default**:
```php
$site_data = [
	'telemetry' => json_encode( $this->provider->get_data() ),
];
```
### stellarwp/telemetry/{hook-prefix}/register_site_user_details
Filters the user details that is sent to the telemetry server when registering a new site.

**Parameters**: _array_ `$user_details`

**Default**:
```php
$user_details = [
	'name'  => $user->display_name,
	'email' => $user->user_email,
	'plugin_slug' => Config::get_container()->get( Core::PLUGIN_SLUG ),
];
```
### stellarwp/telemetry/{hook-prefix}/send_data_args

**Parameters**: _array_ $data_args

**Default**:
```php
$data_args = [
	'token'     => $this->get_token(),
	'telemetry' => json_encode( $this->provider->get_data() ),
];
```

### stellarwp/telemetry/{hook-prefix}/send_data_url
Filters the full url to use when sending data to the telemetry server.

**Parameters**: _string_ `$url`

**Default**: `https://telemetry.example.com/api/v1/telemetry`

### stellarwp/telemetry/{hook-prefix}/last_send_expire_seconds
Filters how often the library should send site health data to the telemetry server.

**Parameters**: _integer_ `$seconds`

**Default**: `7 * DAY_IN_SECONDS`

### stellarwp/telemetry/{stellar_slug}/exit_interview_args
Filters the arguments used in the plugin deactivation "exit interview" form.

**Parameters**: _array_ `$args`

**Default**:
```php
$args = [
	'plugin_slug'        => $this->container->get( Core::PLUGIN_SLUG ),
	'plugin_logo'        => plugin_dir_url( __DIR__ ) . 'public/logo.png',
	'plugin_logo_width'  => 151,
	'plugin_logo_height' => 32,
	'plugin_logo_alt'    => 'StellarWP Logo',
	'heading'            => __( 'We’re sorry to see you go.', 'stellarwp-telemetry' ),
	'intro' 		     => __( 'We’d love to know why you’re leaving so we can improve our plugin.', 'stellarwp-telemetry' ),
	'questions'          => [
		[
			'question'   => __( 'I couldn’t understand how to make it work.', 'stellarwp-telemetry' ),
			'show_field' => true
		],
		[
			'question'   => __( 'I found a better plugin.', 'stellarwp-telemetry' ),
			'show_field' => true
		],
		[
			'question'   => __( 'I need a specific feature it doesn’t provide.', 'stellarwp-telemetry' ),
			'show_field' => true
		],
		[
			'question'   => __( 'The plugin doesn’t work.', 'stellarwp-telemetry' ),
			'show_field' => true
		],
		[
			'question'   => __( 'It’s not what I was looking for.', 'stellarwp-telemetry' ),
			'show_field' => true
		]
	],
];
```

## Adding Plugin Data to Site Health

We collect the Site Health data as json on the server.  In order to pass additional plugin specific items that can be reported on, you will need to add a section to the Site Health Data. The process for adding a section is documented on [developer.wordpress.org](https://developer.wordpress.org/reference/hooks/debug_information/).

We do have some requirements so that we can grab the correct data from Site Health. When setting the key to the plugins site health section, use the plugin slug. Do not nest your settings in a single line, use one line per option. Do not translate the debug value. This will help make sure that the data is reportable on the Telemetry Server.

``` php
function add_summary_to_telemtry( $info ) {
	$info[ 'stellarwp' ] = [
			'label'       => esc_html__( 'StellarWP Plugin Section', 'text-domain' ),
			'description' => esc_html__( 'There are some key things here... Everything should be output in key value pairs. Follow the translation instructions in the codex (do not translate debug). Plugin Slug should be the main key.', 'text-domain' ),
			'fields'      => [
				'field_key_one' => [
					'label' => esc_html__( 'This is the field text', 'text-domain' ),
					'value' => esc_html__( 'value', 'text-domain' ),
					'debug' => 'value'
				],
				'field_key_two' => [
					'label' => esc_html__( 'Field Two', 'text-domain' ),
					'value' => esc_html__( 'yes', 'text-domain' ),
					'debug' => true,
				],
				'field_key_three' => [
					'label' => esc_html__( 'Three', 'text-domain' ),
					'value' => esc_html__( 'Tempus pellentesque id hac', 'text-domain' ),
					'debug' => 'Tempus pellentesque id hac',
				],
				'field_key_four' => [
					'label' => esc_html__( 'Option Four', 'text-domain' ),
					'value' => esc_html__( 'on', 'text-domain' ),
					'debug' => true,
				],
			],
		];
	return $info;
}

add_filter( 'debug_information', 'add_summary_to_telemetry', 10, 1) ;
```
