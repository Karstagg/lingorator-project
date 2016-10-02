<?php
	include_once 'functions.php';
	include_once 'db_connect.php';
	session_start()
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>frame_contents_login</title>
        
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		 <link rel="stylesheet" href="../css/site_main.css" />
        <script type="text/JavaScript" src="../js/sha512.js"></script> 
        <script type="text/JavaScript" src="../js/forms.js"></script>
    </head>
    <body>
		<div id="frame_contents">
<?php
		if (login_check($mysqli) == true) {		
			include_once '../forms/protected_page.php';
			
		}
		else {
			include_once '../forms/login_page.php';
			
		}
?>
		<div>

    </body>
</html>