<?php
/**
 * Welcart Blog Calendar Widget
 *
 * @package Welcart
 */

/**
 * Blog Calendar
 *
 * @param bool $initial Initial or not.
 * @param bool $echo Return value or echo.
 * @return string|void
 */
function get_wcblog_calendar( $initial = true, $echo = true ) {
	global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;

	$cache = array();
	$key   = md5( $m . $monthnum . $year );

	if ( ! is_array( $cache ) ) {
		$cache = array();
	}

	// Quick check. If we have no posts at all, abort!.
	if ( ! $posts ) {
		$gotsome = $wpdb->get_var( "SELECT 1 as test FROM $wpdb->posts WHERE post_type = 'post' AND post_mime_type <> 'item' AND post_status = 'publish' LIMIT 1" );
		if ( ! $gotsome ) {
			$cache[ $key ] = '';
			wp_cache_set( 'get_calendar', $cache, 'calendar' );
			return;
		}
	}

	if ( isset( $_GET['w'] ) ) {
		$w = '' . intval( $_GET['w'] );
	}

	// week_begins = 0 stands for Sunday.
	$week_begins = intval( get_option( 'start_of_week' ) );

	// Let's figure out when we are.
	if ( ! empty( $monthnum ) && ! empty( $year ) ) {
		$thismonth = '' . zeroise( intval( $monthnum ), 2 );
		$thisyear  = '' . intval( $year );
	} elseif ( ! empty( $w ) ) {
		// We need to get the month from MySQL.
		$thisyear  = '' . intval( substr( $m, 0, 4 ) );
		$d         = ( ( (int) $w - 1 ) * 7 ) + 6; // it seems MySQL's weeks disagree with PHP's.
		$thismonth = $wpdb->get_var( "SELECT DATE_FORMAT((DATE_ADD('${thisyear}0101', INTERVAL $d DAY) ), '%m')" );
	} elseif ( ! empty( $m ) ) {
		$thisyear = '' . intval( substr( $m, 0, 4 ) );
		if ( strlen( $m ) < 6 ) {
			$thismonth = '01';
		} else {
			$thismonth = '' . zeroise( intval( substr( $m, 4, 2 ) ), 2 );
		}
	} else {
		$thisyear  = wp_date( 'Y' );
		$thismonth = wp_date( 'm' );
	}

	$unixmonth = mktime( 0, 0, 0, $thismonth, 1, $thisyear );

	// Get the next and previous month and year with at least one post.
	$previous = $wpdb->get_row(
		"SELECT DISTINCT MONTH(post_date) AS `month`, YEAR(post_date) AS `year`
		FROM $wpdb->posts
		WHERE post_date < '$thisyear-$thismonth-01'
		AND post_mime_type <> 'item' 
		AND post_type = 'post' AND post_status = 'publish'
			ORDER BY post_date DESC
			LIMIT 1"
	);
	$next     = $wpdb->get_row(
		"SELECT DISTINCT MONTH(post_date) AS `month`, YEAR(post_date) AS `year`
		FROM $wpdb->posts
		WHERE post_date > '$thisyear-$thismonth-01'
		AND post_mime_type <> 'item' 
		AND MONTH( post_date ) != MONTH( '$thisyear-$thismonth-01' )
		AND post_type = 'post' AND post_status = 'publish'
			ORDER BY post_date ASC
			LIMIT 1"
	);

	/* translators: Calendar caption: 1: month name, 2: 4-digit year */
	$calendar_caption = _x( '%1$s %2$s', 'calendar caption' );
	$calendar_output  = '<table id="wp-calendar" summary="' . esc_attr__( 'Calendar', 'usces' ) . '">
	<caption>' . sprintf( $calendar_caption, $wp_locale->get_month( $thismonth ), date( 'Y', $unixmonth ) ) . '</caption>
	<thead>
	<tr>';

	$myweek = array();

	for ( $wdcount = 0; $wdcount <= 6; $wdcount++ ) {
		$myweek[] = $wp_locale->get_weekday( ( $wdcount + $week_begins ) % 7 );
	}

	foreach ( $myweek as $wd ) {
		$day_name         = ( $initial ) ? $wp_locale->get_weekday_initial( $wd ) : $wp_locale->get_weekday_abbrev( $wd );
		$wd               = esc_attr( $wd );
		$calendar_output .= "<th scope=\"col\" title=\"$wd\">$day_name</th>";
	}

	$calendar_output .= '
	</tr>
	</thead>

	<tfoot>
	<tr>';

	if ( $previous ) {
		$calendar_output .= '<td colspan="3" id="prev"><a href="' . get_month_link( $previous->year, $previous->month ) . '" title="' . sprintf( __( 'View posts for %1$s %2$s', 'usces' ), $wp_locale->get_month( $previous->month ), date( 'Y', mktime( 0, 0, 0, $previous->month, 1, $previous->year ) ) ) . '">&laquo; ' . $wp_locale->get_month_abbrev( $wp_locale->get_month( $previous->month ) ) . '</a></td>';
	} else {
		$calendar_output .= '<td colspan="3" id="prev" class="pad">&nbsp;</td>';
	}

	$calendar_output .= '<td class="pad">&nbsp;</td>';

	if ( $next ) {
		$calendar_output .= '<td colspan="3" id="next"><a href="' . get_month_link( $next->year, $next->month ) . '" title="' . esc_attr( sprintf( __( 'View posts for %1$s %2$s', 'usces' ), $wp_locale->get_month( $next->month ), date( 'Y', mktime( 0, 0, 0, $next->month, 1, $next->year ) ) ) ) . '">' . $wp_locale->get_month_abbrev( $wp_locale->get_month( $next->month ) ) . ' &raquo;</a></td>';
	} else {
		$calendar_output .= '<td colspan="3" id="next" class="pad">&nbsp;</td>';
	}

	$calendar_output .= '
	</tr>
	</tfoot>

	<tbody>
	<tr>';

	// Get days with posts.
	$dayswithposts = $wpdb->get_results(
		"SELECT DISTINCT DAYOFMONTH(post_date)
		FROM $wpdb->posts WHERE MONTH(post_date) = '$thismonth'
		AND post_mime_type <> 'item' 
		AND YEAR(post_date) = '$thisyear'
		AND post_type = 'post' AND post_status = 'publish'
		AND post_date < '" . current_time( 'mysql' ) . '\'',
		ARRAY_N
	);
	if ( $dayswithposts ) {
		foreach ( (array) $dayswithposts as $daywith ) {
			$daywithpost[] = $daywith[0];
		}
	} else {
		$daywithpost = array();
	}

	if ( false !== strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE' ) || false !== stripos( $_SERVER['HTTP_USER_AGENT'], 'camino' ) || false !== stripos( $_SERVER['HTTP_USER_AGENT'], 'safari' ) ) {
		$ak_title_separator = "\n";
	} else {
		$ak_title_separator = ', ';
	}

	$ak_titles_for_day = array();
	$ak_post_titles    = $wpdb->get_results(
		"SELECT ID, post_title, DAYOFMONTH(post_date) as dom 
		FROM $wpdb->posts 
		WHERE YEAR(post_date) = '$thisyear' 
		AND MONTH(post_date) = '$thismonth' 
		AND post_mime_type <> 'item' 
		AND post_date < '" . current_time( 'mysql' ) . "' 
		AND post_type = 'post' AND post_status = 'publish'"
	);
	if ( $ak_post_titles ) {
		foreach ( (array) $ak_post_titles as $ak_post_title ) {

			$post_title = esc_attr( apply_filters( 'the_title', $ak_post_title->post_title, $ak_post_title->ID ) );

			if ( empty( $ak_titles_for_day[ 'day_' . $ak_post_title->dom ] ) ) {
				$ak_titles_for_day[ 'day_' . $ak_post_title->dom ] = '';
			}
			if ( empty( $ak_titles_for_day[ "$ak_post_title->dom" ] ) ) { // first one.
				$ak_titles_for_day[ "$ak_post_title->dom" ] = $post_title;
			} else {
				$ak_titles_for_day[ "$ak_post_title->dom" ] .= $ak_title_separator . $post_title;
			}
		}
	}

	// See how much we should pad in the beginning.
	$pad = calendar_week_mod( date( 'w', $unixmonth ) - $week_begins );
	if ( 0 !== (int) $pad ) {
		$calendar_output .= '<td colspan="' . esc_attr( $pad ) . '" class="pad">&nbsp;</td>';
	}
	$daysinmonth = intval( date( 't', $unixmonth ) );
	$todayd      = wp_date( 'j' );
	$todaym      = wp_date( 'm' );
	$todayy      = wp_date( 'Y' );
	for ( $day = 1; $day <= $daysinmonth; ++$day ) {
		if ( isset( $newrow ) && $newrow ) {
			$calendar_output .= '</tr><tr>';
		}
		$newrow = false;

		if ( $day === (int) $todayd && (int) $thismonth === (int) $todaym && (int) $thisyear === (int) $todayy ) {
			$calendar_output .= '<td id="today">';
		} else {
			$calendar_output .= '<td>';
		}

		if ( in_array( $day, $daywithpost ) ) { // any posts today?
			$calendar_output .= '<a href="' . get_day_link( $thisyear, $thismonth, $day ) . '" title="' . esc_attr( $ak_titles_for_day[ $day ] ) . "\">$day</a>";
		} else {
			$calendar_output .= $day;
		}
		$calendar_output .= '</td>';

		if ( 6 == calendar_week_mod( date( 'w', mktime( 0, 0, 0, $thismonth, $day, $thisyear ) ) - $week_begins ) ) {
			$newrow = true;
		}
	}

	$pad = 7 - calendar_week_mod( date( 'w', mktime( 0, 0, 0, $thismonth, $day, $thisyear ) ) - $week_begins );
	if ( 0 !== (int) $pad && 7 !== (int) $pad ) {
		$calendar_output .= '<td class="pad" colspan="' . esc_attr( $pad ) . '">&nbsp;</td>';
	}
	$calendar_output .= '</tr></tbody></table>';

	$cache[ $key ] = $calendar_output;
	wp_cache_set( 'get_calendar', $cache, 'calendar' );

	if ( $echo ) {
		echo apply_filters( 'get_calendar', $calendar_output );
	} else {
		return apply_filters( 'get_calendar', $calendar_output );
	}
}

/**
 * Welcart_Blog_Calendar Class
 *
 * @see WP_Widget
 */
class Welcart_Blog_Calendar extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'welcart_blog_calendar',
			'description' => __( 'A calendar of your site&#8217;s posts.', 'usces' ) . __( 'Non-item', 'usces' ),
		);
		parent::__construct( 'welcart-blog-calendar', 'Welcart ' . __( 'Blog Calendar', 'usces' ), $widget_ops );
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
		extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '&nbsp;' : $instance['title'], $instance, $this->id_base );
		wel_esc_script_e( $before_widget );
		if ( $title ) {
			wel_esc_script_e( $before_title . $title . $after_title );
		}
		echo '<div id="calendar_wrap">';
		get_wcblog_calendar();
		echo '</div>';
		wel_esc_script_e( $after_widget );
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
		$instance          = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}

	/**
	 * Outputs the settings update form.
	 *
	 * @see WP_Widget::form
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title    = strip_tags( $instance['title'] );
		?>
		<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'usces' ); ?></label>
		<input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'title' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
		<?php
	}
}
