<?php
include_once 'db_connect.php';
include_once 'functions.php';
 
 //code adapted from http://www.wikihow.com/Create-a-Secure-Login-Script-in-PHP-and-MySQL

session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['email'], $_POST['p'])) {
    $email = $_POST['email'];
    $password = $_POST['p']; // The hashed password.
 
    if (login($email, $password, $mysqli) == true) {
         $username = $_SESSION['username'];
		 //creates a new database named after the user
			$create_db_stmt = "CREATE DATABASE $username";
		//will not create a new DB if one already exists with the same name
			$language_creator->query($create_db_stmt);
		// Login success 
        header('Location: ../forms/protected_page.php');
		
		
    } else {
        // Login failed 
        header('Location: ../forms/login_page.php?error=1');
    }
} else {
    // The correct POST variables were not sent to this page. 
    echo 'Invalid Request';
}