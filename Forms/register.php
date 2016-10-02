<?php

 //code adapted from http://www.wikihow.com/Create-a-Secure-Login-Script-in-PHP-and-MySQL

include_once '../includes/register.inc.php';
include_once '../includes/functions.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Lingorator Registration</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/8bit-wonder" type="text/css"/>
        <script type="text/JavaScript" src="../js/sha512.js"></script> 
        <script type="text/JavaScript" src="../js/forms.js"></script>
        <link rel="stylesheet" href="../css/site_main.css" />

    </head>
    <body>
        <!-- Registration form to be output if the POST variables are not
        set or if the registration script caused an error. -->
	<div id="reg_form">
        <h1>Create a Lingorator account</h1>
        <?php
        if (!empty($error_msg)) {
            echo $error_msg;
        }
        ?>
		<br>
        <ul>
            <li>Usernames may contain only digits, upper and lowercase letters and underscores</li>
            <li>Emails must have a valid email format</li>
            <li>Passwords must be at least 6 characters long</li>
            <br>
			<br>
			<li>Passwords must contain
                <ul>
                    <li>At least one uppercase letter (A..Z)</li>
                    <li>At least one lowercase letter (a..z)</li>
                    <li>At least one number (0..9)</li>
                </ul>
            </li>
			<br>
			<br>
            <li>Your password and confirmation must match exactly</li>
        </ul>
		<br>
		<br>
        <form id="reg_form" action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" 
                method="post" 
                name="registration_form">
            Username: <input type='text' 
                name='username' 
                id='username' /><br>
            Email: <input type="text" name="email" id="email" /><br>
            Password: <input type="password"
                             name="password" 
                             id="password"/><br>
            Confirm password: <input type="password" 
                                     name="confirmpwd" 
                                     id="confirmpwd" /><br>
	<br>
	<br>
	

   <input type="button" 
                   value="Register" 
				   id="reg_button"
                   onclick="regformhash(this.form,
                                   this.form.username,
                                   this.form.email,
                                   this.form.password,
                                   this.form.confirmpwd);" /> 
	<br>
	<br>
	<br>
	<input type="button" 
                   value="Back to login" 
				   id="reg_close" onclick="location.href='../forms/login_page.php'"/> 

        </form>
    </div>
    </body>
</html>