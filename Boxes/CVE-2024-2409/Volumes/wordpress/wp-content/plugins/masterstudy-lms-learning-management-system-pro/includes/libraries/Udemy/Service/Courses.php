<?php
/*
 * Copyright 2016 Bloter and Media Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Service definition for Courses (v1).
 *
 */
class Udemy_Service_Courses extends Udemy_Service {

	public $courses;
	public $courses_reviews;


	/**
	 * Constructs the internal representation of the Courses service.
	 *
	 * @param Udemy_Client $client
	 */
	public function __construct( Udemy_Client $client ) {
		parent::__construct( $client );
		$this->rootUrl     = 'https://www.udemy.com/api-2.0/';
		$this->servicePath = '';
		$this->version     = 'v2.0';
		$this->serviceName = 'courses';

		$this->courses = new Udemy_Service_Courses_Courses_Resource(
			$this,
			$this->serviceName,
			'courses',
			array(
				'methods' => array(
					'get'  => array(
						'path'       => 'courses/{courseId}',
						'httpMethod' => 'GET',
						'parameters' => array(
							'courseId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'list' => array(
						'path'       => 'courses/',
						'httpMethod' => 'GET',
						'parameters' => array(
							'page'                         => array(
								'location' => 'query',
								'type'     => 'integer',
							),
							'page_size'                    => array(
								'location' => 'query',
								'type'     => 'integer',
							),
							'search'                       => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'category'                     => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'subcategory'                  => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'price'                        => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'is_affiliate_agreed'          => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'is_fixed_priced_deals_agreed' => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'is_percentage_deals_agreed'   => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'language'                     => array(
								'location' => 'query',
								'type'     => 'string',
							),

							'has_closed_caption'           => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'has_coding_exercises'         => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'has_simple_quiz'              => array(
								'location' => 'query',
								'type'     => 'boolean',
							),

							'instructional_level'          => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'ordering'                     => array(
								'location' => 'query',
								'type'     => 'string',
							),

						),
					),
				),
			)
		);
		$this->courses_reviews = new Udemy_Service_Courses_CoursesReviews_Resource(
			$this,
			$this->serviceName,
			'reviews',
			array(
				'methods' => array(
					'list' => array(
						'path'       => 'courses/{courseId}/reviews',
						'httpMethod' => 'GET',
						'parameters' => array(
							'courseId'       => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'page'           => array(
								'location' => 'query',
								'type'     => 'integer',
							),
							'page_size'      => array(
								'location' => 'query',
								'type'     => 'integer',
							),
							'is_text_review' => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'rating'         => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'user'           => array(
								'location' => 'query',
								'type'     => 'string',
							),
						),
					),
				),
			)
		);
	}
}

// phpcs:ignore Generic.Files.OneObjectStructurePerFile.MultipleFound
class Udemy_Service_Courses_Courses_Resource extends Udemy_Service_Resource {
	public function get( $courseId, $optParams = array() ) {
		$params = array( 'courseId' => $courseId );
		$params = array_merge( $params, $optParams );
		return $this->call( 'get', array( $params ), 'Udemy_Service_Courses_Course' );
	}

	public function listCourses( $optParams = array() ) {
		$params = array();
		$params = array_merge( $params, $optParams );
		return $this->call( 'list', array( $params ), 'Udemy_Service_Courses_Courses' );
	}
}

// phpcs:ignore Generic.Files.OneObjectStructurePerFile.MultipleFound
class Udemy_Service_Courses_CoursesReviews_Resource extends Udemy_Service_Resource {
	public function listCoursesReviews( $courseId, $optParams = array() ) {
		$params = array( 'courseId' => $courseId );
		$params = array_merge( $params, $optParams );
		return $this->call( 'list', array( $params ), 'Udemy_Service_Courses_Reviews' );
	}
}

// phpcs:ignore Generic.Files.OneObjectStructurePerFile.MultipleFound
class Udemy_Service_Courses_Course extends Udemy_Model {

	protected $internal_gapi_mappings = array();

	public $_class; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore
	public $id;
	public $title;
	public $url;
	public $is_paid;
	public $price;
	public $visible_instructors;
	public $image_125_H;
	public $image_240x135;
	public $image_480x270;
	public $published_title;
	public $predictive_score;
	public $relevancy_score;
	public $input_features;

	public function set_class( $_class ) {
		$this->_class = $_class;
	}

	public function get_class() {
		return $this->_class;
	}

	public function setId( $id ) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setTitle( $title ) {
		$this->title = $title;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setUrl( $url ) {
		$this->url = $url;
	}

	public function getUrl() {
		return $this->url;
	}

	public function setIs_paid( $is_paid ) {
		$this->is_paid = $is_paid;
	}

	public function getIs_paid() {
		return $this->is_paid;
	}

	public function setPrice( $price ) {
		$this->price = $price;
	}

	public function getPrice() {
		return $this->price;
	}

	public function setVisible_instructors( $visible_instructors ) {
		$this->visible_instructors = $visible_instructors;
	}

	public function getVisible_instructors() {
		return $this->visible_instructors;
	}


	public function setImage_125_H( $image_125_H ) {
		$this->image_125_H = $image_125_H;
	}

	public function getImage_125_H() {
		return $this->image_125_H;
	}

	public function setImage_240x135( $image_240x135 ) {
		$this->image_240x135 = $image_240x135;
	}

	public function getImage_240x135() {
		return $this->image_240x135;
	}

	public function setImage_480x270( $image_480x270 ) {
		$this->image_480x270 = $image_480x270;
	}

	public function getImage_480x270() {
		return $this->image_480x270;
	}


	public function setPublished_title( $published_title ) {
		$this->published_title = $published_title;
	}

	public function getPublished_title() {
		return $this->published_title;
	}

	public function setPredictive_score( $predictive_score ) {
		$this->predictive_score = $predictive_score;
	}

	public function getPredictive_score() {
		return $this->predictive_score;
	}

	public function setRelevancy_score( $relevancy_score ) {
		$this->relevancy_score = $relevancy_score;
	}

	public function getRelevancy_score() {
		return $this->relevancy_score;
	}

	public function setInput_features( $input_features ) {
		$this->input_features = $input_features;
	}

	public function getInput_features() {
		return $this->input_features;
	}
}

// phpcs:ignore Generic.Files.OneObjectStructurePerFile.MultipleFound
class Udemy_Service_Courses_Courses extends Udemy_Collection {

	protected $collection_key         = 'results';
	protected $internal_gapi_mappings = array();
	protected $itemsType              = 'Udemy_Service_Courses_Course';
	protected $itemsDataType          = 'array';

	public $count;
	public $next;
	public $previous;
	public $aggregations;


	public function setItems( $items ) {
		$this->items = $items;
	}

	public function getItems() {
		return $this->items;
	}

	public function setCount( $count ) {
		$this->count = $count;
	}

	public function getCount() {
		return $this->count;
	}

	public function setNext( $next ) {
		$this->next = $next;
	}

	public function getNext() {
		return $this->next;
	}

	public function setPrevious( $previous ) {
		$this->previous = $previous;
	}

	public function getPrevious() {
		return $this->previous;
	}

	public function setAggregations( $aggregations ) {
		$this->aggregations = $aggregations;
	}

	public function getAggregations() {
		return $this->aggregations;
	}
}

// phpcs:ignore Generic.Files.OneObjectStructurePerFile.MultipleFound
class Udemy_Service_Courses_Reviews extends Udemy_Collection {

	protected $collection_key         = 'results';
	protected $internal_gapi_mappings = array();
	protected $itemsType              = 'Udemy_Service_Courses_Review';
	protected $itemsDataType          = 'array';

	public $count;
	public $next;
	public $previous;

	public function setItems( $items ) {
		$this->items = $items;
	}

	public function getItems() {
		return $this->items;
	}

	public function setCount( $count ) {
		$this->count = $count;
	}

	public function getCount() {
		return $this->count;
	}

	public function setNext( $next ) {
		$this->next = $next;
	}

	public function getNext() {
		return $this->next;
	}

	public function setPrevious( $previous ) {
		$this->previous = $previous;
	}

	public function getPrevious() {
		return $this->previous;
	}

}

// phpcs:ignore Generic.Files.OneObjectStructurePerFile.MultipleFound
class Udemy_Service_Courses_Review extends Udemy_Model {

	protected $internal_gapi_mappings = array();


	public $_class; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore
	public $id;
	public $title;
	public $content;
	public $rating;
	public $created;
	public $modified;
	public $user;

	public function set_class( $_class ) {
		$this->_class = $_class;
	}

	public function get_class() {
		return $this->_class;
	}

	public function setId( $id ) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setTitle( $title ) {
		$this->title = $title;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setContent( $content ) {
		$this->content = $content;
	}

	public function getContent() {
		return $this->content;
	}

	public function setRating( $rating ) {
		$this->rating = $rating;
	}

	public function getRating() {
		return $this->rating;
	}

	public function setCreated( $created ) {
		$this->created = $created;
	}

	public function getCreated() {
		return $this->created;
	}

	public function setModified( $modified ) {
		$this->modified = $modified;
	}

	public function getModified() {
		return $this->modified;
	}

	public function setUser( $user ) {
		$this->user = $user;
	}

	public function getUser() {
		return $this->user;
	}
}

