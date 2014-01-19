<?php
# Logging in with Google accounts requires setting special identity, so this example shows how to do it.
require 'openid.php';
$_GET['login'] = true;
try {
    # Change 'localhost' to your domain name.
    $openid = new LightOpenID('localhost');
    if(!$openid->mode) {
        if(isset($_GET['login'])) {
            $openid->identity = 'https://www.google.com/accounts/o8/id';
			//Get's email address of person we're trying to authenticate
			$openid->required = array('namePerson/friendly', 'contact/email');
            header('Location: ' . $openid->authUrl());
        }
?>
<form action="?login" method="post">
    <button>Login with Google</button>
</form>
<?php
    } elseif($openid->mode == 'cancel') {
        echo 'User has canceled authentication!';
    } else {
        //echo 'User ' . ($openid->validate() ? $openid->identity . ' has ' : 'has not ') . 'logged in.';
        if($openid->validate()){
        	$returnVariables = $openid->getAttributes();
			echo "User: " . $openid->identity . " has logged in with email address: " . $returnVariables['contact/email'];
        }
		else {
			echo "User has not logged in";
		}
    }
} catch(ErrorException $e) {
    echo $e->getMessage();
}
