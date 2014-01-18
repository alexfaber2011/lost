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

$app->get('/report-lost', function () use ($app){
	$app->render('insert.php');
});

$app->get('/report-found', function () use ($app){
	$app->render('insert.php', array('output' => "Found"));
});

$app->post('/report', function () use ($app, $db){
	$can_update = false;
	if(filter_has_var(INPUT_POST, "name") && $_POST["name"] != ""){
		$name = $_POST["name"];
		
		if(filter_has_var(INPUT_POST, "item") && $_POST["item"] != ""){
			$item = $_POST["item"];
				
				if(filter_has_var(INPUT_POST, "description") && $_POST["description"] != ""){
					$description = $_POST["description"];
					
					if(filter_has_var(INPUT_POST, "tags") && $_POST["tags"] != ""){
						$tags = $_POST["tags"];
						$tags = explode(',' , $tags);
						
						if(filter_has_var(INPUT_POST, "location_found") && $_POST["location_found"] != ""){
							$location = $_POST["location_found"];
							$isFound = true;
							$can_update = true;
						}
						elseif(filter_has_var(INPUT_POST, "location") && $_POST["location"] != ""){
							$location = $_POST["location"];
							$isFound = false;
							$can_update = true;
						}
					}
				}
		}
	}
	if($can_update){
		$date = date('M d Y');
		if($isFound){
			$document = array("Date Created" => $date, "Item" => $item , "Found_Location" =>  $location, "User" => $name, "Matched" => 0, "Tags" => $tags, "Description" => $description, "Match_id" => "");
			$db->Found->insert($document);
		}
		else{
			$document = array("Date Created" => $date, "Item" => $item , "Location" =>  $location, "User" => $name, "Matched" => 0, "Tags" => $tags, "Description" => $description, "Match_id" => "");
			$db->Lost->insert($document);
		}
		$output = "You have successfully added an item";
		$app->render('main.php', array('output' => $output));
	}
	else{
		$output = "Update not sucessful.  :(";
		$app->render('main.php', array('output' => $output));
	}
	
});

$app->get('/login', function() use($app){
	$app->render('login.php');
});

$app->run();
?>