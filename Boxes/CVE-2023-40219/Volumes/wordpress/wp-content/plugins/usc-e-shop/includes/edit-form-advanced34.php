<?php
/**
 * Post advanced form for inclusion in the administration panels.
 *
 * @package WordPress
 * @subpackage Administration
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
global $usces;

/**
 * Post ID global
 * @name $post_ID
 * @var int
 */
$post_ID          = isset( $post_ID ) ? (int) $post_ID : 0;
$temp_ID          = isset( $temp_ID ) ? (int) $temp_ID : 0;
$user_ID          = isset( $user_ID ) ? (int) $user_ID : 0;
$action           = isset( $action ) ? $action : '';
$messages         = array();
$messages['post'] = array(
	0  => '', // Unused. Messages start at index 1.
	1  => sprintf( __( 'Post updated. <a href="%s">View post</a>' ), esc_url( get_permalink( $post_ID ) ) ),
	2  => __( 'Custom field updated.' ),
	3  => __( 'Custom field deleted.' ),
	4  => __( 'Post updated.' ),
	/* translators: %s: Date and time of the revision. */
	5  => isset( $_GET['revision'] ) ? sprintf( __( 'Post restored to revision from %s' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
	6  => sprintf( __( 'Post published. <a href="%s">View post</a>' ), esc_url( get_permalink( $post_ID ) ) ),
	7  => __( 'Post saved.' ),
	8  => sprintf( __( 'Post submitted. <a target="_blank" href="%s">Preview post</a>' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
	9  => sprintf( __( 'Post scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview post</a>' ),
		/* translators: Publish box date format, see http://php.net/date */
		( isset( $post->post_date ) ? date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) : '' ), esc_url( get_permalink( $post_ID ) ) ),
	10 => sprintf( __( 'Post draft updated. <a target="_blank" href="%s">Preview post</a>' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
);
$messages         = apply_filters( 'post_updated_messages', $messages );

$message = false;
if ( isset( $_GET['message'] ) ) {
	$_GET['message'] = absint( $_GET['message'] );
	if ( isset( $messages[ $post_type ][ $_GET['message'] ] ) ) {
		$message = $messages[ $post_type ][ $_GET['message'] ];
	} elseif ( ! isset( $messages[ $post_type ] ) && isset( $messages['post'][ $_GET['message'] ] ) ) {
		$message = $messages['post'][ $_GET['message'] ];
	}
}

$notice     = false;
$form_extra = '';
if ( isset( $post->post_status ) && 'auto-draft' === $post->post_status ) {
	if ( 'edit' === $action ) {
		$post->post_title = '';
	}
	$autosave    = false;
	$form_extra .= "<input type='hidden' id='auto_draft' name='auto_draft' value='1' />";
} else {
	$autosave = wp_get_post_autosave( $post_ID );
}

$form_action  = 'editpost';
$nonce_action = 'update-' . $post_type . '_' . $post_ID;
$form_extra  .= "<input type='hidden' id='post_ID' name='post_ID' value='" . esc_attr( $post_ID ) . "' />";

// Detect if there exists an autosave newer than the post and if that autosave is different than the post.
if ( $autosave && mysql2date( 'U', $autosave->post_modified_gmt, false ) > mysql2date( 'U', $post->post_modified_gmt, false ) ) {
	foreach ( _wp_post_revision_fields() as $autosave_field => $_autosave_field ) {
		if ( normalize_whitespace( $autosave->$autosave_field ) !== normalize_whitespace( $post->$autosave_field ) ) {
			$notice = sprintf( __( 'There is an autosave of this post that is more recent than the version below.  <a href="%s">View the autosave</a>' ), get_edit_post_link( $autosave->ID ) );
			break;
		}
	}
	unset( $autosave_field, $_autosave_field );
}

$post_type_object = get_post_type_object( $post_type );

// All meta boxes should be defined and added before the first do_meta_boxes() call (or potentially during the do_meta_boxes action).

/****************************************************************************/

do_action( 'add_meta_boxes', $post_type, $post );
do_action( 'add_meta_boxes_' . $post_type, $post );

do_action( 'do_meta_boxes', $post_type, 'normal', $post );
do_action( 'do_meta_boxes', $post_type, 'advanced', $post );
do_action( 'do_meta_boxes', $post_type, 'side', $post );

/* 30 ***************************************/

global $screen_layout_columns;
?>
<div class="wrap">
<div class="usces_admin">
<h1><?php echo esc_html( $title ); ?></h1>
<?php usces_admin_action_status(); ?>
<?php wp_nonce_field( 'admin_setup', 'wc_nonce' ); ?>

<form name="post" action="" method="post" id="post">
<?php
$product         = wel_get_product( $post_ID, false );
$itemCode        = $product['itemCode'];
$itemName        = $product['itemName'];
$itemRestriction = $product['itemRestriction'];
$itemPointrate   = ( ! empty( $product['itemPointrate'] ) ) ? $product['itemPointrate'] : '';
$itemGpNum1      = $product['itemGpNum1'];
$itemGpNum2      = $product['itemGpNum2'];
$itemGpNum3      = $product['itemGpNum3'];
$itemGpDis1      = $product['itemGpDis1'];
$itemGpDis2      = $product['itemGpDis2'];
$itemGpDis3      = $product['itemGpDis3'];

$itemOrderAcceptable         = $product['itemOrderAcceptable'];
$itemOrderAcceptable_checked = ( ! empty( $itemOrderAcceptable ) && 1 === (int) $itemOrderAcceptable ) ? ' checked="checked"' : '';

$itemShipping          = $product['itemShipping'];
$itemDeliveryMethod    = $product['itemDeliveryMethod'];
$itemShippingCharge    = $product['itemShippingCharge'];
$itemIndividualSCharge = $product['itemIndividualSCharge'];
/*************************************** 30 */
$usces_referer = ( isset( $_REQUEST['usces_referer'] ) ) ? $_REQUEST['usces_referer'] : '';
?>

<?php wp_nonce_field( $nonce_action ); ?>
<input type="hidden" name="usces_nonce" id="usces_nonce" value="<?php echo wp_create_nonce( 'usc-e-shop' ); ?>" />
<input type="hidden" id="user-id" name="user_ID" value="<?php echo (int) $user_ID; ?>" />
<input type="hidden" id="hiddenaction" name="action" value="<?php echo esc_attr( $form_action ); ?>" />
<input type="hidden" id="originalaction" name="originalaction" value="<?php echo esc_attr( $form_action ); ?>" />
<input type="hidden" id="post_author" name="post_author" value="<?php echo esc_attr( $post->post_author ); ?>" />
<input type="hidden" id="post_type" name="post_type" value="<?php echo esc_attr( $post_type ); ?>" />
<input type="hidden" id="original_post_status" name="original_post_status" value="<?php echo esc_attr( $post->post_status ); ?>" />
<input type="hidden" id="referredby" name="referredby" value="<?php echo esc_url( stripslashes( wp_get_referer() ) ); ?>" />

<input type="hidden" name="post_mime_type" value="item" />
<input type="hidden" name="page" value="usces_itemedit" />
<input name="usces_referer" type="hidden" id="usces_referer" value="<?php echo esc_url( $usces_referer ); ?>" />

<?php
if ( isset( $post->post_status ) && 'draft' !== $post->post_status ) {
	wp_original_referer_field( true, 'previous' );
}
wel_esc_script_e( $form_extra );

wp_nonce_field( 'autosave', 'autosavenonce', false );
wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
?>
<div id="refbutton"><a href="<?php echo esc_url( USCES_ADMIN_URL ) . '?page=usces_itemedit&amp;action=duplicate&amp;post=' . $post->ID . '&usces_referer=' . urlencode( esc_url( $usces_referer ) ); ?>">[<?php esc_html_e( 'make a copy', 'usces' ); ?>]</a> <a href="<?php echo esc_url( $usces_referer ); ?>">[<?php esc_html_e( 'back to item list', 'usces' ); ?>]</a></div>
<!--<div id="poststuff" class="metabox-holder has-right-sidebar">-->
<div id="poststuff">
<div id="post-body" class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>">
<div id="post-body-content">

<!--<div id="postitemcustomstuff">-->
<div id="meta_box_product_first_box" class="postbox " >
<div class="inside">
<table class="iteminfo_table">
<?php ob_start(); ?>
<tr>
<th><?php esc_html_e( 'item code', 'usces' ); ?></th>
<td><input type="text" name="itemCode" id="itemCode" class="itemCode" value="<?php echo esc_attr( $itemCode ); ?>" onkeydown="if (event.keyCode == 13) {return false;}" />
<input type="hidden" name="itemCode_nonce" id="itemCode_nonce" value="<?php echo wp_create_nonce( 'itemCode_nonce' ); ?>" /></td>
</tr>
<tr>
<th><?php esc_html_e( 'item name', 'usces' ); ?></th>
<td><input type="text" name="itemName" id="itemName" class="itemName" value="<?php echo esc_attr( $itemName ); ?>" />
<input type="hidden" name="itemName_nonce" id="itemName_nonce" value="<?php echo wp_create_nonce( 'itemName_nonce' ); ?>" /></td>
</tr>
<tr>
<th><?php esc_html_e( 'Limited amount for purchase', 'usces' ); ?></th>
<td><?php printf( __( 'limit by%s%s%s', 'usces' ), '<input type="text" name="itemRestriction" id="itemRestriction" class="itemRestriction" value="', esc_attr( $itemRestriction ), '" />' ); ?>
<input type="hidden" name="itemRestriction_nonce" id="itemRestriction_nonce" value="<?php echo wp_create_nonce( 'itemRestriction_nonce' ); ?>" /></td>
</tr>
<tr>
<th><?php esc_html_e( 'Percentage of points', 'usces' ); ?></th>
<td><input type="text" name="itemPointrate" id="itemPointrate" class="itemPointrate" value="<?php echo esc_attr( $itemPointrate ); ?>" />%<em>(<?php esc_html_e( 'Integer', 'usces' ); ?>)</em>
<input type="hidden" name="itemPointrate_nonce" id="itemPointrate_nonce" value="<?php echo wp_create_nonce( 'itemPointrate_nonce' ); ?>" /></td>
</tr>
<tr>
<?php
$gp_row = '<th rowspan="3">' . __( 'Business package discount', 'usces' ) . '</th>
<td>1.' . sprintf( __( 'in more than%s%s%s%s%s %s%s%s,', 'usces' ), '<input type="text" name="', 'itemGpNum1', '" id="', 'itemGpNum1', '" class="itemPointrate"', 'value="', esc_attr( $itemGpNum1 ), '" />') . '<input type="text" name="itemGpDis1" id="itemGpDis1" class="itemPointrate" value="' . esc_attr( $itemGpDis1 ) . '" />' . __( '%discount', 'usces' ) . '(' . __( 'Unit price', 'usces' ) . ')</td>
</tr>
<tr>
<td>2.' . sprintf( __( 'in more than%s%s%s%s%s %s%s%s,', 'usces' ), '<input type="text" name="', 'itemGpNum2', '" id="', 'itemGpNum2', '" class="itemPointrate"', 'value="', esc_attr( $itemGpNum2 ), '" />') . '<input type="text" name="itemGpDis2" id="itemGpDis2" class="itemPointrate" value="' . esc_attr( $itemGpDis2 ) . '" />' . __( '%discount', 'usces' ) . '(' . __( 'Unit price', 'usces' ) . ')</td>
</tr>
<tr>
<td>3.' . sprintf( __( 'in more than%s%s%s%s%s %s%s%s,', 'usces' ), '<input type="text" name="', 'itemGpNum3', '" id="', 'itemGpNum3', '" class="itemPointrate"', 'value="', esc_attr( $itemGpNum3 ), '" />') . '<input type="text" name="itemGpDis3" id="itemGpDis3" class="itemPointrate" value="' . esc_attr( $itemGpDis3 ) . '" />' . __( '%discount', 'usces' ) . '(' . __( 'Unit price', 'usces' ) . ')</td>
</tr>';
echo apply_filters( 'usces_item_master_gp_row', $gp_row, $post_ID );
?>
<tr>
<th><?php esc_html_e( 'Purchase restriction when sold out', 'usces' ); ?></th>
<td><label type="itemOrderAcceptable"><input name="itemOrderAcceptable" type="checkbox" id="itemOrderAcceptable" class="itemOrderAcceptable" value="1"<?php echo esc_attr( $itemOrderAcceptable_checked ); ?>/><?php esc_html_e( 'Purchase is not restricted (not checked the stock) even when sold out.', 'usces' ); ?></label></td>
<input type="hidden" name="itemOrderAcceptable_nonce" id="itemOrderAcceptable_nonce" value="<?php echo wp_create_nonce( 'itemOrderAcceptable_nonce' ); ?>" /></td>
</tr>
<?php apply_filters( 'usces_item_master_first_section', null, $post_ID ); ?>
<?php
$first_section = ob_get_contents();
ob_end_clean();
$first_section = apply_filters( 'usces_item_master_first_section_full', $first_section, $post_ID );
echo $first_section; // no escape due to filter.
?>
</table>
</div>
</div>
<?php do_action( 'usces_after_item_master_first_section', $post_ID ); ?>
<!--<div id="postitemcustomstuff">-->
<div id="meta_box_product_second_box" class="postbox " >
<div class="inside">
<table class="iteminfo_table">
<?php
$second_section   = '<tr class="shipped">
<th>' . __( 'estimated shipping date', 'usces' ) . '</th>
<td><select name="itemShipping" id="itemShipping" class="itemShipping">';
foreach ( (array) $this->shipping_rule as $key => $label ) {
	$selected        = ( (int) $key === (int) $itemShipping ) ? ' selected="selected"' : '';
	$second_section .= '<option value="' . esc_attr( $key ) . '"' . $selected . '>' . esc_html( $label ) . '</option>';
}
$second_section  .= '</select>
<input type="hidden" name="itemShipping_nonce" id="itemShipping_nonce" value="' . wp_create_nonce( 'itemShipping_nonce' ) . '" /></td>
</tr>
<tr class="shipped">
<th>' . __( 'shipping option', 'usces' ) . '</th>
<td>';
$delivery_methods = ( isset( $this->options['delivery_method'] ) && is_array( $this->options['delivery_method'] ) ) ? $this->options['delivery_method'] : array();
if ( count( $delivery_methods ) === 0 ) {
	$second_section .= __( '* Please register an item, after you finished delivery setting!', 'usces' );
} else {
	foreach ( $delivery_methods as $deli ) {
		$second_section .= '<label for="itemDeliveryMethod[' . $deli['id'] . ']"><input name="itemDeliveryMethod[' . $deli['id'] . ']" id="itemDeliveryMethod[' . $deli['id'] . ']" type="checkbox" value="' . esc_attr( $deli['id'] ) . '"';
		if ( in_array( $deli['id'], (array) $itemDeliveryMethod ) ) {
			$second_section .= ' checked="checked"';
		}
		$second_section .= ' />' . esc_html( $deli['name'] ) . '</label>';
	}
}
$second_section .= '</td>
</tr>
<tr class="shipped">
<th>' . __( 'Shipping', 'usces' ) . '</th>
<td><select name="itemShippingCharge" id="itemShippingCharge" class="itemShippingCharge">';
foreach ( $this->options['shipping_charge'] as $cahrge ) {
	$selected        = ( (int) $cahrge['id'] === (int) $itemShippingCharge ) ? ' selected="selected"' : '';
	$second_section .= '<option value="' . $cahrge['id'] . '"' . $selected . '>' . esc_html( $cahrge['name'] ) . '</option>';
}
$second_section .= '</select>
<input type="hidden" name="itemShippingCharge_nonce" id="itemShippingCharge_nonce" value="' . wp_create_nonce( 'itemShippingCharge_nonce' ) . '" /></td>
</tr>
<tr class="shipped">
<th>' . __( 'Postage individual charging', 'usces' ) . '</th>
<td><input name="itemIndividualSCharge" id="itemIndividualSCharge" type="checkbox" value="1"';
if ( $itemIndividualSCharge ) {
	$second_section .= ' checked="checked"';
}
$second_section .= ' /></td>
</tr>';
$second_section  = apply_filters( 'usces_item_master_second_section', $second_section, $post_ID );
echo $second_section; // no escape due to filter.
?>
</table>
</div>
</div>
<?php do_action( 'usces_after_item_master_second_section', $post_ID ); ?>

<div id="itemsku" class="postbox">
<h3><span>SKU <?php esc_html_e( 'Price', 'usces' ); ?></span></h3>
<div class="inside">
	<div id="postskucustomstuff" class="skustuff">
	<?php
	//$skus = $usces->get_skus($post_ID);
	$skus = $product['_sku'];
	list_item_sku_meta( $skus );
	item_sku_meta_form();
	?>
	</div>
</div>
</div>
<?php do_action( 'usces_after_item_master_sku_section', $post_ID ); ?>
<div id="itemoption" class="postbox">
<h3><span><?php esc_html_e( 'options for items', 'usces' ); ?></span></h3>
<div class="inside">
<div id="postoptcustomstuff"><div id="optajax-response"></div>
<?php
//$opts = usces_get_opts($post_ID);
$opts = $product['_opt'];
list_item_option_meta( $opts );
item_option_meta_form();
?>
</div>
</div>
</div>
<?php do_action( 'usces_after_item_master_option_section', $post_ID ); ?>

<div class="postbox">
<?php if ( post_type_supports( $post_type, 'title' ) ) { ?>
<div class="inside">
<div class="itempagetitle"><?php esc_html_e( 'The product details page title', 'usces' ); ?></div>
<div id="titlediv">
	<div id="titlewrap">
		<label class="hide-if-no-js" style="visibility:hidden" id="title-prompt-text" for="title"><?php esc_html_e( 'Enter title here' ); ?></label>
		<input type="text" name="post_title" size="30" tabindex="1" value="<?php echo esc_attr( $post->post_title ); ?>" id="title" autocomplete="off" />
	</div>
	<?php
	$sample_permalink_html = get_sample_permalink_html( $post->ID );
	$shortlink             = wp_get_shortlink( $post->ID, 'post' );
	if ( ! empty( $shortlink ) ) {
		$sample_permalink_html .= '<input id="shortlink" type="hidden" value="' . esc_attr( $shortlink ) . '" /><a href="#" class="button" onclick="prompt(&#39;URL:&#39;, jQuery(\'#shortlink\').val()); return false;">' . __( 'Get Shortlink' ) . '</a>';
	}
	if ( ! ( 'pending' === $post->post_status && ! current_user_can( $post_type_object->cap->publish_posts ) ) ) {
		?>
		<div id="edit-slug-box">
		<?php
		if ( ! empty( $post->ID ) && ! empty( $sample_permalink_html ) && 'auto-draft' !== $post->post_status ) {
			wel_esc_script_e( $sample_permalink_html );
		}
		?>
		</div>
		<?php
	}
	?>
</div>
	<?php
	wp_nonce_field( 'samplepermalink', 'samplepermalinknonce', false );
}
?>
<?php if ( post_type_supports( $post_type, 'editor' ) ) { ?>
<div class="itempagetitle"><?php esc_html_e( 'Full product details', 'usces' ); ?></div>
<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">

<style type="text/css">
<!--
.wp_themeSkin table td {
	background-color: white;
}
-->
</style>
	<?php
	// if ( version_compare( $wp_version, '3.3-beta', '>' ) ) {
		wp_editor( $post->post_content, 'content', array( 'dfw' => true, 'tabindex' => 1 ) );
	// } else {
	// 	the_editor( $post->post_content );
	// }
	?>
<table id="post-status-info" cellspacing="0"><tbody><tr>
	<td id="wp-word-count"></td>
	<td class="autosave-info">
	<span id="autosave">&nbsp;</span>
	<?php
	if ( 'auto-draft' !== $post->post_status ) {
		echo '<span id="last-edit">';
		$last_user = get_userdata( get_post_meta( $post_ID, '_edit_last', true ) );
		if ( $last_user ) {
			/* translators: 1: Name of most recent post author, 2: Post edited date, 3: Post edited time. */
			printf( __( 'Last edited by %1$s on %2$s at %3$s' ), esc_html( $last_user->display_name ), mysql2date( __( 'F j, Y' ), $post->post_modified ), mysql2date( __( 'g:i a' ), $post->post_modified ) );
		} else {
			if ( isset( $post->post_modified ) ) {
				/* translators: 1: Post edited date, 2: Post edited time. */
				printf( __( 'Last edited on %1$s at %2$s' ), mysql2date( __( 'F j, Y' ), $post->post_modified ), mysql2date( __( 'g:i a' ), $post->post_modified ) );
			}
		}
		echo '</span>';
	}
	?>
	</td>
</tr></tbody></table>

</div>
	<?php
}
?>
</div>
</div>

<?php
do_action( 'edit_form_after_title', $post );
?>

</div>

<div id="postbox-container-1" class="postbox-container">
<div id="side-info-column" class="inner-sidebar">
</div>

<?php

if ( 'page' === $post_type ) {
	do_action( 'submitpage_box' );
} else {
	do_action( 'submitpost_box' );
}
do_meta_boxes( $post_type, 'side', $post );

?>
</div>
<div id="postbox-container-2" class="postbox-container">
<?php

do_meta_boxes( null, 'normal', $post );

if ( 'page' === $post_type ) {
	do_action( 'edit_page_form' );
} else {
	do_action( 'edit_form_advanced' );
}
do_meta_boxes( null, 'advanced', $post );

?>
</div>
<?php
do_action( 'dbx_post_sidebar' );
?>
</div>

<br class="clear" />
</div><!-- /poststuff -->
</form>
</div><!-- /usces_admin -->
</div><!-- /wrap -->

<?php
if ( post_type_supports( $post_type, 'comments' ) ) {
	wp_comment_reply();
}
?>

<?php if ( ( isset( $post->post_title ) && '' === $post->post_title ) || ( isset( $_GET['message'] ) && 2 > $_GET['message'] ) ) : ?>
<script type="text/javascript">
try{document.post.itemCode.focus();}catch(e){}
try{
	var dfo = document.post;
	if(dfo.itemRestriction.value == '') dfo.itemRestriction.value = uscesL10n.purchase_limit;
	if(dfo.itemPointrate.value == '') dfo.itemPointrate.value = uscesL10n.point_rate;
	if(dfo.itemShipping.selectedIndex == '0') dfo.itemShipping.selectedIndex = uscesL10n.shipping_rule;
}catch(e){}

jQuery(document).ready(function($){
	$("#in-category-"+<?php echo esc_attr( USCES_ITEM_CAT_PARENT_ID ); ?>).prop( "checked", true );
});
</script>
<?php endif; ?>
