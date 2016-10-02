<?php
//saves information about user selected consonants
	include_once "../includes/language_arrays.php";
	include_once "../includes/db_connect.php";
	
	session_start();
	$username = $_SESSION['username'];
	$table_main = "main";
	$table_consonants_ini = "initial_consonants";
	$table_vowel = "vowels";
	$table_consonants_ini = "initial_consonants";
	$table_consonants_fin = "final_consonants";
	$table_diphthongs = "diphthongs";
	$table_clusters_ini = "initial_clusters";
	$table_clusters_fin = "final_clusters";
	$table_vc = "no_vc_combo";
	$table_initials = "non_initials";
	$table_finals = "non_finals";
	


  
  
  
  
  
  //insert basic info about a language from create_lang_info 1
  $insert_lang_info_query = "INSERT INTO `$username`.`$table_main` SET
								`lang_name` = '".$_SESSION['lang_name']."',
								`word_order` = '".$_SESSION['order']."',
								`syllables` = '".$_SESSION['syllables']."',
								`type` = '".$_SESSION['lang_type']."',
								`addpos` = '".$_SESSION['addpos']."'";
  $language_creator->query($insert_lang_info_query);	
  
  $retrieve_language_query = "SELECT lang_id FROM `$username`.`$table_main`";
  $retrieve_language_result = $language_creator->query($retrieve_language_query);
  if ($retrieve_language_result->num_rows > 0) { 
     for ($i = 0; $i < $retrieve_language_result->num_rows; $i++) { 
         $language = $retrieve_language_result->fetch_assoc(); 
			
				$lang_id = $language['lang_id'];
				
	}
  }	
  
  $_SESSION['lang_id'] = $lang_id;
				
	
 //insert info about vowels from create_lang_info_2	
   foreach ($_SESSION['vowels'] as $value) {
	
		$insert_vowels_query = "INSERT INTO `$username`.`$table_vowel` SET
									`vowel_id` = '".$lang_id."',
									`v` = '".$value."'";		
		
		$language_creator->query($insert_vowels_query);
  }			
  
  
  //insert info about syllable initial consonants create_lang_info_3
  foreach ($_SESSION['initial_consonants'] as $value) {
	
	$insert_consonants_query = "INSERT INTO `$username`.`$table_consonants_ini` SET
								`consonant_id` = '".$lang_id."',
								`c` = '".$value."'";		
	
	$language_creator->query($insert_consonants_query);
  }			
  
  if (isset($_SESSION['final_consonants'])) {
	 //insert information about syllable final consonants if there is any. create_lang_info_3.2  
	   foreach ($_SESSION['final_consonants'] as $value) {
		
		$insert_consonants_query = "INSERT INTO `$username`.`$table_consonants_fin` SET
									`consonant_id` = '".$lang_id."',
									`c` = '".$value."'";		
		
		$language_creator->query($insert_consonants_query);
	  }	
  }  
  
   //inserts diphthongs, create_lang_info_4.
	//loops through the keys of ipa characters and adds them to the database
	foreach ($_SESSION['all_dip'] as $key => $value) {
		//so that no blank values can be inserted, a and b refer to keys for the ipa vowel arrays in the language_arrays.php file
		if (strpos($key, 'a') !== FALSE || strpos($key, 'b') !== FALSE) {
			//inserts info about allowed vowel combinations into the db
			$insert_diphthong_query = "INSERT INTO `$username`.`$table_diphthongs` SET
								`diphthong_id` = '".$lang_id."',
								`diphthong` = '".$value."'";		
		
			$language_creator->query($insert_diphthong_query);
		}
  	}	
	
	//inserts onset clusters, create_lang_info_5
  foreach ($_SESSION['clusters_onset'] as $key => $value) {
		//so that no blank values can be inserted, checks to see if letters from the ipa array keys are present 
		if (strpos($key, 'a') !== FALSE || strpos($key, 'b') !== FALSE || strpos($key, 'd') !== FALSE || strpos($key, 'p') !== FALSE || strpos($key, 'r') !== FALSE || strpos($key, 'b') !== FALSE || strpos($key, 'v') !== FALSE || strpos($key, 'g') !== FALSE || strpos($key, 'o') !== FALSE) {
			//inserts info about allowed consonant combinations into the db
			$insert_clusters_query = "INSERT INTO `$username`.`$table_clusters_ini` SET
								`cluster_id` = '".$lang_id."',
								`cluster` = '".$value."'";		
		
			$language_creator->query($insert_clusters_query);
		}
  	}	
  
  //inserts coda clusters if there are any. create_lang_info_5.2
   if (isset($_SESSION['clusters_coda'])) {
	  foreach ($_SESSION['clusters_coda'] as $key => $value) {
			//so that no blank values can be inserted, checks to see if letters from the ipa array keys are present 
			if (strpos($key, 'a') !== FALSE || strpos($key, 'b') !== FALSE || strpos($key, 'd') !== FALSE || strpos($key, 'p') !== FALSE || strpos($key, 'r') !== FALSE || strpos($key, 'b') !== FALSE || strpos($key, 'v') !== FALSE || strpos($key, 'g') !== FALSE || strpos($key, 'o') !== FALSE) {
				//inserts info about allowed consonant combinations into the db
				$insert_clusters_query = "INSERT INTO `$username`.`$table_clusters_fin` SET
									`cluster_id` = '".$lang_id."',
									`cluster` = '".$value."'";		
			
				$language_creator->query($insert_clusters_query);
			}
		}	
   }
  
  //inserts info about forbidden VC combinations, create_lang_info_6
  foreach ($_SESSION['vc_combo'] as $key => $value) {
		//so that no blank values can be inserted, checks to see if leters from the ipa array keys are present 
		if (strpos($key, 'a') !== FALSE || strpos($key, 'b') !== FALSE || strpos($key, 'd') !== FALSE || strpos($key, 'p') !== FALSE || strpos($key, 'r') !== FALSE || strpos($key, 'b') !== FALSE || strpos($key, 'v') !== FALSE || strpos($key, 'g') !== FALSE || strpos($key, 'o') !== FALSE) {
			//inserts info about forbidden vowel/consonant combinations into the db
			$insert_vc_query = "INSERT INTO `$username`.`$table_vc` SET
								`vc_id` = '".$lang_id."',
								`vc` = '".$value."'";		
		
			$language_creator->query($insert_vc_query);
		}
  	}	
	
	//inserts info about forbidden morpheme initial sounds
	 foreach ($_SESSION['morph_initial'] as $value) {
	
	$insert_non_initials_query = "INSERT INTO `$username`.`$table_initials` SET
								`non_i_id` = '".$lang_id."',
								`non_i` = '".$value."'";		
	
	$language_creator->query($insert_non_initials_query);
  }			
				
  
  
  //inserts info about forbidden morpheme initial sounds
  foreach ($_POST as $value) {
	
	$insert_non_finals_query = "INSERT INTO `$username`.`$table_finals` SET
								`non_f_id` = '".$lang_id."',
								`non_f` = '".$value."'";		
	
	$language_creator->query($insert_non_finals_query);
  }			
	

	
		//sends the user to the vocab entry page
			echo "<script> top.window.location='../language_page.php'; </script>";
	
	