<?php
session_start();
	include_once("includes/functions.php");
	if(!haveAccess()) header('location: .');
?>
<!doctype html>
<html lang="en">
	<head>
		<title>RaceShop Cart</title>
		<link type="text/css" rel="stylesheet" href="css/style.css">
		<script src="js/script.js"></script>
	</head>
	<body>
		<?php
			cartPage();
			include_once("includes/nav.php");
		?>
	</body>
</html>