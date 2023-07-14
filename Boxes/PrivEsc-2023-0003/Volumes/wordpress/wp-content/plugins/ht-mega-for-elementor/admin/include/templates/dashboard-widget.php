<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<style>
    .hastheme-header-title {
        width: 100%;
        -webkit-box-shadow: 0 5px 8px rgba(0,0,0,.05);
        box-shadow: 0 5px 8px rgba(0,0,0,.05);
        margin: 0 -12px 8px;
        padding: 0 12px 12px;
        text-decoration: none;
        color: #23282d;
        font-size: 14px;
    }
    .hastheme-header-title a {
        text-decoration: none;
        color: #23282d;
        font-size: 14px;
    }
    .hastheme-dashboard-widget-header img {
        width: 100%;
    }
    .hastheme-dashboard-widget-newsfeed ul li{
        margin: 10px 0;
    }
    .hastheme-dashboard-widget-newsfeed ul li .hastheme-dashboard-widget-newsfeed-item-title a{
        font-size: 14px;
        margin-bottom: 3px;
        display: inline-block;
    }
    .hastheme-dashboard-widget-newsfeed-item-description {
        margin: 0 0 1.2em;
    }
    .hastheme-dashboard-widget-footer {
        border-top: 1px solid #eee;
        margin: 0 -12px;
        padding: 12px 6px 0 12px;
    }
    .hastheme-dashboard-widget-footer ul {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .hastheme-dashboard-widget-footer ul li {
        padding: 0 10px;
        margin: 0;
        border-left: 1px solid #ddd;
    }
    .hastheme-dashboard-widget-footer ul li:first-child {
        padding-left: 0;
        border: none;
    }
    .hastheme-dashboard-widget-footer ul li a{
        text-decoration: none;
    }
</style>

<?php $info_data = HTMega_Api::get_remote_data(); ?>
<div class="hastheme-dashboard-widget-area">
    <div class="hastheme-dashboard-widget-header">
        <?php
            if ( ! empty( $info_data['banner'] ) ){
                echo wp_kses_post( $info_data['banner'] );
            }
        ?>
    </div>
    <?php if ( ! empty( $info_data['feed'] ) ) : ?>
        <div class="hastheme-dashboard-widget-newsfeed">
            <ul>
                <?php foreach ( $info_data['feed'] as $feed ) : if( $feed['status'] == '0' ) continue; ?>
                    <li class="hastheme-dashboard-widget-newsfeed-item">
                        <div class="hastheme-dashboard-widget-newsfeed-item-title">
                            <a target="_blank" href="<?php echo esc_url( $feed['url'] ); ?>"><?php echo esc_html( $feed['title'] ); ?></a>
                        </div>
                        <div class="hastheme-dashboard-widget-newsfeed-item-description">
                            <?php echo wp_kses_post( $feed['description'] ); ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <div class="hastheme-dashboard-widget-footer">
        <ul>
            <li>
                <a href="https://hasthemes.com/blog/" target="_blank">
                    <?php esc_html_e( 'Blog', 'htmega-addons' ); ?>
                    <span aria-hidden="true" class="dashicons dashicons-external"></span>
                </a>
            </li>
            <li>
                <a href="https://wphtmega.com/docs/" target="_blank">
                    <?php esc_html_e( 'Documentation', 'htmega-addons' ); ?>
                    <span aria-hidden="true" class="dashicons dashicons-external"></span>
                </a>
            </li>
            <li>
                <a href="https://wphtmega.com/contact/" target="_blank">
                    <?php esc_html_e( 'Support', 'htmega-addons' ); ?>
                    <span aria-hidden="true" class="dashicons dashicons-external"></span>
                </a>
            </li>
        </ul>
    </div>
</div>