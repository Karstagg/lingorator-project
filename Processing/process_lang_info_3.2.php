<?php
//saves information about user selected consonants
	include_once "../includes/language_arrays.php";
	include_once "../includes/db_connect.php";
	
	session_start();
	



	//creates a session variable out of the post array
	$_SESSION['final_consonants'] = $_POST;

  
  
				
				
			include_once "../forms/create_lang_info_4.php";
