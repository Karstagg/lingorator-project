<!-- input form that allows users to look up their language by its code name-->
<?php
include_once "../includes/language_arrays.php";
	include_once "../includes/db_connect.php";
	//if there is no active session
if (session_status() === PHP_SESSION_NONE) {
	session_start();
 }
	$username = $_SESSION['username'];
	$table_main = "main";
//retrieves names of languages that the user has created and places them in a datalist for easy access to previously made languages
$names = [];
$retrieve_language_query = "SELECT lang_id, lang_name FROM `$username`.`$table_main`";
  $retrieve_language_result = $language_creator->query($retrieve_language_query);
  if ($retrieve_language_result->num_rows > 0) { 
     for ($i = 0; $i < $retrieve_language_result->num_rows; $i++) {  
         $language = $retrieve_language_result->fetch_assoc(); 
				
				//creating another array with the language id as key and the name as value
				$names[$language['lang_id']] = $language['lang_name'];
				
	}
  }	
  $_SESSION['names_array'] = $names;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Create_language</title>
        <link rel="stylesheet" href="../css/site_main.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type="text/JavaScript" src="../js/forms.js"></script> 
		<link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/8bit-wonder" type="text/css"/>
<script>
$( document ).ready(function() {
	//keeps the submit button hidden until the user has input the correct data
	document.getElementById("reg_button").style.visibility = "hidden";
	//checks to see if all select fields have been selected
	$('#lang_name_search').on('keyup', function() { 
		if ($( "#lang_name_search" ).val() != '') {
			document.getElementById("reg_button").style.visibility = "visible";
		}
	});
});
</script>
   </head>
    <body>
	<div class="tooltip">Help
		<span class="tooltiptext">
			<p>Enter at least the first letter of the language you wish to work with.</p>
		</span>
	</div>
	
	<div id="text_div">
	<form action="../processing/process_select_lang.php" method="post">
	<br><br><h2 style="text-align: center;">Find your language</h2><br><br>
        <label for="lang_name_search">Language code name: </label>
				<input list ="lang_name_search" name="lang_name_search" id="lang_name_search" value="" size="40" maxlength="100">
            <datalist id="lang_name_search">
			<?php
				//places the values of $family array from language_arrays.php
				// in the datalist 
				foreach ($name as $value) {
			?>
			<option value="<?php echo $value;?>">
			<?php
				}
			?>
			</datalist>
		<p style="font-size:75%">Please only use letters a-z</p>
		<br>		
		<br>		
		<br>
		<p><input type="submit"  id = "reg_button" value = "Work With Your Language" align = "center"  /></p>
	</form>
	<input type="button" 
                   value="Back" 
				   id="reg_close" onclick="location.href='../forms/protected_page.php'"/> 
	
    
	</div>
	</body>
</html>