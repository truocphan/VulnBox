<?php

namespace StartklarElmentorFormsExtWidgets;

class startklarDropZoneUploadProcess
{
    static function process()
    {
        $uploads_dir_info = wp_upload_dir();
        $user = wp_get_current_user();

        if (!isset($user) || !is_object($user) || !is_a($user, 'WP_User')) {
            $user_id = 0;
        } else {
            $user_id = $user->ID;
        }

        if (in_array('administrator', $user->roles)) {
            $admin_mode = 1;
        }

        if (!isset($_FILES["file"]) && !isset($_POST["mode"])) {
            die(__("There is no file to upload.", "startklar-elmentor-forms-extwidgets"));
        }

        $options = get_option('startklar_options');
        if (!is_array($options) || !count($options) || empty($options) || !isset($options['blocking_php_file_upload'])){
            $options['blocking_php_file_upload'] = "true";
        }
        if ($options['blocking_php_file_upload'] == "true"){
            $file = fopen($_FILES['file']['tmp_name'], 'r');
            if (!$file) {
                die(__("There is no file to upload.", "startklar-elmentor-forms-extwidgets"));
            }
            while (!feof($file)) {
                $line = fgets($file);
                if (strpos($line, "<?php") !== false) {
                    fclose($file);
                    http_response_code(400);
                    die(__("Uploading PHP files is prohibited!", "startklar-elmentor-forms-extwidgets"));
                }
            }
        }
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'hash') !== false) {
                $hash = sanitize_key($value);
                $hash = sanitize_text_field($hash);

                if (empty($hash)) {
                    die(__("No HASH code match.", "startklar-elmentor-forms-extwidgets"));
                }

                if (isset($_POST["mode"]) && $_POST["mode"] == "remove" && isset($_POST["fileName"])) {
                    $fileName = sanitize_file_name($_POST["fileName"]);
                    $newFilepath = $uploads_dir_info['basedir'] . "/elementor/forms/" . $user_id . "/temp/" . $hash . "/" . $fileName;

                    if (file_exists($newFilepath)) {
                        unlink($newFilepath);
                    }

                    die();
                }

                $filepath = $_FILES['file']['tmp_name'];
                $fileSize = filesize($filepath);

                if ($fileSize === 0) {
                    die(__("The file is empty.", "startklar-elmentor-forms-extwidgets"));
                }

                $newFilepath = $uploads_dir_info['basedir'] . "/elementor/forms/" . $user_id . "/temp/" . $hash . "/" . sanitize_file_name($_FILES['file']['name']);
                $target_dir = dirname($newFilepath);

                $validate = wp_check_filetype( $_FILES['file']['name'] );

                if (!$validate['type']) {
                    die(__("File type is not allowed.", "startklar-elmentor-forms-extwidgets"));
                }

                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                if (!copy($filepath, $newFilepath)) { // Copy the file, returns false if failed
                    die(__("Can't move file.", "startklar-elmentor-forms-extwidgets"));
                }
                unlink($filepath); // Delete the temp file
            }
        }
        die();
    }
}