<?php
/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
// $app = new \Slim\Slim();

//With custom settings
$app = new \Slim\Slim(array(
    'log.enable' => true,
    'log.path' => './logs',
    'log.level' => 4,
));

/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */
require_once('includes/mongo.php');
 
//Connect to Mongo database
$connection = new MongoClient("mongodb://alexfaber:lost@linus.mongohq.com:10089/LF");
$db = $connection->LF;


// GET route
$app->get('/', function() use ($app, $db){
	$embed = "<h1>Welcome</h1>";
	$collection = $db->Lost;
	$cursor = $collection->find();
	$app->render('main.php', array('embed' => $embed, 'cursor' => $cursor));
});

$app->get('/report-lost', function() use ($app){
	
});

$app->run();
?>