<?php
namespace stmLms\Classes\Models;

use stmLms\Classes\Vendor\StmBaseModel;

class StmOrder extends StmBaseModel {

	protected $fillable = [
		'ID',
		'post_author',
		'post_date',
		'post_date_gmt',
		'post_content',
		'post_title',
		'post_excerpt',
		'post_status',
		'comment_status',
		'ping_status',
		'post_password',
		'post_name',
		'to_ping',
		'post_modified',
		'post_modified_gmt',
		'post_content_filtered',
		'post_parent',
		'guid',
		'menu_order',
		'post_type',
		'post_mime_type',
		'comment_count'
	];

	public $ID;
	public $post_author;
	public $post_date;
	public $post_date_gmt;
	public $post_content;
	public $post_title;
	public $post_excerpt;
	public $post_status;
	public $comment_status;
	public $ping_status;
	public $post_password;
	public $post_name;
	public $to_ping;
	public $post_modified;
	public $post_modified_gmt;
	public $post_content_filtered;
	public $post_parent;
	public $guid;
	public $menu_order;
	public $post_type;
	public $post_mime_type;
	public $comment_count;
	public $post;

	public static function get_primary_key()
	{
		return 'ID';
	}

	public static function get_table()
	{
		global $wpdb;
		return $wpdb->prefix . 'posts';
	}

	public static function get_searchable_fields()
	{
		return [
			'ID',
			'post_author',
			'post_date',
			'post_date_gmt',
			'post_content',
			'post_title',
			'post_excerpt',
			'post_status',
			'comment_status',
			'ping_status',
			'post_password',
			'post_name',
			'to_ping',
			'post_modified',
			'post_modified_gmt',
			'post_content_filtered',
			'post_parent',
			'guid',
			'menu_order',
			'post_type',
			'post_mime_type',
			'comment_count',
		];
	}

	public static function init(){

	}

	public static function after_delete_post($postid){

	}

	/**
	 * @param $meta_key string
	 * @param bool $flip boolean
	 *
	 * @return array|mixed|null
	 */
	public function getMeta($meta_key ,$flip = false){
		if($meta = get_post_meta($this->ID, $meta_key, true) AND !empty($meta)) {
			return ($flip) ? array_flip(unserialize($meta)) : unserialize($meta);
		}
		return null;
	}

	public static function load($data) {
		$model = new StmOrder();
		foreach ($data as $key => $val) {
			$model->$key = $val;
		}
		return $model;
	}
}

