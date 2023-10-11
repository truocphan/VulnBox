<?php
/**
 * Welcart Recent Posts Widget
 *
 * @package Welcart
 */

/**
 * Welcart_Recent_Posts Class
 *
 * @see WP_Widget
 */
class Welcart_Recent_Posts extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'usces_recent_entries',
			'description' => ( __( 'Your site&#8217;s most recent posts.', 'usces' ) . __( 'Non-item', 'usces' ) ),
		);
		parent::__construct( 'usces-recent-posts', 'Welcart ' . __( 'Recent Posts', 'usces' ), $widget_ops );
		$this->alt_option_name = 'usces_recent_entries';

		add_action( 'save_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( &$this, 'flush_widget_cache' ) );
	}

	/**
	 * Echoes the widget content.
	 *
	 * @see WP_Widget::widget
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {
		$cache = wp_cache_get( 'usces_recent_posts', 'widget' );

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			wel_esc_script_e( $cache[ $args['widget_id'] ] );
			return;
		}

		usces_remove_filter();

		ob_start();
		extract( $args );
		$title  = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Recent Posts', 'usces' ) : $instance['title'], $instance, $this->id_base );
		$number = ( isset( $instance['number'] ) ) ? (int) $instance['number'] : 10;
		if ( ! $number ) {
			$number = 10;
		} elseif ( $number < 1 ) {
			$number = 1;
		} elseif ( $number > 15 ) {
			$number = 15;
		}

		$r = new WP_Query(
			array(
				'showposts'           => $number,
				'nopaging'            => 0,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'cat'                 => -( USCES_ITEM_CAT_PARENT_ID ),
				'order'               => 'DESC',
				'orderby'             => 'date',
			)
		);
		if ( $r->have_posts() ) :

			wel_esc_script_e( $before_widget );

			if ( $title ) {
				wel_esc_script_e( $before_title . $title . $after_title );
			}
			?>
		<ul>
			<?php
			while ( $r->have_posts() ) :
				$r->the_post();
				?>
			<li><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( get_the_title() ? get_the_title() : get_the_ID() ); ?>">
				<?php
				if ( get_the_title() ) {
					the_title();
				} else {
					the_ID();
				}
				?>
			</a></li>
			<?php endwhile; ?>
		</ul>
			<?php
			wel_esc_script_e( $after_widget );

			// Reset the global $the_post as this query will have stomped on it.
			wp_reset_postdata();

		endif;
		$cache[ $args['widget_id'] ] = ob_get_flush();
		wp_cache_set( 'usces_recent_posts', $cache, 'widget' );
	}

	/**
	 * Updates a particular instance of a widget.
	 *
	 * @see WP_Widget::update
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance           = $old_instance;
		$instance['title']  = strip_tags( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['usces_recent_entries'] ) ) {
			delete_option( 'usces_recent_entries' );
		}

		return $instance;
	}

	/**
	 * Flushes the Recent Comments widget cache.
	 */
	public function flush_widget_cache() {
		wp_cache_delete( 'usces_recent_posts', 'widget' );
	}

	/**
	 * Outputs the settings update form.
	 *
	 * @see WP_Widget::form
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		if ( ! isset( $instance['number'] ) || ! $number = (int) $instance['number'] ) {
			$number = 5;
		}
		?>
		<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'usces' ); ?></label>
		<input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'title' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php wel_esc_script_e( $title ); ?>" /></p>

		<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'usces' ); ?></label>
		<input id="<?php wel_esc_script_e( $this->get_field_id( 'number' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php wel_esc_script_e( $number ); ?>" size="3" /></p>
		<?php
	}
}
