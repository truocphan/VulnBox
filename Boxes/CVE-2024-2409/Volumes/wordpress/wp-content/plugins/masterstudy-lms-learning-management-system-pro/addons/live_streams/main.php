<?php

new STM_LMS_Live_Streams();

class STM_LMS_Live_Streams {

	public function __construct() {
		add_action( 'stm_lms_lesson_types', array( $this, 'add_lesson_type' ) );

		add_action( 'stm_lms_lesson_manage_settings', array( $this, 'lesson_manage_settings' ) );

		add_filter( 'stm_lms_course_item_content', array( $this, 'course_item_content' ), 10, 3 );

		add_filter( 'stm_wpcfto_fields', array( $this, 'add_lesson_type_admin' ), 100, 1 );

		add_filter( 'stm_lms_show_item_content', array( $this, 'show_item_content' ), 10, 3 );

		add_filter( 'stm_lms_navigation_complete_class', array( $this, 'navigation_complete_class' ), 10, 2 );

		add_filter( 'stm_lms_navigation_complete_atts', array( $this, 'navigation_complete_atts' ), 10, 2 );
	}

	public static function register_stream_post_type() {
		$labels = array(
			'name'               => _x( 'Live Streams', 'post type general name', 'masterstudy-lms-learning-management-system-pro' ),
			'singular_name'      => _x( 'Live Stream', 'post type singular name', 'masterstudy-lms-learning-management-system-pro' ),
			'menu_name'          => _x( 'Live Streams', 'admin menu', 'masterstudy-lms-learning-management-system-pro' ),
			'name_admin_bar'     => _x( 'Live Stream', 'add new on admin bar', 'masterstudy-lms-learning-management-system-pro' ),
			'add_new'            => _x( 'Add New', 'live_stream', 'masterstudy-lms-learning-management-system-pro' ),
			'add_new_item'       => __( 'Add New Live Stream', 'masterstudy-lms-learning-management-system-pro' ),
			'new_item'           => __( 'New Live Stream', 'masterstudy-lms-learning-management-system-pro' ),
			'edit_item'          => __( 'Edit Live Stream', 'masterstudy-lms-learning-management-system-pro' ),
			'view_item'          => __( 'View Live Stream', 'masterstudy-lms-learning-management-system-pro' ),
			'all_items'          => __( 'All Live Streams', 'masterstudy-lms-learning-management-system-pro' ),
			'search_items'       => __( 'Search Live Streams', 'masterstudy-lms-learning-management-system-pro' ),
			'parent_item_colon'  => __( 'Parent Live Streams:', 'masterstudy-lms-learning-management-system-pro' ),
			'not_found'          => __( 'No Live Streams found.', 'masterstudy-lms-learning-management-system-pro' ),
			'not_found_in_trash' => __( 'No Live Streams found in Trash.', 'masterstudy-lms-learning-management-system-pro' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'masterstudy-lms-learning-management-system-pro' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'live_stream' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'menu_icon'          => 'dashicons-video-alt3',
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
		);

		register_post_type( 'live_stream', $args );
	}

	public static function add_lesson_type() {
		if ( apply_filters( 'stm_lms_live_stream_allowed', true ) ) {
			?>
			<option value="stream"><?php esc_html_e( 'Stream', 'masterstudy-lms-learning-management-system-pro' ); ?></option>
			<?php
		}
	}

	public static function add_lesson_type_admin( $fields ) {

		$fields['stm_lesson_settings']['section_lesson_settings']['fields']['type']['options']['stream'] = esc_html__( 'Stream', 'masterstudy-lms-learning-management-system-pro' );

		$fields['stm_lesson_settings']['section_lesson_settings']['fields']['stream_start_date'] = array(
			'type'       => 'date',
			'label'      => esc_html__( 'Stream Start Date', 'masterstudy-lms-learning-management-system-pro' ),
			'value'      => '',
			'dependency' => array(
				'key'   => 'type',
				'value' => 'stream || zoom_conference',
			),
		);

		$fields['stm_lesson_settings']['section_lesson_settings']['fields']['stream_start_time'] = array(
			'type'       => 'time',
			'label'      => esc_html__( 'Stream Start Time', 'masterstudy-lms-learning-management-system-pro' ),
			'value'      => '',
			'dependency' => array(
				'key'   => 'type',
				'value' => 'stream || zoom_conference',
			),
		);

		$fields['stm_lesson_settings']['section_lesson_settings']['fields']['stream_end_date'] = array(
			'type'       => 'date',
			'label'      => esc_html__( 'Stream End Date', 'masterstudy-lms-learning-management-system-pro' ),
			'value'      => '',
			'dependency' => array(
				'key'   => 'type',
				'value' => 'stream',
			),
		);

		$fields['stm_lesson_settings']['section_lesson_settings']['fields']['stream_end_time'] = array(
			'type'       => 'time',
			'label'      => esc_html__( 'Stream End Time', 'masterstudy-lms-learning-management-system-pro' ),
			'value'      => '',
			'dependency' => array(
				'key'   => 'type',
				'value' => 'stream',
			),
		);

		return $fields;
	}

	public static function lesson_manage_settings() {
		?>
		<?php STM_LMS_Templates::show_lms_template( 'manage_course/forms/js/date' ); ?>

		<div v-if="fields['type'] === 'stream'">

			<div class="form-group">
				<label>
					<h4><?php esc_html_e( 'Stream start date', 'masterstudy-lms-learning-management-system-pro' ); ?></h4>
					<stm-date v-bind:current_date="fields['stream_start_date']" placeholder="" v-on:date-changed="dateChanged($event, 'stream_start_date');"></stm-date>
				</label>
			</div>

			<div class="form-group">
				<label>
					<h4><?php esc_html_e( 'Stream start time', 'masterstudy-lms-learning-management-system-pro' ); ?></h4>
					<input class="form-control" type="time" v-model="fields['stream_start_time']" /> </label>
			</div>

			<div class="form-group">
				<label>
					<h4><?php esc_html_e( 'Stream end date', 'masterstudy-lms-learning-management-system-pro' ); ?></h4>
					<stm-date v-bind:current_date="fields['stream_end_date']" placeholder="" v-on:date-changed="dateChanged($event, 'stream_end_date');"></stm-date>
				</label>
			</div>

			<div class="form-group">
				<label>
					<h4><?php esc_html_e( 'Stream end time', 'masterstudy-lms-learning-management-system-pro' ); ?></h4>
					<input class="form-control" type="time" v-model="fields['stream_end_time']" /> </label>
			</div>
		</div>
		<?php
	}

	public static function show_item_content( $show, $post_id, $item_id ) {
		if ( self::is_stream( $item_id ) ) {
			return false;
		}

		return $show;
	}

	public static function course_item_content( $content, $post_id, $item_id ) {
		if ( self::is_stream( $item_id ) ) {
			$template = stm_lms_get_course_player_template( 'course-player/stream', 'stream/main' );
			ob_start();
			STM_LMS_Templates::show_lms_template( $template, compact( 'post_id', 'item_id' ) );
			return ob_get_clean();}
		return $content;
	}

	public static function is_stream( $post_id ) {
		$type = get_post_meta( $post_id, 'type', true );

		return 'stream' === $type;
	}

	public static function get_video_url( $url ) {
		if ( empty( $url ) ) {
			return '';
		}

		if ( ! empty( $url ) ) {
			$url_parsed = wp_parse_url( $url );

			if ( empty( $url_parsed['host'] ) ) {
				return $url;
			}

			if ( 'www.youtube.com' !== $url_parsed['host'] ) {
				return $url;
			}

			if ( empty( $url_parsed['path'] ) ) {
				return $url;
			}

			if ( ! empty( $url_parsed['query'] ) ) {
				return str_replace( array( '/embed/', 'v=' ), array( '' ), $url_parsed['query'] ) . '&is_youtube';
			}

			return str_replace( array( '/embed/', 'v=' ), array( '' ), $url_parsed['path'] ) . '&is_youtube';
		}

		return $url;
	}

	public static function time_offset() {
		return get_option( 'gmt_offset' ) * 60 * 60;
	}

	public static function get_current_timezone() {
		$timezone_string = get_option( 'timezone_string' );
		if ( ! empty( $timezone_string ) ) {
			return $timezone_string;
		}

		$offset  = get_option( 'gmt_offset' );
		$hours   = (int) $offset;
		$minutes = abs( ( $offset - (int) $offset ) * 60 );
		$seconds = $hours * 60 * 60 + $minutes * 60;

		$timezone = timezone_name_from_abbr( '', $seconds, 1 );

		if ( false === $timezone ) {
			$timezone = timezone_name_from_abbr( '', $seconds, 0 );
		}

		return $timezone;
	}

	public static function stream_start_time( $item_id ) {
		$start_date = get_post_meta( $item_id, 'stream_start_date', true );
		$start_time = get_post_meta( $item_id, 'stream_start_time', true );

		if ( empty( $start_date ) ) {
			return '';
		}

		$offset       = self::time_offset();
		$stream_start = strtotime( 'today', ( $start_date / 1000 ) ) - $offset;

		if ( ! empty( $start_time ) ) {
			$time = explode( ':', $start_time );
			if ( is_array( $time ) && count( $time ) === 2 ) {
				$stream_start = strtotime( "+{$time[0]} hours +{$time[1]} minutes", $stream_start );
			}
		}

		return $stream_start;
	}

	public static function get_stream_date( $post_id, $start ) {
		$date_key   = $start ? 'stream_start_date' : 'stream_end_date';
		$time_key   = $start ? 'stream_start_time' : 'stream_end_time';
		$meta_value = get_post_meta( $post_id, $date_key, true );
		$time_value = get_post_meta( $post_id, $time_key, true );

		$current_date = self::validate_stream_start_date( $meta_value );
		$date_time    = gmdate( 'F j, Y', $current_date / 1000 );
		$time         = gmdate( 'g:i a', strtotime( $time_value ) );

		return "$date_time, $time";
	}

	public static function validate_stream_start_date( $start_date ) {
		if ( is_numeric( $start_date ) && 0 !== $start_date ) {
			return $start_date;
		}

		return strtotime( 'today' ) . '000';
	}

	public static function is_stream_started( $item_id ) {
		$stream_start = self::stream_start_time( $item_id );

		/*NO TIME - STREAM STARTED*/
		if ( empty( $stream_start ) ) {
			return true;
		}

		if ( $stream_start > time() ) {
			return false;
		}

		return true;
	}

	public static function stream_end_time( $item_id ) {
		$end_date = get_post_meta( $item_id, 'stream_end_date', true );
		$end_time = get_post_meta( $item_id, 'stream_end_time', true );

		if ( empty( $end_date ) || empty( $end_time ) ) {
			return '';
		}

		$offset = self::time_offset();

		$stream_end = strtotime( 'today', ( $end_date / 1000 ) - $offset );

		if ( ! empty( $end_time ) ) {
			$time = explode( ':', $end_time );
			if ( is_array( $time ) && count( $time ) === 2 ) {
				$stream_end = strtotime( "+{$time[0]} hours +{$time[1]} minutes", $stream_end );
			}
		}

		return $stream_end;
	}

	public static function is_stream_ended( $item_id ) {
		$time_now = time();

		$stream_end = self::stream_end_time( $item_id );

		if ( empty( $stream_end ) ) {
			return true;
		}

		if ( $stream_end > $time_now ) {
			return false;
		}

		return true;
	}

	public static function navigation_complete_class( $class, $item_id ) {
		if ( self::is_stream( $item_id ) && ! self::is_stream_started( $item_id ) ) {
			return "stream-cannot-be-completed stream-is-not-started {$class}";
		}

		if ( self::is_stream( $item_id ) && ! self::is_stream_ended( $item_id ) ) {
			return "stream-cannot-be-completed stream-is-not-ended {$class}";
		}

		return $class;
	}

	public static function navigation_complete_atts( $atts, $item_id ) {
		if ( self::is_stream( $item_id ) && ! self::is_stream_ended( $item_id ) ) {
			$end_time = self::stream_end_time( $item_id );

			$end_time = ( $end_time - time() );

			return $atts . " data-timer='" . $end_time . "' data-disabled='true'";
		}

		return $atts;
	}

}
