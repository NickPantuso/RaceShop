<?php
include_once("functions.php");

	echo '<nav>';
		echo '<a class="nav" href="catalog.php">RaceShop Products</a>';
		if(!haveAccess()) echo '<a class="nav" href="create-account.php">Create Account</a>';
		if(!haveAccess()) echo '<a class="nav" href=".">Log In</a>';
		if(haveAccess()) echo '<a class="nav" href="cart.php">Your Cart</a>';
		if(haveAccess()) echo '<a class="nav" href="logout.php">Log Out</a>';
	echo '</nav>';
?>

