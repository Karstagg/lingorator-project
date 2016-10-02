<?php
include_once '../includes/db_connect.php';
include_once '../includes/functions.php';
 
//session_start();

if (login_check($mysqli) == true) {
    $logged = 'in';
} else {
    $logged = 'out';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Secure Login: Log In</title>
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
			<div class="tooltip">Help
				<span class="tooltiptext">
					<p>Here you can choose to either log in or create a new account.</p>
					<p>Please record your password as there is not currently support for lost passwords.</p>
				</span>
			</div>
			<div id="text_div">
<?php
			if (login_check($mysqli) == true) { //tells the user they are logged in
                        echo '<p>Currently logged ' . $logged . ' as ' . htmlentities($_SESSION['username']) . '.</p>';
			
				echo '<p>Do you want to change user? <a href="../includes/logout.php">Log out</a>.</p>';

			} 
			else { //tells the user they are not logged in
                        echo '<p>Currently logged ' . $logged . '</p>';
                        
                } 
			if (isset($_GET['error'])) {
				echo '<p class="error" style="color:red">Error, please enter a username and password</p>';
			};
?> 
			</div>
		</div>
		<div id="login_form">
			<div id="text_div">
				<form action="../includes/process_login.php" method="post" name="login_form">                      
					<br>
					<br>
					<br>
					<label for="email">Email: </label>
					<input type="text" name="email" id="email"/>
					<br>
					<br>
					<label for="password">Password: </label>
					<input type="password" name="password" id="password"/>
					<br>
					<br>
					<br>
					<br>
					<br>
			</div>
			<div id="login_button_div">
		   <input type="button" 
                   value="Login" 
				   id = "login_button"
				   align = "center"
                   onclick="formhash(this.form, this.form.password);" />
			</div>
				</form>
			<br>
			<br>
		</div>
		<div id="reg_div2">
			<div id="text_div">
<?php
				if (login_check($mysqli) != true) { //if the user is not already logged in
				echo "<p> or </p><br>";
?>
			<div id="reg_div3">
<?php
			echo "<p> <button id='reg_button' onclick=\"location.href='../forms/register.php';\">Create an account</button></p>";
				};
        
?>  	
			</div>
	</div>
</div>



    </body>
</html>