<?php 
	session_cache_limiter(false);
	session_start(); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title></title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<div id="left-panel">
				<?php
					if($_SERVER['DOCUMENT_ROOT'] == "/var/www/"){
						$location = "/lost";
					}
					else{
						$location = "/";
					}
				?>
				<div id="bar">
					<a href="<?php echo $location ?>"><img src="img/landfsmall.png" /></a>
					<a href="report-found"><div class="menu-item">Report Found</div></a>
					<a href="report-lost"><div class="menu-item">Report Lost</div></a>
					<a href="my-items"><div class="menu-item">My Items</div></a>
					<a href="matches"><div class="menu-item">Matches</div></a>
					<a href="matched"><div class="menu-item">Matched</div></a>
					<a href="logout"><div class="menu-item">Logout</div></a>
					<a href="<?php echo $location ?>"><div id="name" class="menu-item">User: <?php if(isset($_SESSION['first-name'])){echo $_SESSION['first-name'];}else{echo "NAME";} ?></div></a>
				</div>
		</div>
		<div id="right">
