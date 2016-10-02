<!-- input form that allows users to ender a code name for their language -->
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
	document.getElementById("reg_button").style.visibility = "hidden";
	//checks to see if all select fields have been selected
	$('#lang_name').on('keyup', function() { 
		if ($( "#lang_name" ).val() != '') {
			document.getElementById("reg_button").style.visibility = "visible";
		}
	});
});
</script>
   </head>
    <body>
	<div class="tooltip">Help
		<span class="tooltiptext">
			<p>Enter a code name for your language. This will be used to save your language for future access.</p>
			<p>Code names can only consist of standard latin alphabet characters a-z.</p>
		</span>
	</div>
	
	<div id="text_div">
	<form action="../processing/process_lang.php" method="post">
	<br><br><h2 style="text-align: center;">Create a New Language</h2><br><br>
        <label for="lang_name">Language code name: </label>
					<input type="text" name="lang_name" id="lang_name"/>
		<p style="font-size:75%">Please only use letters a-z</p>
		<br>		
		<br>		
		<br>
		<p><input type="submit"  id = "reg_button" value = "Create Your Language" align = "center"  /></p>
	</form>
	<br>
	<br>
	<input type="button" 
                   value="Back" 
				   id="reg_close" onclick="location.href='../forms/protected_page.php'"/> 
    
	</div>
	</body>
</html>