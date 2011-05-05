<?php

use Symfony\Component\BrowserKit\Response;

use Roadrunner\Model\Item;
use Roadrunner\Model\Container;
use Roadrunner\Controller\ItemController;

/**
 * Root controller
 */
$app->get('/', array(new ItemController($app), 'executeIndex'));
$app->get('/test', array(new ItemController($app), 'executeTest'));
/**
 * Add item controller
 */
$app->get('/item/add', array(new ItemController($app), 'executeAdd'));

/**
 * Add item controller
 */
$app->get('/item/list', array(new ItemController($app), 'executeList'));

/**
 * Create item controller
 */ 
$app->post('/item/create', array(new ItemController($app), 'executeCreate'));

/**
 * Error controller
 */
$app->error(function(Exception $e) use ($app) {
	if ($e instanceof NotFoundHttpException) {
		return new Response('What you are looking for does not exist', 404);
	}
	
	$app['log']->addError(json_encode(array(
		'class'   => get_class($e),
		'message' => $e->getMessage(),
		'code'    => $e->getCode(),
	)));
	
	return new Response('Something bad happend.', 500);
});

/**
 * After controller
 */ 
$app->after(function() {	
});

$app->run();