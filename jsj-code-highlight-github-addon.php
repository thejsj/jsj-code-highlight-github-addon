<?php 

/* * * * * * * * *

Plugin Name: JSJ Code Hightlight Github Addon
Plugin URI: http://thejsj.com
Description: Add Github Gists and file contents in your WordPress site
Author: Jorge Silva Jetter
Version: 0.1
Author URI: http://thejsj.com

 * * * * * * * * */

// Include Settings Files
require( plugin_dir_path( __FILE__ ) . '/classes/github-api-request.php');

$jsj_code_highlight_github_addon = new JSJCodeHighlightGithubAddon();

class JSJCodeHighlightGithubAddon {

	public $api = null;

	/**
	 * Populate a couple of variables and hook all wordpress actions and filters
	 * 
	 * @return void
	 */
	public function __construct(){

		// Init Set All Plugin Variables
		add_action('jsj_code_highlight/add_admin_formatting', array($this, 'add_admin_options') );

		// Get Settings
		$this->user_name; 


		// Add Shortcode
		add_shortcode('jsj-code', array($this, 'code_shortcode'));

		// Code
		add_shortcode('code', array($this, 'code_shortcode'));

	}

	/**
	 * Add a box for options
	 *
	 * @return void
	 */
	public function add_admin_options(){ ?>
		
		<h2>Github Settings</h2>

	<? }

	/**
	 * Process shortcode. If necessary, make all API calls. 
	 *
	 * @return string
	 */
	public function code_shortcode($atts, $content = ""){
		// Populate 
		if(!$api)
			$api = new GitHubApiRequest($user_name);

		if($api->user_exists){
			$response =  $api->get_gist(8126742); 
		}

		return json_encode($response->content_lines);
	}

}

?>