<?php
//allows the user to choose the consonants for their languages
	$username = $_SESSION['username'];
	$lang_name = $_SESSION['lang_name'];
?>

<!-- input form that allows users to enter specifications for their language-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Create_language_info_consonants_onset</title>
        <link rel="stylesheet" href="../css/site_main.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type="text/JavaScript" src="../js/forms.js"></script> 
		<link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/8bit-wonder" type="text/css"/>

    </head>
    <body>
	<div class="tooltip">Help
		<span class="tooltiptext">
			<p>This form allows you to select the consonants that you would like to see appear in the coda of syllables. 
			Select all that you would like to see appear in a syllable coda position.</p>
		</span>
	</div>
	<form id="info_form" name="info_form" action="../processing/process_lang_info_3.2.php" method="post">
	<div id="text_div" style="font-size:80%">
        
		
		
		<p style="font-size:120%">Choose <span style="color:red">allowed</span> syllable <span style="color:red">coda</span> single consonants</p>
		<br>
	</div>
	
		<table>
		
			<tr>
				<td>Bilabial: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($ipa_bilabial as $key => $value) {
						
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
					}
				?>
			</tr>	
			
			<tr>
				<td>Labiodental: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($ipa_labiodental as $key => $value) {
						
				?>
				  <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
					}
				?>
			</tr>
			
			<tr>
				<td>Dental: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($ipa_dental as $key => $value) {
						
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
					}
				?>
			</tr>	
			
			<tr>
				<td>Alveolar: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($ipa_alveolar as $key => $value) {
						
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
					}
				?>
			</tr>	
			
			<tr>
				<td>Postalveolar: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($ipa_postalveolar as $key => $value) {
						
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
					}
				?>
			</tr>	
			
			<tr>
				<td>Retroflex: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($ipa_retroflex as $key => $value) {
						
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
					}
				?>
			</tr>	
				
			<tr>
				<td>Palatal: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($ipa_palatal as $key => $value) {
						
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
					}
				?>
			</tr>		
			
			<tr>
				<td>Velar: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($ipa_velar as $key => $value) {
						
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
					}
				?>
			</tr>	
			
			<tr>
				<td>Uvular: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($ipa_uvular as $key => $value) {
						
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
					}
				?>
			</tr>
			
			<tr>
				<td>Pharyngeal: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($ipa_pharyngeal as $key => $value) {
						
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
					}
				?>
			</tr>
			
			<tr>
				<td>Glotal: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($ipa_glotal as $key => $value) {
						
				?>
				    <td><input class="check" type="checkbox" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
					}
				?>
			</tr>
			<tr>
				<td>Others: </td>
				<?php
					//places the values of ipa consonant arrays from language_arrays.php
					
					foreach ($ipa_others as $key => $value) {
						
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