<?php 

/* * * * * * * * *

Plugin Name: JSJ Code Hightlight Github Addon
Plugin URI: http://thejsj.com
Description: Add Github Gists and file contents in your WordPress site
Author: Jorge Silva Jetter
Version: 0.1
Author URI: http://thejsj.com

 * * * * * * * * */
/*

1. Check if user exits with API

- User Exists

- Get Message From Server

- Get if can't find server message

- Default to false

2. Add Shortcodes

- Add shortcodes for gist

- Add shortcodes for file

- Add shortcode for lines requested

*/
// Include Settings Files
require( plugin_dir_path( __FILE__ ) . '/classes/github-api-request.php');

$jsj_code_highlight_github_addon = new JSJCodeHighlightGithubAddon();

class JSJCodeHighlightGithubAddon {

	public $api = null;
	public $name_space = "jsj_code_highlight_github_addon";
	public $parent_name_space = "jsj_code_highlight";
	public $api_url = 'http://jsj-code-highlight-api.thejsj.webfactional.com/';

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

		// Add Tab to Settings Page
		add_action($this->parent_name_space . '/add_tab', array($this, 'add_tab') );

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
		$this->api = new GitHubApiRequest($this->settings['github_username']->value, $this->api_url);
	}

	public function add_tab(){ ?>
		<a class="nav-tab" href="?page=<?php echo $this->parent_name_space; ?>&amp;tab=github-settings"><?php _e('GitHub Settings', 'jsj_code_highlight' ); ?></a>
	<?php }

	/**
	 * Add a box for options
	 *
	 * @return void
	 */
	public function add_admin_options($options_tab){ ?>

		<!-- Tab #2 -->
		<div class="<?php echo $this->parent_name_space; ?>-tab-content <?php echo (($options_tab == 'github-settings') ? 'active' : 'disabled' );?>">
		
			<h3>Github Settings</h3>

			<div class="<?php echo $this->parent_name_space; ?>-options_box">
				<p>In order for this plugin to work, it must be propertly configured with the <a href="#">JSJ Code Highlight API</a>.</p>
				

				<?php if($this->api->user_exists): ?>
					<div class="<?php echo $this->parent_name_space; ?>-registration_box active">
						<p><strong>Configuation Complete: </strong>User with corresponding token found in API database.</p>
					</div>
				<?php else: ?>
					<!-- This box will be hidden if this plugin is properly configured -->
					<div class="<?php echo $this->parent_name_space; ?>-registration_box inactive">
						<p><strong>Plesase Register your Github username:</strong> In order for this plugin to work correctly, you must register <a href="#">here</a>. This will create the necessary tokens to communicate with the GitHub API.</p>
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

		</div>

	<? }

	/**
	 * Process shortcode. If necessary, make all API calls. 
	 *
	 * @return string
	 */
	public function code_shortcode($atts, $content = ""){
		
		// Init the API if not defined
		if(!$this->api)
			$this->api = new GitHubApiRequest($user_name);

		// Make Api Request if user exists
		if(!$this->api->user_exists)
			return "<!-- JSJ Code Highlight Github Addon : Addon not properly configured. User does not exist in API. Please register user at: " . $this->api_url . " -->";
		
		// List all posiblities for this shortcode, for future reference
		extract( shortcode_atts( array(
			'type'  => false,    // gist, repo
			'id'    => false, // gist id
			'repo'  => false,    // path to file in repo
			'path'  => false,    // path to file in repo
			'lines' => false,   // hypehn separated values for lines 7-10
		), $atts ) );

		// Make API Call
		if($type == "gist" && $id != false){
			
			$response =  $this->api->get_gist($id); 
			echo json_encode($response);
		}
		elseif($type == "repo" && $repo != false && $path != false){
			
			$response =  $this->api->get_single_file_in_repo($repo, $path);
			echo json_encode($response);
		}
		else {
			echo json_encode("No Response");
			return "<!-- JSJ Code Highlight Github Addon : Not enough parameters provided to make an API call. Check you have all the necessary paramters set in your shortcode -->";
		}

		

		// Parse Response into HTML
		if($response->response == "success"){

			// Parse Line Numbers
			$beginning_line = 0;
			$ending_line = count($response->content_lines);
			if($lines){
				$line_numbres = preg_split('/-/', $lines);
				$beginning_line = max($beginning_line, $line_numbres[0] - 1); // Convert to 0 based
				$ending_line = min($ending_line , $line_numbres[1] ); // Conver to 0 based
			}
			$numbers_of_lines = $ending_line - $beginning_line;
			$code = array_slice($response->content_lines, $beginning_line, $numbers_of_lines); 

			// Remove Whitesapce
			if($lines){
				$code = $this->remove_whitespace_at_beggining($code);
			}

			// Parse Document
			$html  = "<pre>";
			$html .= "	<code>";
			$html .= implode("\n<!-- -->", $code);
			$html .= "	  </code>";
			$html .= "</pre>";
		}
		else {
			return "<!-- JSJ Code Highlight Github Addon : The API server returned an error -->";
		}
		return $html;
	}

	/**
	 * Remove Extra whitesapce at begginning of lines
	 *
	 * @return string
	 */
	public function remove_whitespace_at_beggining($code){
		// Remove Extra Tabs/Spaces from code 
		$first_line = str_split($code[0]); 
		$first_line_char = $first_line[0];
		// Check if first char in first line is tab or space
		if(ctype_space($first_line_char)){
			$min_indentation = 999; 
			// Get Minimum Whitespace
			foreach($code as $line){
				if($line != "" && !ctype_space($line)){
					$pattern = '/^[ '. $first_line_char .' ]*/';
					preg_match($pattern, $line, $matches, PREG_OFFSET_CAPTURE);
					$min_indentation = min($min_indentation, strlen($matches[0][0]));
				}
			}
			// Remove whitespace from string
			foreach($code as $key => $line){
				$pattern = '/^[ '. $first_line_char .' ]{0,' . $min_indentation . '}/';
				$code[$key] = preg_replace($pattern, '', $line);
			}
		}
		return $code;
	}

}

?>