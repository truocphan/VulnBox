<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Archive;

use Elementor\Core\DynamicTags\Tag as Tag;

defined( 'ABSPATH' ) || die();

class Archive_Description extends Tag {

	public function get_name() {
		return 'archive-description';
	}

	public function get_title() {
		return esc_html__( 'Archive Description', 'jupiterx-core' );
	}

	public function get_group() {
		return 'archive';
	}

	public function get_categories() {
		return [ 'text' ];
	}

	public function render() {
		echo wp_kses_post( get_the_archive_description() );
	}
}
