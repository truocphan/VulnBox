<?php
/**
 * @todo Clean up.
 *
 * phpcs:ignoreFile
 * @SuppressWarnings(PHPMD)
 */
if ( ! function_exists( 'jupiterx_logic_message_helper' ) ) {
	/**
	 * This function is responsible to store every message we use in system , this way we can translate them or convert them
	 * to meaningfull messages in easy steps.
	 * also this way we can translate all third-party messages too
	 * We can pass some parameters in the message as long as we follow this format:
	 * array(
	 *     '{param} is activated in {param}.', // Message will be displayed
	 *     'Artbees Captcha',                  // Will replace first {param}
	 *     'Jupiter',                          // Will replace second {param}
	 *     'Etc...',                           // Will replace third {param} (if exist), etc.
	 * )
	 *
	 * @author       Reza Marandi <ross@artbees.net>
	 *
	 * @copyright    Artbees LTD (c)
	 * @link         https://artbees.net
	 * @version      5.5
	 * @package      jupiter
	 *
	 * @param string $which_page Which page we should look for the message (for example : plugin-management , template-management , ..)
	 * @param string $which_string Which message we are looking for ?
	 * @param string $which_type Which message type is suitable to return ? (user_msg , system_msg)
	 *
	 * @return string replaced and prepared message
	 */
	function jupiterx_logic_message_helper( $which_page, $which_string, $which_type = 'user_msg' ) {
		if ( empty( $which_page ) || empty( $which_string ) ) {
			return null;
		}

		/*====================== FILESYSTEM ============================*/
		$filesystem_messages = array(
			array(
				'sys_msg'  => 'No filesystem credentials found!',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
		);

		/*====================== TEST CASES ============================*/
		$test_cases_messages = array(
			array(
				'sys_msg'  => 'error 1 in 0x0x111',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'error 2 in 0x0x112',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'error 3 in 0x0x113',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'error 4 in 0x0x114 {param}',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'error 5 in {param} 0x0x114 {param} you can say {param}',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
		);
		/*====================== PLUGIN MANAGEMENT ============================*/
		$plugin_management_messages = array(
			array(
				'sys_msg'  => 'Can not find plugin path in deactivate plugin func',
				'user_msg' => __( 'Whoops! We seem to be having some problems. Please contact Support and supply the number shown and we\'ll be happy to figure this out for you. (code 200)', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Can not find plugin path in activate plugin func',
				'user_msg' => __( 'Whoops! We seem to be having some problems. Please contact Support and supply the number shown and we\'ll be happy to figure this out for you. (code 201)', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Plugin successfully added and activated.',
				'user_msg' => __( 'Your plugin has been successfully added and activated.', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Plugin directory is not writable.',
				'user_msg' => __( 'Whoops! We seem to be having some problems. Please contact Support and supply the number shown and we\'ll be happy to figure this out for you. (code 202)', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Successfull',
				'user_msg' => __( 'Congratulations,your operation has been done successfully', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Plugins source not found or it have invalid url',
				'user_msg' => __( 'Whoops! We seem to be having some problems. Please contact Support and supply the number shown and we\'ll be happy to figure this out for you. (code 203)', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Can not find {param} plugin path.',
				'user_msg' => __( 'Whoops! We seem to be having some problems. Please contact Support and supply the number shown and we\'ll be happy to figure this out for you. (code 204)', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Plugin parent directory is not writable - RPPM01x01.',
				'user_msg' => __( 'Whoops! We seem to be having some problems. Please contact Support and supply the number shown and we\'ll be happy to figure this out for you. (code 205)', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Plugin directory is not writable  - RPPM01x01',
				'user_msg' => __( 'Whoops! We seem to be having some problems. Please contact Support and supply the number shown and we\'ll be happy to figure this out for you. (code 206)', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Can not remove directory of plugin - RPPM01x01-Directory',
				'user_msg' => __( 'Whoops! We seem to be having some problems. Please contact Support and supply the number shown and we\'ll be happy to figure this out for you. (code 207)', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Can not remove directory of plugin - RPPM01x01-File',
				'user_msg' => __( 'Whoops! We seem to be having some problems. Please contact Support and supply the number shown and we\'ll be happy to figure this out for you. (code 208)', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Plugin you want to remove is not exist  - RPPM01x01',
				'user_msg' => __( 'Whoops! We seem to be having some problems. Please contact Support and supply the number shown and we\'ll be happy to figure this out for you. (code 209)', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Plugin you want to remove is still activated , deactive it first  - RPPM01x01.',
				'user_msg' => __( 'To remove your plugin, please deactivate it first and then try again.', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Can not find plugin head file name.',
				'user_msg' => __( 'Whoops! We seem to be having some problems. Please contact Support and supply the number shown and we\'ll be happy to figure this out for you. (code 210)', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Plugin successfully Updated.',
				'user_msg' => __( 'Your plugin have been successfully updated.', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Plugin successfully Removed.',
				'user_msg' => __( 'Your plugin have been successfully removed.', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Plugin list is not an array , use install method instead.',
				'user_msg' => __( 'Whoops! We seem to be having some problems. Please contact Support and supply the number shown and we\'ll be happy to figure this out for you. (code 211)', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'The plugin ({param}) you are looking for is not exist.',
				'user_msg' => __( 'Whoops! We seem to be having some problems. Please contact Support and supply the number shown and we\'ll be happy to figure this out for you. (code 212)', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Plugin list is empty',
				'user_msg' => __( 'Whoops! We seem to be having some problems. Please contact Support and supply the number shown and we\'ll be happy to figure this out for you. (code 213)', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => '{param} plugins installed successfully',
				'user_msg' => __( 'Whoops! We seem to be having some problems. Please contact Support and supply the number shown and we\'ll be happy to figure this out for you. (code 214)', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Plugin you want to remove is not exist  - RPPM01x01.',
				'user_msg' => __( 'Whoops! We seem to be having some problems. Please contact Support and supply the number shown and we\'ll be happy to figure this out for you. (code 215)', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Undefined',
				'user_msg' => __( 'Whoops! We seem to be having some problems. Please contact Support and supply the number shown and we\'ll be happy to figure this out for you. (code 216)', 'jupiterx-core' ),
			),
		);

		/*====================== ADDON MANAGEMENT ============================*/
		$addon_management_messages = array(
			array(
				'sys_msg'  => 'System problem , please call support',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Successfull',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'System problem at installing , please call support',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Add-on activated successfully',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'The Add-On you are looking for is not exist.',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'The Add-On directory is not writable , Change the permission first.',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'The Add-On removal process was not successfull.',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Add-on removed successfully',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Add-on you are looking for is not exist in API side',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'You have latest version of this add-on.',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Add-on updated successfully',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
		);

		/*====================== TEMPLATE MANAGEMENT ============================*/
		$template_management_messages = array(
			array(
				'sys_msg'  => 'System problem at installing , please contact support',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Choose template first',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Template assets are not completely exist - p1, Contact support.',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'System problem , please contact support',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Successfull',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Template list is not what we expected',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Database reset completed',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Choose one template first',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Template source URL is not validate',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Uploaded to server',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Cannot delete template zip file',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Completed',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Template assets are not completely exist - p2, Contact support.',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Plugin set have wrong structure',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => '{param} plugins are installed.',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Template contents were imported.',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Navigation locations is configured.',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Default homepage and Shop Page is configured.',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Shop Page is configured.',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Default homepage is configured.',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Setup pages completed.',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Template options is empty',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Widgets are imported.',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Can not remove source files',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Widget data could not be read. Please try a different file.',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Sidebar does not exist in theme (using Inactive)',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Site does not support widget',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Widget already exists',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Imported',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Imported to Inactive',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Successfull',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'LayerSlider is not installed , install it first',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'LayerSlider is installed but not activated , activate it first',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg'  => 'Backup created.',
				'user_msg' => __( '', 'jupiterx-core' ),
			),
			array(
				'sys_msg' => 'Not enough max_execution_time.',
				'user_msg' => __( '' , 'jupiterx-core' ),
			),
			array(
				'sys_msg' => 'Not enough memory_limit.',
				'user_msg' => __( '' , 'jupiterx-core' ),
			),
		);

		/*====================== DB MANAGEMENT ============================*/
		$db_management_messages = array(
			array(
				'sys_msg'  => 'Can not create backup db file.',
				'user_msg' => '',
			),
			array(
				'sys_msg'  => 'Backup file is not created in right approach , try again.',
				'user_msg' => '',
			),
			array(
				'sys_msg'  => 'Backup Successfuly created',
				'user_msg' => '',
			),
			array(
				'sys_msg'  => 'Can not create index , Securesection',
				'user_msg' => '',
			),
			array(
				'sys_msg'  => 'Can not create htaccess , Securesection',
				'user_msg' => '',
			),
		);

		/*====================== DECISION LOGIC ============================*/
		switch ( $which_page ) {
			case 'filesystem':
				$data = $filesystem_messages;
				break;
			case 'test-cases':
				$data = $test_cases_messages;
				break;
			case 'plugin-management':
				$data = $plugin_management_messages;
				break;
			case 'addon-management':
				$data = $addon_management_messages;
				break;
			case 'template-management':
				$data = $template_management_messages;
				break;
			case 'db-management':
				$data = $db_management_messages;
				break;
			case 'theme-options':
				$data = $theme_options_messages;
				break;
		}

		// Check if the which_string is array
		$which_param = array();
		if ( is_array( $which_string ) ) {
			$which_param = $which_string;
			if ( ! empty( $which_param[0] ) ) {
				// Set back which_string as string, then remove it from which_param
				$which_string = $which_param[0];
				array_shift( $which_param );
			}
		}

		$message_key = JupiterX_Core_Control_Panel_Helpers::search_multdim( $data, 'sys_msg', $which_string );
		if ( $message_key === false ) {
			// LOG THIS SECTION
			// DESC : This error message is not defined
			return jupiterx_logic_message_output( $which_string, $which_param );
		}
		if ( ! empty( $data[ $message_key ]['user_msg'] ) && $which_type == 'user_msg' ) {
			return jupiterx_logic_message_output( $data[ $message_key ]['user_msg'], $which_param );
		} else {
			// LOG THIS SECTION
			// DESC : User message is not defined , DEFINE IT (Translate Department);
			return jupiterx_logic_message_output( $data[ $message_key ]['sys_msg'], $which_param );
		}
	}
}

if ( ! function_exists( 'jupiterx_logic_message_output' ) ) {
	/**
	 * Check the message format before return it. If the message need some parameter to pass, check the parameter
	 * then replace it with the correct ones.
	 *
	 * @param  string $message Message will be displayed
	 * @param  array  $param   Parameter to pass
	 * @return string          Output of the message in text format
	 */
	function jupiterx_logic_message_output( $message = null, $param = array() ) {
		if ( $message == null ) {
			return 'Undefined';
		}

		// Check if param is not empty
		if ( ! empty( $param ) ) {
			// Count {param} that need to be replaced
			$count = substr_count( $message, '{param}' );
			if ( count( $param ) < $count ) {
				$param = array_pad( $param, $count, '{param}' );
			}

			$message = str_replace( '{param}', '%s', $message );
			$message = vsprintf( $message, $param );
		}

		return $message;
	}
}
