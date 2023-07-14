<?php 
ob_start(); 
$htmega_coupon_old_price = 599;
$htmega_coupon_new_price = 120;
$htmega_coupon_save = 100;
$htmega_coupon = "D63AACD";

?>
<div class="htoptions-sidebar-adds-area">
<?php 

$template_data = HTMega_Template_Library::instance()->get_templates_info();
    if( is_plugin_active('htmega-pro/htmega_pro.php') ){
        $htmega_license_title = apply_filters('htmega_license_title', 'lifetime' ); 
        if ( !str_contains( $htmega_license_title, 'Growth' ) && !str_contains( $htmega_license_title, 'Unlimited - Lifetime' ) ) {

            if( isset( $template_data['notices']['sidebar'][1]['status'] ) && !empty( $template_data['notices']['sidebar'][1]['status'] ) ){
                ?>
                <a href="<?php echo esc_url( $template_data['notices']['sidebar'][1]['bannerlink'] ); ?>" target="_blank">
                    <img class="htoptions-banner-img" src="<?php echo esc_url( $template_data['notices']['sidebar'][1]['bannerimage'] ); ?>" alt="<?php echo esc_attr__( 'HT Mega Addons', 'htmega-addons' ); ?>"/>
                </a>
                <?php
            }
        }
    }else{

        if( isset( $template_data['notices']['sidebar'][0]['status'] ) && !empty( $template_data['notices']['sidebar'][0]['status'] )){
            ?>
            <a href="<?php echo esc_url( $template_data['notices']['sidebar'][0]['bannerlink'] ); ?>" target="_blank">
                <img  class="htoptions-banner-img" src="<?php echo esc_url( $template_data['notices']['sidebar'][0]['bannerimage'] ); ?>" alt="<?php echo esc_attr__( 'HT Mega Addons', 'htmega-addons' ); ?>"/>
            </a>
         <?php 
        }
    }
    ?>

    <!--<div class="htoption-banner-area">

         <div class="htoption-banner-head">
            <div class="htoption-intro">
                <p><?php echo esc_html__('Upgrade now & Save money. Get highest plan at lowest price.','htmega-addons'); ?></p>
            </div>
        </div>
        <div class="htoption-banner-pricing">
            <h2><span class="htoption-old-price"><?php echo esc_html("$".$htmega_coupon_old_price);?></span><span class="htoption-new-price"><?php echo esc_html("$".$htmega_coupon_new_price);?></span></h2>
            <p class="htoption-save-amount"><?php esc_html_e('SAVE $','htmega-addons'); esc_html_e($htmega_coupon_save);?></p>
        </div>

        <div class="htoption-coupon-box">
            <h4>USE COUPON CODE</h4>
            <div class="htoption-coupon">
                <input class="htoption-coupon-text" type="text" value="D63AACD" readonly>
                <button class="htoption-coupon-btn htoption-btn-copy-status-copy">
                    <span class="htoption-coupon-copy-content">
                        <img src="<?php echo esc_url(HTMEGA_ADDONS_PL_URL.'admin/assets/images/icon/copy.png'); ?>" alt="<?php echo esc_attr__( 'copy', 'htmega-addons' ); ?>"/>
                        <?php echo esc_html__( 'COPY', 'htmega-addons' ); ?>
                    </span>
                    <span  class="htoption-coupon-copied-content">
                        <img src="<?php echo esc_url(HTMEGA_ADDONS_PL_URL.'admin/assets/images/icon/copied.png'); ?>" alt="<?php echo esc_attr__( 'copied', 'htmega-addons' ); ?>"/>
                        <?php echo esc_html__( 'COPIED', 'htmega-addons' ); ?>
                    </span>
                </button>
            </div>
        </div>

        <div class="htoption-action-btn">
            <a class="htoption-btn" href="<?php echo esc_url( 'https://wphtmega.com/pricing/' ); ?>" target="_blank">
            <span>
               <?php echo esc_html__( 'Upgrade Now', 'htmega-addons' ); ?>
            </span>
            </a>
        </div> 
    </div> -->

    <div class="htoption-rating-area">
        <div class="htoption-rating-icon">
            <img src="<?php echo esc_url(HTMEGA_ADDONS_PL_URL.'admin/assets/images/icon/rating.png'); ?>" alt="<?php echo esc_attr__( 'Rating icon', 'htmega-addons' ); ?>">
        </div>
        <div class="htoption-rating-intro">
            <?php echo esc_html__('If you’re loving how our product has helped your business, please let the WordPress community know by','htmega-addons'); ?> <a target="_blank" href="https://wordpress.org/support/plugin/ht-mega-for-elementor/reviews/?filter=5#new-post"><?php echo esc_html__( 'leaving us a review on our WP repository', 'htmega-addons' ); ?></a>. <?php echo esc_html__( 'Which will motivate us a lot.', 'htmega-addons' ); ?>
        </div>
    </div>

</div>
<?php echo apply_filters('htmega_sidebar_adds_banner', ob_get_clean() ); ?>