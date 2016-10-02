<?php
include_once 'db_connect.php';
include_once 'functions.php';
 


if (login_check($mysqli) == true) {
    $logged = 'in';
} else {
    $logged = 'out';
	
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Confirmation</title>
        <link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/8bit-wonder" type="text/css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		 <link rel="stylesheet" href="../css/site_main.css" />
        <script type="text/JavaScript" src="../js/sha512.js"></script> 
        <script type="text/JavaScript" src="../js/forms.js"></script>
	

    </head>
    <body>
	<br>
	<br>
        
		
	    <div id="reg_div">
			<div id="text">
<?php
			if (login_check($mysqli) == true) { //tells the user they are logged in
                        echo '<p>Currently logged ' . $logged . ' as ' . htmlentities($_SESSION['username']) . '.</p>';
			} 

			
?>	
<!-- logs the user out -->		
<p><input type="button" value="Logout" id = "login_button" align = "center" onclick="location.href='logout.php';" /></p>
<script>
		//sends the user back to the index page on logout
		$('#login_button').click(function() {
			parent.location.reload();
		});
</script>
<br>
<br>
<br>
<br>
<!-- returns to the language page -->
<p><input type="button" id = "login_button" onclick=" window.history.back();" value="Go Back" /></p>


			
		
 
			</div>
		</div>




    </body>
</html>