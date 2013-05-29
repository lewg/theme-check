<?php
include_once('cli/cli-includes.php');

# Mock some WordPress functions
function do_action() {
	return(false);
}

function add_action() {
    return(false);
}

// function wp_allowed_protocols() {
//     return array( 'http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet', 'mms', 'rtsp', 'svn', 'tel', 'fax', 'xmpp' );
// }

// function get_option() {
//     return(false);
// }

function apply_filters($action, $value) {
    return($value);
}

// function wp_cache_add_non_persistent_groups() {
//     return(false);
// }

// Get the version of this plugin for use in output
function get_plugins() {
	$plugins = array();
	$theme_check_plugin = file_get_contents('theme-check.php');
	preg_match('/^Version: (.+)$/m', $theme_check_plugin, $version_matches);
	$plugins['theme-check.php']['Version'] = $version_matches[1];
	return($plugins);
}

// function __($string) {
// 	return($string);
// }

// function _x($string) {
//     return($string);
// }

function is_multisite() { return(false); }



# Check a theme at a given path
function get_theme_root($theme) {
	global $arguments;
	$relative_path = $arguments['folder'];
	$path = realpath($relative_path);
	return $path;
}


include 'checkbase.php';
include 'main.php';


$usage_string = "Usage: cli.php --wordpress=/path/to/wordpress --folder=/path/to/themes theme_folder";
global $arguments;
$arguments = parseArgs($argv);

/* Check for arguments */

if (array_key_exists('wordpress', $arguments)) {
    $wp_path = realpath($arguments['wordpress']);
} else {
    die("Missing WordPress path.\n\n$usage_string");
}

if (!array_key_exists('folder', $arguments)) {
    die("Missing theme folder path.\n\n$usage_string");
} 

if (!array_key_exists(0, $arguments)) {
    die("Missing Theme Folder\n\n$usage_string");
}

define('ABSPATH', $wp_path);
define('WPINC', '/wp-includes');
define('WP_SETUP_CONFIG', true);
define('WP_DEBUG', true);
define('WP_CONTENT_DIR', '.');


# WordPress Codebase Includes


include($wp_path . '/wp-includes/cache.php');
include($wp_path . '/wp-includes/functions.php');
include($wp_path . '/wp-includes/formatting.php');
include($wp_path . '/wp-includes/kses.php');
include($wp_path . '/wp-includes/class-wp-theme.php');
include($wp_path . '/wp-includes/pomo/translations.php');
include($wp_path . '/wp-includes/l10n.php');


global $wp_object_cache;
$wp_object_cache = new WP_Object_Cache;

# Wrap output Bootstrap CSS template
echo '<!DOCTYPE html>
<html><head>
<title>WordPress Theme Test</title>
<style type="text/css">';
echo file_get_contents(dirname(__FILE__) . '/css/bootstrap.min.css');
echo file_get_contents(dirname(__FILE__) . '/css/bootstrap-responsive.min.css');
echo file_get_contents('style.css'); 
echo '</style></head><body><div class="container">';

check_main($arguments[0]);

echo '</div><script type="text/javascript">';
echo file_get_contents("js/bootstrap.min.js");
echo '</script></body></html>';

exit(0);