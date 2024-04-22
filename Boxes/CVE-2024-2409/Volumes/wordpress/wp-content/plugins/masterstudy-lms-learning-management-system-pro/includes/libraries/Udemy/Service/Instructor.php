<?php

class Udemy_Service_Instructor extends Udemy_Service {
	public function __construct( Udemy_Client $client ) {
		parent::__construct( $client );
		$this->rootUrl     = 'https://www.udemy.com/api-2.0/';
		$this->servicePath = '';
		$this->version     = '';
		$this->serviceName = 'instructor';
	}
}
