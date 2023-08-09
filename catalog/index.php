<?php
session_start();
	include_once("includes/functions.php");	
?>
<!doctype html>
<html lang="en">
	<head>
		<title>RaceShop Log In</title>
		<link type="text/css" rel="stylesheet" href="css/style.css">
		<script src="js/script.js"></script>
	</head>
	<body>
		<?php
			/*If they aren't logged in, display a log in page.
			If they are logged in, display a welcome message.*/
			if(!haveAccess())
			{
				homePage();
			} else 
			{
				welcomeMessage();
			}
			include_once("includes/nav.php");
		?>
	</body>
</html>

