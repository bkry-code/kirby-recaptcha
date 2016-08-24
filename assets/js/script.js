var recaptcha = (function () {
	var fn = {};
	var input;
	var selector;
	var form;
	var route;

	// Init
	fn.init = function(input_data) {
		input = input_data;
		selector = fn.selector();
		form = fn.form();
		route = fn.route();

		fn.eventSubmit();
	};

	// Event submit - Load ajax
	fn.eventSubmit = function() {
		fn.form().onsubmit = function(e) {
			e.preventDefault();
			fn.ajax( fn.formToArray() );
		}
	};

	// Get selector
	fn.selector = function() {
		return input.selector;
	};

	// Get form object
	fn.form = function() {
		return document.querySelector(selector);
	};

	// Get route url
	fn.route = function() {
		return input.url + '/plugin.recaptcha';
	};

	// Convert form to array
	fn.formToArray = function() {
		var form_data = new FormData( document.querySelector(selector) );
		var form_array = [];

		for(var pair of form_data.entries()) {
			form_array[pair[0]] = pair[1];
		}
		return form_array;
	};

	// Convert data to url
	fn.dataToUrl = function(post_data) {
		var data = '';
		
		for(var key in post_data) {
			if(typeof post_data[key] !== 'function') {
				data += '&' + key + '=' + encodeURIComponent( post_data[key] );
			}
		}
		return data.substring(1);
	};

	// Call ajax
	fn.ajax = function(post_data) {
		var url = route + '?' + fn.dataToUrl(post_data) + '&g-recaptcha-selector=' + encodeURIComponent(selector);

		xhr = new XMLHttpRequest();
		xhr.open('POST', url );
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.onload = function() {
			input.callback(xhr);
		};
		xhr.send();
	};

	return fn;
})();