<!-- processes input from select_lang.php, creates a db for each user and tables for their languages -->
<?php
	include_once "../includes/language_arrays.php";
	include_once "../includes/db_connect.php";
	
	session_start();
	$username = $_SESSION['username'];
	$lang_name = mysqli_real_escape_string($language_creator, $_POST['lang_name_search']);
	$table_main = "main";
	$names = $_SESSION['names_array'];
	
	$_SESSION['lang_name'] = $lang_name;
	
	foreach ($names as $key => $value) {
		if ($value == $lang_name){
			$_SESSION['lang_id'] = $key;
		}
	}
	
 //only if lang_name is alphabet characters
  if (ctype_alpha($lang_name)) { 	
		
			//checks to see if a language name has already been used
			if (!in_array($lang_name, $names)){
				echo "<br><br><div id='text_div' style='color:red;text-align: center'>$lang_name is not in the database</div>";
				include_once "../forms/select_lang.php";
			}
			else {
					//sends the user to the vocab entry page
				echo "<script> top.window.location='../language_page.php'; </script>";
			}
	} 
else {
	echo "<br><br><div id='text_div' style='color:red;text-align: center'>use leters only</div>";
			include_once "../forms/select_lang.php";
}
