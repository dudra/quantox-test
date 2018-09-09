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
	return 'Home';
});

$router->get('/get', function () {
	return EventController::getEvents();
});

$router->post('/data', function($request) {
	return EventController::saveEvent($request);
});