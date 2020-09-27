<?php

if ( ! class_exists( 'PB_Newsletter_Gen_Utils' ) ) {

	class PB_Newsletter_Gen_Utils {

		public function __construct() {
		}

		/**
		 *
		 * Format query parameters
		 *
		**/
		public static function extract_params($queryParams) {
			// 
			$query = ''; 

			foreach($queryParams as $key => $value) {
				$query .= $key . '=' . urlencode($value) . '&';
			}

			$query = rtrim($query, '& ');

			return $query;
		}

		/**
		 *
		 * Format post parameters
		 *
		**/
		public static function extract_post_params($post) {
			$data = ""; 

			foreach( $post as $key => $value ) {
				$data .= urlencode($key) . '=' . urlencode($value) . '&'; 
			}

			$data = rtrim($data, '& ');

			return $data;
		}

		public function getRequest($url) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$response = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			$response = json_decode($response, true);

			unset($response["result_code"]);
			unset($response["result_message"]);
			unset($response["result_output"]);

			$response = json_encode($response);

			return $response;
		}

		public function postRequest($url, $data){
			$request = curl_init($url); // initiate curl object
			curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
			curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
			curl_setopt($request, CURLOPT_POSTFIELDS, $data); // use HTTP POST to send form data
			curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);

			$response = (string)curl_exec($request); // execute curl post and store results in $response

			curl_close($request); // close curl object

			$response = json_decode($response, true);

			unset($response["result_code"]);
			unset($response["result_message"]);
			unset($response["result_output"]);

			$response = json_encode($response);

			return $response;
		}

	}

}