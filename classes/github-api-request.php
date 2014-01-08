<?php

class GitHubApiRequest {

	private $user_agent = "JSJ-Code-Highlight";
	private $max_redirs = 10; 
	private $timeout = 10; 

	public function __construct($user_name, $url){
		$this->user_name = $user_name;
		$this->api_url = $url; // Comes from main addon php file
		$this->user_exists = $this->check_if_user_exists(); // Also appends scopes and created_at timestamp
	}

	/**
	 * Check if this user exists and has a token in the API
	 *
	 * @return booelan
	 */
	private function check_if_user_exists(){
		$url = $this->api_url . 'check-if-user-exists/' . $this->user_name . '/';
		$response = $this->get_curl_json($url);
		// Check of user exits
		if($response->response == 'success' && $response->token == true){
			$this->scopes = json_decode($response->scopes); 
			$this->created_at = $response->created_at; 
			return true; 
		}
		else {
			return false; 
		}
	}

	/**
	 * Parse url and maki API call for single gist
	 *
	 * @return array
	 */
	public function get_gist($gist_id = false, $file_name = false, $user_name = false){
		
		if(!$gist_id)
			return false;

		$url = $this->parse_request_url('gist', $user_name); 

		$url = $this->append_get_statements($url, array(
			'id' => $gist_id,
			'file_name' => $file_name,
		)); 

		return $this->get_curl_json($url);
	}

	/**
	 * Parse url and maki API call for single file in repository
	 *
	 * @return array
	 */
	public function get_single_file_in_repo($repo = false, $path = false, $user_name = false){

		if(!$repo || !$path)
			return false;

		$url = $this->parse_request_url('repo', $user_name); 

		$url = $this->append_get_statements($url, array(
			'repo' => $repo,
			'path' => $path,
		)); 
		return $this->get_curl_json($url);
	}

	/**
	 * Get API for Query if another user_name is defined
	 * Return default api url if not defined
	 *
	 * @return string
	 */
	private function parse_request_url($query_type = false, $user_name = false){

		if(!isset($query_type) || !$query_type)
			return false; 

		if(!isset($user_name) || !$user_name)
			$user_name = $this->user_name;

		return $this->api_url . 'request/' . $user_name . '/' . $query_type . '/';
	}

	/**
	 * Append defined and available variables to the url through get statementes
	 *
	 * @return string
	 */
	private function append_get_statements($url, $parameters = false){
		if(!$parameters)
			return $url;

		$sign = '?';
		foreach($parameters as $key => $variable){
			if(isset($variable) && $variable){
				$url .= $sign . $key . '=' . $variable; 
				$sign = '&'; // Change sign, once it's been used
			}
		}
		return $url; 
	}

	/**
	 * Use CURL to make API call with the necessary headers 
	 *
	 * @return array
	 */
	public function get_curl_json($url){
		// Get cURL resource
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_AUTOREFERER => true,
			CURLOPT_CONNECTTIMEOUT => $this->timeout,
			CURLOPT_TIMEOUT => $this->timeout,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_USERAGENT => $this->user_agent,
			CURLOPT_MAXREDIRS => $this->timeout,
			CURLOPT_RETURNTRANSFER => true,
		));
		// Send the request & save response to $response_str
		$response_str = curl_exec($curl);
		// Close request to clear up some resources
		curl_close($curl);
		return json_decode($response_str);
	}
}

?>