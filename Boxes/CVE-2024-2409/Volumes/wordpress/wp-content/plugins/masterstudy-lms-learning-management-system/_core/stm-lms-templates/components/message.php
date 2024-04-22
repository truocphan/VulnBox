<?php
/**
 * Message handler component.
 *
 * @var boolean $download    - if true link is downloadable, otherwise not. Default false.
 * @var string  $icon        - icon name. Default ''.
 * @var boolean $show_header - if true $title and close button will be shown. Default false.
 * @var string  $title       - Header title (opposite to close button). Default ''.
 * @var string  $link_url    - Link (a tag) url. Default ''.
 * @var string  $link_text   - Link (a tag) text, if there is not $link_url will be shown just as text. Default ''.
 * @var string  $link_target - Link (a tag) target. Default `_blank`.
 * @var string  $id          - ID of the component. Defaul unique ID generated.
 * @var string  $color       - Default `dark`. Accepts: `accent`,`success`,`danger`,`warning`, `light`
 * @var string  $bg          - Default `dark`. Accepts: `accent`,`success`,`danger`,`warning`, `secondary`
 * @var boolean $is_vertical - if true components items will be diwplayed vertically, otherwise horizontally. Default `horizontal`;
 *
 * @package masterstudy
 */

$download       = ! empty( $download );
$icon           = ! empty( $icon ) ? $icon : '';
$is_header      = ! empty( $show_header );
$message_title  = ! empty( $title ) ? $title : '';
$link_url       = ! empty( $link_url ) ? $link_url : '';
$link_text      = ! empty( $link_text ) ? $link_text : '';
$link_target    = ! empty( $link_target ) ? $link_target : '_blank';
$message_id     = ! empty( $id ) ? $id : uniqid( 'masterstudy-message-' );
$message_class  = ! empty( $color ) ? ' masterstudy-message_color-' . $color : ' masterstudy-message_color-dark';
$message_class .= ! empty( $bg ) ? ' masterstudy-message_bg-' . $bg : ' masterstudy-message_bg-light';
$message_class .= ! empty( $is_vertical ) ? ' masterstudy-message_vertical' : ' masterstudy-message_horizontal';

wp_enqueue_style( 'masterstudy-message' );
wp_enqueue_script( 'masterstudy-message' );
?>

<div data-id="<?php echo esc_attr( $message_id ); ?>" class="masterstudy-message masterstudy-message_hidden <?php echo esc_attr( $message_class ); ?>">
	<?php if ( $is_header ) : ?>
	<div class="masterstudy-message__header">
		<div class="masterstudy-message__title">
			<?php echo esc_html( $message_title ); ?>
		</div>
		<div class="masterstudy-message__close">
			<span class="stmlms-close"></span>
		</div>
	</div>
	<?php endif; ?>
	<div class="masterstudy-message__body">
		<div class="masterstudy-message__content">
			<?php if ( $icon ) : ?>
			<span class="masterstudy-message__icon stmlms-<?php echo esc_attr( $icon ); ?>"></span>
			<?php endif; ?>
			<span class="masterstudy-message__text">
				<?php echo esc_html( $text ); ?>
			</span>
		</div>
		<div class="masterstudy-message__info">
			<?php if ( $link_url ) : ?>
			<a href="<?php echo esc_url( $link_url ); ?>" class="masterstudy-message__link" target="<?php echo esc_attr( $link_target ); ?>" <?php echo $download ? 'download' : ''; ?>>
				<?php if ( $download ) : ?>
				<span class="stmlms-download"></span>
				<?php endif; ?>
				<?php echo esc_html( $link_text ); ?>
			</a>
			<?php else : ?>
				<?php echo esc_html( $link_text ); ?>
			<?php endif; ?>
		</div>
	</div>
</div>
