<?php

//allows user to select allowed/forbidden consonant clusters 
	$username = $_SESSION['username'];
	$lang_name = $_SESSION['lang_name'];
	

	
	

//$all_consonants = array_merge($_SESSION['initial_consonants'], $_SESSION['final_consonants']); 

?>

<!-- input form that allows users to enter specifications for their language-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Create_language_info_clusters</title>
        <link rel="stylesheet" href="../css/site_main.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type="text/JavaScript" src="../js/forms.js"></script> 
		<link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/8bit-wonder" type="text/css"/>
<script>

	$(document).ready(function() {
	
	//space bar function, also deletes excess + 
	$( "#space" ).on( "click", function() {
			var txt = $('#selected_v');
			var val = $('#selected_v').val();
			var txt_2 = $('#selected_v_hidden');
			var val_2 = $('#selected_v_hidden').val();
			//if the character at the end of this string is +, only the string - the final character will be placed back in the text
			if (val.charAt(val.length-1) == "+") {
				txt.text( txt.text().slice(0,-1) );
			}
			if (val_2.charAt(val_2.length-1) == "+") {
				txt_2.text( txt_2.text().slice(0,-1) );
			}
			var space = " ";
			//selected_v stands for selected values
			$( "#selected_v" ).append(space);
			$( "#selected_v_hidden" ).append(space);
	});
	
	
	//backspace fucntion, deletes the last character 
	//uses jquery slice to return a portion of the string from 0 until the specified distance (negative number) from the end of the input string
	$( "#back" ).on( "click", function() {
			var txt = $('#selected_v');
			var txt_2 = $('#selected_v_hidden');
			var val = $('#selected_v').val();
			var val_2 = $('#selected_v_hidden').val();
			
			//if the character at the end of the text area is a +, the string - the last two characters is returned to the text field (the + and the ipa character)
			if (val.charAt(val.length-1) == "+") {
				txt.text( txt.text().slice(0,-2) );
			}
			else {
				//if the character at the end of the text area is not a +, the string except for the last character is returned (an ipa character)
				txt.text( txt.text().slice(0,-1) );
			}
			
			//if the character at the end of the hidden text area is blank, everything up until that blank space is returned 
			if (val_2.charAt(val_2.length-1) == " " || val_2.charAt(val_2.length-1) == "!" || val_2.charAt(val_2.length-1) == "*" ) {
				txt_2.text( txt_2.text().slice(0,-1) );
			}
			//if the character at the end of the hidden text area is a + and the second to last character is not *, the string - the last six characters (a + and a key from the ipa array) is returned
			else if (val_2.charAt(val_2.length-1) == "+" && val_2.charAt(val_2.length-2) != "*") {
				txt_2.text( txt_2.text().slice(0,-6) );
			}
			//if the character at the end of the hidden text area is not a +, then the string - the last five characters (an ipa array key) is returned
			else {
				txt_2.text( txt_2.text().slice(0,-5) );
			}
			
	});	
	
	
	//appends a * (**** in the hidden value to simplify the backspace button) to represent all consonants
	$( "#all_c" ).on( "click", function() {
		var value = $( this ).val();
			$( "#selected_v" ).append( value + "+");
			$( "#selected_v_hidden" ).append( "****" + "+" );
	});
	//appends a * (** in the hidden value to simplify the backspace button) to represent all consonants
	$( "#except_c" ).on( "click", function() {
		var value = $( this ).val();
			$( "#selected_v" ).append( value );
			$( "#selected_v_hidden" ).append( value );
	});
	
	//removes unnecessary + on submit
	$( "#reg_button" ).on( "click", function() {
			var txt = $('#selected_v');
			var val = $('#selected_v').val();
			var txt_2 = $('#selected_v_hidden');
			var val_2 = $('#selected_v_hidden').val();
			
			if (val.charAt(val.length-1) == "+") {
				txt.text( txt.text().slice(0,-1) );
			}
			if (val_2.charAt(val_2.length-1) == "+") {
				txt_2.text( txt_2.text().slice(0,-1) );
			}
			
			
	});	
	//prevents the user from using their hardware keyboard
	$('#selected_v').keypress(function (e) {
    e.preventDefault();
   
	});
	$('#selected_v_hidden').keypress(function (e) {
    e.preventDefault();
   
	});
	//checks to see if the user changes the select box for extra consonant options
	$('.select').on('change', function() { 
	//if the value isn't the default value
		if ($( "#full_c_list" ).val() != '') {
			//creates an array from the key/value combo stored as the select value
			var full_c_arr = $( "#full_c_list" ).val().split("*")
			//appends the first item in the array to the main text area
			$( "#selected_v" ).append( full_c_arr[0] + "+");
			//appends the second item in the array to the hidden text area
			$( "#selected_v_hidden" ).append( full_c_arr[1] + "+" );
		}
	});
   

	});
</script>
    </head>
    <body>
	<div class="tooltip">Help
		<span class="tooltiptext">
			<p>This form allows you to create syllable inital consontant clusters from the syllable initial consonants that you already selected.</p>
			<p>You may choose to add aditional consonants that were not previously selected using the select box at the bottom of the consonant table.</p>
			<p>You may enter as many clusters as you like, but entering too many may cause display issues.</p>
			<p>Valid clusters look like this: t+r.</p>
			<p>You can use the all consonants button to create a lot of clusters simultaniously. An example of this would be if you wanted /r/ to be able to follow all other
			previously defined consonants. This would look like: *+r.</p>
			<p>You can make exceptions to your all consonant rule by using the exept button. If you wanted to a rule stating that /r/ can follow all consonants except for /w/ 
			it would look like this: *+!w+r.</p>
			<p>vallid all-except statements always have the excepted consonants following the all consonant marker (*)</p>
			<p>You can enter as many exceptions as you want, but using more than one * is not recommened.</p>
			<p>manually defined clusters may be any length, however, it is not recommened to make clusters longer than two consonants using the all consonant rule.</p>
		</span>
	</div>
	<br>
	<form id="info_form" name="info_form" action="../processing/process_lang_info_5.php" method="post">
	<div id="text_div" style="font-size:80%">
        
		
	
		
			
<p style="font-size:120%">Choose <span style="color:red">allowed</span> syllable <span style="color:red">onset</span> consonant clusters </p>
<p style="font-size:80%">Choose consonant clusters you want to use </p>
	
			
			
	
	

		<p style="font-size:80%">leave a space in between each combination </p>
		<p style="font-size:80%"><no8bit>*</no8bit> represents all consonants </p>
		<br>
		<br>

	</div>
		<table>
		
			<tr>
				<td>Bilabial: </td>
				<?php
					//uses an array of user selected ipa characters and their keys to create an ipa keyboard
					
					foreach ($_SESSION['initial_consonants'] as $key => $value) {
						//checks the key to make sure that the correct values are placed in the table row
						if (strpos($key, 'bi') !== FALSE) {
				?>
<script>
$(document).ready(function() {	
	
	//IPA keyboard function, enters ipa characters that corespond to the button that a user presses
	$( "#<?php echo $key;?>" ).on( "click", function() {
			
			var value = $( this ).val();
			
				$( "#selected_v" ).append( value + "+");
				$( "#selected_v_hidden" ).append( "<?php echo $key;?>" + "+" );
			
			
	});
  
   

});	
</script>
				    <td><button class="keyboard" type="button"  id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>	
			
			<tr>
				<td>Labiodental: </td>
				<?php
					//uses an array of user selected ipa characters and their keys to create an ipa keyboard
					foreach ($_SESSION['initial_consonants'] as $key => $value) {
						//checks the key to make sure that the correct values are placed in the table row
						if (strpos($key, 'la') !== FALSE) {
											
						
				?>
<script>
$(document).ready(function() {	
	
	//IPA keyboard function, enters ipa characters that corespond to the button that a user presses
	$( "#<?php echo $key;?>" ).on( "click", function() {
			
			var value = $( this ).val();
			
				$( "#selected_v" ).append( value + "+");
				$( "#selected_v_hidden" ).append( "<?php echo $key;?>" + "+" );
			
			
	});
  
   

});	
</script>
				  <td><button class="keyboard" type="button"  id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>
			
			<tr>
				<td>Dental: </td>
				<?php
					
					//uses an array of user selected ipa characters and their keys to create an ipa keyboard
					foreach ($_SESSION['initial_consonants'] as $key => $value) {
						//checks the key to make sure that the correct values are placed in the table row
						if (strpos($key, 'de') !== FALSE) {
						
				?>
<script>
$(document).ready(function() {
	
	//IPA keyboard function, enters ipa characters that corespond to the button that a user presses
	$( "#<?php echo $key;?>" ).on( "click", function() {
			
			var value = $( this ).val();
			
				$( "#selected_v" ).append( value + "+");
				$( "#selected_v_hidden" ).append( "<?php echo $key;?>" + "+" );
			
			
	});
  
   

});	
</script>
				<td><button class="keyboard" type="button"  id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>	
			
			<tr>
				<td>Alveolar: </td>
				<?php
					//uses an array of user selected ipa characters and their keys to create an ipa keyboard
					foreach ($_SESSION['initial_consonants'] as $key => $value) {
						//checks the key to make sure that the correct values are placed in the table row
						if (strpos($key, 'al') !== FALSE) {
						
				?>
<script>
$(document).ready(function() {	
	
	//IPA keyboard function, enters ipa characters that corespond to the button that a user presses
	$( "#<?php echo $key;?>" ).on( "click", function() {
			
			var value = $( this ).val();
			
				$( "#selected_v" ).append( value + "+");
				$( "#selected_v_hidden" ).append( "<?php echo $key;?>" + "+" );
			
			
	});
  
   

});	
</script>
				<td><button class="keyboard" type="button"  id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>	
			
			<tr>
				<td>Postalveolar: </td>
				<?php
					//uses an array of user selected ipa characters and their keys to create an ipa keyboard
					foreach ($_SESSION['initial_consonants'] as $key => $value) {
						//checks the key to make sure that the correct values are placed in the table row
						if (strpos($key, 'po') !== FALSE) {
						
				?>
<script>
$(document).ready(function() {	
	
	//IPA keyboard function, enters ipa characters that corespond to the button that a user presses
	$( "#<?php echo $key;?>" ).on( "click", function() {
			
			var value = $( this ).val();
			
				$( "#selected_v" ).append( value + "+");
				$( "#selected_v_hidden" ).append( "<?php echo $key;?>" + "+" );
			
			
	});
  
   
});
	
</script>				
				    <td><button class="keyboard" type="button"  id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>	
			
			<tr>
				<td>Retroflex: </td>
				<?php
					//uses an array of user selected ipa characters and their keys to create an ipa keyboard
					foreach ($_SESSION['initial_consonants'] as $key => $value) {
						//checks the key to make sure that the correct values are placed in the table row
						if (strpos($key, 're') !== FALSE) {
						
				?>
<script>
$(document).ready(function() {	
	
	//IPA keyboard function, enters ipa characters that corespond to the button that a user presses
	$( "#<?php echo $key;?>" ).on( "click", function() {
			
			var value = $( this ).val();
			
				$( "#selected_v" ).append( value + "+");
				$( "#selected_v_hidden" ).append( "<?php echo $key;?>" + "+" );
			
			
	});
  
   
});
	
</script>
				<td><button class="keyboard" type="button"  id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>	
				
			<tr>
				<td>Palatal: </td>
				<?php
					//uses an array of user selected ipa characters and their keys to create an ipa keyboard
					foreach ($_SESSION['initial_consonants'] as $key => $value) {
						//checks the key to make sure that the correct values are placed in the table row
						if (strpos($key, 'pa') !== FALSE) {
						
				?>
<script>
$(document).ready(function() {	
	
	//IPA keyboard function, enters ipa characters that corespond to the button that a user presses
	$( "#<?php echo $key;?>" ).on( "click", function() {
			
			var value = $( this ).val();
			
				$( "#selected_v" ).append( value + "+");
				$( "#selected_v_hidden" ).append( "<?php echo $key;?>" + "+" );
			
			
	});
  
   
});
	
</script>
				<td><button class="keyboard" type="button"  id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>		
			
			<tr>
				<td>Velar: </td>
				<?php
					//uses an array of user selected ipa characters and their keys to create an ipa keyboard
					foreach ($_SESSION['initial_consonants'] as $key => $value) {
						//checks the key to make sure that the correct values are placed in the table row
						if (strpos($key, 've') !== FALSE) {
						
				?>
<script>
$(document).ready(function() {	
	
	//IPA keyboard function, enters ipa characters that corespond to the button that a user presses
	$( "#<?php echo $key;?>" ).on( "click", function() {
			
			var value = $( this ).val();
			
				$( "#selected_v" ).append( value + "+");
				$( "#selected_v_hidden" ).append( "<?php echo $key;?>" + "+" );
			
			
	});
  
   
});
	
</script>
				<td><button class="keyboard" type="button"  id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>	
			
			<tr>
				<td>Uvular: </td>
				<?php
					//uses an array of user selected ipa characters and their keys to create an ipa keyboard
					foreach ($_SESSION['initial_consonants'] as $key => $value) {
						//checks the key to make sure that the correct values are placed in the table row
						if (strpos($key, 'uv') !== FALSE) {
						
				?>
<script>
$(document).ready(function() {	
	
	//IPA keyboard function, enters ipa characters that corespond to the button that a user presses
	$( "#<?php echo $key;?>" ).on( "click", function() {
			
			var value = $( this ).val();
			
				$( "#selected_v" ).append( value + "+");
				$( "#selected_v_hidden" ).append( "<?php echo $key;?>" + "+" );
			
			
	});
  
   
});
	
</script>
				<td><button class="keyboard" type="button"  id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>
			
			<tr>
				<td>Pharyngeal: </td>
				<?php
					//uses an array of user selected ipa characters and their keys to create an ipa keyboard
					foreach ($_SESSION['initial_consonants'] as $key => $value) {
						//checks the key to make sure that the correct values are placed in the table row
						if (strpos($key, 'ph') !== FALSE) {
						
				?>
<script>
$(document).ready(function() {	
	
	//IPA keyboard function, enters ipa characters that corespond to the button that a user presses
	$( "#<?php echo $key;?>" ).on( "click", function() {
			
			var value = $( this ).val();
			
				$( "#selected_v" ).append( value + "+");
				$( "#selected_v_hidden" ).append( "<?php echo $key;?>" + "+" );
			
			
	});
  
   
});
	
</script>
				    <td><button class="keyboard" type="button"  id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>
			
			<tr>
				<td>Glotal: </td>
				<?php
					//uses an array of user selected ipa characters and their keys to create an ipa keyboard
					foreach ($_SESSION['initial_consonants'] as $key => $value) {
						//checks the key to make sure that the correct values are placed in the table row
						if (strpos($key, 'gl') !== FALSE) {
						
				?>
<script>
$(document).ready(function() {	
	
	//IPA keyboard function, enters ipa characters that corespond to the button that a user presses
	$( "#<?php echo $key;?>" ).on( "click", function() {
			
			var value = $( this ).val();
			
				$( "#selected_v" ).append( value + "+");
				$( "#selected_v_hidden" ).append( "<?php echo $key;?>" + "+" );
			
			
	});
  
   
});
	
</script>
				<td><button class="keyboard" type="button"  id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>
			<tr>
				<td>Others: </td>
				<?php
					//uses an array of user selected ipa characters and their keys to create an ipa keyboard
					foreach ($_SESSION['initial_consonants'] as $key => $value) {
						//checks the key to make sure that the correct values are placed in the table row
						if (strpos($key, 'ot') !== FALSE) {
						
				?>
<script>
$(document).ready(function() {	
	
	//IPA keyboard function, enters ipa characters that corespond to the button that a user presses
	$( "#<?php echo $key;?>" ).on( "click", function() {
			
			var value = $( this ).val();
			
				$( "#selected_v" ).append( value + "+");
				$( "#selected_v_hidden" ).append( "<?php echo $key;?>" + "+" );
			
			
	});
  
   
});
	
</script>
				<td><button class="keyboard" type="button"  id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></td>

				<?php
						}
					}
				?>
			</tr>	
			<tr>
				<td>Full List: </td>
				<td><select class="select" name="full_c_list" id="full_c_list">
				<option value="">Select</option>
				<?php
					//places the values of $type_array from language_arrays.php
					// in select box
					foreach ($ipa_all_consonants as $key => $value) {
				?>
					<option value="<?php echo $value;?>*<?php echo $key;?>"><?php echo $value;?></option>
				<?php
					}
				?>
			</select></td>
			</tr>
			
		</table>		
			<br>
			<div class="wrapper">
				<button type="button" id="space" id="space" name="space" value="space" align = "center">Space</button>
				<button type="button" id="back" id="back" name="back" value="back" align = "center">Backspace</button>
				<br>
				<br>
				<button type="button" id="all_c" id="all_c" name="all_c" value="*" align = "center_c">All Consonants</button>
				<button type="button" id="except_c" id="except_c" name="except_c" value="!" align = "center">Except</button>
				<br>
				<br>
				<br>
	
				<textarea autofocus name ="selected_v" id="selected_v"></textarea>
				<textarea autofocus name ="selected_v_hidden" id="selected_v_hidden" style="display: none"></textarea>
			</div>

			<p><input type="submit"  id = "reg_button" value = "Next" align = "center"/></p>
	
	
	</form>
</body>
</html>