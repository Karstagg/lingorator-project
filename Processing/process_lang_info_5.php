<?php
//saves data on allowed/forbidden consonant clusters 
	include_once "../includes/language_arrays.php";
	include_once "../includes/db_connect.php";
	
	session_start();

	
	
	$syllables = $_SESSION['syllables'];
	
 
//an array of keys for ipa hex codes that the user entered on create_lang_info_4.php(from language_arrays.php)
	$clus_key_array = explode(" ", mysqli_real_escape_string($language_creator, $_POST['selected_v_hidden']));
	//an array of hex codes for ipa characters that the user entered on create_lang_info_4.php (from language_arrays.php)
	$clus_ipa_array = explode(" ", mysqli_real_escape_string($language_creator, $_POST['selected_v']));
	
	
	//sets a session variable consisting of an array with ipa variables and their keys (from ipa arrays in language_arrays.php)
	$_SESSION['clusters_onset'] = array_combine($clus_key_array, $clus_ipa_array);
  

	
	
	
	 

	
				
				
		//if the syllables are not open only
		if ($syllables != 'open') {
				
				include_once "../forms/create_lang_info_5.2.php";
			

		}
		else {
			include_once "../forms/create_lang_info_6.php";
		}
 	
			
			