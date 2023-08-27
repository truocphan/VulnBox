<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\ACF;

use Elementor\Core\DynamicTags\Base_Tag;

defined( 'ABSPATH' ) || die();

class Util {
	/**
	 * Add 'key' control to passed tag control stack.
	 * 'key' is common among all ACF tags.
	 *
	 * @param Base_Tag $tag
	 * @since 2.5.0
	 */
	public static function add_key_control( Base_Tag $tag ) {
		$tag->add_control(
			'key',
			[
				'label'  => esc_html__( 'Key', 'jupiterx-core' ),
				'type'   => 'select',
				'groups' => self::get_control_options( $tag->get_supported_fields() ),
			]
		);
	}

	/**
	 * Get value of passed ACF dynamic Tag.
	 * All ACF tags use this function.
	 *
	 * @param Base_Tag $tag
	 * @return Array
	 * @since 2.5.0
	 */
	public static function get_tag_value_field( Base_Tag $tag ) {
		$key = $tag->get_settings( 'key' );

		if ( ! empty( $key ) ) {
			list( $field_key, $meta_key ) = explode( ':', $key );

			if ( 'options' === $field_key ) {
				$field = get_field_object( $meta_key, $field_key );
				return [ $field, $meta_key ];
			}

			$field = get_field_object( $field_key, get_queried_object() );
			return [ $field, $meta_key ];
		}

		return [ null, null ];
	}

	/**
	 * Retrieves ACF field groups and their corresponding data.
	 *
	 * @param array $types
	 * @return Array
	 * @since 2.5.0
	 *
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public static function get_control_options( $types ) {
		// ACF >= 5.0.0
		if ( function_exists( 'acf_get_field_groups' ) ) {
			$acf_groups = acf_get_field_groups();
		} else {
			$acf_groups = apply_filters( 'acf/get_field_groups', [] );
		}

		$groups = [];

		$options_page_groups_ids = [];

		if ( function_exists( 'acf_options_page' ) ) {
			$pages = acf_options_page()->get_pages();

			foreach ( $pages as $slug => $page ) {
				$options_page_groups = acf_get_field_groups( [
					'options_page' => $slug,
				] );

				foreach ( $options_page_groups as $options_page_group ) {
					$options_page_groups_ids[] = $options_page_group['ID'];
				}
			}
		}

		foreach ( $acf_groups as $acf_group ) {
			// ACF >= 5.0.0
			if ( function_exists( 'acf_get_fields' ) ) {
				$fields = acf_get_fields( $acf_group );

				if ( isset( $acf_group['ID'] ) && ! empty( $acf_group['ID'] ) ) {
					$fields = acf_get_fields( $acf_group['ID'] );
				}
			} else {
				$fields = apply_filters( 'acf/field_group/get_fields', [], $acf_group['id'] );
			}

			$options = [];

			if ( ! is_array( $fields ) ) {
				continue;
			}

			$has_option_page_location = in_array( $acf_group['ID'], $options_page_groups_ids, true );

			$is_only_options_page = $has_option_page_location && 1 === count( $acf_group['location'] );

			foreach ( $fields as $field ) {
				if ( ! in_array( $field['type'], $types, true ) ) {
					continue;
				}

				// Use group ID for unique keys.
				if ( $has_option_page_location ) {
					$key = 'options:' . $field['name'];

					$options[ $key ] = esc_html__( 'Options', 'jupiterx-core' ) . ':' . $field['label'];
					if ( $is_only_options_page ) {
						continue;
					}
				}

				$key = $field['key'] . ':' . $field['name'];

				$options[ $key ] = $field['label'];
			}

			if ( empty( $options ) ) {
				continue;
			}

			if ( 1 === count( $options ) ) {
				$options = [ -1 => ' -- ' ] + $options;
			}

			$groups[] = [
				'label' => $acf_group['title'],
				'options' => $options,
			];
		}

		return $groups;
	}
}
