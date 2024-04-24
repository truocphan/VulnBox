<?php
/**
 * Base repository interface.
 *
 * It should implemented by all the repositiries.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Database
 */

namespace Masteriyo\Repository;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Database\Model;
use Masteriyo\MetaData;

interface RepositoryInterface {
	/**
	 * Method to create a new record of a Model based object.
	 *
	 * @since 1.0.0
	 *
	 * @param Model  $model a Model object.
	 */
	public function create( Model &$model );

	/**
	 * Method to read a record. Creates a new Model based object.
	 *
	 * @since 1.0.0
	 *
	 * @param Model  $model a Model object.
	 */
	public function read( Model &$model );

	/**
	 * Updates a record in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param Model  $model a Model object.
	 */
	public function update( Model &$model );

	/**
	 * Deletes a record from the database.
	 *
	 * @param  Model  $model a Model object.
	 * @param  array   $args Array of args to pass to the delete method.
	 * @return bool result
	 */
	public function delete( Model &$model, $args = array() );

	/**
	 * Returns an array of meta for an object.
	 *
	 * @since 1.0.0
	 *
	 * @param  Model  $model a Model object.
	 * @return MetaData[]
	 */
	public function read_meta( Model &$model );

	/**
	 * Deletes meta based on meta ID.
	 *
	 * @since 1.0.0
	 *
	 * @param  Model  $model a Model object.
	 * @param  MetaData  $meta Meta object (containing at least ->id).
	 * @return array
	 */
	public function delete_meta( Model &$model, MetaData $meta );

	/**
	 * Add new piece of meta.
	 *
	 * @since 1.0.0
	 *
	 * @param  Model  $model a Model object.
	 * @param  MetaData  $meta Meta object (containing ->key and ->value).
	 * @return int meta ID
	 */
	public function add_meta( Model &$model, MetaData $meta );

	/**
	 * Update meta.
	 *
	 * @since 1.0.0
	 *
	 * @param  Model  $model a Model object.
	 * @param  MetaData  $meta Meta object (containing ->id, ->key and ->value).
	 */
	public function update_meta( Model &$model, MetaData $meta );

	// /**
	//  * Fetch the records.
	//  *
	//  * @since 1.0.0
	//  * @param array $args Arguments.
	//  */
	// public function query( $args );
}
