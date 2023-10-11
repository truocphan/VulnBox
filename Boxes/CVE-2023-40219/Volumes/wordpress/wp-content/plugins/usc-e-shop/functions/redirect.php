<?php
function usces_redirect(){
	if( isset($_GET['page']) && ($_GET['page'] == 'usces_itemedit' || $_GET['page'] == 'usces_itemnew') ){	
		if ( isset($_POST['wp-preview']) && 'dopreview' == $_POST['wp-preview'] ){
			$action = 'preview';
		}else{
			$action = '';
		}
		switch( $action ){
			case 'preview':
				check_admin_referer( 'autosave', 'autosavenonce' );
			
				$url = post_preview();
			
				wp_redirect($url);
				exit();
				break;
				
		}
	}
}
?>
