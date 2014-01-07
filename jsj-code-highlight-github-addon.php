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
	public $name_space = "jsj_code_highlight_github_addon";
	public $parent_name_space = "jsj_code_highlight";

	/**
	 * Populate a couple of variables and hook all wordpress actions and filters
	 * 
	 * @return void
	 */
	public function __construct(){

		// Init Set All Plugin Variables
		add_action($this->parent_name_space . '/add_admin_options', array($this, 'add_admin_options') );

		// Get Settings
		// $this->user_name; 

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
		
		<h3>Github Settings</h3>

		<div class="<?php echo $this->parent_name_space; ?>-options_box">
			<p>In order for this plugin to work, it must be propertly configured with the <a href="#">JSJ Code Highlight API</a>.</p>
			<!-- This box will be hidden if this plugin is properly configured -->
			<div class="<?php echo $this->parent_name_space; ?>-registration_box">
				<h4>Plesase Register your Github username.</h4>
				<p>In order for this plugin to work correctly, you must register <a href="#">here</a>. This will create the necessary tokens to communicate with the GitHub API.</p>
			</div>

			<table>
				<tr>
					<td>Github Username</td>
					<td>Plugin Key</td>
				</tr>
				<tr>
					<td><input type="text" name="" value="" placeholder="thejsj"/></td>
					<td><input type="text" name="" value="" placeholder="This doesn't work"/></td>
				</tr>
			</table>
		</div>

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