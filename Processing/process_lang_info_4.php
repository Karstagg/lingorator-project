<?php
//saves information about allowed vowel combinations (could be treated as diphthongs)
	include_once "../includes/language_arrays.php";
	include_once "../includes/db_connect.php";
	
	session_start();


	
	
	
	
	//used to store user submitted info about ipa characters and their keys from arrays on language_arrays.php
	$dip_key_array_1 = [];
	$dip_key_array_2 = [];
	$dip_ipa_array_1 = [];
	$dip_ipa_array_2 = [];
	
	
	//an array of keys for ipa hex codes that the user entered on create_lang_info_4.php(from language_arrays.php)
	$dip_key_proto = explode(" ", str_replace("+", "", mysqli_real_escape_string($language_creator, $_POST['selected_v_hidden'])));
	//an array of hex codes for ipa characters that the user entered on create_lang_info_4.php (from language_arrays.php)
	$dip_ipa_proto = explode(" ", str_replace("+", "", mysqli_real_escape_string($language_creator, $_POST['selected_v'])));
	
	
	//splits the user data into two arrays if they enter more than 10 vowel combinations so that they can be displayed properly on following pages
	//the user can only enter 20 combinations in total, further combinations will be disregarded 
	for ($i = 0; $i < count($dip_key_proto); $i++) { 
		if ($i < 10 ) {
			$dip_key_array_1[$i] = $dip_key_proto[$i];
		}
		if ($i > 10 && $i < 20 ) {
			$dip_key_array_2[$i] = $dip_key_proto[$i];
		}
	}
	for ($i = 0; $i < count($dip_ipa_proto); $i++) { 
		if ($i < 10 ) {
			$dip_ipa_array_1[$i] = $dip_ipa_proto[$i]; 
		}
		if ($i > 10 && $i < 20 ) {
			$dip_ipa_array_2[$i] = $dip_ipa_proto[$i]; 
		}
	}
	
	
	
	
	//sets a session variable consisting of an array with ipa variables combined with their keys (from ipa arrays in language_arrays.php)
	$_SESSION['diphthongs_1'] = array_combine($dip_key_array_1, $dip_ipa_array_1);
	$_SESSION['diphthongs_2'] = array_combine($dip_key_array_2, $dip_ipa_array_2);
	//an array of all ipa characters combined with their keys
	$_SESSION['all_dip'] = array_merge($_SESSION['diphthongs_1'], $_SESSION['diphthongs_2']);
	
	
  
 
	
		
	
				include_once "../forms/create_lang_info_5.php";

		
		