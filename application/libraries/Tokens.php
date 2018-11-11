<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tokens {
	protected $CI;
	protected $config_vars;
	protected $url = '';
	protected $userpwd = '';
	
	public function __construct() {
		$this->CI = & get_instance();
		
		// Load config
		$this->CI->config->load('tokens');
		$this->config_vars = $this->CI->config->item('tokens');
		$this->url = $this->config_vars['token_api_url'];
		$user = $this->config_vars['token_api_user'];
		$pwd = $this->config_vars['token_api_passwd'];
		$this->userpwd = "$user:$pwd";
	}
	
	private function parse_data($data) {
		if (!$data)
			throw new Exception('Token API offline');
		$obj = json_decode($data);
		if (!$obj)
			throw new Exception('Invalid API response');
		else if ($obj->error != '')
			throw new Exception($obj->error);
		return $obj;
	}
	
	private function api_get($id) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"{$this->url}?token_id=$id");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $this->userpwd);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		$data = curl_exec($ch);
		curl_close($ch);
		return $this->parse_data($data);
	}
	
	private function api_post($id, $tokens) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "token_id=$id&tokens=$tokens");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $this->userpwd);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		$data = curl_exec($ch);
		curl_close($ch);
		return $this->parse_data($data);
	}
	
	public function get_tokens($id) {
		$obj = $this->api_get($id);
		return $obj->tokens;
	}
	
	public function withdraw($id, $tokens) {
		$obj = $this->api_post($id, -$tokens);
		return $obj->tokens->total;
	}
	
	public function deposit($id, $tokens) {
		$obj = $this->api_post($id, $tokens);
		return $obj->tokens->total;
	}
}
