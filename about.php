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
		

		<a href="#overlay1" id="open-overlay">Login</a>
		
	</div>
<div id="overlay2">
   	 <a href="#guide" class="close">&times;</a>
    
		<?php include 'includes/guide_form_frame.php'; ?>

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
	<button type="button" id="nav_button_current" onclick="location.href='about.php';">About</button>
	<br>
	<br>
	<a href="#overlay2" id="open-overlay2">guide</a>
	<br>
	<br>
	<a href="#overlay1" id="open-overlay2">Create a language</a>
  </div>
 
 
	<div id="lang_container">
		<div id="text_div">
		<br>
		<h1>What Lingorator Does<h1>
		</div>
		<p>The main purpose of Lingorator is to help users create their own natural language by automating some of the more time consuming aspects of such an undertaking.</P>
		<p>At its core, Lingorator is a program that translates user input language into a new, freshly generated language</P>
		<p>Lingorator accomplishes its goal by doing the following: </P>
		<ul>
			<li>Generating vocabulary in IPA based on user specified phonotactics</li>
			<li>Re-organizing simple sentences into different world orders based on user preference</li>
			<li>Re-organizing prepositions into postpositions based on the user's preference</li>
			<li>Storing language settings and vocabulary for use at a later time</li>
		</ul>
		<div id="text_div">
		<h1>What Lingorator does not do<h1>
		</div>
		<p>Lingorator does not create entire languages from scratch without user input.</P>
		<p>Lingorator cannot: </P>
		<ul>
			<li>Determine parts of speach or phrase structures without tagging by the user</li>
			<li>Generate vocabulary without user input</li>
			<li>translate complex sentences or more than one sentence at a time</li>
		</ul>
		<div id="text_div">
		<h1>Future plans for Lingorator<h1>
		</div>
		<p>Lingorator is a program that could, given time, be expanded to support more kinds of language and the gramatical features of those languages</P>
		<p>Planned additions:</P>
		<ul>
			<li>The ability to create fusional languages</li>
			<li>Giving users direct control of more variable, such as syllable count per word or morpheme</li>
			<li>Giving users the ability set the head for noun phrases so that they are automatically output in the correct order</li>
			<li>Giving users the ability to determine the frequency of long vowels</li>
			<li>Giving users a method to create tonal languages and mark stress</li>
			<li>The ability to use all word orders, even the less common ones</li>
		</ul>
	</div>
    

  </body>
</html>