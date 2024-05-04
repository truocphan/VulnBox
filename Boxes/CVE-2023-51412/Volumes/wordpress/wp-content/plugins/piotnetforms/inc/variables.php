<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Piotnetforms_Variables {
	protected $slug;
	protected $slug_under;
	protected $text_domain;
	protected $post_type_name;
	protected $plugin_name;

	public function __construct() {
		$this->slug           = 'piotnetforms'; // limit 20 characters for post type
		$this->slug_under     = 'piotnetforms';
		$this->text_domain    = 'piotnetforms';
		$this->post_type_name = 'Forms';
		$this->plugin_name    = 'Piotnet Forms';
	}
}
