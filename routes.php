<?php
kirby()->routes([
	[
		'pattern' => ['plugin.recaptcha'],
		'method' => 'POST',
		'action' => function() {
			new recaptcha();
		}
	]
]);