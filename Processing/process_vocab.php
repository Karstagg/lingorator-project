<?php
 
 //turning off errors because there are simply too many that can be made
 // and most of them will not completely destroy the output
 //error_reporting(0);
	include_once "../includes/language_arrays.php";
	include_once "../includes/db_connect.php";
	include_once "../includes/functions.php";
	
	session_start();
	$username = $_SESSION['username'];
	$lang_name = $_POST['name'];
	$text = mysqli_real_escape_string($language_creator, $_POST['english']);
	$text_array = explode(" ", $text);

	
	$table_main = "main";
	
	
	
	//table names for english vocab
	$table_v = "verbs";
	$table_n = "nouns";
	$table_adj = "adjectives";
	$table_adv = "adverbs";
	$table_pro = "pronouns";
	$table_prep = "prepositions";
	$table_conj = "conjunctions";
	$table_det = "determiners";
	$table_i = "inflections";
	$table_int ="interjections";
	$table_class = "classifiers";
	$table_case = "cases";
	$table_af = "affixes";
	$table_num = "numbers";
	$table_gen = "gender";
	//table for special other features of the language
	$table_others = "others";
	
	//for user defined sound system
	$table_vowel = "vowels";
	$table_ini_consonants = "initial_consonants";
	$table_fin_consonants = "final_consonants";
	$table_allowed_v = "allowed_v_combo";
	$table_ini_clusters = "initial_clusters";
	$table_fin_clusters = "final_clusters";
	$table_non_initial = "non_initials";
	$table_non_final = "non_finals";
	$table_forbidden_vc = "no_vc_combo";
	$table_forbidden_isolation = "forbidden_isolation";
	$table_diphthongs = "diphthongs";
	
	
	//contains information about the current language
	$data = (object) $_SESSION['data'];
	
	$order;
	
	
	//selects the word order information from the $data array. 
	//changed from foreach to object oriented notation for reading ease.
	$order = $data->{'word_order'};
	$addpos = $data->{'addpos'};
	$lang_type = $data->{'type'};
	$lang_id = $data->{'lang_id'};
	//$clus_num = $data->{'clusters'};
	
	//change this - needs to be coming from the db not sessions
	$syllables = $data->{'syllables'};
	
	
	

$v_base;
$adj_base;
$adv_base;	

//arrays to store user defined sounds retrieved from the db. these are retrieved as IPA
$vowel_values = [];
$onset_consonant_values = [];
$coda_consonant_values = [];
$onset_cluster_values = [];
$coda_cluster_values = [];
$non_initial_values = [];
$non_final_values = [];
$dip_values = [];
$vc_combo_values = [];


//queries used to check if vocabulary items have already been defined.
$retrieve_verbs_query = "SELECT eng, new_root, new_inf FROM `$username`.`$table_v` WHERE v_id = $lang_id";
$retrieve_nouns_query = "SELECT * FROM `$username`.`$table_n` WHERE n_id = $lang_id";
$retrieve_adjectives_query = "SELECT * FROM `$username`.`$table_adj` WHERE adj_id = $lang_id";
$retrieve_adverbs_query = "SELECT * FROM `$username`.`$table_adv` WHERE adv_id = $lang_id";
$retrieve_pronouns_query = "SELECT * FROM `$username`.`$table_pro` WHERE pro_id = $lang_id";
$retrieve_addpositions_query = "SELECT * FROM `$username`.`$table_prep` WHERE prep_id = $lang_id";
$retrieve_conjunctions_query = "SELECT * FROM `$username`.`$table_conj` WHERE conj_id = $lang_id";
$retrieve_determiners_query = "SELECT * FROM `$username`.`$table_det` WHERE det_id = $lang_id";
$retrieve_inflections_query = "SELECT * FROM `$username`.`$table_i` WHERE i_id = $lang_id";
$retrieve_interjections_query = "SELECT * FROM `$username`.`$table_int` WHERE int_id = $lang_id";
$retrieve_affixes_query = "SELECT * FROM `$username`.`$table_af` WHERE af_id = $lang_id";
$retrieve_cases_query = "SELECT * FROM `$username`.`$table_case` WHERE case_id = $lang_id";
$retrieve_classifiers_query = "SELECT * FROM `$username`.`$table_class` WHERE cls_id = $lang_id";
$retrieve_numbers_query = "SELECT * FROM `$username`.`$table_num` WHERE num_id = $lang_id";
$retrieve_gender_query = "SELECT * FROM `$username`.`$table_gen` WHERE gen_id = $lang_id";

$retrieve_vowels_query = "SELECT v FROM `$username`.`$table_vowel` WHERE vowel_id = $lang_id";
$retrieve_ini_consonants_query = "SELECT c FROM `$username`.`$table_ini_consonants` WHERE consonant_id = $lang_id";
$retrieve_fin_consonants_query = "SELECT c FROM `$username`.`$table_fin_consonants` WHERE consonant_id = $lang_id";

$retrieve_ini_clusters_query = "SELECT cluster FROM `$username`.`$table_ini_clusters` WHERE cluster_id = $lang_id";
$retrieve_fin_clusters_query = "SELECT cluster FROM `$username`.`$table_fin_clusters` WHERE cluster_id = $lang_id";
$retrieve_initials_query = "SELECT non_i FROM `$username`.`$table_non_initial` WHERE non_i_id = $lang_id";
$retrieve_finals_query = "SELECT non_f FROM `$username`.`$table_non_final` WHERE non_f_id = $lang_id";
$retrieve_diphthong_query = "SELECT diphthong FROM `$username`.`$table_diphthongs` WHERE diphthong_id = $lang_id";
$retrieve_vc_combo_query = "SELECT vc FROM `$username`.`$table_forbidden_vc` WHERE vc_id = $lang_id";
//$retrieve_isolation_query

	
$retrieve_vowels_result = $language_creator->query($retrieve_vowels_query);
  if ($retrieve_vowels_result->num_rows > 0) { 
     for ($i = 0; $i < $retrieve_vowels_result->num_rows; $i++) { 
         $vow = $retrieve_vowels_result->fetch_assoc(); 
				
				$vowel_values[] = $vow['v'];
	}
  }
 $retrieve_ini_consonants_result = $language_creator->query($retrieve_ini_consonants_query);
  if ($retrieve_ini_consonants_result->num_rows > 0) { 
     for ($i = 0; $i < $retrieve_ini_consonants_result->num_rows; $i++) { 
         $cons_ini = $retrieve_ini_consonants_result->fetch_assoc(); 
				
				$onset_consonant_values[] = $cons_ini['c'];
	}
  }
  $retrieve_fin_consonants_result = $language_creator->query($retrieve_fin_consonants_query);
  if ($retrieve_fin_consonants_result->num_rows > 0) { 
     for ($i = 0; $i < $retrieve_fin_consonants_result->num_rows; $i++) { 
         $cons_fin = $retrieve_fin_consonants_result->fetch_assoc(); 
				
				$coda_consonant_values[] = $cons_fin['c'];
	}
  }
  $retrieve_ini_clusters_result = $language_creator->query($retrieve_ini_clusters_query);
  if ($retrieve_ini_clusters_result->num_rows > 0) { 
     for ($i = 0; $i < $retrieve_ini_clusters_result->num_rows; $i++) { 
         $clus = $retrieve_ini_clusters_result->fetch_assoc(); 
				
				$onset_cluster_values[] = $clus['cluster'];
	}
  }
  $retrieve_fin_clusters_result = $language_creator->query($retrieve_fin_clusters_query);
  if ($retrieve_fin_clusters_result->num_rows > 0) { 
     for ($i = 0; $i < $retrieve_fin_clusters_result->num_rows; $i++) { 
         $clus = $retrieve_fin_clusters_result->fetch_assoc(); 
				
				$coda_cluster_values[] = $clus['cluster'];
	}
  }
   $retrieve_initials_result = $language_creator->query($retrieve_initials_query);
  if ($retrieve_initials_result->num_rows > 0) { 
     for ($i = 0; $i < $retrieve_initials_result->num_rows; $i++) { 
         $ini = $retrieve_initials_result->fetch_assoc(); 
				
				$non_initial_values[] = $ini['non_i'];
	}
  }
   $retrieve_finals_result = $language_creator->query($retrieve_finals_query);
  if ($retrieve_finals_result->num_rows > 0) { 
     for ($i = 0; $i < $retrieve_finals_result->num_rows; $i++) { 
         $fin = $retrieve_finals_result->fetch_assoc(); 
				
				$non_final_values[] = $fin['non_f'];
	}
  }
  $retrieve_vc_combo_result = $language_creator->query($retrieve_vc_combo_query);
  if ($retrieve_vc_combo_result->num_rows > 0) { 
     for ($i = 0; $i < $retrieve_vc_combo_result->num_rows; $i++) { 
         $vc_com = $retrieve_vc_combo_result->fetch_assoc(); 
				
				$vc_combo_values[] = $vc_com['vc'];
	}
  }
  $retrieve_diphthong_result = $language_creator->query($retrieve_diphthong_query);
  if ($retrieve_diphthong_result->num_rows > 0) { 
     for ($i = 0; $i < $retrieve_diphthong_result->num_rows; $i++) { 
         $dip = $retrieve_diphthong_result->fetch_assoc(); 
				
				$dip_values[] = $dip['diphthong'];
	}
  }

//--------------------------------------------------------------------------------------
 //running these functions creats arrays of possible consonant clusters
  $cluster_values_coda = clusterator ($coda_cluster_values, $coda_consonant_values);
  $cluster_values_onset = clusterator ($onset_cluster_values, $onset_consonant_values);

 
 //-----------------------------------------------------------------------------------

	//removes any brackets from the text
	$semi_clean_text = preg_replace('/[^\sA-Za-z0-9:\-]/', '', $text);
	$semi_clean_array = preg_split('/[\s]/', $semi_clean_text, NULL, PREG_SPLIT_NO_EMPTY);
	
	//removes all special characters and caps from the text
	$clean_text = preg_replace('/[^\sa-z0-9\-]/', '', $text);
	$clean_text_array = preg_split('/[\s]/', $clean_text, NULL, PREG_SPLIT_NO_EMPTY);
	
	//creating arrays for english vocab by part of speech
	$n_array_eng = [];
	$pro_array_eng = [];
	$v_array_eng = [];
	$adv_array_eng = [];
	$adj_array_eng = [];
	$prep_array_eng = [];
	$det_array_eng = [];
	$conj_array_eng = [];
	$af_array_eng = [];
	$case_array_eng = [];
	$int_array_eng = [];
	$i_array_eng = [];
	$gender_array_eng = [];
	$class_array_eng = [];
	$num_array_eng = [];
	$eng_array_eng = [];
	
	//----------------------------------------------------------------------------------------------

	//places english vocabulary into arrays for each part of speech
	if (count($semi_clean_array) > 0) {
		foreach (text_array_processor($semi_clean_array, 'full') as $key => $value) {
			
			// create phrases which can be repositioned depending on word order rules
			
			//$pos refers to the position in the string
			$pos = $value[0];
			
			if (strlen($value) > 1) {
				$pos2 = $value[1];
			}
			if (strlen($value) > 2) {
				$pos3 = $value[2];
			}
			if (strlen($value) > 3) {
				$pos4 = $value[3];
			}
			if (strlen($value) > 4) {
				$pos5 = $value[4];
			}
			if (strlen($value) > 5) {
				$pos6 = $value[5];
			}
			
			//these if statements place the words the user entered into arrays after subtracting the tag character
			//parts of speech
			//noun
			if ($pos == 'N' && $pos2 == ':') {
				$n_array_eng[] = substr($value, 2);
			}
			//pronoun
			if ($pos == 'P' && $pos2 == 'R' && $pos3 == "O" && $pos4 == ":") {
				$pro_array_eng[] = substr($value, 4);	
			}
			//verb
			if ($pos == 'V' && $pos2 == ':') {
				
				$v_array_eng[] = substr($value, 2);
			}
			//adv
			if ($pos == 'A' && $pos2 == 'D' && $pos3 == 'V' && $pos4 == ':' ) {
				$adv_array_eng[] = substr($value, 4);
			}
			//adj
			if ($pos == 'A' && $pos2 == 'D' && $pos3 == 'J' && $pos4 == ':' ) {
				$adj_array_eng[] = substr($value, 4);
			}
			//preposition
			if ($pos == 'P' && $pos2 == ':') {
				$prep_array_eng[] = substr($value, 2);
				
			}
			//determiner
			if ($pos == 'D' && $pos2 == ':') {
				$det_array_eng[] = substr($value, 2);
			}
			//conjunction
			if ($pos == 'C' && $pos2 == 'O' && $pos3 == 'N' ) {
				$conj_array_eng[] = substr($value, 4);
			}
			//suffix
			if ($pos == 'A' && $pos2 == 'F' && $pos3 == ':') {
				$af_array_eng[] = substr($value, 3);
			}
			//interjection
			if ($pos == 'I' && $pos2 == 'N' && $pos3 == 'T' && $pos4 == ':') {
				$int_array_eng[] = substr($value, 4);
			}
			//inflection
			if ($pos == 'I' && $pos2 == ':') {
				$i_array_eng[] = substr($value, 2);
			} 
			//case
			if ($pos == 'C' && $pos2 == ':') {
				$case_array_eng[] = substr($value, 2);
			} 
			//gender
			if ($pos == 'G' && $pos2 == ':') {
				$gender_array_eng[] = substr($value, 2);
			} 
			//classifiers
			if ($pos == 'C' && $pos2 == 'L' && $pos3 == 'S'  && $pos4 == ':') {
				$class_array_eng[] = substr($value, 4);
			} 
			//numbers
			if ($pos == 'N' && $pos2 == 'U' && $pos3 == 'M'  && $pos4 == ':') {
				$num_array_eng[] = substr($value, 4);
			} 
			//numbers
			if ($pos == 'E' && $pos2 == 'N' && $pos3 == ':') {
				$eng_array_eng[] = substr($value, 4);
			} 
			
			
			
			
		}
	}
	
//-------------------------------------------------------------------------------
    //these end up being multi-dimensional arrays - the first item contains brackets the second does not
	//sbj np with items in []
	$sbj_array = [];
	//obj np with items in || or |{
	$obj_array = [];
	//vp items in ()
	$vp_array = [];
	//pp items in {}
	$pp_array = [];
	
	
	if (substr_count($text, "{") > 0) {
		//fills pp array. looks for things between {}
		preg_match_all('/\{([^}]+)\}/', $text, $pp_array);
	}	

	if (substr_count($text, "[") > 0) {
		//fills sbj array. looks for things between []
		preg_match_all('/\[([^}]+)\]/', $text, $sbj_array);
	}
	//fills obj array when a pp is present in the obj phrase. looks for things between |{
	if (substr_count($text, "|") > 0) {
		if (count($pp_array) > 1) {
			if (array_key_exists(0, $pp_array[1])) {
				preg_match_all('/\|([^}]+)\{/', $text, $obj_array);
			}
		} //fills obj array when a pp is not present in the obj phrase looks for things between ||
		else {
			
				preg_match_all('/\|([^}]+)\|/', $text, $obj_array);
			
		}
	}
	if (substr_count($text, "(") > 0) {
		//fills the vp array. looks for things between ()
		preg_match_all('/\(([^}]+)\)/', $text, $vp_array);
	}
	
	//recreates the multi-dimensional arrays created by preg match
	//so that if I user enters an un-tagged sentence it will still translated
	//but the order will not be changed
	if (count($sbj_array) == 0 && count($vp_array) == 0 && count($pp_array) == 0 && count($obj_array) == 0) {
		$sbj_array[0] = $text_array;
		$sbj_array[1] = $text_array;
	}
	
	//this variable represents the output of a function that aranges the word order of translated sentences
	$send_text_array = order_changer ($order, $sbj_array, $obj_array, $vp_array, $pp_array, $addpos);

//-------------------------------------------------------------------------------------------------
	//these function calls generate vocabulary words 
	$v_gen = vocab_gen ($username, "v", $lang_id, $v_array_eng, $retrieve_verbs_query, $table_v, $onset_consonant_values, $coda_consonant_values, $vowel_values, $dip_values, $cluster_values_onset, $cluster_values_coda, $language_creator, $non_initial_values, $non_final_values, $syllables, $vc_combo_values);
	$n_gen = vocab_gen ($username, "n", $lang_id, $n_array_eng, $retrieve_nouns_query, $table_n, $onset_consonant_values, $coda_consonant_values, $vowel_values, $dip_values, $cluster_values_onset, $cluster_values_coda, $language_creator, $non_initial_values, $non_final_values, $syllables, $vc_combo_values);
	$adj_gen = vocab_gen ($username, "adj", $lang_id, $adj_array_eng, $retrieve_adjectives_query, $table_adj, $onset_consonant_values, $coda_consonant_values, $vowel_values, $dip_values, $cluster_values_onset, $cluster_values_coda, $language_creator, $non_initial_values, $non_final_values, $syllables, $vc_combo_values);
	$adv_gen = vocab_gen ($username, "adv", $lang_id, $adv_array_eng, $retrieve_adverbs_query, $table_adv, $onset_consonant_values, $coda_consonant_values, $vowel_values, $dip_values, $cluster_values_onset, $cluster_values_coda, $language_creator, $non_initial_values, $non_final_values, $syllables, $vc_combo_values);
	$i_gen = vocab_gen ($username, "i", $lang_id, $i_array_eng, $retrieve_inflections_query, $table_i, $onset_consonant_values, $coda_consonant_values, $vowel_values, $dip_values, $cluster_values_onset, $cluster_values_coda, $language_creator, $non_initial_values, $non_final_values, $syllables, $vc_combo_values);
	$prep_gen = vocab_gen ($username, "prep", $lang_id, $prep_array_eng, $retrieve_addpositions_query, $table_prep, $onset_consonant_values, $coda_consonant_values, $vowel_values, $dip_values, $cluster_values_onset, $cluster_values_coda, $language_creator, $non_initial_values, $non_final_values, $syllables, $vc_combo_values);
	$pro_gen = vocab_gen ($username, "pro", $lang_id, $pro_array_eng, $retrieve_pronouns_query, $table_pro, $onset_consonant_values, $coda_consonant_values, $vowel_values, $dip_values, $cluster_values_onset, $cluster_values_coda, $language_creator, $non_initial_values, $non_final_values, $syllables, $vc_combo_values);
	$conj_gen = vocab_gen ($username, "conj", $lang_id, $conj_array_eng, $retrieve_conjunctions_query, $table_conj, $onset_consonant_values, $coda_consonant_values, $vowel_values, $dip_values, $cluster_values_onset, $cluster_values_coda, $language_creator, $non_initial_values, $non_final_values, $syllables, $vc_combo_values);
	$int_gen = vocab_gen ($username, "int", $lang_id, $int_array_eng, $retrieve_interjections_query, $table_int, $onset_consonant_values, $coda_consonant_values, $vowel_values, $dip_values, $cluster_values_onset, $cluster_values_coda, $language_creator, $non_initial_values, $non_final_values, $syllables, $vc_combo_values);
	$det_gen = vocab_gen ($username, "det", $lang_id, $det_array_eng, $retrieve_determiners_query, $table_det, $onset_consonant_values, $coda_consonant_values, $vowel_values, $dip_values, $cluster_values_onset, $cluster_values_coda, $language_creator, $non_initial_values, $non_final_values, $syllables, $vc_combo_values);
	$af_gen = vocab_gen ($username, "af", $lang_id, $af_array_eng, $retrieve_affixes_query, $table_af, $onset_consonant_values, $coda_consonant_values, $vowel_values, $dip_values, $cluster_values_onset, $cluster_values_coda, $language_creator, $non_initial_values, $non_final_values, $syllables, $vc_combo_values);
	$case_gen = vocab_gen ($username, "case", $lang_id, $case_array_eng, $retrieve_cases_query, $table_case, $onset_consonant_values, $coda_consonant_values, $vowel_values, $dip_values, $cluster_values_onset, $cluster_values_coda, $language_creator, $non_initial_values, $non_final_values, $syllables, $vc_combo_values);
	$cls_gen = vocab_gen ($username, "cls", $lang_id, $class_array_eng, $retrieve_classifiers_query, $table_class, $onset_consonant_values, $coda_consonant_values, $vowel_values, $dip_values, $cluster_values_onset, $cluster_values_coda, $language_creator, $non_initial_values, $non_final_values, $syllables, $vc_combo_values);
	$num_gen = vocab_gen ($username, "num", $lang_id, $num_array_eng, $retrieve_numbers_query, $table_num, $onset_consonant_values, $coda_consonant_values, $vowel_values, $dip_values, $cluster_values_onset, $cluster_values_coda, $language_creator, $non_initial_values, $non_final_values, $syllables, $vc_combo_values);
	$g_gen = vocab_gen ($username, "gen", $lang_id, $gender_array_eng, $retrieve_gender_query, $table_gen, $onset_consonant_values, $coda_consonant_values, $vowel_values, $dip_values, $cluster_values_onset, $cluster_values_coda, $language_creator, $non_initial_values, $non_final_values, $syllables, $vc_combo_values);
//--------------------------------------------------------------------------------------------------------------------
	
	if (count($send_text_array) > 0) {

		$generated_sentence = s_gen ($send_text_array, $v_gen, $n_gen, $adj_gen, $adv_gen, $i_gen, $prep_gen, $pro_gen, $conj_gen, $int_gen, $det_gen, $cls_gen, $case_gen, $af_gen, $num_gen);
	
	echo '<br>';
	
	//this shows the user their sentence translated into their new language (in IPA)
	echo "<p style='font-size:16pt' align='center'>/" . implode(" ", $generated_sentence) ."/</p>";
	}
	
	echo '<br>';
	echo '<br>';
	echo '<br>';
	echo '<br>';
	
?>
<!DOCTYPE HTML>
<html>
	<body>
	
	<!-- this table shows a comparison of the user entered text (re-ordered according to the world order/adposition rules that they chose) and the generated text -->
		<table id="result_table">
			<tr>
<?php 
if (count($send_text_array) > 0) {
	foreach ($send_text_array as $value) {
?>
				<td id="result_td" align='center'> <?php echo $value;?> </td>
<?php
	}
}
?>
			<tr>
			</tr>
<?php 
if (count($send_text_array) > 0) {
	foreach ($generated_sentence as $value) {
?>
				<td id="result_td" align='center'>&nbsp;<?php echo $value;?>&nbsp;</td>
<?php
	}
}
?>

			</tr>
		</table>
		<br>
<br>
<br>
<br>

<!-- this table shows individual morphemes with their english definition -->
<table id="result_table_2">
			<tr>
				<td id="result_td">Nouns:&nbsp;</td>
<?php 
	foreach ($n_gen as $key => $value) {
?>
				<td id="result_td_2" align='center'><?php echo $key;?></td>
<?php
	}
?>
			</tr>			
			<tr>
				<td id="result_td"></td>
<?php 
	foreach ($n_gen as $value) {
?>
				<td id="result_td_3" align='center'><?php echo $value;?></td>
<?php
	}
?>
			</tr>
			<tr>
				<td id="result_td">Pronouns:&nbsp;</td>
<?php 
	foreach ($pro_gen as $key => $value) {
?>
				<td id="result_td_2" align='center'><?php echo $key;?></td>
<?php
	}
?>
			</tr>			
			<tr>
				<td id="result_td"></td>
<?php 
	foreach ($pro_gen as $value) {
?>
				<td id="result_td_3" align='center'><?php echo $value;?></td>
<?php
	}
?>
			</tr>
			<tr>
				<td id="result_td">Verbs:&nbsp;</td>
<?php 
	foreach ($v_gen as $key => $value) {
?>
				<td id="result_td_2" align='center'><?php echo $key;?></td>
<?php
	}
?>
			</tr>			
			<tr>
				<td id="result_td"></td>
<?php 
	foreach ($v_gen as $value) {
?>
				<td id="result_td_3" align='center'><?php echo $value;?></td>
<?php
	}
?>
			</tr>
			<tr>
				<td id="result_td">Adverbs:&nbsp;</td>
<?php 
	foreach ($adv_gen as $key => $value) {
?>
				<td id="result_td_2" align='center'><?php echo $key;?></td>
<?php
	}
?>
			</tr>			
			<tr>
				<td id="result_td"></td>
<?php 
	foreach ($adv_gen as $value) {
?>
				<td id="result_td_3" align='center'><?php echo $value;?></td>
<?php
	}
?>
			</tr>
			<tr>
				<td id="result_td">Adjectives:&nbsp;</td>
<?php 
	foreach ($adj_gen as $key => $value) {
?>
				<td id="result_td_2" align='center'><?php echo $key;?></td>
<?php
	}
?>
			</tr>			
			<tr>
				<td id="result_td"></td>
<?php 
	foreach ($adj_gen as $value) {
?>
				<td id="result_td_3" align='center'><?php echo $value;?></td>
<?php
	}
?>
			</tr>
			<tr>
				<td id="result_td">Prepositions:&nbsp;</td>
<?php 
	foreach ($prep_gen as $key => $value) {
?>
				<td id="result_td_2" align='center'><?php echo $key;?></td>
<?php
	}
?>
			</tr>			
			<tr>
				<td id="result_td"></td>
<?php 
	foreach ($prep_gen as $value) {
?>
				<td id="result_td_3" align='center'><?php echo $value;?></td>
<?php
	}
?>
			</tr>
			<tr>
				<td id="result_td">Determiners:&nbsp;</td>
<?php 
	foreach ($det_gen as $key => $value) {
?>
				<td id="result_td_2" align='center'><?php echo $key;?></td>
<?php
	}
?>
			</tr>			
			<tr>
				<td id="result_td"></td>
<?php 
	foreach ($det_gen as $value) {
?>
				<td id="result_td_3" align='center'><?php echo $value;?></td>
<?php
	}
?>
			</tr>
			<tr>
				<td id="result_td">Conjuctions:&nbsp;</td>
<?php 
	foreach ($conj_gen as $key => $value) {
?>
				<td id="result_td_2" align='center'><?php echo $key;?></td>
<?php
	}
?>
			</tr>			
			<tr>
				<td id="result_td"></td>
<?php 
	foreach ($conj_gen as $value) {
?>
				<td id="result_td_3" align='center'><?php echo $value;?></td>
<?php
	}
?>
			</tr>
			<tr>
				<td id="result_td">Affixes:&nbsp;</td>
<?php 
	foreach ($af_gen as $key => $value) {
?>
				<td id="result_td_2" align='center'><?php echo $key;?></td>
<?php
	}
?>
			</tr>			
			<tr>
				<td id="result_td"></td>
<?php 
	foreach ($af_gen as $value) {
?>
				<td id="result_td_3" align='center'><?php echo $value;?></td>
<?php
	}
?>
			</tr>
			<tr>
				<td id="result_td">Inflections:&nbsp;</td>
<?php 
	foreach ($i_gen as $key => $value) {
?>
				<td id="result_td_2" align='center'><?php echo $key;?></td>
<?php
	}
?>
			</tr>			
			<tr>
				<td id="result_td"></td>
<?php 
	foreach ($i_gen as $value) {
?>
				<td id="result_td_3" align='center'><?php echo $value;?></td>
<?php
	}
?>
			</tr>
			<tr>
				<td id="result_td">Interjections:&nbsp;</td>
<?php 
	foreach ($int_gen as $key => $value) {
?>
				<td id="result_td_2" align='center'><?php echo $key;?></td>
<?php
	}
?>
			</tr>			
			<tr>
				<td id="result_td"></td>
<?php 
	foreach ($int_gen as $value) {
?>
				<td id="result_td_3" align='center'><?php echo $value;?></td>
<?php
	}
?>
			</tr>
			<tr>
				<td id="result_td">Cases:&nbsp;</td>
<?php 
	foreach ($case_gen as $key => $value) {
?>
				<td id="result_td_2" align='center'><?php echo $key;?></td>
<?php
	}
?>
			</tr>			
			<tr>
				<td id="result_td"></td>
<?php 
	foreach ($case_gen as $value) {
?>
				<td id="result_td_3" align='center'><?php echo $value;?></td>
<?php
	}
?>
			</tr>
			<tr>
				<td id="result_td">Genders:&nbsp;</td>
<?php 
	foreach ($g_gen as $key => $value) {
?>
				<td id="result_td_2" align='center'><?php echo $key;?></td>
<?php
	}
?>
			</tr>			
			<tr>
				<td id="result_td"></td>
<?php 
	foreach ($g_gen as $value) {
?>
				<td id="result_td_3" align='center'><?php echo $value;?></td>
<?php
	}
?>
			</tr>
			<tr>
				<td id="result_td">Classifiers:&nbsp;</td>
<?php 
	foreach ($cls_gen as $key => $value) {
?>
				<td id="result_td_2" align='center'><?php echo $key;?></td>
<?php
	}
?>
			</tr>			
			<tr>
				<td id="result_td"></td>
<?php 
	foreach ($cls_gen as $value) {
?>
				<td id="result_td_3" align='center'><?php echo $value;?></td>
<?php
	}
?>
			</tr>
			<tr>
				<td id="result_td">Numbers:&nbsp;</td>
<?php 
	foreach ($num_gen as $key => $value) {
?>
				<td id="result_td_2" align='center'><?php echo $key;?></td>
<?php
	}
?>
			</tr>			
			<tr>
				<td id="result_td"></td>
<?php 
	foreach ($num_gen as $value) {
?>
				<td id="result_td_3" align='center'><?php echo $value;?></td>
<?php
	}
?>
			</tr>
</table>

	</body>
</html>
	
