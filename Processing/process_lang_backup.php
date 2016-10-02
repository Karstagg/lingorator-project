<!-- processes input from creat_lang.php, creates a db for each user and tables for their languages -->
<?php
	include_once "../includes/language_arrays.php";
	include_once "../includes/db_connect.php";
	
	session_start();
	$username = $_SESSION['username'];
	$lang_name = $_POST['lang_name'];
	$table_main = $lang_name . "_main";
	$table_casegen = $lang_name . "_casegen";
	
	//tables for english vocab
	$table_v_eng = $lang_name . "_v_eng";
	$table_n_eng = $lang_name . "_n_eng";
	$table_adj_eng = $lang_name . "_adj_eng";
	$table_adv_eng = $lang_name . "_adv_eng";
	$table_pn_eng = $lang_name . "_pn_eng";
	$table_prep_eng = $lang_name . "_prep_eng";
	$table_conj_eng = $lang_name . "_conj_eng";
	$table_det_eng = $lang_name . "_det_eng";
	
	//tables for new vocab
	$table_v_new = $lang_name . "_v_new";
	$table_n_new = $lang_name . "_n_new";
	$table_adj_new = $lang_name . "_adj_new";
	$table_adv_new = $lang_name . "_adv_new";
	$table_pn_new = $lang_name . "_pn_new";
	$table_prep_new = $lang_name . "_prep_new";
	$table_conj_new = $lang_name . "_conj_new";
	$table_det_new = $lang_name . "_det_new";

  //only if lang_name is alphabet characters
  if (ctype_alpha($lang_name)) { 
  
  //creates a new table in the user's new database
  $create_main_table = "CREATE TABLE IF NOT EXISTS $username.`$table_main` (
				`order` varchar(10) NOT NULL,
				`phon` varchar(10) NOT NULL,
				`type` varchar(20) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
  
   //creates a new table in the user's new database
  $create_casegen_table = "CREATE TABLE IF NOT EXISTS $username.`$table_casegen` (
				`nom` int(1) NOT NULL,
				`acc` int(1) NOT NULL,
				`gen` int(1) NOT NULL,
				`loc` int(1) NOT NULL,
				`lat` int(1) NOT NULL,
				`abl` int(1) NOT NULL,
				`dat` int(1) NOT NULL,
				`ins` int(1) NOT NULL,
				`m_f` int(1) NOT NULL,
				`m_f_n` int(1) NOT NULL,
				`a_i` int(1) NOT NULL,
				`c_n` int(1) NOT NULL,
				`class` int(1) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
  
  //create table variables for english vocab
  $create_eng_verb_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_v_eng` (
						`v_id` int(11) NOT NULL,
						`verb` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_eng_noun_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_n_eng` (
						`n_id` int(11) NOT NULL,
						`noun` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_eng_adj_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_adj_eng` (
						`adj_id` int(11) NOT NULL,
						`adjective` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_eng_adv_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_adv_eng` (
						`adv_id` int(11) NOT NULL,
						`adverb` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_eng_pn_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_pn_eng` (
						`pn_id` int(11) NOT NULL,
						`pronoun` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_eng_prep_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_prep_eng` (
						`prep_id` int(11) NOT NULL,
						`preposition` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_conj_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_conj_eng` (
						`conj_id` int(11) NOT NULL,
						`conjunction` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_det_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_det_eng` (
						`n_id` int(11) NOT NULL,
						`noun` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
 
 //create table variables for new vocab
  $create_new_verb_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_v_new` (
						`v_id` int(11) NOT NULL,
						`verb` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_new_noun_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_n_new` (
						`n_id` int(11) NOT NULL,
						`noun` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_new_adj_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_adj_new` (
						`adj_id` int(11) NOT NULL,
						`adjective` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_new_adv_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_adv_new` (
						`adv_id` int(11) NOT NULL,
						`adverb` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_new_pn_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_pn_new` (
						`pn_id` int(11) NOT NULL,
						`pronoun` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_new_prep_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_prep_new` (
						`prep_id` int(11) NOT NULL,
						`preposition` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_new_conj_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_conj_new` (
						`conj_id` int(11) NOT NULL,
						`conjunction` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  $create_new_det_table = "CREATE TABLE IF NOT EXISTS `$username`.`$table_det_new` (
						`n_id` int(11) NOT NULL,
						`noun` varchar(30) NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

$check_query = "SHOW TABLES FROM `$username`";
$check_result = $language_creator->query($check_query);

$user_lang_array = array();
//if tables exist the names of those tables are placed in an array
if ($check_result->num_rows > 0) {

for($i = 0; $i < $check_result->num_rows; $i++) {
$check_tables = $check_result->fetch_assoc();
	$user_lang_array[] = $check_tables['Tables_in_'.$username];
	
}
}


//checks to see if the language code name already exists, if not it will create a database for the user if none exists
//and create a table to store information about that user's language
 if (in_array($lang_name, $user_lang_array)) {
	 echo "<br><br><div id='text_div' style='color:red;text-align: center'>A database for $lang_name already exists </div>";
			include_once "create_lang.php";
 }
 else {
 //runs a query to create a new database for the user if one does not exist, then generates a table for their new language
 
	
	//creating the basic data table for the new language
	if ($language_creator->query($create_main_table) === TRUE) {
			$language_creator->query($create_casegen_table);
			//creating tables to store english vocab
			$language_creator->query($create_eng_noun_table);
			$language_creator->query($create_eng_verb_table);
			$language_creator->query($create_eng_adj_table);
			$language_creator->query($create_eng_adv_table);	
			$language_creator->query($create_eng_pn_table);
			$language_creator->query($create_eng_prep_table);
			$language_creator->query($create_eng_conj_table);
			$language_creator->query($create_eng_det_table);
			
			//creating tables to store new vocab
			$language_creator->query($create_new_noun_table);
			$language_creator->query($create_new_verb_table);
			$language_creator->query($create_new_adj_table);
			$language_creator->query($create_new_adv_table);	
			$language_creator->query($create_new_pn_table);
			$language_creator->query($create_new_prep_table);
			$language_creator->query($create_new_conj_table);
			$language_creator->query($create_new_det_table);
			
			
		
		echo "<br><br><div id='text_div' style='text-align:center'>Specify parameters for $lang_name</div>";
			include_once "../forms/create_lang_info1.php";
	} 
 }
	
}

else {
	echo "<br><br><div id='text_div' style='color:red;text-align: center'>use leters only</div>";
			include_once "../forms/create_lang.php";
}


