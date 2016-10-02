<?php
//saves information about user selected vowels
	include_once "../includes/language_arrays.php";
	include_once "../includes/db_connect.php";
	
	session_start();

	
	
	//creates a session variable out of the post array
	$_SESSION['vowels'] = $_POST;



  
 
						
				
			include_once "../forms/create_lang_info_3.php";

