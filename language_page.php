<?php
  include_once 'includes/db_connect.php';
  include_once 'includes/functions.php';
  

  $my_site_title = 'Lingorator ';
  session_start();

	$lang_name = $_SESSION['lang_name'];
	$lang_id = $_SESSION['lang_id'];
	

  
  $username = $_SESSION['username'];
  
  $table_main = "main";
  $non_initials = "non_initials";
  $non_finals = "non_finals";
 
  

  $retrieve_language_query = "SELECT * FROM `$username`.`$table_main` WHERE lang_id = $lang_id";
  $retrieve_language_result = $language_creator->query($retrieve_language_query);
  if ($retrieve_language_result->num_rows > 0) { 
     for ($i = 0; $i < $retrieve_language_result->num_rows; $i++) { 
         $language = $retrieve_language_result->fetch_assoc();
			
			
				$order = $language['word_order'];
				$syll = $language['syllables'];
				$type = $language['type'];
				$addpos = $language['addpos'];
				//$clus_num = $language['clusters'];
				
	}
  }
  
 if ($addpos == 0) {
	 $adposition = 'prepositions';
 }
 else {
	 $adposition = 'postpositions';
 }
	
 $_SESSION['data'] = $language;
?>
<!DOCTYPE HTML>
<html>
  <head>
    <title><?php echo $my_site_title; ?></title>
    <meta charset="utf-8">
	<link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/8bit-wonder" type="text/css"/>
    <link rel="stylesheet" href="/css/site_main.css" type="text/css" media="screen" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/JavaScript" src="js/forms.js"></script>
	
<script>

	
$(document).ready(function() {
	document.getElementById("scroll").style.visibility = "hidden";
	document.getElementById("translation_text_area").style.visibility = "hidden";
	$('#reg_button').click(function(e) {
		e.preventDefault();

		
		var info = $('#english').val();
		var name = $('#lang_name').val();
	
		
		$.ajax({
			type: "POST",
			cache: false,
			url: "processing/process_vocab.php",
			data: {english: info, name: name},
			success: function(data){
				$('#translation_text_area').html(data)
				document.getElementById("scroll").style.visibility = "visible";
				document.getElementById("translation_text_area").style.visibility = "visible";
			}
		});
		
		
	});
	
		
	 });
</script>
<?php	
	//redirects to the homepage if no user is logged in
	if (login_check($mysqli) != true) {
		header("Location: index.php");
		exit;
	}
?>
  </head>
  <body>
  <div id="header" align="center">
	<div id="login" align ="right">
		
		<!-- adapted from http://jsfiddle.net/JoshMesser/vbMw3/	-->
	<div id="overlay1">
   	 <a href="#o" class="close">&times;</a>
    
		<?php include 'includes/login_form_frame.php'; ?>

	</div>

		<a href="#overlay1" id="open-overlay">Logout</a>
		
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
	<button type="button" id="nav_button" onclick="location.href='about.php';">About</button>
	<br>
	<br>
	<a href="#overlay2" id="open-overlay2">guide</a>
	<br>
	<br>
	<a href="#overlay1" id="open-overlay2">Create a language</a>
  </div>
  
  <div id="lang_page_div">
	<div id="lang_page_name" style="text_align: center">
		<p style="font-size:100%"><?php echo $lang_name; ?></p>
	</div>
  </div>
<div id="lang_container">

  <div id="lang_page_text">
 
		
		<dl>
			<dt>Word Order</dt><br>
				<dd style="font-size:80%"><?php echo $order; ?></dd><br>
			<dt>Adposition type</dt><br>
				<dd style="font-size:80%"><?php echo $adposition; ?></dd><br>	
			<dt>Language Type</dt><br>
				<dd style="font-size:80%"><?php echo $type; ?></dd><br>
			<dt>Syllables</dt><br>
				<dd style="font-size:80%"><?php echo $syll; ?></dd><br>
			<dt>Vocabulary tagging key</dt><br>
			<dt style="font-size:60%">use capitals for tags and lowercase for vocabulary</dt><br>
				<dd style="font-size:60%">N: = Noun </dd><br>
				<dd style="font-size:60%">PRO: = Pronoun </dd><br>
				<dd style="font-size:60%">V: = Verb </dd><br>
				<dd style="font-size:60%">ADV: = Adverb </dd><br>
				<dd style="font-size:60%">ADJ: = Adjective </dd><br>
				<dd style="font-size:60%">P: = Preposition </dd><br>
				<dd style="font-size:60%">D: = Determiner </dd><br>
				<dd style="font-size:60%">CON: = Conjunction </dd><br>
				<dd style="font-size:60%">AF: = Affix </dd><br>
				<dd style="font-size:60%">I: = Inflection </dd><br>
				<dd style="font-size:60%">INT: = Interjection </dd><br>
				<dd style="font-size:60%">C: = Case </dd><br>
				<dd style="font-size:60%">G: = Gender </dd><br>
				<dd style="font-size:60%">CLS: = Classifier </dd><br>
				<dd style="font-size:60%">NUM: = Numbers </dd><br>
				<dd style="font-size:60%">EN: = keep a word in english </dd><br>
			<dt style="font-size:60%">use - to concatinate morphemes</dt><br>
				
	</dl>
		
	</div>

	
	<div id="lang_text_area">
	<p style="font-size:100%">Enter English here</p>
	<br>
	<ul>
	<li style="font-size:80%" align="left">please remember to tag parts of speech and phrases</li>
	<li style="font-size:80%" align="left">Leave spaces between phrase tags and between words</li>
	<li style="font-size:80%" align="left">Write POS tags in caps and vocabulary in lowercase</li>
	<li style="font-size:80%" align="left">Click the "Guide" button for more instructions</li>
	</ul>
	<p style="font-size:80%">Phrase tagging key: <no8bit>[]</no8bit> = Subject, <no8bit>||</no8bit> = Object, <no8bit>()</no8bit> = vp,<no8bit>{}</no8bit> = pp </p>
	<br>
	<br>
	<input type="hidden" id="lang_name" name="lang_name" value="<?php echo $lang_name?>">
			<textarea autofocus name = "english" id="english">[PRO:1sg] (ADV:quietly V:cook-I:pst) |ADJ:green N:pancake NUM:5-CLS:flatthings {P:in D:the N:kitchen}|</textarea>
	<br>
	<br>
	<p><input type="submit"  id = "reg_button" value = "Translate" align = "center"  /></p>
	<br>
	<p id="scroll"style="font-size:80%">Scroll down for more detailed information</p>
	<br>
	</div>
	
	<div id="translation_text_area">
	</div>
	
	
</div>
  
  
   
  </body>
</html>