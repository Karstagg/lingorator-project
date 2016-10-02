<?php
	include_once "../includes/language_arrays.php";
	include_once "../includes/db_connect.php";
	
	session_start();
	$username = $_SESSION['username'];
	$lang_name = $_POST['lang_name'];
	$table_main = "main";
	$lang_type = $_POST['lang_type'];
	$order = $_POST['order'];
	$syllables = $_POST['syllables'];
	$addpos = $_POST['addpos'];
	
	$_SESSION['lang_type'] = $lang_type;
	$_SESSION['lang_name'] = $lang_name;
	$_SESSION['syllables'] = $syllables;
	$_SESSION['order'] = $order;
	$_SESSION['addpos'] = $addpos;
	
	
	

		
	
			
			include_once "../forms/create_lang_info_2.php";
			
		
		
	
	