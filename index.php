<?php
/**
 * Created by PhpStorm.
 * User: dulo
 * Date: 9/8/18
 * Time: 6:49 PM
 */
include_once 'Request/Request.php';
include_once 'Router/Router.php';
include_once 'Controllers/EventController.php';

$router = new Router(new Request);

$router->get('/', function() {
	return <<<HTML
  <h1>Home</h1>
HTML;
});

$router->get('/get', EventController::getEvents());

$router->get('/profile', function($request) {
	return <<<HTML
  <h1>Profile</h1>
HTML;
});

$router->post('/data', function($request) {
	return json_encode($request->getBody());
});