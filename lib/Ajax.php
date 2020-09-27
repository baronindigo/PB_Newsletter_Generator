<?php 

if ( ! class_exists( 'PB_Newsletter_Gen_Admin_Ajax' ) ) {

	class PB_Newsletter_Gen_Admin_Ajax {
		private $_url = "PLACE-API-URL-HERE";
		private $_key = "PLACE-KEY-HERE";

		public function __construct() {
			$this->define_ajax_calls();
		}

		/**/
		private function define_ajax_calls() {
			// Campaigns
			add_action( 'wp_ajax_pb_post_campaign', [$this, 'create_campaign'] );
			add_action( 'wp_ajax_nopriv_pb_post_campaign', [$this, 'create_campaign'] );
			add_action( 'wp_ajax_pb_get_campaigns', [$this, 'get_campaigns'] );

			// Messages
			add_action( 'wp_ajax_pb_view_message', [$this, 'view_message'] );
			add_action( 'wp_ajax_pb_post_message', [$this, 'create_message'] );
			add_action( 'wp_ajax_nopriv_pb_post_message', [$this, 'create_message'] );
			add_action( 'wp_ajax_pb_delete_message', [$this, 'delete_message'] );
			add_action( 'wp_ajax_nopriv_pb_delete_message', [$this, 'delete_message'] );
			add_action( 'wp_ajax_pb_get_messages', [$this, 'get_messages'] );

			// Lists
			add_action( 'wp_ajax_pb_get_lists', [$this, 'get_lists'] );
		}

		/**
		 * Docs: https://www.activecampaign.com/api/example.php?call=campaign_create
		**/
		public function create_campaign() {
			$Utils = new PB_Newsletter_Gen_Utils();

			// Query Params for Request.
			$queryParams = [
				"api_key"    => $this->_key,
				"api_action" => "campaign_create",
				'api_output' => 'json',
			];

			$query = $Utils::extract_params($queryParams);

			$inputJSON = file_get_contents('php://input');
			$obj       = json_decode($inputJSON);

			$sendDate = strtotime($obj->emailDate .' ' . $obj->emailHour . ':00:00');

			// Data to be sent
			$post = [
				'type'                    => 'single', // campaign type (defaults to single)
				'segmentid'               => 0,        // use list segment with ID (0 for no segment),
				'bounceid'                => -1,
				'name'                    => $obj->campaignName,
				'sdate'                   => date('Y-m-d H:i:s', $sendDate), // the date when campaign should be 
				'status'                  => 1, // 0: draft, 1: scheduled
				'public'                  => 1, // if campaign should be visible via public side
				'tracklinks'              => 'all', // possible values: 'all', 'mime', 'html', 'text', 'none'
				'trackreads'              => 1, // possible values: 0 and 1
				'trackreplies'            => 0, // possible values: 0 and 1
				'htmlunsub'               => 1, // append unsubscribe link to the bottom of HTML body
				'textunsub'               => 1, // append unsubscribe link to the bottom of TEXT body
				'p['.$obj->list.']'       => $obj->list, // example list ID
				'm['.$obj->messageID.']'  => 100, // example message ID would be 123. 100 means send to 100% of contacts
			];

			$data = $Utils::extract_params($post);

			// Final API URL
			$api = $this->_url . $query;

			$response = $Utils->postRequest($api, $data);

			echo $response;
			exit;
		}

		/** 
		 * Docs: https://www.activecampaign.com/api/example.php?call=message_view
		**/
		public function view_message() {
			$Utils = new PB_Newsletter_Gen_Utils();

			$inputJSON = file_get_contents('php://input');
			$ID       = json_decode($inputJSON);

			$queryParams = [
				"api_key"    => $this->_key,
				"api_action" => "message_view",
				"api_output" => "json",
				"id"         => $ID
			];

			$query = $Utils::extract_params($queryParams);

			$url = $this->_url.$query;

			$response = $Utils->getRequest($url);

			echo $response;
			exit;
		}

		/**
		 * Docs: https://www.activecampaign.com/api/example.php?call=message_add
		**/
		public function create_message() {
			$Utils = new PB_Newsletter_Gen_Utils();

			$queryParams = [
				"api_key"    => $this->_key,
				"api_action" => "message_add",
				'api_output' => 'json'
			];

			$query = $Utils::extract_params($queryParams);

			$inputJSON = file_get_contents('php://input');
			$obj       = json_decode($inputJSON);

			//
			$template = new PB_Newsletter_Gen_Templates();
			$html = $template->getTemplate($obj->template, $obj->articlesList);

			$post = [
				'format'            => 'html',
				'subject'           => $obj->subject,
				'fromemail'         => $obj->fromemail,
				'fromname'          => $obj->fromname,
				'reply2'            => $obj->reply2,
				'priority'          => '3',
				'charset'           => 'utf-8',
				'encoding'          => 'quoted-printable',
				'html'              => $html,
				'htmlconstructor'   => 'editor',
				'p['.$obj->list.']' => $obj->list,
			];

			$data = $Utils::extract_params($post);

			// Final API URL
			$api = $this->_url . $query;

			$response = $Utils->postRequest($api, $data);

			echo $response;
			exit;
		}

		/**
		 * Docs: https://www.activecampaign.com/api/example.php?call=message_delete
		**/
		public function delete_message() {
			$Utils = new PB_Newsletter_Gen_Utils();

			$inputJSON = file_get_contents('php://input');
			$ID       = json_decode($inputJSON);

			$queryParams = [
				"api_key"    => $this->_key,
				"api_action" => "message_delete",
				"api_output" => "json",
				"id"         => $ID
			];

			$query = $Utils::extract_params($queryParams);

			$url = $this->_url.$query;

			$response = $Utils->getRequest($url);

			echo $response;
			exit;
		}

		/**
		 * Docs: https://www.activecampaign.com/api/example.php?call=message_list
		**/
		public function get_messages() {
			$Utils = new PB_Newsletter_Gen_Utils();

			$queryParams = [
				"api_key"    => $this->_key,
				"api_action" => "message_list",
				'api_output' => 'json'
			];

			$query = $Utils::extract_params($queryParams);

			$url = $this->_url.$query;

			$response = $Utils->getRequest($url);

			echo $response;
			exit;
		}

		/**
		 * Docs: https://www.activecampaign.com/api/example.php?call=list_list
		**/
		public function get_lists() {
			$Utils = new PB_Newsletter_Gen_Utils();

			$queryParams = [
				"api_key"    => $this->_key,
				"api_action" => "list_list",
				'api_output' => 'json',
				'ids'        => 'all'
			];

			$query = $Utils::extract_params($queryParams);

			$url = $this->_url.$query;

			$response = $Utils->getRequest($url);

			echo $response;
			exit;
		}

		/**
		 * Docs: https://www.activecampaign.com/api/example.php?call=campaign_list
		**/
		public function get_campaigns() {
			$Utils = new PB_Newsletter_Gen_Utils();

			$queryParams = [
				"api_key"    => $this->_key,
				"api_action" => "campaign_list",
				'api_output' => 'json',
				'ids'        => 'all',
				'full' => 0
			];

			$query = $Utils::extract_params($queryParams);

			$url = $this->_url.$query;

			$response = $Utils->getRequest($url);

			echo $response;
			exit;
		}

	}
}