<!DOCTYPE html>
<html>
    <head>
<?php
//tells the user that their registration was successful and creates a database for that user
	include_once "../includes/language_arrays.php";
	include_once "../includes/db_connect.php";
	
	session_start();
	$username = $_SESSION['username'];
	
	$table_main = "main";
	$table_class = "class";
	
	//table names for english vocab
	$table_v = "verbs";
	$table_n = "nouns";
	$table_adj = "adjectives";
	$table_adv = "adverbs";
	$table_pro = "pronouns";
	$table_prep = "prepositions";
	$table_conj = "conjunctions";
	$table_det = "determiners";
	$table_i = "inflections";
	$table_int = "interjections";
	$table_class = "classifiers";
	$table_af = "affixes";
	$table_case = "cases";
	$table_gen = "gender";
	$table_num = "numbers";
	$table_vowel = "vowels";
	$table_consonants_ini = "initial_consonants";
	$table_consonants_fin = "final_consonants";
	$table_diphthongs = "diphthongs";
	$table_clusters_ini = "initial_clusters";
	$table_clusters_fin = "final_clusters";
	$table_initials = "non_initials";
	$table_finals = "non_finals";
	$table_vc = "no_vc_combo";
	$table_isolation = "no_isolation";
	
	//table for special other features of the language
	$table_others = "others";



  

  //creates a new table in the user's new database
  $create_main_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_main` (
				`lang_id` int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`lang_name` varchar(10) NOT NULL,
				`word_order` varchar(10) NOT NULL,
				`syllables` varchar(20) NOT NULL,
				`type` varchar(20) NOT NULL,
				`addpos` int(1) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
				
  
  //create table variables for english vocab
  $create_verb_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_v` (
						`v_id` int(10) NOT NULL,
						`eng` varchar(30) NOT NULL,
						`new_root` varchar(30) NOT NULL,
						`new_inf` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_noun_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_n` (
						`n_id` int(10) NOT NULL,
						`eng` varchar(30) NOT NULL,
						`new_root` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_adj_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_adj` (
						`adj_id` int(10) NOT NULL,
						`eng` varchar(30) NOT NULL,
						`new_root` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_adv_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_adv` (
						`adv_id` int(10) NOT NULL,
						`eng` varchar(30) NOT NULL,
						`new_root` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_pro_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_pro` (
						`pro_id` int(10) NOT NULL,
						`eng` varchar(30) NOT NULL,
						`new_root` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_prep_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_prep` (
						`prep_id` int(10) NOT NULL,
						`eng` varchar(30) NOT NULL,
						`new_root` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_conj_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_conj` (
						`conj_id` int(10) NOT NULL,
						`eng` varchar(30) NOT NULL,
						`new_root` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_det_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_det` (
						`det_id` int(10) NOT NULL,
						`eng` varchar(30) NOT NULL,
						`new_root` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_i_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_i` (
						`i_id` int(10) NOT NULL,
						`eng` varchar(30) NOT NULL,
						`new_root` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_int_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_int` (
						`int_id` int(10) NOT NULL,
						`eng` varchar(30) NOT NULL,
						`new_root` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_af_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_af` (
						`af_id` int(10) NOT NULL,
						`eng` varchar(30) NOT NULL,
						`new_root` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_case_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_case` (
						`case_id` int(10) NOT NULL,
						`eng` varchar(30) NOT NULL,
						`new_root` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  
  $create_class_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_class` (
				`cls_id` int(10) NOT NULL,
				`eng` varchar(30) NOT NULL,
				`new_root` varchar(30) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_num_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_num` (
				`num_id` int(10) NOT NULL,
				`eng` varchar(30) NOT NULL,
				`new_root` varchar(30) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_gen_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_gen` (
				`gen_id` int(10) NOT NULL,
				`eng` varchar(30) NOT NULL,
				`new_root` varchar(30) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  
  //used in creating a phonology
  $create_vowel_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_vowel` (
				`vowel_id` int(10) NOT NULL,
				`v` varchar(30) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_consonant_ini_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_consonants_ini` (
				`consonant_id` int(10) NOT NULL,
				`c` varchar(30) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_consonant_fin_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_consonants_fin` (
				`consonant_id` int(10) NOT NULL,
				`c` varchar(30) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_diphthongs_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_diphthongs` (
				`diphthong_id` int(10) NOT NULL,
				`diphthong` varchar(30) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_clusters_ini_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_clusters_ini` (
				`cluster_id` int(10) NOT NULL,
				`cluster` varchar(30) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_clusters_fin_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_clusters_fin` (
				`cluster_id` int(10) NOT NULL,
				`cluster` varchar(30) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_initials_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_initials` (
				`non_i_id` int(10) NOT NULL,
				`non_i` varchar(30) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_finals_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_finals` (
				`non_f_id` int(10) NOT NULL,
				`non_f` varchar(30) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_vc_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_vc` (
				`vc_id` int(10) NOT NULL,
				`vc` varchar(30) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_isolation_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_isolation` (
				`non_is_id` int(10) NOT NULL,
				`non_is` varchar(30) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
 


	
	//creating the basic data table for the new language
	if ($language_creator->query($create_main_table) === TRUE) {
			
			//creating tables to store english vocab
			$language_creator->query($create_noun_table);
			$language_creator->query($create_verb_table);
			$language_creator->query($create_adj_table);
			$language_creator->query($create_adv_table);	
			$language_creator->query($create_pro_table);
			$language_creator->query($create_prep_table);
			$language_creator->query($create_conj_table);
			$language_creator->query($create_det_table);
			$language_creator->query($create_i_table);
			$language_creator->query($create_gen_table);
			$language_creator->query($create_int_table);
			$language_creator->query($create_af_table);
			$language_creator->query($create_case_table);
			$language_creator->query($create_class_table);
			$language_creator->query($create_num_table);
			$language_creator->query($create_vowel_table);
			$language_creator->query($create_consonant_ini_table);
			$language_creator->query($create_consonant_fin_table);
			$language_creator->query($create_diphthongs_table);
			$language_creator->query($create_clusters_ini_table);
			$language_creator->query($create_clusters_fin_table);
			$language_creator->query($create_initials_table);
			$language_creator->query($create_finals_table);
			$language_creator->query($create_vc_table);
			$language_creator->query($create_isolation_table);
		


?>
        <meta charset="UTF-8">
        <title>Secure Login: Registration Success</title>
        <link rel="stylesheet" href="../css/site_main.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/8bit-wonder" type="text/css"/>
    </head>
    <body>
	<div id="text_div">
        <h1>Registration successful</h1>
        <p>You can now go back to the <a href="login_page.php">login page</a> and log in</p>
	</div>
    </body>
</html>		
<?php 
			}  
			//destroys the session - a different session is used on login
			session_destroy();
?>