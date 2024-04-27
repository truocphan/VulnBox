<?php
/*
** PPOM Existing Meta Template
*/

/* 
**========== Direct access not allowed =========== 
*/
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Not Allowed' );
}

$all_forms = PPOM()->get_product_meta_all();

wp_nonce_field( 'ppom_meta_nonce_action', 'ppom_meta_nonce' );
?>

<div class="ppom-existing-meta-wrapper">
	<form id="ppom-groups-export-form" method="post" action="admin-post.php" enctype="multipart/form-data">
		<input type="hidden" name="action" value="ppom_export_meta"/>
		<div class="table-responsive">
			<table id="ppom-meta-table" class="table">
				<thead>
				<tr class="ppom-thead-bg">
					<th class="ppom-checkboxe-style">
						<label>
							<input type="checkbox" name="allselected" id="ppom-all-select-products-head-btn">
							<span></span>
						</label>
					</th>
					<th><?php _e( 'Meta ID', 'woocommerce-product-addon' ); ?></th>
					<th><?php _e( 'Name', 'woocommerce-product-addon' ); ?></th>
					<th><?php _e( 'Meta', 'woocommerce-product-addon' ); ?></th>
					<th><?php _e( 'Select Products', 'woocommerce-product-addon' ); ?></th>
					<th><?php _e( 'Actions', 'woocommerce-product-addon' ); ?></th>
				</tr>
				</thead>
				<tfoot>
				<tr class="ppom-thead-bg">
					<th class="ppom-checkboxe-style">
						<label>
							<input type="checkbox" name="allselected" id="ppom-all-select-products-foot-btn">
							<span></span>
						</label>
					</th>
					<th><?php _e( 'Meta ID', 'woocommerce-product-addon' ); ?></th>
					<th><?php _e( 'Name', 'woocommerce-product-addon' ); ?></th>
					<th><?php _e( 'Meta', 'woocommerce-product-addon' ); ?></th>
					<th><?php _e( 'Select Products', 'woocommerce-product-addon' ); ?></th>
					<th><?php _e( 'Actions', 'woocommerce-product-addon' ); ?></th>
				</tr>
				</tfoot>

				<?php

				foreach ( $all_forms as $productmeta ) {

					$url_edit     = add_query_arg(
						array(
							'productmeta_id' => $productmeta->productmeta_id,
							'do_meta'        => 'edit',
						)
					);
					$url_clone    = add_query_arg(
						array(
							'productmeta_id' => $productmeta->productmeta_id,
							'do_meta'        => 'clone',
						)
					);
					$url_clone    = wp_nonce_url( $url_clone, 'ppom_clone_nonce_action', 'ppom_clone_nonce' );
					$url_products = admin_url( 'edit.php?post_type=product', ( is_ssl() ? 'https' : 'http' ) );
					$product_link = '<a href="' . esc_url( $url_products ) . '">' . __( 'Products', 'woocommerce-product-addon' ) . '</a>';
					?>
					<tr>
						<td class="ppom-meta-table-checkbox-mr ppom-checkboxe-style">
							<label>
								<input class="ppom_product_checkbox" type="checkbox" name="ppom_meta[]"
									   value="<?php echo esc_attr( $productmeta->productmeta_id ); ?>">
								<span></span>
							</label>
						</td>

						<td><?php echo $productmeta->productmeta_id; ?></td>
						<td>
							<a href="<?php echo esc_url( $url_edit ); ?>">
								<?php echo stripcslashes( $productmeta->productmeta_name ); ?>
							</a>
						</td>
						<td><?php echo ppom_admin_simplify_meta( $productmeta->the_meta ); ?></td>
						<td>
							<a class="btn btn-sm btn-secondary ppom-products-modal"
							   data-ppom_id="<?php echo esc_attr( $productmeta->productmeta_id ); ?>"
							   data-formmodal-id="ppom-product-modal"><?php _e( 'Attach to Products', 'woocommerce-product-addon' ); ?></a>
						</td>
						<td class="ppom-admin-meta-actions-colunm">
							<a id="del-file-<?php echo esc_attr( $productmeta->productmeta_id ); ?>" href="#"
							   class="button button-sm ppom-delete-single-product"
							   data-product-id="<?php echo esc_attr( $productmeta->productmeta_id ); ?>"><span
										class="dashicons dashicons-no"></span></a>
							<a href="<?php echo esc_url( $url_edit ); ?>" title="<?php _e( 'Edit', 'woocommerce-product-addon' ); ?>"
							   class="button"><span class="dashicons dashicons-edit"></span></a>
							<a href="<?php echo esc_url( $url_clone ); ?>" title="<?php _e( 'Clone', 'woocommerce-product-addon' ); ?>"
							   class="button"><span class="dashicons dashicons-image-rotate-right"></span></a>
						</td>
					</tr>
					<?php
				}
				?>
			</table>
			
		</div>
	</form>
</div>

<!-- Product Modal -->
<div id="ppom-product-modal" class="ppom-modal-box" style="display: none;">
	<form id="ppom-product-form">
		<input type="hidden" name="action" value="ppom_attach_ppoms"/>
		<input type="hidden" name="ppom_id" id="ppom_id">

		<header>
			<h3><?php _e( 'WooCommerce Products', 'woocommerce-product-addon' ); ?></h3>
		</header>

		<div class="ppom-modal-body">

		</div>

		<footer>
			<button type="button"
					class="btn btn-default close-model ppom-js-modal-close"><?php _e( 'Close', 'woocommerce-product-addon' ); ?></button>
			<button type="submit" class="btn btn-info"><?php _e( 'Save', 'woocommerce-product-addon' ); ?></button>
		</footer>
	</form>
</div>
