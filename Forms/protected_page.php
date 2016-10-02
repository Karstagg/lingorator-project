<!--- menu page for logged in users-->

<?php
include_once '../includes/db_connect.php';
include_once '../includes/functions.php';
//if there is no active session
 if (session_status() === PHP_SESSION_NONE) {
	session_start();
 }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>User Menu</title>
       
		<link rel="stylesheet" href="../css/site_main.css" />
        <script type="text/JavaScript" src="../js/sha512.js"></script> 
        <script type="text/JavaScript" src="../js/forms.js"></script>
		<link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/8bit-wonder" type="text/css"/>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    </head>
    <body>
	<div class="tooltip">Help
		<span class="tooltiptext">
			<p>If you wish to work with a language that you have previously created, click on "Select a Language".</p>
		</span>
	</div>
	<div id="text_div">
		<!-- checks to see if the user is logged in. adapted from http://www.wikihow.com/Create-a-Secure-Login-Script-in-PHP-and-MySQL, specifially if statement notation involving :-->
        <?php if (login_check($mysqli) == true) : ?>
			<p align="center" style="font-size: 20pt">Welcome to Lingorator</p>
            <p align="center">Logged in as: <?php echo htmlentities($_SESSION['username']); ?></p>
			<br>
			<br>
            <p><input type="button" value="Select a language" id = "reg_button" align = "center" onclick="location.href='../forms/select_lang.php';" /></p>
			<br>
			<br>
			<p><input type="button" value="Create a New Language" id = "reg_button" align = "center" onclick="location.href='../forms/create_lang.php';" /></p>
			<br>
			<br>
			<p><input type="button" value="Logout" id = "login_button" align = "center" onclick="location.href='logout.php';" /></p>
<script>
		//Reloads the main page on logout, if the user is on a protected page this will redirect them to the homepage
		$('#login_button').click(function() {
			parent.location.reload();
		});
</script>
		
			

		<!-- if the user is not logged in-->
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="login_page.php">login</a>.
            </p>
        <?php endif; ?>
		</div>
		
    </body>
</html>