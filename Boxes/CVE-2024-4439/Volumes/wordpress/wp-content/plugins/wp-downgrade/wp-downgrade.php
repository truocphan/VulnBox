<?php
/*
Plugin Name: WP Downgrade | Specific Core Version
Plugin URI: https://www.reisetiger.net
Description: WP Downgrade allows you to either downgrade or update WordPress Core to an arbitrary version of your choice. The version you choose is downloaded directly from wordpress.org and installed just like any regular release update. The target version WordPress allows you to update to remains constant until you enter a different one or deactivate the plugin either completely or by leaving the target version field empty.
Version: 1.2.6
Author: Reisetiger
Author URI: https://www.reisetiger.net
License: GPL2
Text Domain: wp-downgrade
Domain Path: /languages
*/

// Abbruch, wenn direkt aufgerufen
if ( ! defined( 'ABSPATH' ) )
	exit;

function wpdowngrade_load_plugin_textdomain() {
$loaded = load_plugin_textdomain( 'wp-downgrade', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
//if ($loaded){ echo 'Success: Textdomain wp-downgrade loaded!'; }else{ echo 'Failed to load textdomain wp-downgrade!'; }
}
add_action('plugins_loaded', 'wpdowngrade_load_plugin_textdomain');

// create custom plugin settings menu
add_action('admin_menu', 'wpdowngrade_create_menu');

function wpdowngrade_create_menu() {

	//create new sub-menu
	add_submenu_page('options-general.php', 'WP Downgrade', 'WP Downgrade', 'administrator', 'wpdowngrade', 'wpdowngrade_settings_page');

	//call register settings function
	add_action( 'admin_init', 'register_wpdowngrade_settings' );
}

function register_wpdowngrade_settings() {
	//register our settings
	register_setting( 'wpdg-settings-group', 'wpdg_specific_version_name', array('sanitize_callback' => 'wpdowngrade_sanitize_version') );
	register_setting( 'wpdg-settings-group', 'wpdg_download_url', array('sanitize_callback' => 'sanitize_url') );
	register_setting( 'wpdg-settings-group', 'wpdg_edit_download_url', array('sanitize_callback' => 'sanitize_url') );
	// register_setting( 'wpdg-settings-group', 'some_other_option' );
}

function wpdowngrade_sanitize_version($userstring)
// Sicherstellen, dass eine plausible Versionsnummer eingegeben wurde (andernfalls Sicherheitsrisiko). Ein 'sanitize_text_field' oder sowas würde ggf. auch reichen, aber so prüfen wir genauer, dass der Input wirklich zum pattern passt. 
{
  if (!preg_match("/^[-+]?[0-9]*[.]?[0-9]?[.]?[0-9]+$/", $userstring) AND $userstring !== ''){
        add_settings_error('prefix_messages', 'wpdg_message', __('Version number looks strange.', 'wp-downgrade'), 'error');
        return;
    } else {
        return $userstring;
    }    
}

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wpdowngrade_action_links' );
function wpdowngrade_action_links( $links ) {
   $links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=wpdowngrade') ) .'">Settings</a>';
   return $links;
}

function wpdowngrade_settings_page() {
$allowed_tags = array( 'br' => array(), 'strong' => array() );

?>
<div class="wrap">
<h2>WP Downgrade Options</h2>

<h3><?php _e('WP Downgrade', 'wp-downgrade'); ?>: <?php if (get_option('wpdg_specific_version_name')) { ?><span style="color: green;"><?php _e('Active', 'wp-downgrade'); ?> (<?php _e('WP', 'wp-downgrade'); ?> <?php echo esc_html(get_option('wpdg_specific_version_name')); ?> <?php _e('is set as target version', 'wp-downgrade'); ?>)</span><?php } else {; ?><span style="color: red;"><?php _e('Inactive', 'wp-downgrade'); ?></span><?php }; ?></h3>

   <p><?php _e('WARNING! You are using this plugin entirely at your own risk! DO MAKE SURE you have a current backup of both your files and database, since a manual version change is deeply affecting your WP installation!', 'wp-downgrade'); ?></p>

<h3><?php _e('Which WordPress version would you like to up-/downgrade to?', 'wp-downgrade'); ?></h3>

<script>
function wpdgshowhide() {
  var checkBox = document.getElementById("myCheck");
  var text = document.getElementById("download-url-text");
  var field = document.getElementById("download-url");
  if (checkBox.checked == true){
    text.style.display = "inline";
    field.style.display = "inline";
  } else {
    text.style.display = "none";
    field.style.display = "none";
  }
}
</script>


<?php global $wp_version;

// ein paar wichtige Sprachversionen, ansonsten international
if(get_locale() == 'de_DE')
  $release_link = 'https://de.wordpress.org/releases/';
else if(get_locale() == 'es_ES')
  $release_link = 'https://es.wordpress.org/releases/';
else
  $release_link = 'https://wordpress.org/download/releases/';
?>

<form method="post" action="options.php">
    <?php settings_fields( 'wpdg-settings-group' ); ?>
    <?php do_settings_sections( __FILE__ ); ?>
    <table class="form-table">
      <col width="180px">
      <col width="180px">
        <tr valign="top">
        <th scope="row"><?php _e('WordPress Target Version', 'wp-downgrade'); ?>:</th>
        <td><input type="text" maxlength="6"  pattern="[-+]?[0-9]*[.]?[0-9]?[.]?[0-9]+" placeholder="<?php echo esc_attr($wp_version); ?>" name="wpdg_specific_version_name" value="<?php echo esc_attr( get_option('wpdg_specific_version_name') ); ?>" /> </td>
        <td><?php esc_html_e('Exact version number from', 'wp-downgrade'); ?> <a href="<?php echo esc_url($release_link); ?>" target="_blank"><?php esc_html_e('WP Releases', 'wp-downgrade'); ?></a>, <?php esc_html_e('for example "4.9.8". Leave empty to disable.', 'wp-downgrade'); ?></td>
        </tr>
        
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Current WP Version', 'wp-downgrade'); ?>:</th>
        <td><?php echo esc_html($wp_version); ?></td>
        <td></td>
        </tr>
        
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Language Detected', 'wp-downgrade'); ?>:</th>
        <td><?php echo esc_html(get_locale()) ?></td>
        <td></td>
        </tr>

    <?php if (get_option('wpdg_specific_version_name') AND version_compare($wp_version, get_option('wpdg_specific_version_name') ) <> 0 ){
    if(filter_var(get_option('wpdg_download_url'), FILTER_VALIDATE_URL) AND get_option('wpdg_edit_download_url')) 
      $wpdg_download_url = get_option('wpdg_download_url');
    else 
      $wpdg_download_url = wpdg_get_url(get_option('wpdg_specific_version_name'));
    ?>
        <tr valign="top">
        <td><input type="checkbox" id="myCheck" onclick="wpdgshowhide()" name="wpdg_edit_download_url"  <?php if (get_option('wpdg_edit_download_url')){ echo 'checked';} ?>> <?php esc_html_e('edit download URL', 'wp-downgrade'); ?> </td>
        <td> <span id="download-url" style="display:<?php if (get_option('wpdg_edit_download_url')){ echo 'inline';} else {echo 'none';} ?>"><input type="url" pattern="https?://.+" name="wpdg_download_url" id="download-url" value="<?php echo esc_attr( $wpdg_download_url ); ?>" /> </span></td>
        <td> <span id="download-url-text" style="display:<?php if (get_option('wpdg_edit_download_url')){ echo 'inline';} else {echo 'none';} ?>"><?php wp_kses(_e('Usually you <strong>do not</strong> need to change this. But you can, if necessary. Must be a valid URL to a WordPress ZIP. <strong>Be careful!</strong> The content will not be verified! Wordpress will use what ever you give here, even if it does not contain release ', 'wp-downgrade'), $allowed_tags); echo esc_html(get_option('wpdg_specific_version_name')).' or for example a wrong language. After your special update is done, you need to check for success yourself. And after that, you should turn off this option.'; ?></span></td>
        </tr>
    <?php } ?>

    </table>


    
    <?php submit_button(); ?>
    
    </form>
    
<?php if (get_option('wpdg_specific_version_name')) { 
if (version_compare($wp_version, get_option('wpdg_specific_version_name') ) == 0 ) { ?>
  <div style="border: 2px solid green; padding: 5px;">
  <?php wp_kses(_e('<strong>All fine!</strong> You are currently on your desired release. And it will stay like that. <br>If you ever want to <strong>reinstall</strong> ', 'wp-downgrade'), $allowed_tags); ?> <?php echo esc_html(get_option('wpdg_specific_version_name')).', you have to switch to another version and come back. If you want to return to the regular update channel, you need to empty the version number above and go to '; ?> <a href="<?php echo esc_url(get_admin_url( null, '/update-core.php' )) ;?>"><?php esc_html_e('Update Core', 'wp-downgrade'); ?></a>.
  </div>
<?php } else { ?>
<div style="border: 2px solid orange; padding: 5px;">
  <p><strong><?php esc_html_e('In order to perform the upgrade/downgrade to WP', 'wp-downgrade'); ?> <?php echo esc_html(get_option('wpdg_specific_version_name')); ?> <?php esc_html_e('please go to', 'wp-downgrade'); ?> <a href="<?php echo esc_url(get_admin_url( null, '/update-core.php' )) ;?>"><?php esc_html_e('Update Core', 'wp-downgrade'); ?></a>. </strong></p>
  <p><a href="<?php echo esc_url(get_admin_url( null, '/update-core.php' )) ;?>" class="button"><?php esc_html_e('Up-/Downgrade Core', 'wp-downgrade'); ?></a></p>
</div>
<?php } ?>

<?php if (wpdg_urlcheck(wpdg_get_url(get_option('wpdg_specific_version_name'))) == false){ ?>
<div style="border: 2px solid red; padding: 5px;">
<span style="color: red;"> <?php esc_html_e('Attention! The target version does not seem to exist!', 'wp-downgrade'); ?> </span><br>
<span style="color: red;"> URL: <?php echo esc_url(wpdg_get_url(get_option('wpdg_specific_version_name')));  ?></span><br>
<span style="color: red;"> <?php esc_html_e('The update could fail. Are you sure that the version number is correct? You can also manually edit the download URL if needed.', 'wp-downgrade'); echo " <strong>". esc_html(get_option('wpdg_specific_version_name')); ?> </strong></span><br>
</div>
<?php }

  //echo wpdg_get_url(get_option('wpdg_specific_version_name'));
  //echo wpdg_urlcheck(wpdg_get_url(get_option('wpdg_specific_version_name')));

?>

<?php }; ?>

</div>
<?php } 

add_filter('pre_site_option_update_core','wpdg_specific_version' );
add_filter('site_transient_update_core','wpdg_specific_version' );
function wpdg_specific_version($updates){

$sprache = get_locale().'/';
if ($sprache == 'en_US/' OR $sprache == 'en' OR $sprache == 'en/'){
  $sprache = '';
  };
$dg_version = get_option('wpdg_specific_version_name');
if ($dg_version < 1)
  return $updates;
    
    global $wp_version;
    // If current version is target version then stop
    
    if ( version_compare( $wp_version, $dg_version ) == 0 ) {
        return;
    } //https://downloads.wordpress.org/release/de_DE/wordpress-4.5.zip
    
    if($updates){
      $updates->updates[0]->download = wpdg_get_url($dg_version);
      $updates->updates[0]->packages->full = wpdg_get_url($dg_version);
      $updates->updates[0]->packages->no_content = '';
      $updates->updates[0]->packages->new_bundled = '';
      $updates->updates[0]->current = $dg_version;
    }
    
    return $updates;
}

function wpdg_urlcheck($url) {
    if (($url == '') || ($url == null)) { return false; }
    $response = wp_remote_head( $url, array( 'timeout' => 5 ) );
    $accepted_status_codes = array( 200, 301, 302 );
    if ( ! is_wp_error( $response ) && in_array( wp_remote_retrieve_response_code( $response ), $accepted_status_codes ) ) {
        return true;
    }
    return false;
}

function wpdg_get_url($version) {
    if(get_option('wpdg_edit_download_url') AND filter_var(get_option('wpdg_download_url'), FILTER_VALIDATE_URL)){
      $url = get_option('wpdg_download_url');
    } else {      
      $sprache = get_locale().'/';
      if ($sprache == 'en_US/' OR $sprache == 'en' OR $sprache == 'en/'){
        $sprache = '';
        };
      $url = "https://downloads.wordpress.org/release/".$sprache."wordpress-".$version.".zip";
    }
  return $url;
}

?>