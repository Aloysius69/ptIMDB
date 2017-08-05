<?php
/*
Plugin Name: ptimdb
Plugin URI: http://www.alakhnor.com/post-thumb
Description: This plugin adds a button in the editor to search for IMDb movie title & id.
Author: Alakhnor
Version: 1.00
Author URI: http://www.alakhnor.com/post-thumb

Copyright 2007  Alakhnor (email : alakhnor@wanadoo.fr)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// define URL
define('PTIMDB_URLPATH', get_settings('siteurl').'/wp-content/plugins/' . dirname(plugin_basename(__FILE__)).'/');
define('PTIMDB_ABSPATH', ABSPATH.'wp-content/plugins/' . dirname(plugin_basename(__FILE__)).'/');


$PTIMDb = new ptimdb();

class ptimdb {

	function ptimdb() {
	
		// Load language file
		$locale = get_locale();
		if ( !empty($locale) )
			load_textdomain('ptimdb',str_replace( "\\", "/",ABSPATH).'wp-content/plugins/ptimdb/languages/ptimdb'.$locale.'.mo');
	

		// Load admin functions if call is in admin panel, otherwise load plugin
		if (is_admin()) {
			add_action('admin_menu', array($this, 'add_ptimdb'));
		}
	}
	/***********************************************************************************/
	/* Insert table menu
	/***********************************************************************************/
	function add_ptimdb() {

		add_action('edit_page_form', 'insert_ptimdb_script');
		add_action('edit_form_advanced', 'insert_ptimdb_script');

	}
} // End of main class


/***********************************************************************************/
/* Load the Script for the Button
/***********************************************************************************/
function insert_ptimdb_script() {

	echo "\n".'
		<script type="text/javascript">
			function pti_buttonscript()
        		{
				window.open("'.PTIMDB_URLPATH.'lib/ptimdb-button.php", "SelectPicture",  "width=600,height=320,scrollbars=no");
			}
		</script>';
	return;
}

/***********************************************************************************/
/* Editor functions
/***********************************************************************************/

add_action('init', 'pti_addbuttons');
/*
	Adds button to editor
*/
function pti_addbuttons() {

	global $wp_db_version, $buttonsnap;

	// Don't bother doing this stuff if the current user lacks permissions
	if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) return;
	
	// If WordPress 2.1+ (or WPMU?) and using TinyMCE, we need to insert the buttons differently
	// Thanks to Viper007bond and An-archos for the pioneer work
	if ( 3664 <= $wp_db_version && 'true' == get_user_option('rich_editing') ) {
	
	// add the button for wp21 in a new way
		add_filter("mce_plugins", "pti_button_plugin", 5);
		add_filter('mce_buttons', 'pti_button', 5);
		add_action('tinymce_before_init', 'pti_button_script');
	}
	else {
	 	$button_image_url = PTIMDB_URLPATH . 'javascript/ptimdb.gif';
		buttonsnap_separator();
		buttonsnap_jsbutton($button_image_url, __('Search IMDb', 'ptimdb'), 'pti_buttonscript();');
	}
}

if (is_admin()) include (PTIMDB_ABSPATH . 'lib/ptimdb-buttonsnap.php');


	/*
		Tell TinyMCE that there is a plugin (wp2.1)
	*/
	function pti_button_plugin($plugins) {
		array_push($plugins, "-ptimdb","bold");
		return $plugins;
	}

	/*
		Used to insert button in wordpress 2.1x editor
	*/
	function pti_button($buttons) {
		array_push($buttons, "separator", "ptimdb");
		return $buttons;
	}

	/*
		Load the TinyMCE plugin : editor_plugin.js (wp2.1)
	*/
	function pti_button_script() {
	 	$pluginURL =  PTIMDB_URLPATH.'javascript/';
		echo 'tinyMCE.loadPlugin("ptimdb", "'.$pluginURL.'");' . "\n";
		return;
	}


?>