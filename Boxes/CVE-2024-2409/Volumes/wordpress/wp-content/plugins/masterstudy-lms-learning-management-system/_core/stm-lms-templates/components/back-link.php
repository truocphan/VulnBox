<?php

/**
 * @var int $id
 * @var string $url
 */

wp_enqueue_style( 'masterstudy-back-link' );
?>

<a href="<?php echo esc_url( $url ); ?>" class="masterstudy-back-link" data-id="<?php echo esc_attr( $id ); ?>"></a>
