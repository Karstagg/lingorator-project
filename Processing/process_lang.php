<!-- processes input from creat_lang.php, creates a db for each user and tables for their languages -->
<?php
	include_once "../includes/language_arrays.php";
	include_once "../includes/db_connect.php";
	
	session_start();
	$username = $_SESSION['username'];
	$lang_name = mysqli_real_escape_string($language_creator, $_POST['lang_name']);
	$table_main = "main";
	$names = [];
$retrieve_language_query = "SELECT lang_name FROM `$username`.`$table_main`";
  $retrieve_language_result = $language_creator->query($retrieve_language_query);
  if ($retrieve_language_result->num_rows > 0) { //if there is anything in the table
     for ($i = 0; $i < $retrieve_language_result->num_rows; $i++) { //$i controls number of iterations. 
         $language = $retrieve_language_result->fetch_assoc(); //fetch associative array (one row at a time) 
			
				$names[] = $language['lang_name'];
				
	}
  }	
	
 //only if lang_name is alphabet characters
  if (ctype_alpha($lang_name)) { 	
		echo "<br><br><div id='text_div' style='text-align:center'>Specify parameters for $lang_name</div>";
			//checks to see if a language name has already been used
			if (in_array($lang_name, $names)){
				echo "<br><br><div id='text_div' style='color:red;text-align: center'>Language already exists</div>";
				include_once "../forms/create_lang.php";
			}
			else {
				include_once "../forms/create_lang_info_1.php";
			}
	} 
else {
	echo "<br><br><div id='text_div' style='color:red;text-align: center'>use leters only</div>";
			include_once "../forms/create_lang.php";
}


	



