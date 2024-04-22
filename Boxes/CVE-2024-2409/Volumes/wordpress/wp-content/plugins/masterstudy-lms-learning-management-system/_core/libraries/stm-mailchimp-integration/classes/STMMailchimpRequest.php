<?php

class STMMailchimpRequest {
	const HEADER_TYPES = [ 'AUTHORIZATION', ];

	public function getHeaders( $function_name = 'getallheaders' ) {
		$all_headers = [];

		if ( function_exists( $function_name ) ) {

			$all_headers = $function_name();
		} else {

			foreach ( $_SERVER as $name => $value ) {

				if ( substr( $name, 0, 5 ) == 'HTTP_' ) {

					$name = substr( $name, 5 );
					$name = str_replace( '_', ' ', $name );
					$name = strtolower( $name );
					$name = ucwords( $name );
					$name = str_replace( ' ', '-', $name );

					$all_headers[ $name ] = $value;
				} elseif ( $function_name == 'apache_request_headers' ) {

					$all_headers[ $name ] = $value;
				}
			}
		}

		return $all_headers;
	}

	/**
	 *
	 * @param string $headerName
	 *
	 * @return type
	 */
	public function getHeaderByName( $headerName ) {
		$headerName = strtoupper( $headerName );

		if ( ! in_array( $headerName, static::HEADER_TYPES, true ) ) {
			return false;
		}

		$headers = null;

		if ( isset( $_SERVER[ $headerName ] ) ) {
			$headers = trim( $_SERVER[ $headerName ] );
		} elseif ( isset( $_SERVER[ 'HTTP_' . $headerName ] ) ) {
			$headers = trim( $_SERVER[ 'HTTP_' . $headerName ] );
		} elseif ( function_exists( 'apache_request_headers' ) ) {
			$requestHeaders = apache_request_headers();
			$requestHeaders = array_combine(
				array_map( 'ucwords', array_keys( $requestHeaders ) ),
				array_values( $requestHeaders )
			);
			$headerName     = ucfirst( strtolower( $headerName ) );
			if ( isset( $requestHeaders[ $headerName ] ) ) {
				$headers = trim( $requestHeaders[ $headerName ] );
			}
		}

		return $headers;
	}

	public function getParams() {
		$params = [];

		if ( $json = file_get_contents( 'php://input' ) ) {
			$data = json_decode( $json );
			if ( ! $data ) {
				return [];
			}

			foreach ( $data as $dKey => $dValue ):
				$params[ $dKey ] = $dValue;
			endforeach;
		}

		return $params;
	}

	public static function post( $url, $data, $headers ) {
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $data ) );
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );

		$response = curl_exec( $ch );
		$code     = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		curl_close( $ch );

		return [ 'status' => $code, 'response' => json_decode( $response ) ];
	}

	public static function delete( $url, $data, $headers ) {
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_HEADER, false );
		curl_setopt( $ch, CURLOPT_DELETE, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );

		$response = curl_exec( $ch );
		$code     = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		curl_close( $ch );

		return [ 'status' => $code, 'response' => json_decode( $response ) ];
	}

	public static function get( $url, $data, $headers ) {
		$url = $url . '&' . http_build_query( $data, '', '&' );

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_HEADER, false );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );

		$response = curl_exec( $ch );
		$code     = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

		curl_close( $ch );

		return [ 'status' => $code, 'response' => json_decode( $response ) ];
	}
}
