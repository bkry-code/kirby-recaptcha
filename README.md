# Kirby reCaptcha

*Version 0.1*

## Install

Add the folder `recaptcha` into `/site/plugins/`.

## Setup

### 1. Register reCaptcha

Go to [recaptcha](https://www.google.com/recaptcha/admin) and register the domain you want to use.

### 2. Setup config

You need to add `secret` and `sitekey` to your config.

```php
c::set('plugin.recaptcha.secret', 'your-secret');
c::set('plugin.recaptcha.sitekey', 'your-sitekey');
```

### 3. Add javascript files

Add this to your `footer.php` snippet:

```php
<?php
	echo js('/assets/plugins/recaptcha/js/script.js');
	echo js('https://www.google.com/recaptcha/api.js?hl=sv');
?>
```

It should be placed just before `</body>`.

1. The first file is a setup for this Kirby plugin.
2. The second file is a setup from Google.

The `sv` language code can be changed to [your language](https://developers.google.com/recaptcha/docs/language).

## Usage

### 1. Add a html form

Make sure it uses `method` that is `post`.

```html
<form class="my-form" action="" method="post">
	<label for="name">Name:</label>
	<input name="name">

	<div class="g-recaptcha" data-sitekey="<?php echo c::get('plugin.recaptcha.sitekey'); ?>"></div>

	<input type="submit" value="Submit" />
</form>
```

### 2. Add your javascript callback

This script is to run the reCaptcha.

```html
<script>
document.addEventListener("DOMContentLoaded", function() {
	recaptcha.init({
		url: '<?php echo u(); ?>',
		selector: '.my-form',
		callback: function(xhr) {
			console.log(xhr);
		}
	});
});
</script>
```

**Url**

To make the javascript know about the route you need to send the root url.

**Selector**

Your selector to the html `<form class=".my-form">`.

**Callback**

The callback can be used to get the `xhr` object. From that you can see if the captcha was successful or not.

### 3. Add your PHP callback

The PHP callback will make you do something when the captcha is successful. You have access to `$post` and `$response`.

```php
function recaptchaCallback( $post, $response) {
	print_r( $post );
	print_r( $response );

	if( $post['g-recaptcha-selector'] == '.my-form') {
		// Some function call
	}
}
```

- The function name has to be `recaptchaCallback`.
- In the `$post` the selector is included as well, called `g-recaptcha-selector`.

## Requirements

- Kirby 2.3
- Google Account

## License

Kirby Recaptcha - MIT
Google Recaptcha - [BSD](http://github.com/google/recaptcha/blob/master/LICENSE)