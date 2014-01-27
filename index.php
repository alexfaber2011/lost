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
 
 
//REQUIRE DWOLLA 
require 'includes/dwolla-php-master/lib/dwolla.php';
$apiKey = 'Jwj1SCxTtuUl4TgqwwkCZMZr0Olqm1k7aJ+TGpZSx25YYMxH78';
$apiSecret = 'Cf7XFcqol/86YGd1DC8GbEcsySgWiCFt/n499zULopwa5FezC9';
$redirectUri = 'http://secret-cove-9044.herokuapp.com/dwolla';

$permissions = array("Send");
$Dwolla = new DwollaRestClient($apiKey, $apiSecret, $redirectUri, $permissions);
$Dwolla->setToken('z/ygp368vra/kIIIuVl+Pmxv2pQ= ');

 
 
$retry = 3;
function getMongoClient() {
    try {
        return new Mongo("mongodb://alexfaber:newpassword@linus.mongohq.com:10089/LF");
    } catch(Exception $e) {
        /* Log the exception so we can look into why mongod failed later */
        //logException($e);
    }
    if ($retry > 0) {
        return getMongoClient($seeds, $options, --$retry);
    }
    throw new Exception("I've tried several times getting MongoClient.. Is mongod really running?");
}

function trigger_kyle($array, $url){
	$curl = curl_init();
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_URL => 'http://mysterious-stream-6921.herokuapp.com/' . $url,
	    //CURLOPT_USERAGENT => 'Codular Sample cURL Request',
	    CURLOPT_POST => 1,
	    CURLOPT_POSTFIELDS => $array
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	
	// Close request to clear up some resources
	curl_close($curl);
	return $resp;
}

//Connect to Mongo database
$connection = getMongoClient();
$db = $connection->LF;
 

// GET route
$app->get('/', function() use ($app, $db){
	session_start();
	$email = $_SESSION['email'];
	$name = $_SESSION['first-name'];
	
	if(isset($email)){
		$header = "<h1>Welcome</h1>";
		$output = "Hello, welcome to lost and found.  Refer to the left panel to begin.";
		$app->render('main.php', array('header' => $header, 'output' => $output));
	}else{
		$app->redirect("login");
	}
});

$app->get('/report-lost', function () use ($app){
	$app->render('insert.php', array('output' => "Lost"));
});

$app->get('/report-found', function () use ($app){
	$app->render('insert.php', array('output' => "Found"));
});

$app->post('/report', function () use ($app, $db){
	session_start();
	$email = $_SESSION['email'];
	$first_name = $_SESSION['first-name'];
	$last_name = $_SESSION['last-name'];
	
	$can_update = false;
		
	if(filter_has_var(INPUT_POST, "item") && $_POST["item"] != ""){
		$item = $_POST["item"];
			
			if(filter_has_var(INPUT_POST, "description") && $_POST["description"] != ""){
				$description = $_POST["description"];
				
				if(filter_has_var(INPUT_POST, "tags") && $_POST["tags"] != ""){
					$tags = $_POST["tags"];
					$tags = explode(',' , $tags);
					
					
					//var_dump(filter_var($email, FILTER_VALIDATE_EMAIL));
					if(filter_has_var(INPUT_POST, "lat") && $_POST["lat"] != "" && filter_var($_POST["lat"], FILTER_VALIDATE_FLOAT)){
						$lat = intval($_POST["lat"]);
						
						if(filter_has_var(INPUT_POST, "long") && $_POST["long"] != "" && filter_var($_POST["long"], FILTER_VALIDATE_FLOAT)){
							$long =  intval($_POST["long"]);
							
							if(isset($_POST['found'])){
								$isFound = true;
							}
							$can_update = true;
						}	
					}
				}
			}
	}
	if($can_update && isset($email)){
		$date = date('M d, Y - H:i a');
		$document = array("Description" => $description, "Date Created" => $date, "Item" => $item, "Matched" => 0, "PMatch_id" => array(), "Rejects" => array(), 
			"Tags" => $tags, "email" => $email, "location" => array("type" => "Point", "coordinates" => array($long, $lat)));
		if($isFound){
			$db->Found->insert($document);
		}else{
			$db->Lost->insert($document);
			$output = $db->lastError();
			$output = $output["err"] . "<br /><br />";
		}
		$output .= "You have successfully added an item";
		trigger_kyle(array(run => true,), "run");
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


$app->get('/matches', function() use($app, $db){
	session_start();
	$email = $_SESSION['email'];
	$name = $_SESSION['first-name'];
	
	$app->render('matches.php', array('name' => $name, 'db' => $db));
});

$app->post('/matches', function() use($app, $db){
	session_start();
	$email = $_SESSION['email'];
	$name = $_SESSION['first-name'];	
		
	//Reject: OpenID of rejected object
	//Lost: OpenID of lost object
	$reject_id = $_POST['Reject'];
	
	
	//Lost: OpenId of lost object
	//Found; OpenId of found object
	$found_id = $_POST['Found'];
	
	$lost_id = $_POST['Lost'];
	
	if(isset($reject_id) && isset($lost_id)){
		$continue = trigger_kyle(array(Reject => $reject_id, Lost => $lost_id), "no");
		if($continue != null){
			sleep(5);
			$app->render('matches.php', array("db" => $db, 'name' => $name));
		}
	}
	if(isset($lost_id) && isset($found_id)){
		$continue = trigger_kyle(array(Found => $found_id, Lost => $lost_id), "yes");
		if($continue != null){
			sleep(5);
			$app->render('matched.php', array("db" => $db));
		}
	}
});

$app->get('/matched', function() use($app, $db){
	session_start();
	$email = $_SESSION['email'];
	$name = $_SESSION['first-name'];
	
	$app->render('matched.php', array('name' => $name, 'db' => $db));
});

$app->get('/pay', function() use($app, $db){
	session_start();
	$email = $_SESSION['email'];
	$name = $_SESSION['first-name'];
	
	$app->render('pay.php');
});

$app->get('/logout', function() use($app){
	// Initialize the session.
	// If you are using session_name("something"), don't forget it now!
	session_start();
	
	// Unset all of the session variables.
	$_SESSION = array();
	$_SESSION['email'] = null;
	
	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
	if (ini_get("session.use_cookies")) {
	    $params = session_get_cookie_params();
	    setcookie(session_name(), '', time() - 42000,
	        $params["path"], $params["domain"],
	        $params["secure"], $params["httponly"]
	    );
	}
	
	// Finally, destroy the session.
	session_destroy();
	echo $_SERVER['DOCUMENT_ROOT'];
	if($_SERVER['DOCUMENT_ROOT'] == "/var/www/"){
		$app->redirect("/lost");
	}else{
		$app->redirect("/");
	}
});

$app->get('/thank-you', function() use($app){
	$header = "<h1>Thank You</h1>";
	$output = "You're user has been paid.";
	$app->render('main.php', array('header' => $header, 'output' => $output));
});

$app->get('/dwolla', function() use($app, $Dwolla){
	echo "pre-poop";
	$id = $Dwolla->send('9999', '812-101-0468', 1.00);
	echo $id;
	echo $Dwolla->getError();
	echo "poop";
	/**
	 * STEP 1: 
	 *   Create an authentication URL
	 *   that the user will be redirected to
	 **/
	
	
	// if(!isset($_GET['code']) && !isset($_GET['error'])) {
	        // $authUrl = $Dwolla->getAuthUrl();
	        // header("Location: {$authUrl}");
			// exit();
	// }
	
	/**
	 * STEP 2:
	 *   Exchange the temporary code given
	 *   to us in the querystring, for
	 *   a never-expiring OAuth access token
	 **/
	// if(isset($_GET['error'])) {
	        // echo "There was an error. Dwolla said: {$_GET['error_description']}";
	// }
// 	
	// else if(isset($_GET['code'])) {
	        // $code = $_GET['code'];
// 	
	        // $token = $Dwolla->requestToken($code);
	        // if(!$token) { $Dwolla->getError(); } // Check for errors
	        // else {
	                // session_start();
	                // $_SESSION['token'] = $token;
	                // echo "Your access token is: {$token}";
	        // } // Print the access token
	// }
});
$app->run();
?>