<?php
session_start();
	include_once("includes/functions.php");
?>
<!doctype html>
<html lang="en">
	<head>
		<title>RaceShop Account Creation</title>
		<link type="text/css" rel="stylesheet" href="css/style.css">
		<script defer src="js/script.js"></script>
	</head>
	<body>
		<?php
			createAccPage();
			include_once("includes/nav.php");
		?>
	</body>
</html>