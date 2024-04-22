<?php

add_action(
	'wp_footer',
	function () {
		require_once STM_LMS_PRO_ADDONS . '/google_classrooms/frontend_view/popup.php';
	}
);

function stm_lms_g_c_colors( &$data, $url ) {
	$colors = array(
		'#64bfd2',
		'#c55bcf',
		'#64d283',
		'#d26473',
	);

	$i = 0;

	foreach ( $data as &$datum ) {
		if ( count( $colors ) === $i ) {
			$i = 0;
		}
		$i++;

		$datum['color'] = $colors[ $i - 1 ];
		$datum['url']   = add_query_arg( 'auditory_id', $datum['id'], $url );
	}

	return $data;

}
