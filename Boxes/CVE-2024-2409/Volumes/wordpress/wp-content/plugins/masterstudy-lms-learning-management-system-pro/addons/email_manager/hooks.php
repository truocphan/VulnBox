<?php

use MasterStudy\Lms\Pro\addons\email_manager\EmailDataCompiler;
use MasterStudy\Lms\Pro\addons\email_manager\EmailManagerSettingsPage;

add_filter( 'stm_lms_filter_email_data', array( EmailDataCompiler::class, 'compile' ), 10, 1 );
add_filter( 'wpcfto_options_page_setup', array( EmailManagerSettingsPage::class, 'setup' ), 100 );
