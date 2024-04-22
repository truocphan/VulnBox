<?php

/**
 * @var string $id
 * @var string $title
 * @var string $text
 * @var string $submit_button_text
 * @var string $cancel_button_text
 * @var string $submit_button_style
 * @var string $cancel_button_style
 * @var boolean $dark_mode
 *
 * masterstudy-alert_dark-mode - for dark mode
 * masterstudy-alert_open - for open alert
 */

wp_enqueue_style( 'masterstudy-alert' );

$submit_button_style = $submit_button_style ?? '';
?>

<div data-id="<?php echo esc_attr( $id ); ?>" class="masterstudy-alert <?php echo esc_attr( $dark_mode ? 'masterstudy-alert_dark-mode' : '' ); ?>">
	<div class="masterstudy-alert__wrapper">
		<div class="masterstudy-alert__header">
			<span class="masterstudy-alert__header-title">
				<?php echo esc_html( $title ); ?>
			</span>
			<span class="masterstudy-alert__header-close"></span>
		</div>
		<div class="masterstudy-alert__text">
			<?php echo esc_html( $text ); ?>
		</div>
		<div class="masterstudy-alert__actions">
			<?php
			STM_LMS_Templates::show_lms_template(
				'components/button',
				array(
					'title'         => $cancel_button_text,
					'type'          => '',
					'link'          => '#',
					'style'         => $cancel_button_style,
					'size'          => 'sm',
					'id'            => 'cancel',
					'icon_position' => '',
					'icon_name'     => '',
				)
			);
			if ( $submit_button_style ) {
				STM_LMS_Templates::show_lms_template(
					'components/button',
					array(
						'title'         => $submit_button_text,
						'type'          => '',
						'link'          => '#',
						'style'         => $submit_button_style,
						'size'          => 'sm',
						'id'            => 'submit',
						'icon_position' => '',
						'icon_name'     => '',
					)
				);
			}
			?>
		</div>
	</div>
</div>
