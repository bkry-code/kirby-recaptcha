<?php
namespace JensTornell\Recaptcha;
use JensTornell\Recaptcha as Recaptcha;
use c;

class Core {
	function __construct() {
		$this->prefix = 'plugin.recaptcha';
		$this->post = kirby()->request()->data();
		$this->secret = c::get( $this->prefix . '.secret');
		$this->recaptcha = new \ReCaptcha\ReCaptcha( $this->secret );

		echo $this->callback();
	}

	// Ip
	function ip() {
		return c::get( $this->prefix . '.ip', $_SERVER['REMOTE_ADDR'] );
	}

	// Recaptcha response
	function recaptchaResponse() {
		if( ! empty( $this->post['g-recaptcha-response'] ) ) {
			return $this->post['g-recaptcha-response'];
		}
	}

	// Response object
	function responseObject() {
		return $this->recaptcha->verify( $this->recaptchaResponse(), $this->ip() );
	}

	// Response
	function response() {
		if( $this->responseObject()->isSuccess() ) {
			return ['success' => true];
		}
		return ['success' => false, 'message' => $this->responseObject()->getErrorCodes()];
	}

	// Callback
	function callback() {
		return call_user_func_array("recaptchaCallback", [
			'post' => $this->post,
			'response' => $this->response(),
		]);
	}
}