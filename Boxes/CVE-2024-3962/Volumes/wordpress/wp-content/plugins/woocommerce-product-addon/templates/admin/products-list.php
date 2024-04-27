<?php
/**
 * Product with PPOM IDs
 **/

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Not Allowed' );
}

// Getting products with already attached PPOM
$ppom_attached_params = array(
	'meta_key'       => PPOM_PRODUCT_META_KEY,
	'meta_value'     => $ppom_id,
	'compare'        => '=',
	'post_type'      => 'product',
	'posts_per_page' => - 1,
	'post_status'    => 'publish',
);

$ppom_attached = get_posts( $ppom_attached_params );

// nonce field
wp_nonce_field( 'ppom_attached_nonce_action', 'ppom_attached_nonce' );

if ( count( $ppom_attached ) > 0 ) :
	?>

	<h3><?php _e( 'Already Attached', 'woocommerce-product-addon' ); ?></h3>
	<table id="ppom-already-attached-table" class="ppom-table table table-striped">
		<thead>
		<tr>
			<th>
				<strong><?php _e( 'Product Title', 'woocommerce-product-addon' ); ?></strong>
			</th>
			<th>
				<strong><?php _e( 'PPOM Fields', 'woocommerce-product-addon' ); ?></strong>
			</th>
		</tr>
		</thead>
		<tbody>

		<?php
		foreach ( $ppom_attached as $ppom_products ) {

			echo '<tr>';
			echo '<td>';

			echo $ppom_products->post_title;

			echo '</td>';

			echo '<td>';
			echo '<input type="checkbox" name="ppom_removed[]" value="' . $ppom_products->ID . '"> Remove';
			echo '</td>';


			echo '</tr>';
		}
		?>

		</tbody>
	</table>

	<?php
endif;
?>

<h3><?php _e( 'Attach', 'woocommerce-product-addon' ); ?></h3>
<table id="ppom-product-table" class="ppom-table table table-striped">
	<thead>
	<tr>
		<th>
			<strong><?php _e( 'Product Title', 'woocommerce-product-addon' ); ?></strong>
		</th>
		<th>
			<strong><?php _e( 'PPOM Fields', 'woocommerce-product-addon' ); ?></strong>
		</th>
	</tr>
	</thead>
	<tbody>

	<?php
	foreach ( $product_list as $product ) {

		echo '<tr>';
		echo '<td>';

		echo $product->post_title;

		echo '</td>';

		echo '<td>';
		echo '<input type="checkbox" name="ppom_attached[]" value="' . $product->ID . '"> Add';
		echo '</td>';


		echo '</tr>';
	}
	?>
	</tbody>
</table>
