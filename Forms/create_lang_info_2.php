<?php
//allows the user to select vowels
	$username = $_SESSION['username'];
	$lang_name = $_SESSION['lang_name'];
?>

<!-- input form that allows users to enter specifications for their language-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Create_language_info_vowels</title>
        <link rel="stylesheet" href="../css/site_main.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type="text/JavaScript" src="../js/forms.js"></script> 
		<link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/8bit-wonder" type="text/css"/>

    </head>
    <body>
	<div class="tooltip">Help
		<span class="tooltiptext">
			<p>This form allows you to select the vowels you wish to use in your language, check all that you wish to use.</p>
		</span>
	</div>
	<form id="info_form" name="info_form" action="../processing/process_lang_info_2.php" method="post">
	<div id="text_div" style="font-size:80%">
        
		
		
		<p style="font-size:120%">Choose your vowels: </p>
		<br>
	</div>
		<table>
			<tr>
  
				<?php
					//places the values of ipa v array from language_arrays.php
					
					foreach ($ipa_vowels as $key => $value) {
						
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
					}
				?>
			</tr>	
				
	<br>
				
			<tr>	
				<?php
					//places the values of ipa v array from language_arrays.php
					
					foreach ($ipa_vowels_2 as $key => $value) {
						
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
					}
				?>
			</tr>
			
		</table>
	<br>
	
	<div id="selected_ipa" style="font-size:80%">
	</div>
	
	<br>
			<p><input type="submit"  id = "reg_button" value = "Next" align = "center"/></p>
	
	</form>
</body>
</html>