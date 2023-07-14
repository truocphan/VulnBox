<?php
if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

class HTMega_Post_Dupicator{

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [HTMega_Post_Dupicator]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * [__construct description]
     */
    public function __construct(){
        add_filter( 'admin_action_htmega_duplicate_post_as_draft', [ $this, 'duplicate' ] );
        add_filter( 'post_row_actions', [ $this, 'row_actions' ], 10, 2 );
        add_filter( 'page_row_actions', [ $this, 'row_actions' ], 10, 2 );
    }

    /**
     * [row_actions]
     * @param  $actions 
     * @param  [string] $post Current Post
     * @return [array]   Row Action List
     */
    public function row_actions( $actions, $post ){
        
        $enable = htmega_get_option( 'postduplicate_condition', 'htmega_general_tabs', array('all') );

        if ( current_user_can('edit_posts') && ( in_array( $post->post_type, $enable ) || in_array('all', $enable) ) ) {
            $actionurl = admin_url('admin.php?action=htmega_duplicate_post_as_draft&post=' . $post->ID );
            $url = wp_nonce_url( $actionurl, 'htmega_duplicate_nonce' );
            $actions['htmegaduplicate'] = '<a href="'.$url.'" title="'.esc_attr__( 'HT Mega Duplicator', 'htmega-addons' ).'" rel="permalink">'.esc_html__( 'HT Duplicate', 'htmega-addons' ).'</a>';

        }
        return $actions;

    }

    /**
     * [duplicate]
     * @return [ERROR | Rediresct To Edit URL] 
     */
    public function duplicate(){
        global $wpdb;

        if (! ( isset( $_REQUEST['post']) || isset( $_REQUEST['post'] )  || ( isset( $_REQUEST['action'] ) && 'htmega_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
            wp_die( 'No post to duplicate has been supplied!' );
        }

        /*
         * Nonce verification
         */
        if( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'htmega_duplicate_nonce' ) ) {
            return; 
        }

        /*
         * get the original post id
         */
        $post_id = ( isset( $_REQUEST['post'] ) ? absint( $_REQUEST['post'] ) : Null );
        /*
         * and all the original post data then
         */
        $post = sanitize_post( get_post( $post_id ), 'db' );
     
        /*
         * if you don't want current user to be the new post author,
         * then change next couple of lines to this: $new_post_author = $post->post_author;
         */
        $current_user = wp_get_current_user();
        $new_post_author = $current_user->ID;

        /*
         * if post data exists, create the post duplicate
         */
        if ( isset( $post ) && $post != null) {

            /*
             * new post data array
             */
            $args = array(
                'comment_status' => $post->comment_status,
                'ping_status'    => $post->ping_status,
                'post_author'    => $new_post_author,
                'post_content'   => $post->post_content,
                'post_excerpt'   => $post->post_excerpt,
                'post_name'      => $post->post_name,
                'post_parent'    => $post->post_parent,
                'post_password'  => $post->post_password,
                'post_status'    => 'draft',
                'post_title'     => $post->post_title,
                'post_type'      => $post->post_type,
                'to_ping'        => $post->to_ping,
                'menu_order'     => $post->menu_order
            );

            /*
             * insert the post by wp_insert_post() function
             */
            $new_post_id = wp_insert_post( $args );

            if( ! is_wp_error( $new_post_id ) ) {
                /*
                 * get all current post terms ad set them to the new post draft
                 */
                $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
                if( ! empty( $taxonomies ) && is_array( $taxonomies ) ) {
                    foreach ($taxonomies as $taxonomy) {
                        $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
                        wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
                    }
                }

                /*
                 * duplicate all post meta just in two SQL queries
                 */
                $post_meta_infos = $wpdb->get_results(
                    $wpdb->prepare("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%d",$post_id)
                );
                if ( is_array( $post_meta_infos ) && count( $post_meta_infos ) !=0) {

                    $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) VALUES ";

                    foreach ($post_meta_infos as $meta_info) {

                        $meta_key = esc_sql($meta_info->meta_key);
                        if( $meta_key == '_wp_old_slug' ) continue;
                        $meta_value =  $meta_info->meta_value;
                        $sql_query_val[]= "( %d, %s, %s )";
                        $sql_query_sel[]= $new_post_id;
                        $sql_query_sel[]= $meta_key;
                        $sql_query_sel[]= $meta_value;

                    }
                    $sql_query.= implode(",", $sql_query_val). ';';
                    $wpdb->query( $wpdb->prepare( $sql_query, $sql_query_sel ) );
                }
            }
            $redirect_to = admin_url( 'post.php?action=edit&post=' . $new_post_id );
            wp_safe_redirect( $redirect_to );

        }else {
            wp_die('Post creation failed, could not find original post: ' . $post_id);
        }


    }

}

HTMega_Post_Dupicator::instance();