<?php
	$username = $_SESSION['username'];
	$lang_name = $_POST['lang_name'];
?>

<!-- input form that allows users to enter specifications for their language-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Create_language_info</title>
        <link rel="stylesheet" href="../css/site_main.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type="text/JavaScript" src="../js/forms.js"></script> 
		<link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/8bit-wonder" type="text/css"/>
<script>

$( document ).ready(function() {
	//sets the options for the addposition select box
	document.getElementById("addpos").innerHTML ='<option value="Select">Select</option><option value="0">Prepositions</option><option value="1">Postpositions</option>';
	document.getElementById("addpos").innerHTML ='<option value="Select">Select</option>';
	document.getElementById("reg_button").style.visibility = "hidden";
	//checks the word order parameter and only allows users to choose viable addposition types
	$( "#order" ).change(function() {
		//svo and sov languages can use pre or postpositions, although prepositions are rare in SOV. http://webspace.ship.edu/cgboer/basiclangstruct.html 
		if ($( "#order" ).val() == 'SVO' || $( "#order" ).val() == 'SOV'  ) {
			
			document.getElementById("addpos").innerHTML ='<option value="0">Prepositions</option><option value="1">Postpositions</option>';
		}
		//vso languages don't use postpositions  http://webspace.ship.edu/cgboer/basiclangstruct.html 
		else {
			document.getElementById("addpos").innerHTML ='<option value="0">Prepositions</option>';
		
		}
	});
	
	//checks to see if all select fields have been selected
	$('.select').on('change', function() { 
		if ($( "#order" ).val() != 'Select' && $( "#lang_type" ).val() != 'Select' && $( "#syllables" ).val() != 'Select' && $( "#addpos" ).val() != 'Select') {
			document.getElementById("reg_button").style.visibility = "visible";
		}
	});

});
</script>
    </head>
    <body>
	<div class="tooltip">Help
		<span class="tooltiptext">
			<p>This form allows you to set some basic specification for your language.</p>
			<p>Please choose carefully as you will not be able to change these selections.</p>
			<p>Currently Only Agglutinative/Analytic languages can be created with Lingorator.</p>
			<p>VSO languages can only use prepositions, this reflects real human languages.</p>
			<p>Lingorator syllables are randomly selected, choosing "mostly open" means that the program will generate more open syllables (CV), while 
			selecting "mostly closed" will generate more closed syllables (CVC). all syllable sellections can generate morphemes that begin with a vowel.</p>
			<p>IMPORTANT: in order to properly use the following forms you must have JavaScript enables.</P>
			<p>IMPORTANT: do not refresh your browser at anytime while filling out forms on lingorator. Doing so will result in the loss of your data.</P>
		</span>
	</div>
	<form id="info_form" name="info_form" action="../processing/process_lang_info_1.php" method="post">
	<div id="text_div" style="font-size:80%">
        
		<input type="hidden" id="lang_name" name="lang_name" value="<?php echo $lang_name?>">			
		<br>
		<br>
		<!-- the current version of this software does not support fusional languages but does provide the tools to create reasonable agglutinating and analytical languages -->
		<label for="lang_type">Language Type: </label>
			<select class="select" name="lang_type" id="lang_type">
				
					<option value="Agglutinative/Analytic">Agglutinative/Analytic</option>
				
			</select>
		<br>
		<br>
		<label for="order">Word Order: </label>
			<select class= "select" name="order" id="order">
				<?php
					//places the values of $order_array from language_arrays.php
					// in the select box 
					foreach ($order_array as $value) {
				?>
					<option value="<?php echo $value;?>"><?php echo $value;?></option>
				<?php
					}
				?>
			</select>
		<br>
		<br>
		<!-- options added with javascript -->
		<label for="addpos">Addpositions: </label>
			<select class= "select" name="addpos" id="addpos">
				
			</select>
		<br>
		<br>
		<label for="syllables">Syllable Type: </label>
			<select class= "select" name="syllables" id="syllables">
				
					<option value="Select">Select</option>
					<option value="open">Open</option>
					<option value="mostly_open">Mostly Open</option>
					<option value="mixed">Mixed</option>
					<option value="closed">Closed</option>
					<option value="mostly_closed">Mostly Closed</option>
				
			</select>
		<br>
		<br>
		
			<p><input type="submit"  id = "reg_button" value = "Create Your Language" align = "center"/></p>
	</div>
	</form>
	
    </body>
</html>