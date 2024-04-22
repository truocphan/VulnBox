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

function udemy_api_php_client_autoload( $className ) {
	$classPath = explode( '_', $className );
	if ( 'Udemy' != $classPath[0] ) { // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
		return;
	}

	$classPath = array_slice( $classPath, 1, 2 );
	$filePath  = dirname( __FILE__ ) . '/' . implode( '/', $classPath ) . '.php';
	if ( file_exists( $filePath ) ) {
		require_once $filePath;
	}
}
spl_autoload_register( 'udemy_api_php_client_autoload' );
