<?php

class Notify_Plugin {
	private $api;
	private $page_hook = 'settings_page_notify-for-wordpress';

	public $errors = null;
	public $update = false;

	public function __construct() {
		add_action('admin_menu', array($this, 'register_menu_page'));
		$this -> api = new Notify_API();
		$this -> register_scripts();
		$this -> register_hooks();

		add_action('admin_init', array($this, 'http_requests'));
	}

	public function register_menu_page() {
		add_options_page("notify", "notify", "manage_options", "notify-for-wordpress", array($this, 'load_page'));
	}

	public function publish_post_hook($strNewStatus, $strOldStatus, $post) {
		
		if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)) {
			return;
		}
		
		$post_type = $post -> post_type;
		// $post_type "page":"固定ページ", "post":"投稿"

		if (($strOldStatus == 'pending' || $strOldStatus == 'future' || $strOldStatus == 'private' || $strOldStatus == 'draft' || $strOldStatus == 'auto-draft' || $strOldStatus == 'new') && $strNewStatus == 'publish') :
			// New post/page published
			$hooks = $this -> get_options();

			if( ( $hooks -> notification_type_post && $post_type == "post") 
				|| ( $hooks -> notification_type_page && $post_type == "page") ) {
	
				$msg = $hooks -> notification_message;
				$msg = str_replace("%title%", $post -> post_title, $msg);
// 				$msg = str_replace("%url%", get_permalink($post -> ID), $msg);
				$msg = str_replace("%url%", wp_get_shortlink($post -> ID), $msg);
				$this -> api -> publish_post($msg);
			
			}
			
		endif;
	}

	public function register_hooks() {
		$hooks = $this -> get_options();
		if (is_object($hooks)) :
			// Using same hook func for post and page actions.
			add_action('transition_post_status', array($this, 'publish_post_hook'), 10, 3);
		endif;
	}

	public function register_options($ops) {
		if (is_array($ops))
			$ops = json_encode($ops);
		update_option('notify_options', $ops);
	}

	public function get_options() {
		$ops = get_option('notify_options');
		if (!$ops) {
			add_option('notify_options', json_encode(array()));
			$ops = get_option('notify_options');
		}
		$ops_decoded = json_decode($ops);
		if (is_null($ops_decoded))
			return $ops;
		else
			return $ops_decoded;
	}

	public function register_scripts() {
		add_action('admin_print_styles-' . $this -> page_hook, array($this, 'notify_plugin_admin_styles'));
	}

	public function notify_plugin_admin_styles() {
		wp_enqueue_style('notify-opensans-css', 'http://fonts.googleapis.com/css?family=Open+Sans:400,700,300');
		wp_enqueue_style('notify-style-css', plugins_url('css/style.css', dirname(__FILE__)));
	}

	public function http_requests() {
		if ($_GET["page"] == "notify-for-wordpress" && isset($_POST["notify_options_submit"])) {
			if (!$this -> valid_notify_options_submit()) {
				return;
			}else{
				$_POST["notification_embed"] = $this -> api -> get_embed($_POST["notification_token"]);
				$this -> register_options($_POST);
				$this -> update = true;
			}
		}
	}

	public function valid_notify_options_submit() {
		if (empty($_POST["notification_message"])) {
			$this -> errors["notification_message"] = "通知メッセージを入力してください";
		}
		if (empty($this -> errors)) {
			return true;
		}
		return false;
	}

	public static function make_request($url) {
		if (function_exists('curl_version')) :
			$CURL = curl_init();
			curl_setopt($CURL, CURLOPT_URL, $url);
			curl_setopt($CURL, CURLOPT_HEADER, 0);
			curl_setopt($CURL, CURLOPT_RETURNTRANSFER, 1);
			$data = curl_exec($CURL);
			curl_close($CURL);
			return $data;
		else :
			return file_get_contents($url);
		endif;
	}

	public function getApi() {
		return $this -> api;
	}

	public function getErrors() {
		return $this -> errors;
	}

	public function getUpdate() {
		return $this -> update;
	}

	public function getVersion() {
		return "1.1.1";
	}

	public function load_page() {
		require_once dirname(__FILE__) . '/../template/index.php';
	}

}
?>