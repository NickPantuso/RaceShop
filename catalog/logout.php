<?php
/*Destroy session and send user to index.*/
session_start();
	session_destroy();
	header('location: .');
?>