<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title></title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<div id="login-box">
			<div class="padding">
				<img src="img/landfsmall.png">
				<form action="login" method="get">
					<input type="hidden" name="request" value="login" />
					<input class="login-button" type="submit" value="login / sign-up" />
				</form>
				<p><?php echo $output ?></p>
			</div>
		</div>
	</body>
</html>
