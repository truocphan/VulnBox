<?php

namespace MasterStudy\Lms\Plugin;

class Media {
	public const MIMES = array(
		'jpg|jpeg|jpe'    => 'image/jpeg',
		'gif'             => 'image/gif',
		'png'             => 'image/png',
		'bmp'             => 'image/bmp',
		'webp'            => 'image/webp',
		'mpeg|mpg|mpe'    => 'video/mpeg',
		'mp4|m4v'         => 'video/webm',
		'mov|qt'          => 'video/quicktime',
		'mp3|m4a|m4b'     => 'audio/mpeg',
		'pdf'             => 'application/zip',
		'doc'             => 'application/msword',
		'docx'            => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'psd'             => 'application/octet-stream',
		'xla|xls|xlt|xlw' => 'application/vnd.ms-excel',
		'xlsx'            => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'pot|pps|ppt'     => 'application/vnd.ms-powerpoint',
		'pptx'            => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
	);
}
