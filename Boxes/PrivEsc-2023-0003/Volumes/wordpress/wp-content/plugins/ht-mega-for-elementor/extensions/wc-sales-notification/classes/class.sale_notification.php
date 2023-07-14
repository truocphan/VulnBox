<?php
/**
* Class Sale Notification
*/
class HTMegaWC_Sales_Notification{

    private static $_instance = null;
    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    function __construct(){

        add_action('wp_head',[ $this, 'ajaxurl' ] );

        // ajax function
        add_action('wp_ajax_nopriv_wcsales_purchased_products', [ $this, 'purchased_products' ] );
        add_action('wp_ajax_wcsales_purchased_products', [ $this, 'purchased_products' ] );

        add_action( 'wp_footer', [ $this, 'ajax_request' ] );
        
    }

    public function purchased_products(){

        $cachekey = 'purchased-new-products';
        $products = get_transient( $cachekey );

        if ( ! $products ) {
            $args = array(
                'post_type' => 'shop_order',
                'post_status' => 'wc-completed, wc-pending, wc-processing, wc-on-hold',
                'orderby' => 'ID',
                'order' => 'DESC',
                'posts_per_page' => htmega_get_option( 'notification_limit','htmegawcsales_setting_tabs','5' ),
                'date_query' => array(
                    'after' => date('Y-m-d', strtotime('-'.'7'.' days'))
                )
            );
            $posts = get_posts( $args );

            $products = array();
            $check_wc_version = version_compare( WC()->version, '3.0', '<') ? true : false;

            foreach( $posts as $post ) {

                $order = new WC_Order( $post->ID );
                $order_items = $order->get_items();

                if( !empty( $order_items ) ) {
                    $first_item = array_values( $order_items )[0];
                    $product_id = $first_item['product_id'];
                    $product = wc_get_product( $product_id );

                    if( !empty( $product ) ){
                        preg_match( '/src="(.*?)"/', $product->get_image( 'thumbnail' ), $imgurl );
                        $p = array(
                            'id'    => $first_item['order_id'],
                            'name'  => $product->get_title(),
                            'url'   => $product->get_permalink(),
                            'date'  => $post->post_date_gmt,
                            'image' => count($imgurl) === 2 ? $imgurl[1] : null,
                            'price' => $this->purchased_productprice( $check_wc_version ? $product->get_display_price() : wc_get_price_to_display( $product ) ),
                            'buyer' => $this->purchased_buyer_info( $order )
                        );
                        $p = apply_filters( 'wcsales_product_data', $p );
                        array_push( $products, $p);
                    }
                }

            }
            set_transient( $cachekey, $products, 60 ); // Cache the results for 1 minute
        }
        echo( json_encode( $products ) );
        wp_die();

    }

    // Product Price
    private function purchased_productprice($price) {
        if( empty( $price ) ){
            $price = 0;
        }
        return sprintf(
            get_woocommerce_price_format(),
            get_woocommerce_currency_symbol(),
            number_format( $price, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator() )
        );  
    }

    // Buyer Info
    private function purchased_buyer_info( $order ){
        $address = $order->get_address( 'billing' );
        if( !isset( $address['city'] ) || empty( $address['city'] ) ){
            $address = $order->get_address( 'shipping' );
        }
        $buyerinfo = array(
            'fname' => isset( $address['first_name'] ) && !empty( $address['first_name'] ) ? ucfirst( $address['first_name'] ) : '',
            'lname' => isset( $address['last_name'] ) && !empty( $address['last_name'] ) ? ucfirst( $address['last_name'] ) : '',
            'city' => isset( $address['city'] ) && !empty( $address['city'] ) ? ucfirst( $address['city'] ) : '',
            'state' => isset( $address['state'] ) && !empty( $address['state'] ) ? ucfirst( $address['state'] ) : '',
            'country' =>  isset( $address['country'] ) && !empty( $address['country'] ) ? WC()->countries->countries[$address['country']] : '',
        );
        return $buyerinfo;
    }

    // Ajax URL Create
    function ajaxurl() {
        ?>
            <script type="text/javascript">
                var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
            </script>
        <?php
    }

    // Ajax request
    function ajax_request() {

        $duration       = (int)htmega_get_option( 'notification_loadduration','htmegawcsales_setting_tabs', '3' )*1000;
        $notposition    = 'bottomleft';
        $notlayout      = 'imageleft';

        //Set Your Nonce
        $ajax_nonce = wp_create_nonce( "wcsales-ajax-request" );
        ?>
            <script>
                jQuery( document ).ready( function( $ ) {

                    var notposition = '<?php echo $notposition; ?>',
                        notlayout = ' '+'<?php echo $notlayout; ?>';

                    $('body').append('<div class="wcsales-sale-notification"><div class="wcsales-notification-content '+notposition+notlayout+'"></div></div>');

                    var data = {
                        action: 'wcsales_purchased_products',
                        security: '<?php echo $ajax_nonce; ?>',
                        whatever: 1234
                    };
                    var intervaltime = 4000,
                        i = 0,
                        duration = <?php echo $duration; ?>,
                        inanimation = 'fadeInLeft',
                        outanimation = 'fadeOutRight';

                    window.setTimeout( function(){
                        $.post(
                            ajaxurl, 
                            data,
                            function( response ){
                                var wcpobj = $.parseJSON( response );
                                if( wcpobj.length > 0 ){
                                    setInterval(function() {
                                        if( i == wcpobj.length ){ i = 0; }
                                        $('.wcsales-notification-content').html('');
                                        $('.wcsales-notification-content').css('padding','15px');
                                        var ordercontent = `<div class="wcnotification_image"><img src="${wcpobj[i].image}" alt="${wcpobj[i].name}" /></div>
                                            <div class="wcnotification_content">
                                                <h4><a href="${wcpobj[i].url}">${wcpobj[i].name}</a></h4>
                                                <p>${wcpobj[i].buyer.city + ' ' + wcpobj[i].buyer.state + ', ' + wcpobj[i].buyer.country }.</p>
                                                <h6>Price : ${wcpobj[i].price}</h6>
                                                <span class="wcsales-buyername">By ${wcpobj[i].buyer.fname + ' ' + wcpobj[i].buyer.lname}</span>
                                            </div>
                                            <span class="wccross">&times;</span>`;
                                        $('.wcsales-notification-content').append( ordercontent ).addClass('animated '+inanimation).removeClass(outanimation);
                                        setTimeout(function() {
                                            $('.wcsales-notification-content').removeClass(inanimation).addClass(outanimation);
                                        }, intervaltime-500 );
                                        i++;
                                    }, intervaltime );
                                }
                            }
                        );
                    }, duration );

                    // Close Button
                    $('.wcsales-notification-content').on('click', '.wccross', function(e){
                        e.preventDefault()
                        $(this).closest('.wcsales-notification-content').removeClass(inanimation).addClass(outanimation);
                    });

                });
            </script>
        <?php 
    }



}

HTMegaWC_Sales_Notification::instance();