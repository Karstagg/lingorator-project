<?php
	//creates arrays which are used in select boxes when creating a new language
	
	//word order options
	$order_array = array(
	"Select",
	"SOV",
	"SVO",
	"VSO");
	
	//phonetic properties
	$phon_array = array(
		'Select',
		'friendly',
		'neutral',
		'agressive');
	
	//type of language
	$type_array = array("Agglutinative/Analytic");
	
	
	
	//created with hex codes supplied by:
	//http://www.internationalphoneticalphabet.org/ipa-charts/ipa-symbols-with-unicode-decimal-and-hex-codes/
	$ipa_vowels = array (
		'va_01' => 'a',
		'va_02' => '&#x0251;',	
		'va_03' => '&#x0250;',	
		'va_04' => '&#x0252;',	
		'va_05' => '&#x00E6;',	
		'va_06' => 'e',
		'va_07' => '&#x0259;',	
		'va_08' => '&#x0258;',	
		'va_09' => '&#x025A;',	
		'va_10' => '&#x025B;',	
		'va_11' => '&#x025C;',	
		'va_12' => '&#x025D;',	
		'va_13' => '&#x025E;',
		'va_14' => '&#x0275;',
		'va_15' => 'i'
		);
	$ipa_vowels_2 = array (
		'vb_01' => 'y',
		'vb_02' => '&#x028F;',
		'vb_03' => '&#x0268;',
		'vb_04' => '&#x026A;',	
		'vb_05' => 'u',
		'vb_06' => '&#x026F;',	
		'vb_07' => '&#x0153;',	
		'vb_08' => '&#x0276;',	
		'vb_09' => '&#x0289;',	
		'vb_10' => '&#x028A;',
		'vb_11' => '&#x028C;',	
		'vb_12' => 'o',
		'vb_13' => '&#x00F8;',
		'vb_14' => '&#x0254;',	
		'vb_15' => '&#x0264;'	
	);
	$ipa_bilabial = array(
		'bi_01' => 'p',
		'bi_02' => 'b',
		'bi_03' => 'm',
		'bi_04' => '&#x0299;',
		'bi_05' => '&#x0278;',	
		'bi_06' => '&#x03B2;'
	);

	$ipa_labiodental = array(
		'la_01' => '&#x0271;',
		'la_02' => '&#x2C71;',
		'la_03' => 'f',
		'la_04' => 'v',
		'la_05' => '&#x028B;'
	);

	$ipa_dental = array (
		'de_01' => '&#x03B8;',
		'de_02' => '&#x00F0;'
	);

	$ipa_alveolar = array ( 
		'al_01' => 't',
		'al_02' => 'd',
		'al_03' => 'n',
		'al_04' => 'r',
		'al_05' => '&#x027E;',
		'al_06' => 's',
		'al_07' => 'z',
		'al_08' => '&#x026C;',
		'al_09' => '&#x026E;',
		'al_10' => '&#x0279;',	
		'al_11' => 'l'
	);

	$ipa_postalveolar = array (
		'po_01' => '&#x0283;',
		'po_02' => '&#x0292;',
		'po_03' => '&#x02A7;',
		'po_04' => '&#x02A4;'
	);

	$ipa_retroflex = array (
		're_01' => '&#x0288;',
		're_02' => '&#x0256;',
		're_03' => '&#x0273;',
		're_04' => '&#x027D;',
		're_05' => '&#x0282;',
		're_06' => '&#x0290;',
		're_07' => '&#x027B;',
		're_08' => '&#x026D;'
	);

	$ipa_palatal = array (
		'pa_01' => 'c',
		'pa_02' => '&#x025F;',
		'pa_03' => '&#626;',
		'pa_04' => '&#x00E7;',
		'pa_05' => '&#x029D;',
		'pa_06' => 'j',
		'pa_07' => '&#x028E;'		
	);

	$ipa_velar = array (
		've_01' => 'k',
		've_02' => 'g',
		've_03' => '&#x014B;',
		've_04' => 'x',
		've_05' => '&#x0263;',
		've_06' => '&#x0270;',	
		've_07' => '&#x029F;'	
	);

	$ipa_uvular = array (
		'uv_01' => 'q',
		'uv_02' => '&#x0262;',
		'uv_03' => '&#x0274;',
		'uv_04' => '&#x0280;',
		'uv_05' => '&#x03C7;',
		'uv_06' => '&#x0281;'
	);

	$ipa_pharyngeal = array (
		'ph_01' => '&#x0127;',
		'ph_02' => '&#x0295;'
	);

	$ipa_glotal = array (
		'gl_01' => '&#x0294;',
		'gl_02' => 'h',
		'gl_03' => '&#x0266;'
	);

	$ipa_others = array (
		'ot_01' => '&#x028D;',
		'ot_02' => 'w',
		'ot_03' => '&#x0265;',
		'ot_04' => '&#x029C;',
		'ot_05' => '&#x02A1;',
		'ot_06' => '&#x02A2;',
		'ot_07' => '&#x0255;',
		'ot_08' => '&#x0291;',
		'ot_09' => '&#x027A;',
		'ot_10' => '&#x0267;'
	);
	$ipa_long_v = array ( 'lv_01' => ':');
	
	$ipa_all_vowels = array_merge($ipa_vowels, $ipa_vowels_2, $ipa_long_v);
	
	$ipa_all_consonants = array_merge($ipa_bilabial, 
									  $ipa_labiodental, 
									  $ipa_dental, 
									  $ipa_alveolar, 
									  $ipa_postalveolar, 
									  $ipa_retroflex, 
									  $ipa_palatal, 
									  $ipa_velar,
									  $ipa_uvular,
									  $ipa_pharyngeal,
									  $ipa_glotal,
									  $ipa_others);
	
	$sound_inventory = array_merge($ipa_all_vowels, $ipa_all_consonants);
													
	?>