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
				<div class="menu-item"><?php if(isset($name)){echo $name;}else{echo "NAME";} ?></div>
				<a href="report-found"><div class="menu-item">Report Found</div></a>
				<a href="report-lost"><div class="menu-item">Report Lost</div></a>
				<a href="my-items"><div class="menu-item">My Items</div></a>
				<div class="menu-item">Matches</div>
		</div>
		<div id="right">
			<div id="header">
				<div class="padding">
					L&amp;F
				</div>
			</div>