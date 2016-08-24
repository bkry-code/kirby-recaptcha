<?php
use JensTornell\Recaptcha as Recaptcha;

kirby()->routes([
	[
		'pattern' => ['plugin.recaptcha'],
		'method' => 'POST',
		'action' => function() {
			new Recaptcha\Core();
		}
	]
]);