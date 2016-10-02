<?php
//saves information about user selected consonants
	include_once "../includes/language_arrays.php";
	include_once "../includes/db_connect.php";
	
	session_start();

	
	

	//creates a session variable out of the post array
	$_SESSION['initial_consonants'] = $_POST;
	
	
  
  
	//skip creation of syllable final consonants if all syllables are open. 		
	if ($_SESSION['syllables'] != 'open') {		
		include_once "../forms/create_lang_info_3.2.php";
	}
	else {
		include_once "../forms/create_lang_info_4.php";
	}