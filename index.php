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
$retry = 3;
function getMongoClient() {
    try {
        return new Mongo("mongodb://alexfaber:lost@linus.mongohq.com:10089/LF");
    } catch(Exception $e) {
        /* Log the exception so we can look into why mongod failed later */
        //logException($e);
    }
    if ($retry > 0) {
        return getMongoClient($seeds, $options, --$retry);
    }
    throw new Exception("I've tried several times getting MongoClient.. Is mongod really running?");
}

//Connect to Mongo database
$connection = getMongoClient();
$db = $connection->LF;
 

// GET route
$app->get('/', function() use ($app, $db){
	$header = "<h1>Welcome</h1>";
	$output = "Hello, welcome to lost and found.  Refer to the left panel to begin.";
	$app->render('main.php', array('header' => $header, 'output' => $output));
	
});

$app->get('/report-lost', function () use ($app){
	$app->render('insert.php');
});

$app->get('/report-found', function () use ($app){
	$app->render('insert.php', array('output' => "Found"));
});

$app->post('/report', function () use ($app, $db){
	session_start();
	$email = $_SESSION['email'];
	$first_name = $_SESSION['first-name'];
	$last_name = $_SESSION['last-name'];
	echo $last_name;
	
	$can_update = false;
	
	function trigger_kyle(){
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => 'http://mysterious-stream-6921.herokuapp.com/run',
		    //CURLOPT_USERAGENT => 'Codular Sample cURL Request',
		    CURLOPT_POST => 1,
		    CURLOPT_POSTFIELDS => array(
		        run => true,
		    )
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		
		// Close request to clear up some resources
		curl_close($curl);
	}
		
	if(filter_has_var(INPUT_POST, "item") && $_POST["item"] != ""){
		$item = $_POST["item"];
			
			if(filter_has_var(INPUT_POST, "description") && $_POST["description"] != ""){
				$description = $_POST["description"];
				
				if(filter_has_var(INPUT_POST, "tags") && $_POST["tags"] != ""){
					$tags = $_POST["tags"];
					$tags = explode(',' , $tags);
					
					if(filter_has_var(INPUT_POST, "location") && $_POST["location"] != ""){
						$location = $_POST["location"];
						if(isset($_POST['found'])){
							$isFound = true;
						}
						$can_update = true;
					}
				}
			}
	}
	if($can_update && isset($email)){
		$date = date('M d, Y - H:i a');
		$document = array("Date Created" => $date, "Item" => $item , "Location" =>  $location, "Matched" => 0, "Tags" => $tags, "Description" => $description, "email" => $email);
		if($isFound){
			$db->Found->insert($document);
		}else{
			$db->Lost->insert($document);
		}
		$output = "You have successfully added an item";
		trigger_kyle();
		$app->render('main.php', array('output' => $output));
	}
	else{
		$output = "Update not sucessful. (Are you logged in?)";
		$app->render('main.php', array('output' => $output));
	}
	
});

$app->get('/login', function() use($app){
	
	$request = $app->request()->get('request');
	if($request == null){
		$app->render('login.php');
	}
	elseif($request == "login"){
		$_GET['login'] = true;
		$app->render('authenticate.php', array('$_GET["login"]' => $_GET["login"]));
	}
});

$app->get('/login/:email/:last_name/:first_name', function($email, $last_name, $first_name) use($app, $db){
	//Check to see if email already exists in database, if it does. Log them in.
	$query = array('email' => $email, 'last' => $last_name, 'first' => $first_name);
	$user = $db->users->findOne($query);
	if($user == null){
		//Insert into datatabse
		$db->users->insert($query);
		$user = $db->users->findOne($query);
	}
	
	session_cache_limiter(false);
	session_start();
	$_SESSION['first-name'] = $user['first'];
	$_SESSION['last-name'] = $user['last'];
	$_SESSION['email'] = $user['email'];
	
	
	$output = "Welcome, " . $user['first'] . ".  What have you lost or found?";
	if($_SERVER['DOCUMENT_ROOT'] == '/var/www/'){
		$app->redirect('/lost/');
	}
	else{
		$app->redirect('/');
	}
});
$app->get('/my-items', function() use($app, $db){
	session_start();
	$email = $_SESSION['email'];
	$name = $_SESSION['first-name'];
	
	if(isset($email)){
		$collection = new MongoCollection($db, 'Lost');
		$query = array("email" => $email);
		$lost_cursor = $collection->find($query);
		$collection = new MongoCollection($db, 'Found');
		$found_cursor = $collection->find($query);
		$app->render('my-items.php', array('lost_cursor' => $lost_cursor, 'found_cursor' => $found_cursor, 'email' => $email, 'name' => $name));
	}
	else{
		$app->render('my-items.php');
	}
});


$app->get('/matches/', function() use($app, $db){
	session_start();
	$email = $_SESSION['email'];
	$name = $_SESSION['first-name'];
	
	$app->render('matches.php', array('name' => $name, 'db' => $db));
	
});

$app->run();
?>