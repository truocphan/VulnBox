<?php if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly ?>

<?php
/**
 * @var $post_id
 * @var $item_id
 * @var $is_previewed
 */

if (!empty($item_id)):
    wp_enqueue_style('video.js');
	stm_lms_register_script('lessons', array('video.js'));
	if(function_exists('vc_asset_url')) {
		wp_enqueue_style('stm_lms_wpb_front_css', vc_asset_url('css/js_composer.min.css'));
	}

	if(class_exists('Ultimate_VC_Addons')) {
		STM_LMS_Lesson::aio_front_scripts();
	}

	$q = new WP_Query(array(
		'posts_per_page' => 1,
		'post_type'      => 'stm-lessons',
		'post__in'       => array($item_id)
	));

	if ($q->have_posts()): ?>
        <div class="stm-lms-course__lesson-content">

            <?php STM_LMS_Templates::show_lms_template('lesson/video', array('id' => $item_id)); ?>

			<?php while ($q->have_posts()): $q->the_post(); ?>
				<?php
                ob_start();
                the_content();
                $content = ob_get_clean();
                $content = str_replace( '../../', site_url() . '/', $content );
                echo stm_lms_filtered_output($content);
                ?>
			<?php endwhile; ?>
        </div>

		<?php wp_reset_postdata(); ?>
	<?php endif; ?>
<?php endif;