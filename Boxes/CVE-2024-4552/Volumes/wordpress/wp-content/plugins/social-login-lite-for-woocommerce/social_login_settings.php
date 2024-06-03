<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}
   function social_login_settings(){

    $setting_data=get_option('psl_social_plugin');

	if(!empty($setting_data)){

		extract($setting_data['facebook_details']);

		extract($setting_data['google_plus_details']);   

		extract($setting_data['change_text_details']);   

		$login_label=trim($login_label);

		$checkout_label=trim($checkout_label);

	}

	?>

   <div class="wrap">

	<?php $tab2 = sanitize_text_field( isset($_GET['tab2'] )?$_GET['tab2']:"");	?>

		<h2> <?php _e('WooCommerce Social Login - Plugin Options','phoen_social_login'); ?> </h2>

		<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">

			<a class="nav-tab <?php if($tab2 == 'general' || $tab2 == ''){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=psl-social-login&amp;tab2=general"><?php _e('General','phoen_social_login'); ?></a>

			<a class="nav-tab <?php if($tab2 == 'reports'){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=psl-social-login&amp;tab2=reports"><?php _e('Reports','phoen_social_login'); ?></a>

		</h2>

    <div class="icon32" id="icon-users"><br></div>

	<?php if($tab2 == 'general' || $tab2 == ''){ ?>

	<form  method="post" action="" id="all_social_setting" enctype="multipart/form-data">
	
		<?php wp_nonce_field( 'all_social_setting_action', 'social_setting_form_nonce_field' ); ?>

	   <table class="form-table"><tbody><tr valign="top" class=""></tr></tbody></table>

		<hr />

		 <table class="form-table">

			<tbody>

				<tr valign="top">

					<th class="titledesc" scope="row">

						<label for="psl_social_label"><?php _e('Label','phoen_social_login'); ?></label>

					</th>

					<td class="forminp forminp-text">

						<input type="text"  value="<?php echo !empty($login_label)?$login_label:''?>"  id="psl_social_label" name="psl_social_label" > 

					</td>

				</tr>

				<tr valign="top">

					<th class="titledesc" scope="row">

						<label for="psl_social_label_checkout"><?php _e('Description in checkout page','phoen_social_login'); ?></label>

					</th>

					<td class="forminp forminp-text">

						<input type="text"  value="<?php echo !empty($checkout_label)?$checkout_label:''?>"  id="psl_social_label_checkout" name="psl_social_label_checkout" > 

					</td>

				</tr>

			</tbody>

		</table>

		<hr />

		 <h3><?php _e('Facebook settings','phoen_social_login'); ?></h3>

		 <table class="form-table">

			<tbody>

			  <tr valign="top" class="">

					<th class="titledesc" scope="row"><?php _e('Enable Facebook Login','phoen_social_login'); ?></th>

					<td class="forminp forminp-checkbox">

						<fieldset>

							<legend class="screen-reader-text"><span><?php _e('Enable Facebook Login','phoen_social_login'); ?></span></legend>

							<label for="psl_facebook_enable">

								<input type="checkbox" id="psl_facebook_enable"  name="psl_facebook_enable" <?php echo (isset($enable_facebook) && $enable_facebook != '')?'checked':'';?>/> 

							</label> 
							
						</fieldset>

					</td>

				</tr>

				<tr valign="top">

					<th class="titledesc" scope="row">

						<label for="psl_facebook_id"><?php _e('Facebook App Id','phoen_social_login'); ?></label>

					</th>

					<td class="forminp forminp-text">

						<input type="text"  value="<?php echo isset($facebook_id)?$facebook_id:''?>"  id="psl_facebook_id" name="psl_facebook_id"> 						

					</td>

				</tr>

				<tr valign="top">

					<th class="titledesc" scope="row"><?php _e('Upload Image','phoen_social_login'); ?></th>

					<td class="forminp forminp-text">

						<label for="upload_image">

						<input id="upload_image" name="ad_image" height="32px" width="32px" type="text" value="<?php echo ($fb_icon_url!='')?$fb_icon_url:'' ?>" />

						<input id="upload_image_button" class="button" type="button" value="Upload Image" />

							<br /><?php _e('Enter a URL or upload an image for the Icon of size 32*32.','phoen_social_login'); ?>

					</label>

				</td>

			</tr>

			</tbody>

		</table>

		<hr />

		<h3><?php _e('Google settings','phoen_social_login'); ?></h3>

		<div class="phoenixx_promt_msg">

			<h5><?php _e('CallBack Url','phoen_social_login'); ?>:&nbsp;&nbsp;<?php echo site_url()."/my-account"?></h5>

		</div>

		<table class="form-table">

			<tbody>

			<tr valign="top" class="">

				<th class="titledesc" scope="row"><?php _e('Enable Google Login','phoen_social_login'); ?></th>

				<td class="forminp forminp-checkbox">

					<fieldset>

					  <legend class="screen-reader-text"><span><?php _e('Enable Google Login','phoen_social_login'); ?></span></legend>

						<label for="psl_google_enable">

						<input type="checkbox"  id="psl_google_enable" name="psl_google_enable" <?php echo (isset($enable_google_plus) && $enable_google_plus != '')?'checked':'';?>/></label>

					</fieldset>								

				 </td>
			 </tr>

			 <tr valign="top">

				<th class="titledesc" scope="row">

					<label for="psl_google_id"><?php _e('Google client id','phoen_social_login'); ?></label>

				</th>

				<td class="forminp forminp-text">

					<input type="text"  value="<?php echo isset($google_id)?$google_id:'';?>"  id="psl_google_id" name="psl_google_id"> 						

				</td>

			 </tr>

			 <tr valign="top">

				<th class="titledesc" scope="row">

					 <label for="psl_google_secret"><?php _e('Google API key','phoen_social_login'); ?></label>

				</th>

				<td class="forminp forminp-text">

					 <input type="text"   value="<?php echo isset($google_api)?$google_api:'';?>"  id="psl_google_secret" name="psl_google_secret"> 						

				</td>

			 </tr>

			 <tr valign="top">

				<th class="titledesc" scope="row"> <?php _e('Upload Image','phoen_social_login'); ?></th>

				<td class="forminp forminp-text">

				<label for="upload_image2">

				<input id="upload_image1" name="ad_image1" height="32px" width="32px" type="text" value="<?php echo ($google_icon_url!='')?$google_icon_url:'' ?>" />

				<input id="upload_image_button1" class="button" type="button" value="Upload Image" />

					<br /><?php _e('Enter a URL or upload an image for the Icon of size 32*32.','phoen_social_login'); ?>

					</label>

				</td>

			</tr>

			</tbody>

		</table>  

		<input type="submit" value="Save Changes" name="save_data" class="button button-primary" style="float: left; margin-right: 10px;">

		<input type="button" id="reset_default_all" value="Reset Default" name="save_data" class="button button-info" style="float: left; margin-right: 10px;">

    </form>

	<?php } else if($tab2 == 'reports'){  ?>

		<html>

			<head>

				<script type="text/javascript" src="https://www.google.com/jsapi"></script>

					<script type="text/javascript">

					  google.load("visualization", "1", {packages:["corechart"]});

					  google.setOnLoadCallback(drawChart);

					  function drawChart() {

						var data = google.visualization.arrayToDataTable([

						  ['Task', 'Hours per Day'],

						  ['Facebook',  <?php $fb_count=get_option('psl_fb_count'); echo ($fb_count!='')?$fb_count:0?>],

						  ['Google+',   <?php $gp_count=get_option('psl_gp_count'); echo ($gp_count!='')?$gp_count:0?>]

						]);

						var options = {

						  title: 'Social Login Activities',

						  pieHole: 0.4,

						};

						var chart = new google.visualization.PieChart(document.getElementById('donutchart'));

						chart.draw(data, options);

					  }

					</script>

			</head>

			<body>

				<div id="donutchart" style="width: 1150px; height: 500px;"></div>

				<div class="socialchart" style="width: 1150px; height: auto;">

					<table class="socialchart-data" width=100%>

						<tr>

						<th><?php _e('Networks','phoen_social_login'); ?></th><th><?php _e('Number of Connections','phoen_social_login'); ?></th>

						</tr>

						<tr><td><?php _e('Facebook','phoen_social_login'); ?></td><td><?php $fb_count=get_option('psl_fb_count'); echo ($fb_count!='')?$fb_count:0?></td></tr>

						<tr><td><?php _e('Google Plus','phoen_social_login'); ?></td><td><?php $gp_count=get_option('psl_gp_count'); echo ($gp_count!='')?$gp_count:0?></td></tr>

					</table>

				</div>

			</body>

		</html>

	<?php 

	}

	if($tab2 == 'allp')

	{

		?>

			<style>

			iframe.more-plugin {

				min-height: 1000px;

				width: 100%;

			}

			.wrap{

				margin:0;

			}

			</style>

		<?php

	}

	?>

  </div>  

<style>

.socialchart {

    background: #fff none repeat scroll 0 0;

    margin-top: 20px;

	 padding: 14px;

}

.socialchart-data{border:1px solid #ccc;}

.socialchart-data th {

    padding-bottom: 10px;

    text-align: left;

}

.form-table th{ padding: 20px 10px 20px 20px;}
.form-table {background: #fff none repeat scroll 0 0;}
.form-table td {  padding: 15px 100px;}
.button-primary{margin-top: 15px !important;}

.button-info{margin-top: 15px !important;}

</style>

<?php

	wp_enqueue_script("checkout-js", plugin_dir_url( __FILE__ )."js/checkout.js",array('jquery'),'',true);

	wp_enqueue_script("addmedia-js", plugin_dir_url( __FILE__ )."js/media_upload.js",array('jquery'),'',true);

	if(!empty($_POST) && isset($_POST['save_data'])) {

		if ( check_admin_referer( 'all_social_setting_action', 'social_setting_form_nonce_field' ) )
		{
			
			$social_setting=array(
											'facebook_details'=>array(
																						'enable_facebook'=> (isset($_POST['psl_facebook_enable']) && $_POST['psl_facebook_enable'] != '')? filter_var($_POST['psl_facebook_enable'], FILTER_SANITIZE_STRING):'', 
																						'facebook_id'=>sanitize_text_field($_POST['psl_facebook_id']),
																						'fb_icon_url'=>esc_url($_POST['ad_image'])
																					),
											'google_plus_details'=>array(
																						'enable_google_plus'=> (isset($_POST['psl_google_enable']) && $_POST['psl_google_enable'] != '')? filter_var($_POST['psl_google_enable'], FILTER_SANITIZE_STRING):'', 
																						'google_id'=>sanitize_text_field($_POST['psl_google_id']),
																						'google_api'=>sanitize_text_field($_POST['psl_google_secret']),
																						'google_icon_url'=>esc_url($_POST['ad_image1'])
																					),
											'change_text_details'=>array(
																						'sign_in'=>sanitize_text_field($_POST['psl_change_sign_in']),
																						'sign_up'=>sanitize_text_field($_POST['psl_change_sign_up'])
																					),
											'change_text_details'=>array(
																						'login_label'=>sanitize_text_field($_POST['psl_social_label']),
																						'checkout_label'=>sanitize_text_field($_POST['psl_social_label_checkout'])
																					)
									);

			update_option('psl_social_plugin', $social_setting);

			wp_redirect("admin.php?page=psl-social-login");
		
		}

   }

}
 
?>