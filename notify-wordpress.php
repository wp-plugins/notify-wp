<?php
/*
Plugin Name: notify.jp for WordPress
Plugin URI: http://wordpress.org/plugins/notify-wordpress/
Description: notify.jp for WordPress.
Author: Shunsuke Hayashi
Version: 1.0.0
Author URI: https://ntfy.jp/
*/

//ini_set( 'display_errors', 1 );

require_once("inc/api.class.php");
require_once("inc/plugin.class.php");
function activate()
{
	$plugin = new Notify_Plugin();
}
global $notify_plugin;
$notify_plugin = new Notify_Plugin();

?>
