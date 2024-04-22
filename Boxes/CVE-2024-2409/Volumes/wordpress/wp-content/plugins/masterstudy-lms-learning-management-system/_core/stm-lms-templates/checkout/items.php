<?php if (!defined('ABSPATH')) exit; //Exit if accessed directly ?>

<?php
/**
 * $var $user_id
 *
 */


$items = stm_lms_get_cart_items($user_id, apply_filters('stm_lms_cart_items_fields', array('item_id', 'price')));
?>

<?php if (empty($items)): ?>
	<?php STM_LMS_Templates::show_lms_template('checkout/empty-cart'); ?>
<?php else:
	$total = 0;
	?>
    <h1><?php esc_html_e('Checkout', 'masterstudy-lms-learning-management-system'); ?></h1>

    <div class="stm_lms_cart">

        <div class="stm_lms_cart__item stm_lms_cart__item_head heading_font">

            <div class="stm_lms_cart__item_title">
				<?php esc_html_e('Course', 'masterstudy-lms-learning-management-system'); ?>
            </div>

            <div class="stm_lms_cart__item_price">
				<?php esc_html_e('Course price', 'masterstudy-lms-learning-management-system'); ?>
            </div>

        </div>

		<?php foreach ($items as $item):
            if(!get_post_type($item['item_id'])) continue;
			$total += $item['price']; ?>

            <div class="stm_lms_cart__item item_can_hide">

                <div class="stm_lms_cart__item_delete" data-label="<?php esc_html_e('Delete', 'masterstudy-lms-learning-management-system'); ?>">
                    <i class="lnr lnr-cross"
                       <?php if(!empty($item['enterprise'])) echo "data-delete-enterprise=" . $item['enterprise']; ?>
                       data-delete-course="<?php echo intval($item['item_id']); ?>"></i>
                </div>

                <div class="stm_lms_cart__item_image">
                    <?php if(function_exists('stm_get_VC_attachment_img_safe')): ?>
                        <?php echo stm_get_VC_attachment_img_safe(get_post_thumbnail_id($item['item_id']), 'img-135-80'); ?>
                    <?php else: ?>
                        <img src="<?php echo esc_url(get_the_post_thumbnail_url($item['item_id'], 'img-300-225')); ?>"/>
                    <?php endif; ?>
                </div>

                <div class="stm_lms_cart__item_title">
					<?php $terms = stm_lms_get_terms_array($item['item_id'], 'stm_lms_course_taxonomy', 'name', true);
					if (!empty($terms)):?>
                        <div class="terms">
                            <div class="value h6">
								<?php echo wp_kses_post(implode(', ', array_slice($terms, 0, 3) )); ?>
                            </div>
                        </div>
					<?php endif; ?>
                    <h4 class="normal_font">
                        <a href="<?php echo esc_url(get_the_permalink($item['item_id'])); ?>">
							<?php echo apply_filters('stm_lms_single_item_cart_title', sanitize_text_field(get_the_title($item['item_id'])), $item); ?>
                        </a>
                    </h4>
                    <?php do_action('stm_lms_after_single_item_cart_title', $item); ?>
                </div>

                <div class="stm_lms_cart__item_price" data-label="<?php esc_attr_e('Price', 'masterstudy-lms-learning-management-system'); ?>">
					<?php echo STM_LMS_Helpers::display_price($item['price']); ?>
                </div>

            </div>
		<?php endforeach; ?>
    </div>

    <div class="stm_lms_checkout">
		<?php STM_LMS_Templates::show_lms_template('checkout/payment', compact('user_id', 'total')); ?>
    </div>
<?php endif;