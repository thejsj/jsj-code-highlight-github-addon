=== JSJ Code Highlight - GitHub Add-On ===
Contributors: jorge.silva
Donate link: 
Tags: code, highlight, syntax, highlighter, prettify, highlighting, markup, formatting, snippet, color, source, jsj
Requires at least: 3.4
Tested up to: 3.8
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This Add-On for JSJ Code Highlight, gives you the ability to add GitHub code snippets directly from GitHub.com

== Description ==

Pull code directly from GitHub into your WordPress site with this Add-On for JSJ Code Highlight. Using this plugin you can show gists and files from public repos right on your site, with only a one line shortcode. You can also pull in only a portion of the file by adding the specific lines of the file you wish to include. 

= Pull a Gist =

Just specify the type="gist" and the gist id:

[code type="gist" id="8126749"]

If you want to show only a couple of lines, specify which lines you wish to include: 

[code type="gist" id="8126749" lines="30-48"]

= Pull a File from a repo =

You can also speficy a file in a repo, like this:

[code type="repo"  repo="foundation"  path="gruntfile.js" ]

Or specify a file deeper in the repo: 

[code type="repo"  repo="clickOnJorge"  path="wp-content/themes/base/js/ss-game.js" ]

Like with gists, you can also specify line numbers: 

[code type="repo"  repo="ThreeJsExperiments"  path="index.php" lines=2-12 ]

== Installation ==

1. Upload the entire jsj-code-hightlight-github-addon folder to the /wp-content/plugins/ directory or simply use the Plugin upload interface in the admin.
2. Activate the plugin through the ‘Plugins’ menu in WordPress. 
3. Go to Settings > JSJ Code Highlight > Github Settings and follow the instructions to set up your account. 

== Frequently asked questions ==

If you have any questions, email me at jorge dot silva at thejsj dot com.

== Changelog ==

= 1.0 =
Added ability to pull gists and files form repo
Added ability to connect with GitHub API
Added ability to limit query by line numbers
Added user check when loading plugin
Added options panel 

== Upgrade notice ==

= 1.0 =
Be sure to register your account in the Plugin page



