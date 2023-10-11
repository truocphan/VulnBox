<!DOCTYPE html>
<html>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, user-scalable=no">
		<meta name="format-detection" content="telephone=no"/>
	</head>
	<body>
	<style>
		body {
			height: 600px !important;
			overflow: hidden !important;
		}
		#load_rich_editor {
			font-size: 13px !important;
			line-height: 2.15384615 !important;
			height: 600px !important;
		}
		#load_rich_editor iframe#sendmailmessage_ifr, #load_rich_editor textarea#sendmailmessage {
			height: 570px !important;
		}
		.wp-core-ui .quicktags-toolbar input.button.button-small {
			font-size: 12px;
			min-height: 26px;
			line-height: 2;
		}
		#wpfooter {display: none}
	</style>
	<div id="load_rich_editor">
		<p id="email_content_response"></p>
		<?php
		/**
		 * Set default tab use for rich editor
		 *
		 * @return string type of tab (text,html).
		 */
		function usces_set_rich_editor_default_open_tab() {
			if ( usces_is_html_mail() ) {
				return 'tinymce';
			}
		}
		// add function set rich editor default show tab.
		add_filter( 'wp_default_editor', 'usces_set_rich_editor_default_open_tab' );

		$order_id = isset( $_GET['orderid'] ) ? stripslashes( $_GET['orderid'] ) : 0;
		$mode_str = isset( $_GET['mode'] ) ? stripslashes( $_GET['mode'] ) : '';
		$content  = '';
		wp_editor(
			$content,
			'sendmailmessage',
			array(
				'dfw'           => true,
				'tabindex'      => 1,
				'textarea_rows' => 26,
				'textarea_name' => 'sendmailmessage',
			)
		);
		?>
	</div>
	<script>
		var settings ={
			url: "<?php echo site_url(); ?>/wp-admin/admin-ajax.php",
			type: 'POST',
			cache: false
		};
		window.onload = function() {
			jQuery("#email_content_response").html('');
			var modeControl = '<?php echo esc_attr( $mode_str ); ?>';
			var order_id = '<?php echo (int) $order_id; ?>';
			var s = settings;
			s.data = "action=order_item_ajax&mode=" + modeControl + "&order_id=" + order_id;
			var now_loading = "<?php esc_attr_e( 'now loading', 'usces' ); ?>";
			if (typeof tinymce !== 'undefined') {
				tinymce.get("sendmailmessage").setContent(now_loading);
			} else {
				jQuery("#sendmailmessage").val(now_loading);
			}
			jQuery.ajax( s ).done(function( data ) {
				if (typeof tinymce !== 'undefined') {
					tinymce.get("sendmailmessage").setContent(data);
				} else {
					jQuery("#sendmailmessage").val(data);
				}
			}).fail(function( msg ) {
				jQuery("#email_content_response").html(msg);
				if (typeof tinymce !== 'undefined') {
					tinymce.get("sendmailmessage").setContent('');
				} else {
					jQuery("#sendmailmessage").val('');
				}
			});
		};
		function getContentEditor() {
			if (typeof tinymce !== 'undefined' && tinymce.get("sendmailmessage")) {
				if ( jQuery("#sendmailmessage").is(':visible') ) {
					// TEXT TAB active
					return jQuery("#sendmailmessage").val();
				} else {
					// VISUAL TAB active
					return tinymce.get("sendmailmessage").getContent();
				}
			} else {
				var sendmailmessageText = jQuery("#sendmailmessage").val();
				return sendmailmessageText;
			}
		}
	</script>
