<?php

require '../vendor/autoload.php';

$app = new \Slim\Slim(array(
	'templates.path' => '../app/templates/'
));

$app->add(new \Slim\Middleware\SessionCookie());

$app->get('/', function () use ($app) {

	$app->render('index.php');

});

$app->post('/download', function () use ($app) {

	$url = $app->request->post('url');

	$valid_url = filter_var($url, FILTER_VALIDATE_URL);

	$token = $app->request->post('token');

	$token = filter_var($token, FILTER_SANITIZE_STRING);

	$username = $app->request->post('username');

	$username = filter_var($username, FILTER_SANITIZE_STRING);

	$password = $app->request->post('password');

	$password = filter_var($password, FILTER_SANITIZE_STRING);

	$user_agent = $app->request->post('user-agent');

	$user_agent = filter_var($user_agent, FILTER_SANITIZE_STRING);

	$ignored_domains = is_array($app->request->post('ignored-domains')) ? $app->request->post('ignored-domains') : array();

	$ignored_domains = filter_var_array($ignored_domains, FILTER_SANITIZE_STRING);

	if ($valid_url && $token) {

		set_time_limit(0);

		$location = '../app/tmp/' . $_SERVER['UNIQUE_ID'] . '/';

		$settings = array('location' => $location);

		if (isset($user_agent)) {

			$settings['userAgent'] = $user_agent;

		}

		if (isset($ignored_domains)) {

			$settings['excludedDomains'] = $ignored_domains;

		}

		$Colligo = new \Colligo\Colligo($settings);

		if (isset($username) && isset($password)) {

			$Colligo->download($url, $username, $password);

		} else {

			$Colligo->download($url);

		}

		if (class_exists('tidy')) {

			$html = \Reconcilio\Reconcilio::repairFile($Colligo->settings['location'] . $Colligo->settings['htmlFilename'], true);

			file_put_contents($Colligo->settings['location'] . $Colligo->settings['htmlFilename'], $html);

		}

		$filename = parse_url($url, PHP_URL_HOST) . '.zip';

		\Utilitas\Utilitas::zip($location, $location . $filename);

		$app->response->headers->set('Content-Type', 'application/zip');
		$app->response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
		$app->response->headers->set('Content-Length', filesize($location . $filename));

		readfile($location . $filename);

		$app->setCookie('token', $token);

		\Utilitas\Utilitas::rmtree($location);

	} else {

		$app->flash('error', 'url');

		$app->response->redirect('./');

	}

});

$app->run();
