<?php
/**
 * Database update progress screen.
 *
 * @package  Welcart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$progress = array(
	'status'   => __( 'Now preparing...', 'usces' ),
	'log'      => 'clear',
);
wel_record_progress( $progress );

?>
<style type="text/css">
.label {
	font-size: small;
	font-weight: bold;
	margin: 10px 10px 0 10px;
}
#fileinfo {
	border: solid;
	border-color: lightgray;
	border-width: 1px;
	border-radius: 10px 10px 10px 10px;
	background-color: white;
	padding: 10px;
	margin-bottom: 20px;
}
.above_p_bar {
	font-size: large;
	font-weight: bold;
	margin: 10px;
}
#p_bar {
	width: 0;
	padding: 0;
	margin: 0;
	border-spacing: 0;
	background-color: #0099CC;
}
#i_p_bar {
	color: #FFFFFF;
	text-align: right;
	font-weight: bold;
	height: 40px;
	font-size: 16px;
	padding-right: 10px;
}
#out_bar {
	width: 100%;
	border: 1px solid #CC9900;
	border-spacing: 0;
	background-color: #FFFFE8;
	padding: 0;
	margin: 10px 0;
}
.under_p_bar {
	color: #565656;
	font-size: larger;
	font-weight: bold;
	margin: 10px 10px 20px 10px;
}
#reg_work {
	border: solid;
	border-color: lightgray;
	border-width: 1px;
	background-color: ghostwhite;
	padding: 10px;
	margin: 0 0 10px 0;
}
#download_file {
	font-weight: normal;
	color: cornflowerblue;
	text-decoration-line: underline;
	cursor: pointer;
	margin: 0;
}
#info_panel {
	display: none;
}
#button_area2 {
	display: none;
}

</style>
<div class="wrap">
<div class="usces_admin">
<h1>Welcart Shop <?php esc_html_e( 'Database update', 'usces' ); ?></h1>
<p class="version_info">Version <?php echo( esc_html( USCES_VERSION ) ); ?></p>
<?php usces_admin_action_status(); ?>

<div id="info_panel">
	<div class="label"><?php esc_html_e( 'Update Information', 'usces' ); ?></div>
	<div id="fileinfo" class="information"></div>

	<div id="status" class="above_p_bar"><?php esc_html_e( 'Now preparing...', 'usces' ); ?></div>

	<table id="out_bar">
	<tbody><tr><td>

	<table id="p_bar">
	<tbody><tr><td id="i_p_bar">&nbsp;</td></tr>
	</tbody></table>

	</td></tr>
	</tbody></table>
	<div id="msg" class="under_p_bar"><?php esc_html_e( 'Now preparing...', 'usces' ); ?></div>
	<div id="rest" class="under_p_bar"></div>

	<div class="label">ログ<span id="download_file"></span></div>
	<div id="reg_work"></div>
</div>

<div id="button_area">
	<button id="start_update" class="button"><?php esc_html_e( 'Start updating', 'usces' ); ?></button>
	<button id="cancel_update" class="button"><?php esc_html_e( 'Cancel and return home', 'usces' ); ?></button>
</div>
<div id="button_area2">
	<button id="to_home" class="button"><?php esc_html_e( 'Go home', 'usces' ); ?></button>
</div>

</div><!--usces_admin-->
</div><!--wrap-->

<?php
$progressfile = WP_CONTENT_DIR . USCES_UPLOAD_TEMP . '/db-progress.txt';
$logfile      = WP_CONTENT_URL . USCES_UPLOAD_TEMP . '/db-log.txt';

?>
<script type='text/javascript'>
(function($) {
	var progressfile = '<?php echo( $progressfile ); ?>';
	var logfile      = '<?php echo( $logfile ); ?>';

	checkPRG = {
		settings: {
			url: uscesL10n.requestFile,
			type: 'POST',
			dataType: 'json',
			cache: false
		},

		refreshProgress : function() {

			var s = checkPRG.settings;

			s.data = {
				'action'       : 'wel_check_progress_ajax',
				'progressfile' : progressfile,
				'noheader'     : 'true'
			};

			$.ajax( s ).done(function( data ){

				if ( data.status ) {
					$("#status").html(data.status);
				}
				if ( data.progress ) {
					$("#msg").html(data.progress);
				}
				if ( data.info ) {
					$("#fileinfo").html(data.info);
				}

				if ( data.log ) {
					$("#reg_work").html( '<div>' + data.log.replace(/\n/g, '<br>') + '</div>' );
				}

				if ( typeof( data.all ) != 'undefined' && typeof( data.i ) != 'undefined' ) {
					if ( 0 != data.all ) {
						$("#p_bar").css( 'width', ( ( data.i / data.all ) * 100 ) + '%' );
						$("#i_p_bar").html( Math.round( 100 * ( data.i / data.all ) ) + "%" );
					}
				}

				if ( typeof( data.flag ) != 'undefined' && 'complete' == data.flag ) {
					checkPRG.completed();
				} else {
					setTimeout( checkPRG.refreshProgress(), 1000 );
				}

			}).fail(function( msg ){
				$("#status").html('Error');
				$("#msg").html('');

				console.log('check_ng');
				console.log(msg);
			});
			return;
		},

		completed : function() {
			$.ajaxSetup({
				cache: false
			});
			$.get( logfile ).done(function( logdata ) {
				$("#reg_work").html( '<div>' + logdata.replace(/\n/g, '<br>') + '</div>' );
			});
			$('#download_file').html('（<?php esc_html_e( 'Download', 'usces' ); ?>）');
		},
	};

	updateDB = {
		settings: {
			url: uscesL10n.requestFile,
			type: 'POST',
			dataType: 'json',
			cache: false
		},

		registration : function( work_number, comp_num, err_num, time_start ) {

			var s = updateDB.settings;

			s.data = {
				'action'      : 'wel_db_update_ajax',
				'work_number' : work_number,
				'comp_num'    : comp_num,
				'err_num'     : err_num,
				'time_start'  : time_start,
				'noheader'    : 'true'
			};

			$.ajax( s ).done(function( data ){
				if ( typeof( data.flag ) != 'undefined' && 'continue' == data.flag ) {
					if ( typeof( data.work_number ) != 'undefined' ) {
						console.log( 'work_number : ' + data.work_number );
						updateDB.registration( data.work_number, data.comp_num, data.err_num, data.time_start );
					}
				} else {
					$("#button_area2").show();
				}
				console.log('Update OK');
				console.log(data);
			}).fail(function( msg ){
				$("#button_area2").show();
				console.log('Update NG');
				console.log(msg);
			});
			return;
		},
	};

	$("#start_update").on('click', function () {
		if ( confirm( '<?php esc_html_e( 'The process will start. Is it OK?', 'usces' ); ?>' ) ) {
			$("#info_panel").show();
			$("#button_area").hide();
			updateDB.registration( 0, 0, 0, 0 );
			setTimeout( checkPRG.refreshProgress(), 1000 );
		}
	});

	$("#cancel_update").on('click', function () {
		location.href = '<?php echo( USCES_ADMIN_URL . '?page=' . rawurlencode( 'usc-e-shop/usc-e-shop.php' ) ); ?>';
	});

	$("#to_home").on('click', function () {
		location.href = '<?php echo( USCES_ADMIN_URL . '?page=' . rawurlencode( 'usc-e-shop/usc-e-shop.php' ) ); ?>';
	});


	$("#download_file").on('click', function () {
		$.get( logfile ).done(function (data) {
			var blob= new Blob([data]);
			var link= document.createElement('a');
			link.href= window.URL.createObjectURL(blob);
			link.download= "db-log.txt";
			link.click();
		});
	});
})(jQuery);
</script>
