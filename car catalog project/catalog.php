<?php
session_start();
	include_once("includes/functions.php");
?>
<!doctype html>
<html lang="en">
	<head>
		<title>RaceShop Products</title>
		<link type="text/css" rel="stylesheet" href="css/style.css">
		<script src="js/script.js"></script>
	</head>
	<body>
		<?php
			catalogPage();
			include_once("includes/nav.php");
		?>
	</body>
</html>