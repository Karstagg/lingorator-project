<?php
//allows the user to set allowed vowel combinations (diphthongs
	$username = $_SESSION['username'];
	$lang_name = $_SESSION['lang_name'];
	

	

	



?>

<!-- input form that allows users to enter specifications for their language-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Create_language_info_allowed_diphthongs</title>
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
	
	
	//appends a * (** in the hidden value to simplify the backspace button) to represent all consonants
	$( "#all_c" ).on( "click", function() {
		var value = $( this ).val();
			$( "#selected_v" ).append( value + "+");
			$( "#selected_v_hidden" ).append( "**" + "+" );
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
			<p>This form allows you to create diphthongs, or vowels that can occur next to eachother if your language does not use diphthongs, from the vowels that you previously selected.</p>
			<p>Use the buttons provided to create combinations of vowels. Valid combinations will look something like this: a+i.</p>
			<p>You may create up to 20 combinations.</p>
			<p>IMPORTANT: Combinations of more than three characters are not recommended and may cause display problems in other forms.</p>
		</span>
	</div>
	<br>
	<form id="info_form" name="info_form" action="../processing/process_lang_info_4.php" method="post">
	<div id="text_div" style="font-size:80%">
        
		
		
		<p style="font-size:120%">Choose diphthongs/possible vowel groupings</p>
		<p style="font-size:80%">leave a space in between each combination </p>
		<br>
		<br>
	
	</div>
		<table>
			<tr>
  
				<?php
					//places the values of ipa v array from language_arrays.php
					
					foreach ($_SESSION['vowels'] as $key => $value) {
						
						if (strpos($key, 'va') !== FALSE) {
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
							
							<td><button class="keyboard" type="button" id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></button></td>

				<?php
						}
					}
				?>
			</tr>	
				
				
			<tr>	
				<?php
					//places the values of ipa v array from language_arrays.php
					
					foreach ($_SESSION['vowels'] as $key => $value) {
						//if the key from the vowel array matches info stored in the db about a vowel
						if (strpos($key, 'vb') !== FALSE) {
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
				    <td><button class="keyboard" type="button"  id="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $value;?>"><?php echo $value;?></button></td>

				<?php
						}
					}
				?>
			</tr>
			
			
		</table>	
			<br>
			<div class="wrapper">
				<button type="button" id="space" id="space" name="space" value="space" align = "center">space</button>
				<button type="button" id="back" id="back" name="back" value="back" align = "center">Backspace</button>
				<br>
				<br>
				<br>
				<!--the first text area shows the user what they have selected, the second is used to send info that can be saved to the database -->
				<textarea autofocus name ="selected_v" id="selected_v"></textarea>
				<br>
				<textarea autofocus name ="selected_v_hidden" id="selected_v_hidden" style="display: none" ></textarea>
			</div>
			
	<br>
			<p><input type="submit"  id = "reg_button" value = "Next" align = "center"/></p>
	
	
	</form>
</body>
</html>