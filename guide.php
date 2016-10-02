<?php


  $my_site_title = 'Lingorator ';
?>
<!DOCTYPE HTML>
<html>
  <head>
    <title><?php echo $my_site_title; ?></title>
    <meta charset="utf-8">
	<link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/8bit-wonder" type="text/css"/>
	<link rel="stylesheet" href="../css/site_main.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/JavaScript" src="js/forms.js"></script> 

  </head>
  <body>
  <div id="header" align="center">
	<div id="login" align ="right">
		
		<!-- adapted from http://jsfiddle.net/JoshMesser/vbMw3/	-->
	<div id="overlay1">
   	 <a href="#o" class="close">&times;</a>
    
<?php include_once 'includes/login_form_frame.php'; ?>

	</div>
		<div id="mask" onclick="document.location='#o';"></div> <!-- the only javascript -->

		<a href="#overlay1" id="open-overlay">Login</a>
		
	</div>
		
	
	
	<div id="headertext1">
		<h1><?php echo $my_site_title; ?></h1>
		<div id="headertext2">
			<h2>The Interactive Language Generator</h2>
		</div>
	</div>
  </div>
  <div id="nav">
	<br>
	<button type="button" id="nav_button" onclick="location.href='index.php';">Home</button>
	<br>
	<br>
	<button type="button" id="nav_button" onclick="location.href='about.php';">About</button>
	<br>
	<br>
	<button type="button" id="nav_button_current">Guide</button>
	<br>
	<br>
	<a href="#overlay1" id="open-overlay2">Create a language</a>
  </div>
 
    

  </body>
</html>