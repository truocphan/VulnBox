<?php
/**
 * Admin - Settlement Setting Page.
 *
 * @package Welcart
 */

// $opts                 = $this->options['acting_settings'];
$openssl              = extension_loaded( 'openssl' );
$curl                 = extension_loaded( 'curl' );
$available_settlement = get_option( 'usces_available_settlement' );
$settlement_selected  = get_option( 'usces_settlement_selected' );
?>
<script type="text/javascript">
jQuery( function($) {
	if ( $.fn.jquery < "1.10" ) {
		$( '#uscestabs_settlement' ).tabs({
			cookie: {
				// store cookie for a day, without, it would be a session cookie
				expires: 1
			}
		});
	} else {
		$( "#uscestabs_settlement" ).tabs({
			active: ( $.cookie( "uscestabs_settlement" ) ) ? $.cookie( "uscestabs_settlement" ) : 0
			, activate: function( event, ui ) {
				$.cookie( "uscestabs_settlement", $( this ).tabs( "option", "active" ) );
			}
		});
	}

	$( function() {
		$( ".settlement-ui-sortable" ).sortable( {
			connectWith: ".settlement-ui-sortable",
			update: function( e, ui ) {
				var updateArray = $( "#settlement-selected" ).sortable( "toArray" ).join( "," );
				$( "#settlement-selected-update" ).val( updateArray );
			}
		});
		$( ".settlement-ui-sortable" ).disableSelection();
	});
<?php do_action( 'usces_action_settlement_script' ); ?>
});
</script>
<div class="wrap">
<div class="usces_admin">
<h1>Welcart Shop <?php esc_html_e( 'Settlement Setting', 'usces' ); ?></h1>
<?php usces_admin_action_status(); ?>
<div id="uscestabs_settlement" class="uscestabs usces_settlement">
	<ul>
		<li><a href="#uscestabs_settlement_top"><?php esc_html_e( 'Selecting a Settlement Module', 'usces' ); ?></a></li>
	<?php do_action( 'usces_action_settlement_tab_title' ); ?>
	</ul>
	<div id="uscestabs_settlement_top">
	<form action="" method="post" id="settlement_top_form">
		<div class="settlement-left">
			<h2><?php esc_html_e( 'Available settlement modules', 'usces' ); ?></h2>
			<div class="settlement-description">
				<p class="description"><?php _e( 'Drag the settlement module you want to use to the right. <br />After dragging it, be sure to click the "Update the modules to use" button.', 'usces' ); ?></p>
			</div>
			<ul class="settlement-ui-sortable" id="available-settlement">
			<?php foreach ( (array) $available_settlement as $key => $name ) : ?>
				<?php if ( ! in_array( $key, (array) $settlement_selected ) ) : ?>
				<li class="ui-available-settlement" id="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $name ); ?></li>
				<?php endif; ?>
			<?php endforeach; ?>
			</ul>
		</div>
		<div class="settlement-right">
			<div class="settlement-selected-header"><?php esc_html_e( 'Settlement modules in use', 'usces' ); ?></div>
			<ul class="settlement-ui-sortable" id="settlement-selected">
		<?php if ( is_array( $settlement_selected ) ) : ?>
			<?php foreach ( (array) $settlement_selected as $key ) : ?>
				<?php if ( array_key_exists( $key, (array) $available_settlement ) ) : ?>
				<li class="ui-settlement-selected" id="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $available_settlement[ $key ] ); ?></li>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
			</ul>
			<?php if ( isset( $_POST['acting'] ) && 'settlement_selected' == $_POST['acting'] ) : ?>
				<?php if ( '' != $mes ) : ?>
				<div class="error_message"><?php wel_esc_script_e( $mes ); ?></div>
				<?php endif; ?>
			<?php endif; ?>
			<div class="settlement-selected-footer">
				<input name="acting" type="hidden" value="settlement_selected" />
				<input name="settlement_selected" id="settlement-selected-update" type="hidden" value="<?php echo implode( ',', (array) $settlement_selected ); ?>" />
				<input name="usces_option_update" type="submit" class="button button-primary" value="<?php esc_attr_e( 'Update the modules to use', 'usces' ); ?>" />
				<?php wp_nonce_field( 'admin_settlement', 'wc_nonce' ); ?>
			</div>
		</div>
		<div class="clear"></div>
	</form>
	</div><!-- uscestabs_settlement_top -->
	<?php do_action( 'usces_action_settlement_tab_body' ); ?>
</div><!--uscestabs-->
</div><!--usces_admin-->
</div><!--wrap-->
