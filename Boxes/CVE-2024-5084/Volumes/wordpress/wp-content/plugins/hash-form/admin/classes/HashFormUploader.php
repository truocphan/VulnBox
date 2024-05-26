<?php

/**
 * Handle file uploads via XMLHttpRequest
 */
class HashFormUploadedFileXhr {

    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);

        if ($realSize != $this->getSize()) {
            return false;
        }

        $target = fopen($path, "w");
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);
        return true;
    }

    function getName() {
        return HashFormHelper::get_var('qqfile');
    }

    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])) {
            return (int) $_SERVER["CONTENT_LENGTH"];
        } else {
            throw new Exception(esc_html__('Getting content length is not supported.', 'hash-form'));
        }
    }

}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class HashFormUploadedFileForm {

    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if (!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)) {
            return false;
        }

        return true;
    }

    function getName() {
        return $_FILES['qqfile']['name'];
    }

    function getSize() {
        return $_FILES['qqfile']['size'];
    }

}

class HashFormFileUploader {

    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760) {
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
        $unallowed_extensions = array('php', 'exe', 'ini', 'perl');
        $exts = array_keys(get_allowed_mime_types());
        $missed_exts = array('JPEG', 'JPG', 'PNG', 'GIF');

        foreach ($missed_exts as $m_e) {
            $exts[] = $m_e;
        }

        $available_exts = array();
        foreach ($exts as $ext) {
            $array = explode('|', $ext);
            foreach ($array as $a) {
                $available_exts[] = $a;
            }
        }

        $count = 0;
        foreach ($allowedExtensions as $ext) {
            if (!in_array($ext, $available_exts)) {
                unset($allowedExtensions[$count]);
            }
            $count++;
        }

        $this->allowedExtensions = $allowedExtensions;
        $this->sizeLimit = $sizeLimit;
        $this->checkServerSettings();

        if (HashFormHelper::get_var('qqfile')) {
            $this->file = new HashFormUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new HashFormUploadedFileForm();
        } else {
            $this->file = false;
        }
    }

    public function getName() {
        if ($this->file) {
            return $this->file->getName();
        }
    }

    private function checkServerSettings() {
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit) {
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");
        }
    }

    private function toBytes($str) {
        $val = trim($str);
        $last = strtolower($str[strlen($str) - 1]);

        switch ($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }

        return $val;
    }

    function handleUpload($uploadDirectory, $replaceOldFile = FALSE, $upload_url = '') {
        $this->ensureUploadDirectory($uploadDirectory);
        $uploadDirectory = trailingslashit($uploadDirectory . '/temp');
        $upload_url = $upload_url . '/temp';

        if (!is_writable($uploadDirectory)) {
            return array('error' => esc_html__('Server error. Upload directory isn\'t writable.', 'hash-form'));
        }

        if (!$this->file) {
            return array('error' => esc_html__('No files were uploaded.', 'hash-form'));
        }

        $size = $this->file->getSize();

        if ($size == 0) {
            return array('error' => esc_html__('File is empty', 'hash-form'));
        }

        if ($size > $this->sizeLimit) {
            return array('error' => esc_html__('File is too large', 'hash-form'));
        }

        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'];
        $ext = @$pathinfo['extension'];  // hide notices if extension is empty

        if ($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)) {
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => esc_html__('File has an invalid extension, it should be one of', 'hash-form') . ' ' . $these . '.');
        }

        if (!$replaceOldFile) {
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename .= rand(10, 99);
            }
        }

        if ($this->file->save($uploadDirectory . $filename . '.' . $ext)) {
            $filetype = wp_check_filetype($filename . '.' . $ext);
            return array(
                'success' => true,
                'url' => $upload_url . '/' . $filename . '.' . $ext,
                'path' => HashFormHelper::encrypt($filename . '.' . $ext)
            );
        } else {
            return array(
                'error' => esc_html__('Could not save uploaded file. The upload was cancelled, or server error encountered.', 'hash-form')
            );
        }
    }

    protected function ensureUploadDirectory($path) {
        if (!is_dir($path)) {
            mkdir($path, 0755);
            file_put_contents($path . '/.htaccess', file_get_contents(HASHFORM_PATH . '/admin/src/stubs/htaccess.stub'));
        }

        if (!is_dir($path . '/temp')) {
            mkdir($path . '/temp', 0755);
            file_put_contents($path . '/temp/.htaccess', file_get_contents(HASHFORM_PATH . '/admin/src/stubs/htaccess.stub'));
        }

        if (!file_exists($path . '/index.php')) {
            file_put_contents($path . '/index.php', file_get_contents(HASHFORM_PATH . '/admin/src/stubs/index.stub'));
        }

        if (!file_exists($path . '/temp/index.php')) {
            file_put_contents($path . '/temp/index.php', file_get_contents(HASHFORM_PATH . '/admin/src/stubs/index.stub'));
        }
    }

}
