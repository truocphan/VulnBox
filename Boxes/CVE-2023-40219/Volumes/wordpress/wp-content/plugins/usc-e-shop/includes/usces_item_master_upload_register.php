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
</style>
<div class="wrap">
<div class="usces_admin">
<h1>Welcart Shop <?php esc_html_e( 'Item list', 'usces' ); ?></h1>
<p class="version_info">Version <?php echo( USCES_VERSION ); ?></p>
<?php usces_admin_action_status(); ?>

<div class="label"><?php esc_html_e( 'File Information', 'usces' ); ?></div>
<div id="fileinfo" class="information">
<p><?php esc_html_e( 'Filename: ', 'usces' ); ?><span id="filename"></span></p>
<p><?php esc_html_e( 'Type: ', 'usces' ); ?><span id="mode"></span></p>
<p><?php esc_html_e( 'Number of data: ', 'usces' ); ?><span id="rowcount"></span></p>
<p><?php esc_html_e( 'Line 1: ', 'usces' ); ?><span id="header"></span></p>
</div>

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


<div class="label"><?php esc_html_e( 'Log', 'usces' ); ?><span id="download_file"></span></div>
<div id="reg_work"></div>
</div><!--usces_admin-->
</div><!--wrap-->

<?php
$progress_ajax_nonce = wp_create_nonce( 'wel_progress_check_ajax' );
$progressfile        = 'progress.txt';
$logfile_txt         = 'log.txt';
$logfile             = WP_CONTENT_URL . USCES_UPLOAD_TEMP . '/log.txt';

$_REQUEST['action'] = 'itemcsv';
$tempfilename       = usces_item_uploadcsv();

$upload_mode = isset( $_REQUEST['upload_mode'] ) ? $_REQUEST['upload_mode'] : '';
$check_mode  = isset( $_REQUEST['checkcsv'] ) ? 1 : 0;
?>
<script type='text/javascript'>
(function($) {
	var progressfile = '<?php echo( $progressfile ); ?>';
	var progress_ajax_nonce = '<?php echo( $progress_ajax_nonce ); ?>';
	var tempfilename = '<?php echo( $tempfilename ); ?>';
	var logfile = '<?php echo( $logfile ); ?>';
	var check_mode = '<?php echo( $check_mode ); ?>';
	var upload_mode = '<?php echo( $upload_mode ); ?>';

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
				'action': 'wel_item_progress_check_ajax',
				'progressfile': progressfile,
				'nonce': progress_ajax_nonce,
				'noheader': 'true'
			};

			$.ajax( s ).done(function( data ){

				if ( data.status ) {
					$("#status").html(data.status);
				}
				if ( data.progress ) {
					$("#msg").html(data.progress);
				}
				if ( typeof( data.info ) != 'undefined' ) {
					$("#filename").html(data.info.filename);
					$("#mode").html(data.info.mode);
					$("#rowcount").html(data.info.rowcount);
					$("#header").html(data.info.header);
				}

				if ( typeof( data.all ) != 'undefined' && typeof( data.i ) != 'undefined' ) {
					$("#p_bar").css( 'width', ( ( data.i / data.all ) * 100 ) + '%' );
					$("#i_p_bar").html( Math.round( 100 * ( data.i / data.all ) ) + "%" );
				}

				if ( typeof( data.flag ) != 'undefined' && 'complete' == data.flag ) {
					checkPRG.completed();
				} else {
					setTimeout( checkPRG.refreshProgress(), 1000 );
				}

				//console.log(data);

			}).fail(function( msg ){
				$("#status").html('Error');
				$("#msg").html('');

				console.log(msg);
			});
			return;
		},

		completed : function() {
			$.ajax({
				url: uscesL10n.requestFile,
				type: 'POST',
				cache: false,
				data: {
					'action': 'wel_item_progress_completed_ajax',
					'logfile': '<?php echo( $logfile_txt ); ?>',
					'nonce': progress_ajax_nonce,
					'noheader': 'true'
				}
			}).done(function( logdata ) {
				$("#reg_work").html( '<div>' + logdata.replace(/\n/g, '<br>') + '</div>' );
			});
			$('#download_file').html('<?php esc_html_e( '( Download )', 'usces' ); ?>');
		},
	};

	itemCSV = {
		settings: {
			url: uscesL10n.requestFile,
			type: 'POST',
			dataType: 'json',
			cache: false
		},

		registration : function( work_number, comp_num, err_num ) {

			var s = itemCSV.settings;

			if ( 1 == check_mode ) {
				s.data = {
					'action'      : 'wel_item_upload_ajax',
					'regfile'     : tempfilename,
					'mode'        : upload_mode,
					'work_number' : work_number,
					'comp_num'    : comp_num,
					'err_num'     : err_num,
					'checkcsv'    : 1,
					'noheader'    : 'true'
				};
			} else {
				s.data = {
					'action'      : 'wel_item_upload_ajax',
					'regfile'     : tempfilename,
					'mode'        : upload_mode,
					'work_number' : work_number,
					'comp_num'    : comp_num,
					'err_num'     : err_num,
					'noheader'    : 'true'
				};
			}

			<?php do_action( 'usces_action_itemcsv_upload_after_setting_data' ); ?>

			$.ajax( s ).done(function( data ){
				if ( typeof( data.flag ) != 'undefined' && 'continue' == data.flag ) {
					if ( typeof( data.work_number ) != 'undefined' ) {
						console.log( 'work_number : ' + data.work_number );
						itemCSV.registration( data.work_number, data.comp_num, data.err_num );
					}
				}
				//console.log('Regist OK');
				//console.log(data);
			}).fail(function( msg ){
				console.log('Regist NG');
				console.log(msg);
			});
			return;
		},
	};
	setTimeout( checkPRG.refreshProgress(), 1000 );

	if ( tempfilename ) {
		itemCSV.registration( 0, 0, 0 );
	}

	$("#download_file").on('click', function () {
		$.get( logfile ).done(function (data) {
			var blob= new Blob([data]);
			var link= document.createElement('a');
			link.href= window.URL.createObjectURL(blob);
			link.download= "log.txt";
			link.click();
		});
	});
})(jQuery);
</script>
