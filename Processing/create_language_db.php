<?php
	include_once "../includes/language_arrays.php";
	include_once "../includes/db_connect.php";
	
	session_start();
	$username = $_SESSION['username'];
	//creates a new database named after the user
			$create_db_stmt = "CREATE DATABASE `$username`";
		//will not create a new DB if one already exists with the same name
			$language_creator->query($create_db_stmt);

