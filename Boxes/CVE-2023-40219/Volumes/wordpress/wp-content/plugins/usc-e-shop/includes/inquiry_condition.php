<?php
global $usces;
$error_message = '';
if( isset($_POST['inq_name']) && !WCUtils::is_blank($_POST['inq_name']) ) {
	$inq_name = trim($_POST['inq_name']);
}else{
	$inq_name = '';
	if($usces->page == 'deficiency')
		$error_message .= __('Please input your name.', 'usces') . "<br />";
}
if( isset($_POST['inq_mailaddress']) && is_email(trim($_POST['inq_mailaddress'])) ) {
	$inq_mailaddress = trim($_POST['inq_mailaddress']);
}elseif( isset($_POST['inq_mailaddress']) && !is_email(trim($_POST['inq_mailaddress'])) ) {
	$inq_mailaddress = trim($_POST['inq_mailaddress']);
	if($usces->page == 'deficiency')
		$error_message .= __('E-mail address is not correct', 'usces') . "<br />";
}else{
	$inq_mailaddress = '';
	if($usces->page == 'deficiency')
		$error_message .= __('Please input your e-mail address.', 'usces') . "<br />";
}
if( isset($_POST['inq_contents']) && !WCUtils::is_blank($_POST['inq_contents']) ) {
	$inq_contents = trim($_POST['inq_contents']);
}else{
	$inq_contents = '';
	if($usces->page == 'deficiency')
		$error_message .= __('Please input contents.', 'usces');
}
if( isset($_POST['reserve'])) {
	foreach($_POST['reserve'] as $key => $value){
		$key = trim($key);
		$reserve[$key] = trim($value);
	}
}
?>
