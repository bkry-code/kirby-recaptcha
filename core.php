<?php
class recaptcha {
	private $prefix;
	private $secret;
	private $ip;
	private $recaptcha_response;
	private $post;
	private $response;
	private $ajax_status;

	function __construct() {
		$this->prefix = 'plugin.recaptcha';
		$this->secret = $this->secret();
		$this->post = $this->post();
		$this->recaptcha_response = $this->recaptchaResponse();
		$this->recaptcha = $this->recaptcha();
		$this->response = $this->response();
		$this->ajax_status = $this->ajaxStatus();

		$this->callback();
		echo $this->ajaxResponse();
	}

	function secret() {
		return c::get($this->prefix . '.secret');
	}

	function ip() {
		return c::get( $this->prefix . '.ip', $_SERVER['REMOTE_ADDR'] );
	}

	function post() {
		return kirby()->request()->data();
	}

	function recaptchaResponse() {
		if( ! empty( $this->post['g-recaptcha-response'] ) ) {
			return $this->post['g-recaptcha-response'];
		}
	}

	function recaptcha() {
		return new \ReCaptcha\ReCaptcha($this->secret);
	}

	function response() {
		return $this->recaptcha->verify( $this->recaptcha_response, $this->ip );
	}

	function ajaxStatus() {
		if( $this->response->isSuccess() ) {
			return ['success' => true];
		}
		$errors = $this->response->getErrorCodes();
		return ['success' => false, 'message' => $errors];
	}

	function callback() {
		call_user_func_array("recaptchaCallback", [
			'post' => $this->post,
			'response' => $this->ajax_status,
		]);
	}

	function ajaxResponse() {
		return json_encode($this->ajax_status);
	}
}