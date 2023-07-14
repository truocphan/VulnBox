<?php
$subcribeFormAtts = '';
$subcribeFormAtts .= ' data-htmega-button-text="' . esc_attr__( 'Subscribe', 'htmega-addons' ) . '"';
$subcribeFormAtts .= ' data-htmega-processing-text="'. esc_attr__( 'Subscribing...', 'htmega-addons' ) . '"';
$subcribeFormAtts .= ' data-htmega-completed-text="' . esc_attr__( 'Subscribed', 'htmega-addons' ) . '"';
$subcribeFormAtts .= ' data-htmega-ajax-error-text="' . esc_attr__( 'Something went wrong.', 'htmega-addons' ) . '"';

ob_start();
?>
<div class="htmega-general-tabs">
<div class="htmega-admin-main-tab-pane-inner">
        <!-- Banner Start -->
        <div class="htmega-admin-banner">
            <img src="<?php echo HTMEGAOPT_URL; ?>/assets/images/dashboard-welcome.png" alt="<?php echo esc_attr__('Welcome To HT Mega','htmega-addons');?>">
        </div>
        <!-- Banner End -->

        <!-- Infoboxes Start -->
        <div class="htmega-admin-infoboxes">

            <!-- Infobox Start -->
            <div class="htmega-admin-infobox">
                <div class="htmega-admin-infobox-icon"><img src="<?php echo HTMEGAOPT_URL; ?>/assets/images/info-icon/documentation.png" alt="<?php echo esc_attr__('documentation','htmega-addons');?>"></div>
                <div class="htmega-admin-infobox-content">
                    <h3 class="htmega-admin-infobox-title"><?php echo esc_html__('Documentation','htmega-addons'); ?></h3>
                    <p class="htmega-admin-infobox-text"><?php echo esc_html__('We\'ve organized the documentation and kept it up to date on a regular basis. This manual will assist you in using our plugin effectively.','htmega-addons');?></p>
                    <a href="https://wphtmega.com/docs/" class="htmega-admin-btn htmega-admin-btn-primary-outline" target="_blank"><span><?php echo esc_html__('Get Now','htmega-addons'); ?></span></a>
                </div>
            </div>
            <!-- Infobox End -->

            <!-- Infobox Start -->
            <div class="htmega-admin-infobox">
                <div class="htmega-admin-infobox-icon"><img src="<?php echo HTMEGAOPT_URL; ?>/assets/images/info-icon/video-tutorial.png" alt="<?php echo esc_attr__('video tutorial','htmega-addons');?>"></div>
                <div class="htmega-admin-infobox-content">
                    <h3 class="htmega-admin-infobox-title"><?php echo esc_html__('Video Tutorial','htmega-addons'); ?></h3>
                    <p class="htmega-admin-infobox-text"><?php echo esc_html__('We create videos to make our customers comprehend the product quickly. Using video tutorials is a fantastic method to learn how to use our plugins. We\'ve compiled a list of videos for you.','htmega-addons'); ?></p>
                    <a href="https://www.youtube.com/watch?v=d7jAiAYusUg&list=PLk25BQFrj7wEEGUHn9x2zwOql990bZAo_&index=1" class="htmega-admin-btn htmega-admin-btn-primary-outline" target="_blank"><span><?php echo esc_html__('Video Tutorial','htmega-addons'); ?></span></a>
                </div>
            </div>
            <!-- Infobox End -->

            <!-- Infobox Start -->
            <div class="htmega-admin-infobox">
                <div class="htmega-admin-infobox-icon"><img src="<?php echo HTMEGAOPT_URL; ?>/assets/images/info-icon/support.png" alt="<?php echo esc_attr__('Support','htmega-addons');?>"></div>
                <div class="htmega-admin-infobox-content">
                    <h3 class="htmega-admin-infobox-title"><?php echo esc_html__('Support','htmega-addons'); ?></h3>
                    <p class="htmega-admin-infobox-text"><?php echo esc_html__('Please do not hesitate to contact us if you require assistance or want a free store set-up. We will assist you within 12-24 hours of receiving your inquiry.','htmega-addons');?></p>
                    <a href="https://wphtmega.com/contact/" class="htmega-admin-btn htmega-admin-btn-primary-outline" target="_blank"><span><?php echo esc_html__('Get Support','htmega-addons'); ?></span></a>
                </div>
            </div>
            <!-- Infobox End -->

            <!-- Infobox Start -->
            <div class="htmega-admin-infobox">
                <div class="htmega-admin-infobox-icon"><img src="<?php echo HTMEGAOPT_URL; ?>/assets/images/info-icon/missing-feature.png" alt="<?php echo esc_attr__('missing feature','htmega-addons');?>"></div>
                <div class="htmega-admin-infobox-content">
                    <h3 class="htmega-admin-infobox-title"><?php echo esc_html__('Missing any Feature?','htmega-addons'); ?></h3>
                    <p class="htmega-admin-infobox-text"><?php echo esc_html__('Have you ever noticed any missing features? Please notify us if you do. As soon as possible, our staff will add any necessary features based on your requests. Our commitment to our clients is second to none. We always attempt to fulfill their demands.','htmega-addons'); ?></p>
                    <a href="https://wphtmega.com/contact/" class="htmega-admin-btn htmega-admin-btn-primary-outline" target="_blank"><span><?php echo esc_html__('Request','htmega-addons'); ?></span></a>
                </div>
            </div>
            <!-- Infobox End -->
            <!-- Infobox Start -->
            <div class="htmega-admin-infobox">
                <div class="htmega-admin-infobox-icon"><img src="<?php echo HTMEGAOPT_URL; ?>/assets/images/info-icon/happy-with-us.png" alt="<?php echo esc_attr__('happy with us','htmega-addons');?>"></div>
                <div class="htmega-admin-infobox-content">
                    <h3 class="htmega-admin-infobox-title"><?php echo esc_html__('Happy With us?','htmega-addons'); ?></h3>
                    <p class="htmega-admin-infobox-text"><?php echo esc_html__('If you’re loving how our product has helped your business, please let the WordPress community know by leaving us a review on our WP repository. Which will motivate us a lot.','htmega-addons'); ?></p>
                    <a href="https://wordpress.org/support/plugin/ht-mega-for-elementor/reviews/?filter=5#new-post" class="htmega-admin-btn htmega-admin-btn-primary-outline" target="_blank"><span><?php echo esc_html__('Sent Feedback','htmega-addons'); ?></span></a>
                </div>
            </div>
            <!-- Infobox End -->
            <!-- Infobox Start -->
            <div class="htmega-admin-infobox">
                <div class="htmega-admin-infobox-icon"><img src="<?php echo HTMEGAOPT_URL; ?>/assets/images/info-icon/woolentor.png" alt="<?php echo esc_attr__('woolentor','htmega-addons');?>"></div>
                <div class="htmega-admin-infobox-content">
                    <h3 class="htmega-admin-infobox-title"><?php echo esc_html__('ShopLentor','htmega-addons'); ?></h3>
                    <p class="htmega-admin-infobox-text"><?php echo esc_html__('Take your WooCommerce store to another level using ShopLentor. Creating an exquisite yet professional online store is just a matter of a few clicks with this plugin.','htmega-addons'); ?></p>
                    <a href="https://woolentor.com/" class="htmega-admin-btn htmega-admin-btn-primary-outline" target="_blank"><span><?php echo esc_html__('Learn More','htmega-addons'); ?></span></a>
                </div>
            </div>
            <!-- Infobox End -->

        </div>
        <!-- Infoboxes End -->

        <!-- Subscribe Banner Start -->
        <div class="htmega-admin-subscribe">
            <div class="htmega-admin-subscribe-content">
                <h3 class="htmega-admin-subscribe-title"><?php echo esc_html__('Subscribe and Get Offers','htmega-addons'); ?></h3>
                <p class="htmega-admin-subscribe-text"><?php echo esc_html__('Sign up for our email list to get discounts, exclusive offers, the latest items, and news in your inbox.','htmega-addons');?></p>
            </div>
            <div class="htmega-admin-subscribe-wrapper">
                <form action="#" class="htmega-admin-subscribe-form" <?php echo wp_kses_post( trim( $subcribeFormAtts ) ); ?>>
                    <input type="email" value="<?php echo get_bloginfo('admin_email'); ?>">
                    <button type="submit"><?php esc_html_e( 'Subscribe', 'htmega-addons' ) ?></button>
                </form>
                <span class="htmega-subscribe-status"></span>
            </div>
            <!-- <a href="https://hasthemes.com/subscribe-and-get-offers/" class="htmega-admin-btn htmega-admin-btn-primary" target="_blank"><?php echo esc_html__('Subscribe','htmega-addons'); ?></a> -->
        </div>
        <!-- Subscribe Banner End -->
    </div>
</div>
<?php echo apply_filters('htmega_dashboard_general', ob_get_clean() ); ?>