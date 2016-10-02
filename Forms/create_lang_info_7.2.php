<?php
//allows the user to choose the consonants for their languages
	$username = $_SESSION['username'];
	$lang_name = $_SESSION['lang_name'];
	
	
	//creates an empty array if final consonants are not set to avoid an error
	if (isset($_SESSION['final_consonants'])) {
			$final_c = $_SESSION['final_consonants'];
	}
	else {
			$final_c = [];
	}
?>

<!-- input form that allows users to enter specifications for their language-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Create_language_info_consonants_morpheme_final</title>
        <link rel="stylesheet" href="../css/site_main.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type="text/JavaScript" src="../js/forms.js"></script> 
		<link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/8bit-wonder" type="text/css"/>

    </head>
    <body>
	<div class="tooltip">Help
		<span class="tooltiptext">
			<p>This allows the user to select items that they do not wish to be at the end of a morpheme.</p>
			<p>No morphemes can end with any single character that you select here. They can still end with any clusters that you have defined.</p>
			<p>WARNING: If you have not defined any clusters and sellect every character offerend here, you will likely end up in an infinite loop when you attempt to use the translator.</p>
		</span>
	</div>
	<br>
	<form id="info_form" name="info_form" action="../processing/process_lang_info_7.2.php" method="post">
	<div id="text_div" style="font-size:80%">
        
		
		
		<p style="font-size:120%">Choose <span style="color:red">forbidden</span> morpheme <span style="color:red">final</span> phonemes</p>
		<br>
	</div>
	
		<table>
		
			<tr>
				<td>Bilabial: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($final_c as $key => $value) {
						if (strpos($key, 'bi') !== FALSE) {
						
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>	
			
			<tr>
				<td>Labiodental: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($final_c as $key => $value) {
						if (strpos($key, 'la') !== FALSE) {
						
				?>
				  <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>
			
			<tr>
				<td>Dental: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($final_c as $key => $value) {
						if (strpos($key, 'de') !== FALSE) {
						
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>	
			
			<tr>
				<td>Alveolar: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($final_c as $key => $value) {
						if (strpos($key, 'al') !== FALSE) {
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>	
			
			<tr>
				<td>Postalveolar: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($final_c as $key => $value) {
						if (strpos($key, 'po') !== FALSE) {
						
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>	
			
			<tr>
				<td>Retroflex: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($final_c as $key => $value) {
						if (strpos($key, 're') !== FALSE) {
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>	
				
			<tr>
				<td>Palatal: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($final_c as $key => $value) {
						if (strpos($key, 'pa') !== FALSE) {
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>		
			
			<tr>
				<td>Velar: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($final_c as $key => $value) {
						if (strpos($key, 've') !== FALSE) {
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>	
			
			<tr>
				<td>Uvular: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($final_c as $key => $value) {
						if (strpos($key, 'uv') !== FALSE) {
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>
			
			<tr>
				<td>Pharyngeal: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($final_c as $key => $value) {
						if (strpos($key, 'ph') !== FALSE) {
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>
			
			<tr>
				<td>Glotal: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($final_c as $key => $value) {
						if (strpos($key, 'gl') !== FALSE) {
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>
			<tr>
				<td>Others: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($final_c as $key => $value) {
						if (strpos($key, 'ot') !== FALSE) {
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>				
			
		</table>	
		<table>
			<tr>
  
				<?php
						//uses an array of user selected ipa characters and their keys to create an ipa keyboard
					foreach ($_SESSION['vowels'] as $key => $value) {
						//checks the key to make sure that the correct values are placed in the table row
						if (strpos($key, 'va') !== FALSE) {
						
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>	
				
	<br>
				
			<tr>	
				<?php
						//uses an array of user selected ipa characters and their keys to create an ipa keyboard
					foreach ($_SESSION['vowels'] as $key => $value) {
						//checks the key to make sure that the correct values are placed in the table row
						if (strpos($key, 'vb') !== FALSE) {
						
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>
			
		</table>
	<br>
				
	<br>
	
	<div id="selected_ipa" style="font-size:80%">
	</div>
	
	<br>
			<p><input type="submit"  id = "reg_button" value = "Next" align = "center"/></p>
	
	</form>
</body>
</html>