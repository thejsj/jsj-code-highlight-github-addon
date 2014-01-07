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

		global $jsj_code_highlight_options;



		// Include Settings Files
		require( plugin_dir_path( __FILE__ ) . '/jsj-code-highlight-github-addon-settings.php');

		// Populate All Options
		$this->options = $jsj_code_highlight_options;

		// Add Settings
		add_filter($this->parent_name_space . '/append_settings', array($this, 'add_local_settings_to_plugin_settings'));

		// Get Settings from Plugin
		add_action($this->parent_name_space . '/get_settings', array($this, 'get_settings') );

		// Init Set All Plugin Variables
		add_action($this->parent_name_space . '/add_admin_options', array($this, 'add_admin_options') );

		// Add Shortcode
		add_shortcode('jsj-code', array($this, 'code_shortcode'));

		// Code
		add_shortcode('code', array($this, 'code_shortcode'));
	}

	/**
	 * Get Settings from the main plugin
	 *
	 * @return array
	 */
	public function add_local_settings_to_plugin_settings($settings){
		return array_merge($settings, $this->options);
	}

	/**
	 * Add the settings for this add-on to the settings 
	 *
	 * @return array
	 */
	public function get_settings($settings){
		$this->settings =  $settings;

		// Once we have user settings (their github username and Licencse Key)
		// Make An API call to verigy them
		$this->api = new GitHubApiRequest($this->settings['github_username']->value);
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
			

			<?php if($this->api->user_exists): ?>
				<div class="<?php echo $this->parent_name_space; ?>-registration_box active">
					<h4>Configuation Complete</h4>
				</div>
			<?php else: ?>
				<!-- This box will be hidden if this plugin is properly configured -->
				<div class="<?php echo $this->parent_name_space; ?>-registration_box inactive">
					<h4>Plesase Register your Github username.</h4>
					<p>In order for this plugin to work correctly, you must register <a href="#">here</a>. This will create the necessary tokens to communicate with the GitHub API.</p>
				</div>
			<?php endif; ?>

			<table>
				<tr>
					<td>Github Username</td>
					<td>Plugin Key</td>
				</tr>
				<tr>
					<td>
						<input type="text" 
							name="<?php echo $this->settings['github_username']->name_space; ?>" 
							value="<?php echo $this->settings['github_username']->value; ?>" 
							placeholder="thejsj"/>
					</td>
					<td>
						<input type="text" 
							name="<?php echo $this->settings['github_addon_api_key']->name_space; ?>" 
							value="<?php echo $this->settings['github_addon_api_key']->value; ?>" 
							placeholder="This doesn't work"/>
					</td>
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