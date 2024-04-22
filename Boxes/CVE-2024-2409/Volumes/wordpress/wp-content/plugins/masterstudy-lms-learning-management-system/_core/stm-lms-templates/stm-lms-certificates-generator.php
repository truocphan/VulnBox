<?php
/**
 * @var $course_id
 */

$user = STM_LMS_User::get_current_user('', false, true);

$first_name = $last_name = '';
if(!empty($user['meta'])) {
    if(!empty($user['meta']['first_name'])) $first_name = $user['meta']['first_name'];
    if(!empty($user['meta']['last_name'])) $last_name = $user['meta']['last_name'];
}

$username = $user['login'];

$shortcodes = array('{username}', '{date}', '{course}', '{user_first_name}', '{user_last_name}');
$shortcode_values = array($username, 'date', get_the_title($course_id), $first_name, $last_name);

$certificate_image_id = STM_LMS_Options::get_option('certificate_image', '');
//$certificate_image = wp_get_attachment_image_url($certificate_image_id, 'img-1120-800');
$certificate_image = get_attached_file($certificate_image_id);

$certificate_stamp_id = STM_LMS_Options::get_option('certificate_stamp', '');

$certificate_title = STM_LMS_Options::get_option('certificate_title', esc_html__('Certificate', 'masterstudy-lms-learning-management-system'));
$certificate_color = STM_LMS_Options::get_option('certificate_title_color', 'rgba(0,0,0,1)');
$certificate_title_fsz = STM_LMS_Options::get_option('certificate_title_fsz', 60);
$certificate_color = str_replace(array('rgba(', ')'), array(''), $certificate_color);
$certificate_color = explode(',', $certificate_color);
$certificate_title = str_replace($shortcodes, $shortcode_values, $certificate_title);

$certificate_subtitle = STM_LMS_Options::get_option('certificate_subtitle', esc_html__('{username}', 'masterstudy-lms-learning-management-system'));
$certificate_subcolor = STM_LMS_Options::get_option('certificate_subtitle_color', 'rgba(0,0,0,1)');
$certificate_subtitle_fsz = STM_LMS_Options::get_option('certificate_subtitle_fsz', 40);
$certificate_subcolor = str_replace(array('rgba(', ')'), array(''), $certificate_subcolor);
$certificate_subcolor = explode(',', $certificate_subcolor);
$certificate_subtitle = str_replace($shortcodes, $shortcode_values, $certificate_subtitle);

$certificate_text = STM_LMS_Options::get_option('certificate_text', '');
$certificate_text_color = STM_LMS_Options::get_option('certificate_text_color', 'rgba(0,0,0,1)');
$certificate_text_fsz = STM_LMS_Options::get_option('certificate_text_fsz', 17);
$certificate_text_color = str_replace(array('rgba(', ')'), array(''), $certificate_text_color);
$certificate_text_color = explode(',', $certificate_text_color);
$certificate_text = str_replace($shortcodes, $shortcode_values, $certificate_text);



/*Check Certificate*/
if (empty($user['id'])) die();
$user_id = $user['id'];
$user_course = stm_lms_get_user_course($user_id, $course_id);
if (empty($user_course)) die();

$passing_grade = intval(STM_LMS_Options::get_option('certificate_threshold', 70));

$user_course = STM_LMS_Helpers::simplify_db_array($user_course);
if ($user_course['progress_percent'] < $passing_grade) {
    printf(
        esc_html__('To qualify for a Certificate, you must get a passing grade of %s in the overall weighting.',
        'masterstudy-lms-learning-management-system'),
        $passing_grade
    );
    die;
};

do_action('stm_lms_certificate_generated', $user_id, $course_id);

$font = 'DejaVu';

require(STM_LMS_PATH . '/libraries/tfpdf/tfpdf.php');

$pdf = new tFPDF('L', 'pt', 'A4');

$pdf->AddFont($font, '', 'OpenSans-Regular.ttf', true);
$pdf->SetFont($font, '', 14);


$pdf->SetTopMargin(20);
$pdf->SetLeftMargin(20);
$pdf->SetRightMargin(20);
$pdf->AddPage();

if (!empty($certificate_image)) {
    $pdf->Image($certificate_image, 0, 0, 850, 600);
}


$pdf->SetTextColor($certificate_color[0], $certificate_color[1], $certificate_color[2]);
$pdf->SetFont($font, '', $certificate_title_fsz);
$pdf->SetXY(40, 100);
$pdf->Multicell(760, 50, $certificate_title, 0, 'C', 0);

$pdf->SetTextColor($certificate_subcolor[0], $certificate_subcolor[1], $certificate_subcolor[2]);
$pdf->SetFont($font, '', $certificate_subtitle_fsz);
$pdf->SetXY(40, 205);
$pdf->Multicell(760, 50, $certificate_subtitle, 0, 'C', 0);

$pdf->SetTextColor($certificate_text_color[0], $certificate_text_color[1], $certificate_text_color[2]);
$pdf->SetFont($font, '', $certificate_text_fsz);
$pdf->SetXY(190, 290);
$pdf->Multicell(460, 24, html_entity_decode($certificate_text), 0, 'C', 0);

$pdf->Output();