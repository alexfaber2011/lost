<?php
	require_once('includes/lightopenid/openid.php');
	
	$documentRoot = $_SERVER['DOCUMENT_ROOT'];
	//Connect to the database
	if($documentRoot == "/var/www"){
		$openid = new LightOpenID('localhost');
	}
	else{
		$openid = new LightOpenID('http://secret-cove-9044.herokuapp.com/');
	}
	
	//Authenticate properly
	try{
		if(!$openid->mode){
			if(isset($_GET['login'])){
				$openid->identity = 'https://www.google.com/accounts/o8/id';
				$openid->required = array('contact/email' , 'namePerson/first' , 'namePerson/last');  //get's the email of address of the person we're trying to authenticate
				header('Location: ' . $openid->authUrl());
				exit();
			}
		}
		elseif($openid->mode == 'cancel'){
			echo 'User has canceled authentication';
		}
		else{
			if($openid->validate()){
				$returnVariables = $openid->getAttributes();
				// Date in the past
				header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
				header("Cache-Control: no-cache");
				header("Pragma: no-cache");
				header("Location: login/" . $returnVariables['contact/email'] . "/" . $returnVariables['namePerson/last'] . "/" . $returnVariables['namePerson/first']);
				exit();
			}
		}
	}
	catch(ErrorException $e){
		echo $e->getMessage();
	}
	
	//Logout if need be
	if(isset($_GET['logout'])){
		session_start();
		if(isset($_SESSION['editable'])){
		  unset($_SESSION['editable']);
		  $output = "You are now logged out.  <a href='index.php'>Continue to the homepage</a>";
		}
	}
?>