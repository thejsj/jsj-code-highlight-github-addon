<?php 

	// Start Setting Options

	$jsj_code_highlight_options = Array();

	$jsj_code_highlight_options['github_username'] = (object) array(
		'name' => 'github_username', 
		'title' => __( 'Github Username', 'jsj_code_highlight' ),
		'descp' => __( 'Enter your github user name you wish to associate with this account.', 'jsj_code_highlight' ),
		'type' => 'text',
		'default' => ''
	);

	$jsj_code_highlight_options['github_addon_api_key'] = (object) array(
		'name' => 'github_addon_api_key', 
		'title' => __( 'Add-on License Key', 'jsj_code_highlight' ),
		'descp' => __( 'Add the license key you received upon purchasing this plugin.', 'jsj_code_highlight' ),
		'type' => 'text',
		'default' => ''
	);


?>