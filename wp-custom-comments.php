<?php
/*
Plugin Name: WP Custom Comments
Plugin URI: http://riselab.ru/
Text Domain: wp-custom-comments
Description: The plugin allows you to replace the standard WordPress commenting form with custom HTML/JavaScript code, such as one of the popular commenting system's widget (Disqus, HyperComments) or with the VKontakte comments widget.
Version: 1.0.1
Author: Vadim Alekseyenko
Author URI: http://riselab.ru/
*/

/**
* Plugin abstract class
*/
class WPCustomComments
{

	// Init function
	public static function Init()
	{
		// Loading plugin's text domain
		add_action('plugins_loaded', Array(get_called_class(), 'LoadTextDomain'));

		// Adding settings page to the administration panel menu
		if (defined('ABSPATH') && is_admin()){
			add_action('admin_menu', Array(get_called_class(), 'SettingsMenu'));
		}

		// Replacing default commenting form
		add_filter('comments_template', Array(get_called_class(), 'GetCommentsTemplate'));
	}

	// Get settings page content function
	public static function SettingsPage()
	{
		// Changing commenting form template on save
		if (isset($_POST['submit'])){
			file_put_contents(self::GetCommentsTemplate(), stripslashes($_POST['commentsTemplate']));
		}
		// Show settings form
		echo '
			<div class="wrap">
				<h1>' . get_admin_page_title() . '</h1>
					<p>' . __('Enter HTML/JavaScript code to replace the standard WordPress commenting form:', 'wp-custom-comments') . '</p>
					<form action="" method="post">
						<textarea name="commentsTemplate" style="height: 340px; width: 100%;">' . file_get_contents(self::GetCommentsTemplate()) . '</textarea>
		';
		submit_button();
		echo '
				</form>
			</div>
		';
	}

	// Add settings page to the administration panel menu function
	public static function SettingsMenu()
	{
		// Checking permissions
		if (!current_user_can('manage_options')){
			return;
		}
		add_submenu_page('edit-comments.php', 'WP Custom Comments', 'WP Custom Comments', 'manage_options', 'wp-custom-comments', array(get_called_class(), 'SettingsPage'));
	}

	// Get comments template file path function
	public static function GetCommentsTemplate()
	{
		return plugin_dir_path(__FILE__) . 'comments-template.php';
	}

	// Load plugin's text domain function
	public static function LoadTextDomain()
	{
		load_plugin_textdomain('wp-custom-comments');
	}

}

// Plugin initialization
WPCustomComments::Init();
