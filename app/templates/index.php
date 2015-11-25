<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<title>Imitor</title>

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css">
	<link rel="stylesheet" href="https://select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css">

	<style>
		.select2-container .select2-search__field:not([placeholder = '']) {
			width: 100% !important;
		}
		header {
			margin-bottom: 40px;
			padding-bottom: 60px;
			padding-top: 60px;
			background-color: #0275d8;
			color: #FFFFFF;
			text-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
		}
		footer {
			margin-top: 100px;
			border-top: 1px solid #CCCCCC;
			padding-bottom: 40px;
			padding-top: 40px;
			text-align: center;
		}
	</style>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.14.0/jquery.validate.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.0.4/js.cookie.min.js"></script>

	<script>

	$.validator.setDefaults({
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.form-group').removeClass('has-success');
			$(element).parents('.form-group').addClass('has-error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.form-group').removeClass('has-error');
			$(element).parents('.form-group').addClass('has-success');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function(error, element) {
			if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		}
	});

	$(document).ready(function() {

		if (Cookies.set('cookie-test', '1')) {
			Cookies.remove('cookie-test');
			$('#cookies-alert').alert('close');
			$('input, select, button').prop('disabled', false);
		} else {
			$('#cookies-alert').removeClass('hidden');
		}

		$('form').on('submit', function(event) {
			if ($('form').valid()) {
				var token = new Date().getTime();
				$('#token').val(token);
				$('button[type="submit"]').button('loading');
				var tokenCheck = window.setInterval(function () {
					var cookieValue = Cookies.get('token');
					if (cookieValue == token) {
						window.clearInterval(tokenCheck);
						Cookies.remove('token');
						$('button[type="submit"]').button('reset');
					}
				}, 1000);
			}
		});

		$('form').validate();

		$('select').select2({
			theme: 'bootstrap',
			width: 'off',
			tags: true,
			tokenSeparators: [',', ' '],
			placeholder: 'Ignored Domains'
		});

		$('select').on('select2:close', function(e) {
			$(this).valid();
		});

	});

	</script>

</head>
<body>

	<header role="banner">
		<div class="container">
			<h1>Imitor</h1>
			<p>An interactive tool for downloading a web page and all its resources.</p>
		</div>
	</header>

	<div class="container">

		<div class="row">

			<div class="col-xs-12" role="main">

				<noscript>
					<div class="alert alert-danger" role="alert">
						<span>JavaScript is disabled in your web browser. JavaScript is required use this tool. Here are the <a class="alert-link" href="http://enable-javascript.com/" target="_blank">instructions how to enable JavaScript in your web browser</a>.</span>
					</div>
				</noscript>

				<div id="cookies-alert" class="alert alert-danger hidden" role="alert">
					<span>Cookies are disabled in your web browser. Cookies must be enabled to use this tool. Here are the <a class="alert-link" href="https://support.google.com/accounts/answer/61416" target="_blank">instructions how to enable Cookies in your web browser</a>.</span>
				</div>

				<?php if ($flash['error'] === 'url'): ?>
				<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						<span class="sr-only">Close</span>
					</button>
					<span>The URL you attempted to use is invalid.</span>
				</div>
				<?php endif; ?>

				<form class="clearfix" method="post" action="download">

					<div class="form-group">
						<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="fa fa-fw fa-link"></i></span>
							<input type="url" class="form-control" name="url" placeholder="URL" required disabled>
						</div>
					</div>

					<div id="options" class="collapse">

						<div class="form-group">
							<div class="input-group input-group-lg">
								<span class="input-group-addon"><i class="fa fa-fw fa-user"></i></span>
								<input type="text" class="form-control" name="username" placeholder="Username">
							</div>
						</div>

						<div class="form-group">
							<div class="input-group input-group-lg">
								<span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
								<input type="password" class="form-control" name="password" placeholder="Password">
							</div>
						</div>

						<div class="form-group">
							<div class="input-group input-group-lg">
								<span class="input-group-addon"><i class="fa fa-fw fa-globe"></i></span>
								<input type="text" class="form-control" name="user-agent" placeholder="User Agent">
							</div>
						</div>

						<div class="form-group">
							<div class="input-group input-group-lg select2-bootstrap-prepend">
								<span class="input-group-addon"><i class="fa fa-fw fa-times"></i></span>
								<select class="form-control select2-multiple" name="ignored-domains[]" multiple>
									<optgroup label="Popular">
										<option value="fonts.googleapis.com" selected>fonts.googleapis.com</option>
										<option value="maps.googleapis.com" selected>maps.googleapis.com</option>
										<option value="fast.fonts.com" selected>fast.fonts.com</option>
										<option value="use.typekit.net" selected>use.typekit.net</option>
									</optgroup>
								</select>
							</div>
						</div>

					</div>

					<div class="row">

						<div class="col-md-6">
							<div class="form-group">
								<button class="btn btn-secondary btn-lg btn-block" type="button" data-toggle="collapse" data-target="#options" aria-expanded="false" aria-controls="options" disabled>Options</button>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<input id="token" type="hidden" name="token" value="">
								<button class="btn btn-primary btn-lg btn-block" type="submit" data-loading-text="Cloning..." disabled>Clone</button>
							</div>
						</div>

					</div>

				</form>

			</div>

		</div>
	</div>

	<footer role="contentinfo">
		<div class="container">
			<p>Built and maintained by: <a href="https://github.com/tristanmills">Tristan Mills</a>.</p>
		</div>
	</footer>

</body>
</html>