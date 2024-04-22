<?php if ( STM_LMS_Cart::woocommerce_checkout_enabled() ) : ?>
	<div class="stm-order">
		<?php
		printf(
			/* translators: %s: link */
			esc_html__( 'Woocommerce Checkout enabled. Get order information here - %s', 'masterstudy-lms-learning-management-system' ),
			"<a href='" . esc_url( admin_url( 'edit.php?post_type=shop_order' ) ) . "'>Orders</a>"
		);
		?>
	</div>
<?php else : ?>

	<div class="stm-order" v-bind:class="{'loading' : !order}">


		<div v-if="order">

			<div class="stm-order_data">
				<div class="stm-order_key">{{i18n.order_key}}:</div>
				<div class="stm-order_value">
					<h5>#{{order.order_key}}</h5>
				</div>
			</div>

			<div class="stm-order_data">
				<div class="stm-order_key">{{i18n.date}}:</div>
				<div class="stm-order_value">
					<h5>{{order.date_formatted}}</h5>
				</div>
			</div>

			<div class="stm-order_data">
				<div class="stm-order_key">{{i18n.status}}:</div>
				<div class="stm-order_value">
					<select v-model="status" name="order_status">
						<option v-bind:value="'pending'">{{i18n.pending}}</option>
						<option v-bind:value="'completed'">{{i18n.completed}}</option>
						<option v-bind:value="'cancelled'">{{i18n.cancelled}}</option>
					</select>
				</div>
			</div>

			<div class="stm-order_data">
				<div class="stm-order_key">{{i18n.user}}:</div>
				<div class="stm-order_value">
					<h5>{{order.user.login}} ({{order.user.email}})</h5>
				</div>
			</div>

			<h2>{{i18n.order_items}}</h2>

			<table>
				<thead>
				<tr>
					<th>{{i18n.course_name}}</th>
					<th>{{i18n.course_price}}</th>
				</tr>
				</thead>
				<tbody>
				<tr v-for="item in order.cart_items">
					<td><a v-bind:href="item.link" v-html="item.title"></a></td>
					<td>{{item.price_formatted}}</td>
				</tr>
				</tbody>
				<tfoot>
				<tr>
					<th></th>
					<th>{{i18n.total}} : {{order.total}}</th>
				</tr>
				</tfoot>
			</table>

		</div>

	</div>

<?php endif; ?>
