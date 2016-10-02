<?php
//saves information about which sounds cannot be at the beginning of a morpheme
	include_once "../includes/language_arrays.php";
	include_once "../includes/db_connect.php";
	
	session_start();
	

	
	
	$_SESSION['morph_initial'] = $_POST;

  
 
	
		include_once "../forms/create_lang_info_7.2.php";
	
	
			