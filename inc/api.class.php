<?php

class Notify_API {
	private $api_url = "https://ntfy.jp/api/";
// 	private $api_url = "http://localhost:3000/api/";
	private $api_version = "v1";
	public $notification_token;

	public function __construct(){
		$ops = Notify_Plugin::get_options();
		$this->notification_token = $ops -> notification_token;
	}

	public function get_notify_send_message_link() {
		// /api/v1/sender/notifications/messages/create
		return $this->api_url . $this->api_version . '/sender/notifications/messages/create';
	}
	
	public function get_notify_embed_link() {
		// /api/v1/sender/notifications/get_embed
		return $this->api_url . $this->api_version . '/sender/notifications/get_embed';
	}

	public function publish_post($msg){
		//if($msg == "") $msg = "(no title)";
		$url  = $this->get_notify_send_message_link();
		$url .= "?title=".urlencode($msg);
		$url .= "&token=".$this->notification_token;
		$result = json_decode(Notify_Plugin::make_request($url));
		return $result;
	}
	
	public function get_embed($token){
		$url  = $this->get_notify_embed_link();
		$url .= "?token=".$token;
		$result = json_decode(Notify_Plugin::make_request($url));
		return @$result->result->embed;
	}

}

?>