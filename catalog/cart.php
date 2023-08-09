<?php
/*If they aren't logged in, send them to index.*/
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
			/*Display cart page.*/
			cartPage();
			include_once("includes/nav.php");
		?>
	</body>
</html>