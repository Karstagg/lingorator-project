<?php
//saves data on forbidden vowel/consonant combinations (can be used to help create complimentary distribution)
	include_once "../includes/language_arrays.php";
	include_once "../includes/db_connect.php";
	
	session_start();


	
 
 //arrays of ipa characters and keys 
 $combo_ipa_proto = explode(" ", str_replace("+", "", mysqli_real_escape_string($language_creator, $_POST['selected_v'])));
 $combo_key_proto = explode(" ", str_replace("+", "", $_POST['selected_v_hidden']));
	 
	 //an array with ipa characters and keys
	 $_SESSION['vc_combo'] = array_combine($combo_key_proto, $combo_ipa_proto);

	
				
				
		
 	
			
			
		include_once "../forms/create_lang_info_7.php";
		
		
		