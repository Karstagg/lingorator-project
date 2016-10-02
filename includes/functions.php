<?php
//code adapted from http://www.wikihow.com/Create-a-Secure-Login-Script-in-PHP-and-MySQL
// until line 203
include_once 'psl-config.php';
	include_once "language_arrays.php";
 //keeps people from acessing session id cookie through JS and helps prevent  session hijacking
function sec_session_start() {
    $session_name = 'lang_gen';   // Set a custom session name
    $secure = true;
    // This stops JavaScript being able to access the session id.
    $httponly = true;
	
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"], 
        $cookieParams["domain"], 
        $secure,
        $httponly);
    // Sets the session name to the one set above.
    session_name($session_name);
    session_start();            // Start the PHP session 
    session_regenerate_id(true);    // regenerated the session, delete the old one. 
	
}
//verifies email/passwords
function login($email, $password, $mysqli) {
    // Using prepared statements means that SQL injection is not possible. 
    if ($stmt = $mysqli->prepare("SELECT id, username, password 
        FROM members
       WHERE email = ?
        LIMIT 1")) {
        $stmt->bind_param('s', $email);  // Bind "$email" to parameter.
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
 
        // get variables from result.
        $stmt->bind_result($user_id, $username, $db_password);
        $stmt->fetch();
 
        if ($stmt->num_rows == 1) {
            // If the user exists we check if the account is locked
            // from too many login attempts 
 
            if (checkbrute($user_id, $mysqli) == true) {
                // Account is locked 
                // Send an email to user saying their account is locked
                return false;
            } else {
                // Check if the password in the database matches
                // the password the user submitted. We are using
                // the password_verify function to avoid timing attacks.
                if (password_verify($password, $db_password)) {
                    // Password is correct!
                    // Get the user-agent string of the user.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    // XSS protection as we might print this value
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id'] = $user_id;
                    // XSS protection as we might print this value
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", 
                                                                "", 
                                                                $username);
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512', 
                              $db_password . $user_browser);
                    // Login successful.
                    return true;
					
                } else {
                    // Password is not correct
                    // We record this attempt in the database
                    $now = time();
                    $mysqli->query("INSERT INTO login_attempts(user_id, time)
                                    VALUES ('$user_id', '$now')");
                    return false;
                }
            }
        } else {
            // No user exists.
            return false;
        }
    }
}

//stops login attempts at 5. Fix this to allow reset*****
function checkbrute($user_id, $mysqli) {
    // Get timestamp of current time 
    $now = time();
 
    // All login attempts are counted from the past 2 hours. 
    $valid_attempts = $now - (2 * 60 * 60);
 
    if ($stmt = $mysqli->prepare("SELECT time 
                             FROM login_attempts 
                             WHERE user_id = ? 
                            AND time > '$valid_attempts'")) {
        $stmt->bind_param('i', $user_id);
 
        // Execute the prepared query. 
        $stmt->execute();
        $stmt->store_result();
 
        // If there have been more than 5 failed logins 
        if ($stmt->num_rows > 5) {
            return true;
        } else {
            return false;
        }
    }
}
//makes sure user is still logged in
function login_check($mysqli) {
    // Check if all session variables are set 

    if (isset($_SESSION['user_id'], 
                        $_SESSION['username'], 
                        $_SESSION['login_string'])) {
 
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];
 
        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
 
        if ($stmt = $mysqli->prepare("SELECT password 
                                      FROM members 
                                      WHERE id = ? LIMIT 1")) {
            // Bind "$user_id" to parameter. 
            $stmt->bind_param('i', $user_id);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();
 
            if ($stmt->num_rows == 1) {
                // If the user exists get variables from result.
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);
 
                if (hash_equals($login_check, $login_string) ){
                    // Logged In!!!! 
                    return true;
                } else {
                    // Not logged in 
				
                    return false;
                }
            } else {
                // Not logged in 
          
				return false;
            }
			
        } else {
            // Not logged in 
		
            return false;
        }
    } else {
        // Not logged in 
	
        return false;
    }
}
//sanitizes data from server
function esc_url($url) {
 
    if ('' == $url) {
        return $url;
    }
 
    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
 
    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;
 
    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }
 
    $url = str_replace(';//', '://', $url);
 
    $url = htmlentities($url);
 
    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);
 
    if ($url[0] !== '/') {
        // We're only interested in relative links from $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}
//-------------------------------------------------------------------
//From this line on is the exclusive work of Matthew Robert Fisher, M.A. Linguistics and Web Technology
//-------------------------------------------------------------------
//processes the sentence so that it can be ordered and vocabulary can be generated from it.
function text_array_processor ($array, $return) {

$half_processed = $array;
//the following two foreach loops process the arrays made from the user entered sentences
//so that it can be used to generate vocabulary						 
foreach ($array as $key => $value) {
	$has_infl = strpos($value, '-');
	//if there is an inflection (or any affix) on a word
	if ($has_infl > 0) {
		//the orriginal string is replaced with an array where the word and the inflections are separate
		$array[$key] = explode('-', $value);
		$half_processed[$key] = explode('-', $value);
	}	
}
foreach ($array as $key => $value) {
	//if one of the values of the array is an array
	if (is_array($value) === TRUE) {
		//the array is imploded into a string consisting of the items in that separated by a space
		$array[$key] = implode(' ', $value);
	}
	
}
//the array is imploded into a string
$s = implode(' ', $array);
//the string is exploded into a new array where every word and inflection/affix is a unique entry in the array
$processed = explode(' ', $s);
//returns a fully processed sentence array
if ($return == 'full') {
	return $processed;
}
//leaves sentence in a multidimensional array
if ($return == 'half') {
	return $half_processed;
}
}
//----------------------------------------------------------
//random consonant
function rand_v ($vowel) {
	$rand_v = $vowel[array_rand($vowel)];
	return $rand_v;
}
//---------------------------
//random vowel 
function rand_c ($consonant) {
	$rand_c = $consonant[array_rand($consonant)];
	return $rand_c;
}
//---------------------------------------
//this function generates syllables and morphemes.

function syllable ($onset_consonant_values, $coda_consonant_values, $vowel, $diphthongs, $cluster_values_onset, $cluster_values_coda, $number, $non_initial_values, $non_final_values, $syllables, $vc_combo_values) {
		
		//if there are any phonemes that cannot be morpheme initial or final 
		if (is_array($non_initial_values) && count($non_initial_values) > 0) {
			$non_i = $non_initial_values;
		}
		else {
			$non_i = array("?");
		}
		if (is_array($non_final_values) && count($non_final_values) > 0) {
			$non_f = $non_final_values;
		}
		else {
			$non_f = array("?");
		}
		
		
		//these new arrays remove forbidden values at the beginning of a morpheme(like phonemes that can occure at the beginning of morphemes);
		$morpheme_onset_consonant_values = array_diff($onset_consonant_values, $non_i);
		if (is_array($cluster_values_onset) && count($cluster_values_onset) > 0) {	
			$morpheme_cluster_values_onset = array_diff($cluster_values_onset, $non_i);
		}
		$morpheme_onset_nucleus_vowels = array_diff($vowel, $non_i);
		if (is_array($diphthongs) && count($diphthongs) > 0) {	
			$morpheme_onset_nucleus_diphthongs = array_diff($diphthongs, $non_i);
		}
		
		
		//these new arrays remove forbidden values at the end of morpheme
		$morpheme_coda_consonant_values = array_diff($coda_consonant_values, $non_f);
		if (is_array($cluster_values_coda) && count($cluster_values_coda) > 0) {	
			$morpheme_cluster_values_coda = array_diff($cluster_values_coda, $non_f);
		}
		else {
			$morpheme_cluster_values_coda = $morpheme_coda_consonant_values;
		}
		$morpheme_final_nucleus_vowels = array_diff($vowel, $non_f);
		if (is_array($diphthongs) && count($diphthongs) > 0) {	
			$morpheme_final_nucleus_diphthongs = array_diff($diphthongs, $non_f);
		}
		
		
		//creates a way to randomly decide if a syllable onset or coda will be a cluster or single consonant. The chance of a cluster grows with proportion of clusters to single consonants
		if (is_array($cluster_values_onset) && count($cluster_values_onset) > 0) {
			//the maximum value for a randomly generated number. the length of the merged onset cluster/onset consonant arrays
			$max_num_onset = count(array_merge($cluster_values_onset, $onset_consonant_values));
			//uses the length of the onset cluster array to define a number which will later be used to decide if a cluster or single consonant will be used based or whether a randomly generated number is lower or higher than it
			$cutoff_onset = count($cluster_values_onset);
			$clus_chance_onset = rand(0, $max_num_onset);
		}
		else {
			//no clusters
			$cutoff_onset = 0;
			//this can be any number greater than 0 
			$clus_chance_onset = 1;
		}
			
		if (is_array($cluster_values_coda) && count($cluster_values_coda) > 0) {
			//the maximum value for a randomly generated number. the length of the merged coda cluster/coda consonant arrays
			$max_num_coda = count(array_merge($cluster_values_coda, $coda_consonant_values));
			//uses the length of the coda cluster array to define a number which will later be used to decide if a cluster or single consonant will be used based or whether a randomly generated number is lower or higher than it
			$cutoff_coda = count($cluster_values_coda);
			$clus_chance_coda = rand(0, $max_num_coda);
		}
		else {
			//no clusters		
			$cutoff_coda = 0;
			//this can be any number greater than 0 
			$clus_chance_coda = 1;
			$cluster_values_coda = $coda_consonant_values;
		}
		
		
		
		if (is_array($diphthongs) && count($diphthongs) > 0) {	
			
				$max_num_dip = count(array_merge($diphthongs, $vowel));
				$cutoff_dip = count($diphthongs);
				$dip_chance = rand(0, $max_num_dip);
			}
		else {
			//no diphthongs		
			$cutoff_dip = 0;
			//this can be any number greater than 0 
			$dip_chance = 1;
		}
		
		
		
		//$consonant_initials = $consonant;
		//array will contain generated sylables
		$syllable_array = [];
		
	//generates open syllables only	
	if ($syllables == 'open') {
		//1 in six chance the syllable starts with a vowel
		$vowel_initial_chance = rand(1,6);
			//creates a number of syllables specified in the vocab_gen function
			for($i = 0; $i <= $number; $i++) {
			
				$long_v_chance = rand(1,8);
			
					
					//if morpheme initial
					if ($i == 0) {
					//a consonant is selected at random. (a random key is selected and used to pick a value)
						if ($clus_chance_onset <= $cutoff_onset) {
							$first_sound = $morpheme_cluster_values_onset[array_rand($morpheme_cluster_values_onset)];
						}
						else{ 	
							$first_sound = $morpheme_onset_consonant_values[array_rand($morpheme_onset_consonant_values)];
							
						}
					}
					else {
						//a consonant is selected at random. (a random key is selected and used to pick a value)
						if ($clus_chance_onset <= $cutoff_onset) {
							$first_sound = $cluster_values_onset[array_rand($cluster_values_onset)];
						}
						else{ 	
							$first_sound = $onset_consonant_values[array_rand($onset_consonant_values)];
							
						}
					}
					//if this is the last syllable
					if ($i == $number) {
						
						if ($dip_chance <= $cutoff_dip) {
							//a diphthong is selected at random (a random key is selected and used to pick a value)
							$second_sound = $morpheme_final_nucleus_diphthongs[array_rand($morpheme_final_nucleus_diphthongs)];
						}
						elseif ($long_v_chance == 1) {
							//a vowel is selected at random (a random key is selected and used to pick a value)
							$second_sound = $morpheme_final_nucleus_vowels[array_rand($morpheme_final_nucleus_vowels)] . ":";
						}
						else {
						//a vowel is selected at random (a random key is selected and used to pick a value)
							$second_sound = $morpheme_final_nucleus_vowels[array_rand($morpheme_final_nucleus_vowels)];
						}	
					}
					else {
						
						if ($dip_chance <= $cutoff_dip) {
							//a diphthong is selected at random (a random key is selected and used to pick a value)
							$second_sound = $diphthongs[array_rand($diphthongs)];
						}
						elseif ($long_v_chance == 1) {
							//a vowel is selected at random (a random key is selected and used to pick a value)
							$second_sound = $vowel[array_rand($vowel)] . ":";
						}
						else {
						//a vowel is selected at random (a random key is selected and used to pick a value)
							$second_sound = $vowel[array_rand($vowel)];
						}
					}
					//vowels and consonants are concatinated into syllables in the following 
						//if this is the first syllable in a morpheme and $vowel_initial_chance = 1 (there can be an initial vowel)
						if ($i == 0 && $vowel_initial_chance == 1) {
							
							if (!in_array($second_sound, $non_i)) {
								$combo = $second_sound;
							}
							else {
								if($dip_chance <= $cutoff_dip) {
									$combo = $morpheme_onset_nucleus_diphthongs[array_rand($morpheme_onset_nucleus_diphthongs)];
								}
								else {
									$combo = $morpheme_onset_nucleus_vowels[array_rand($morpheme_onset_nucleus_vowels)];
								}
							}
						}
						else {
							$combo = $first_sound . $second_sound;
						}
						$syllable_array[] = $combo;
				
				
			}
		
		}	

		//generates mostly open(but occasionally closed) syllables
		if ($syllables == 'mostly_open') {
		//1 in six chance the syllable starts with a vowel
		$vowel_initial_chance = rand(1,6);
			//creates a number of syllables specified in the vocab_gen function
			for($i = 0; $i <= $number; $i++) {
			
				$long_v_chance = rand(1,8);
			
			//generates a number between 1 and 5
				$open_probability = rand(1, 5);
				//4 out of 5 probability of an open syllable
				if ($open_probability > 1) {
					
					//if morpheme initial
					if ($i == 0) {
					//a consonant is selected at random. (a random key is selected and used to pick a value)
						if ($clus_chance_onset <= $cutoff_onset) {
							$first_sound = $morpheme_cluster_values_onset[array_rand($morpheme_cluster_values_onset)];
						}
						else{ 	
							$first_sound = $morpheme_onset_consonant_values[array_rand($morpheme_onset_consonant_values)];
							
						}
					}
					else {
						//a consonant is selected at random. (a random key is selected and used to pick a value)
						if ($clus_chance_onset <= $cutoff_onset) {
							$first_sound = $cluster_values_onset[array_rand($cluster_values_onset)];
						}
						else{ 	
							$first_sound = $onset_consonant_values[array_rand($onset_consonant_values)];
							
						}
					}
					//if this is the last syllable ($number represents the maximum number of syllables in a morpheme, randomly generated in the vocab gen function)
					if ($i == $number) {
						//there is a 25% chance that the  will be a diphthong instead of a monophthong 
						if ($dip_chance <= $cutoff_dip) {
							//a diphthong is selected at random (a random key is selected and used to pick a value)
							$second_sound = $morpheme_final_nucleus_diphthongs[array_rand($morpheme_final_nucleus_diphthongs)];
						}
						elseif ($long_v_chance == 1) {
							//a vowel is selected at random (a random key is selected and used to pick a value)
							$second_sound = $morpheme_final_nucleus_vowels[array_rand($morpheme_final_nucleus_vowels)] . ":";
						}
						else {
						//a vowel is selected at random (a random key is selected and used to pick a value)
							$second_sound = $morpheme_final_nucleus_vowels[array_rand($morpheme_final_nucleus_vowels)];
						}	
					}
					else {
						//there is a 25% chance that the  will be a diphthong instead of a monophthong 
						if ($dip_chance <= $cutoff_dip) {
							//a diphthong is selected at random (a random key is selected and used to pick a value)
							$second_sound = $diphthongs[array_rand($diphthongs)];
						}
						elseif ($long_v_chance == 1) {
							//a vowel is selected at random (a random key is selected and used to pick a value)
							$second_sound = $vowel[array_rand($vowel)] . ":";
						}
						else {
						//a vowel is selected at random (a random key is selected and used to pick a value)
							$second_sound = $vowel[array_rand($vowel)];
						}
					}
					//vowels and consonants are concatinated into syllables in the following 
						//if this is the first syllable in a morpheme and $vowel_initial_chance = 1 (there can be an initial vowel)
						if ($i == 0 && $vowel_initial_chance == 1) {
							
							if (!in_array($second_sound, $non_i)) {
								$combo = $second_sound;
							}
							else {
								if($dip_chance <= $cutoff_dip) {
									$combo = $morpheme_onset_nucleus_diphthongs[array_rand($morpheme_onset_nucleus_diphthongs)];
								}
								else {
									$combo = $morpheme_onset_nucleus_vowels[array_rand($morpheme_onset_nucleus_vowels)];
								}
							}
						}
						else {
							$combo = $first_sound . $second_sound;
						}
						$syllable_array[] = $combo;
				}
				else {
					
					//if morpheme initial
					if ($i == 0) {
					//a consonant is selected at random. (a random key is selected and used to pick a value)
						if ($clus_chance_onset <= $cutoff_onset) {
							$first_sound = $morpheme_cluster_values_onset[array_rand($morpheme_cluster_values_onset)];
						}
						else{ 	
							$first_sound = $morpheme_onset_consonant_values[array_rand($morpheme_onset_consonant_values)];
							
						}
					}
					else {
						//a consonant is selected at random. (a random key is selected and used to pick a value)
						if ($clus_chance_onset <= $cutoff_onset) {
							$first_sound = $cluster_values_onset[array_rand($cluster_values_onset)];
						}
						else{ 	
							$first_sound = $onset_consonant_values[array_rand($onset_consonant_values)];
							
						}
					}
		
				//there is a 25% chance that the  will be a diphthong instead of a monophthong 
					if ($dip_chance <= $cutoff_dip) {
						//a diphthong is selected at random (a random key is selected and used to pick a value)
						$second_sound = $diphthongs[array_rand($diphthongs)];
					}
					elseif ($long_v_chance == 1) {
						//a vowel is selected at random (a random key is selected and used to pick a value)
						$second_sound = $vowel[array_rand($vowel)] . ":";
					}
					else {
					//a vowel is selected at random (a random key is selected and used to pick a value)
						$second_sound = $vowel[array_rand($vowel)];
					}
					
					//if this is the last syllable in a morpheme (based on a random number generated in the vocab gen function)
					if ($i == $number) {
						
						if ($clus_chance_coda <= $cutoff_coda) {
							$third_sound = $morpheme_coda_consonant_values[array_rand($morpheme_coda_consonant_values)];
						}
						else{ 	
							$third_sound = $morpheme_cluster_values_coda[array_rand($morpheme_cluster_values_coda)];
						}
					}
					else {
						
						if ($clus_chance_coda <= $cutoff_coda) {
							$third_sound = $coda_consonant_values[array_rand($coda_consonant_values)];
						}
						else{ 	
							
							$third_sound = $cluster_values_coda[array_rand($cluster_values_coda)];
						}
					}
				//vowels and consonants are concatinated
				//if this is the first syllable and the vowel initial chance is 1, a vowel will begin the morpheme
					if ($i == 0 && $vowel_initial_chance == 1) {
						
						if (!in_array($second_sound, $non_i)) {
							$combo = $second_sound . $third_sound;
							}
							else {
								if($dip_chance <= $cutoff_dip) {
									$combo = $morpheme_onset_nucleus_diphthongs[array_rand($morpheme_onset_nucleus_diphthongs)] . $third_sound;
								}
								else {
									$combo = $morpheme_onset_nucleus_vowels[array_rand($morpheme_onset_nucleus_vowels)] . $third_sound;
								}
							}
							
					}
					else {
						$combo = $first_sound . $second_sound . $third_sound;
					}
					$syllable_array[] = $combo;
					
				}
			}
		
		}
		
					//------------------//		
					
		//generates a mixture of open and closed syllables 
		if ($syllables == 'mixed') {
		//1 in 6 chance the syllable starts with a vowel
		$vowel_initial_chance = rand(1,6);
			//creates a number of syllables specified in the vocab_gen function
			for($i = 0; $i <= $number; $i++) {
			
				$long_v_chance = rand(1,9);
			
			//generates a number between 1 and 2
				$open_probability = rand(1, 2);
				//50/50 chance of a syllable being open or closed
				if ($open_probability > 1) {
					
					//if morpheme initial
					if ($i == 0) {
					//a consonant is selected at random. (a random key is selected and used to pick a value)
						if ($clus_chance_onset <= $cutoff_onset) {
							$first_sound = $morpheme_cluster_values_onset[array_rand($morpheme_cluster_values_onset)];
						}
						else{ 	
							$first_sound = $morpheme_onset_consonant_values[array_rand($morpheme_onset_consonant_values)];
							
						}
					}
					else {
						//a consonant is selected at random. (a random key is selected and used to pick a value)
						if ($clus_chance_onset <= $cutoff_onset) {
							$first_sound = $cluster_values_onset[array_rand($cluster_values_onset)];
						}
						else{ 	
							$first_sound = $onset_consonant_values[array_rand($onset_consonant_values)];
							
						}
					}
					//if this is the last syllable ($number represents the maximum number of syllables in a morpheme, randomly generated in the vocab gen function)
					if ($i == $number) {
						
						if ($dip_chance <= $cutoff_dip) {
							//a diphthong is selected at random (a random key is selected and used to pick a value)
							$second_sound = $morpheme_final_nucleus_diphthongs[array_rand($morpheme_final_nucleus_diphthongs)];
						}
						elseif ($long_v_chance == 1) {
							//a vowel is selected at random (a random key is selected and used to pick a value)
							$second_sound = $morpheme_final_nucleus_vowels[array_rand($morpheme_final_nucleus_vowels)] . ":";
						}
						else {
						//a vowel is selected at random (a random key is selected and used to pick a value)
							$second_sound = $morpheme_final_nucleus_vowels[array_rand($morpheme_final_nucleus_vowels)];
						}	
					}
					else {
						
						if ($dip_chance <= $cutoff_dip) {
							//a diphthong is selected at random (a random key is selected and used to pick a value)
							$second_sound = $diphthongs[array_rand($diphthongs)];
						}
						elseif ($long_v_chance == 1) {
							//a vowel is selected at random (a random key is selected and used to pick a value)
							$second_sound = $vowel[array_rand($vowel)] . ":";
						}
						else {
						//a vowel is selected at random (a random key is selected and used to pick a value)
							$second_sound = $vowel[array_rand($vowel)];
						}
					}
					//vowels and consonants are concatinated into syllables in the following 
						//if this is the first syllable in a morpheme and $vowel_initial_chance = 1 (there can be an initial vowel)
						if ($i == 0 && $vowel_initial_chance == 1) {
							//if the second sound is not in the non morpheme inital items array
							if (!in_array($second_sound, $non_i)) {
								$combo = $second_sound;
							}
							else {
								if($dip_chance <= $cutoff_dip) {
									$combo = $morpheme_onset_nucleus_diphthongs[array_rand($morpheme_onset_nucleus_diphthongs)];
								}
								else {
									$combo = $morpheme_onset_nucleus_vowels[array_rand($morpheme_onset_nucleus_vowels)];
								}
							}
						}
						else {
							$combo = $first_sound . $second_sound;
						}
						$syllable_array[] = $combo;
				}
				else {
					
					//if morpheme initial
					if ($i == 0) {
					//a consonant is selected at random. (a random key is selected and used to pick a value)
						if ($clus_chance_onset <= $cutoff_onset) {
							$first_sound = $morpheme_cluster_values_onset[array_rand($morpheme_cluster_values_onset)];
						}
						else{ 	
							$first_sound = $morpheme_onset_consonant_values[array_rand($morpheme_onset_consonant_values)];
							
						}
					}
					else {
						//a consonant is selected at random. (a random key is selected and used to pick a value)
						if ($clus_chance_onset <= $cutoff_onset) {
							$first_sound = $cluster_values_onset[array_rand($cluster_values_onset)];
						}
						else{ 	
							$first_sound = $onset_consonant_values[array_rand($onset_consonant_values)];
							
						}
					}
		
				
					if ($dip_chance <= $cutoff_dip) {
						//a diphthong is selected at random (a random key is selected and used to pick a value)
						$second_sound = $diphthongs[array_rand($diphthongs)];
					}
					elseif ($long_v_chance == 1) {
						//a vowel is selected at random (a random key is selected and used to pick a value)
						$second_sound = $vowel[array_rand($vowel)] . ":";
					}
					else {
					//a vowel is selected at random (a random key is selected and used to pick a value)
						$second_sound = $vowel[array_rand($vowel)];
					}
					
					//if this is the last syllable in a morpheme (based on a random number generated in the vocab gen function)
					if ($i == $number) {
						
						if ($clus_chance_coda <= $cutoff_coda) {
							$third_sound = $morpheme_coda_consonant_values[array_rand($morpheme_coda_consonant_values)];
						}
						else{ 	
							$third_sound = $morpheme_cluster_values_coda[array_rand($morpheme_cluster_values_coda)];
						}
					}
					else {
						
						if ($clus_chance_coda <= $cutoff_coda) {
							$third_sound = $coda_consonant_values[array_rand($coda_consonant_values)];
						}
						else{ 	
							$third_sound = $cluster_values_coda[array_rand($cluster_values_coda)];
						}
					}
				//if this is the first syllable and the vowel initial chance is 1, a vowel will begin the morpheme
					if ($i == 0 && $vowel_initial_chance == 1) {
						
						//if the second sound is not in the non morpheme inital items array 
						if (!in_array($second_sound, $non_i)) {
							$combo = $second_sound . $third_sound;
							}
							else {
								if($dip_chance <= $cutoff_dip) {
									$combo = $morpheme_onset_nucleus_diphthongs[array_rand($morpheme_onset_nucleus_diphthongs)] . $third_sound;
								}
								else {
									$combo = $morpheme_onset_nucleus_vowels[array_rand($morpheme_onset_nucleus_vowels)] . $third_sound;
								}
							}
							
					}
					else {
						$combo = $first_sound . $second_sound . $third_sound;
					}
					$syllable_array[] = $combo;
					
				}
			}
		
		}
									//------------------//
									
		//generates mostly closed syllables  
		if ($syllables == 'mostly_closed') {
		//1 in 10 chance the syllable starts with a vowel
		$vowel_initial_chance = rand(1,10);
			//creates a number of syllables specified in the vocab_gen function
			for($i = 0; $i <= $number; $i++) {
			
				$long_v_chance = rand(1,9);
			
			//generates a number between 1 and 2
				$open_probability = rand(1, 5);
				//1 in 5 chance that a syllable with be closed
				if ($open_probability == 1) {
					
					//if morpheme initial
					if ($i == 0) {
					//a consonant is selected at random. (a random key is selected and used to pick a value)
						if ($clus_chance_onset <= $cutoff_onset) {
							$first_sound = $morpheme_cluster_values_onset[array_rand($morpheme_cluster_values_onset)];
						}
						else{ 	
							$first_sound = $morpheme_onset_consonant_values[array_rand($morpheme_onset_consonant_values)];
							
						}
					}
					else {
						//a consonant is selected at random. (a random key is selected and used to pick a value)
						if ($clus_chance_onset <= $cutoff_onset) {
							$first_sound = $cluster_values_onset[array_rand($cluster_values_onset)];
						}
						else{ 	
							$first_sound = $onset_consonant_values[array_rand($onset_consonant_values)];
							
						}
					}
					//if this is the last syllable ($number represents the maximum number of syllables in a morpheme, randomly generated in the vocab gen function)
					if ($i == $number) {
						
						if ($dip_chance <= $cutoff_dip) {
							//a diphthong is selected at random (a random key is selected and used to pick a value)
							$second_sound = $morpheme_final_nucleus_diphthongs[array_rand($morpheme_final_nucleus_diphthongs)];
						}
						elseif ($long_v_chance == 1) {
							//a vowel is selected at random (a random key is selected and used to pick a value)
							$second_sound = $morpheme_final_nucleus_vowels[array_rand($morpheme_final_nucleus_vowels)] . ":";
						}
						else {
						//a vowel is selected at random (a random key is selected and used to pick a value)
							$second_sound = $morpheme_final_nucleus_vowels[array_rand($morpheme_final_nucleus_vowels)];
						}	
					}
					else {
						
						if ($dip_chance <= $cutoff_dip) {
							//a diphthong is selected at random (a random key is selected and used to pick a value)
							$second_sound = $diphthongs[array_rand($diphthongs)];
						}
						elseif ($long_v_chance == 1) {
							//a vowel is selected at random (a random key is selected and used to pick a value)
							$second_sound = $vowel[array_rand($vowel)] . ":";
						}
						else {
						//a vowel is selected at random (a random key is selected and used to pick a value)
							$second_sound = $vowel[array_rand($vowel)];
						}
					}
					//vowels and consonants are concatinated into syllables in the following 
						//if this is the first syllable in a morpheme and $vowel_initial_chance = 1 (there can be an initial vowel)
						if ($i == 0 && $vowel_initial_chance == 1) {
							//if the second sound is not in the non morpheme inital items array
							if (!in_array($second_sound, $non_i)) {
								$combo = $second_sound;
							}
							else {
								if($dip_chance <= $cutoff_dip) {
									$combo = $morpheme_onset_nucleus_diphthongs[array_rand($morpheme_onset_nucleus_diphthongs)];
								}
								else {
									$combo = $morpheme_onset_nucleus_vowels[array_rand($morpheme_onset_nucleus_vowels)];
								}
							}
						}
						else {
							$combo = $first_sound . $second_sound;
						}
						$syllable_array[] = $combo;
				}
				else {
					
					
					//if morpheme initial
					if ($i == 0) {
					//a consonant is selected at random. (a random key is selected and used to pick a value)
						if ($clus_chance_onset <= $cutoff_onset) {
							$first_sound = $morpheme_cluster_values_onset[array_rand($morpheme_cluster_values_onset)];
						}
						else{ 	
							$first_sound = $morpheme_onset_consonant_values[array_rand($morpheme_onset_consonant_values)];
							
						}
					}
					else {
						//a consonant is selected at random. (a random key is selected and used to pick a value)
						if ($clus_chance_onset <= $cutoff_onset) {
							$first_sound = $cluster_values_onset[array_rand($cluster_values_onset)];
						}
						else{ 	
							$first_sound = $onset_consonant_values[array_rand($onset_consonant_values)];
							
						}
					}
		
				
					if ($dip_chance <= $cutoff_dip) {
						//a diphthong is selected at random (a random key is selected and used to pick a value)
						$second_sound = $diphthongs[array_rand($diphthongs)];
					}
					elseif ($long_v_chance == 1) {
						//a vowel is selected at random (a random key is selected and used to pick a value)
						$second_sound = $vowel[array_rand($vowel)] . ":";
					}
					else {
					//a vowel is selected at random (a random key is selected and used to pick a value)
						$second_sound = $vowel[array_rand($vowel)];
					}
					
					//if this is the last syllable in a morpheme (based on a random number generated in the vocab gen function)
					if ($i == $number) {
						
						if ($clus_chance_coda <= $cutoff_coda) {
							$third_sound = $morpheme_coda_consonant_values[array_rand($morpheme_coda_consonant_values)];
						}
						else{ 	
							$third_sound = $morpheme_cluster_values_coda[array_rand($morpheme_cluster_values_coda)];
						}
					}
					else {
						
						if ($clus_chance_coda <= $cutoff_coda) {
							$third_sound = $coda_consonant_values[array_rand($coda_consonant_values)];
						}
						else{ 	
							$third_sound = $cluster_values_coda[array_rand($cluster_values_coda)];
						}
					}
				//if this is the first syllable and the vowel initial chance is 1, a vowel will begin the morpheme
					if ($i == 0 && $vowel_initial_chance == 1) {
						
						//if the second sound is not in the non morpheme inital items array
						if (!in_array($second_sound, $non_i)) {
							$combo = $second_sound . $third_sound;
							}
							else {
								if($dip_chance <= $cutoff_dip) {
									$combo = $morpheme_onset_nucleus_diphthongs[array_rand($morpheme_onset_nucleus_diphthongs)] . $third_sound;
								}
								else {
									$combo = $morpheme_onset_nucleus_vowels[array_rand($morpheme_onset_nucleus_vowels)] . $third_sound;
								}
							}
							
					}
					else {
						$combo = $first_sound . $second_sound . $third_sound;
					}
					$syllable_array[] = $combo;
					
				}
			}
		
		}
								//-----------------//
								
		if ($syllables == 'closed') {
			//1 in 15 chance the syllable starts with a vowel
			$vowel_initial_chance = rand(1,15);
			
			$long_v_chance = rand(1,9);
			//if morpheme initial
			//creates a number of syllables specified in the vocab_gen function
			for($i = 0; $i <= $number; $i++) {
				if ($i == 0) {
				//a consonant is selected at random. (a random key is selected and used to pick a value)
					if ($clus_chance_onset <= $cutoff_onset) {
						$first_sound = $morpheme_cluster_values_onset[array_rand($morpheme_cluster_values_onset)];
					}
					else{ 	
						$first_sound = $morpheme_onset_consonant_values[array_rand($morpheme_onset_consonant_values)];
								
					}
				}
				else {
					//a consonant is selected at random. (a random key is selected and used to pick a value)
					if ($clus_chance_onset <= $cutoff_onset) {
								$first_sound = $cluster_values_onset[array_rand($cluster_values_onset)];
					}
					else{ 	
						$first_sound = $onset_consonant_values[array_rand($onset_consonant_values)];
								
					}
				}
			
			//there is a 1 in 6 chance that the  will be a diphthong instead of a monophthong 
				if ($dip_chance <= $cutoff_dip) {
					//a diphthong is selected at random (a random key is selected and used to pick a value)
					$second_sound = $diphthongs[array_rand($diphthongs)];
				}
				elseif ($long_v_chance == 1) {
					//a vowel is selected at random (a random key is selected and used to pick a value)
					$second_sound = $vowel[array_rand($vowel)] . ":";
				}
				else {
				//a vowel is selected at random (a random key is selected and used to pick a value)
					$second_sound = $vowel[array_rand($vowel)];
				}
						
				//if this is the last syllable in a morpheme (based on a random number generated in the vocab gen function)
				if ($i == $number) {
					
					if ($clus_chance_coda <= $cutoff_coda) {
						$third_sound = $morpheme_coda_consonant_values[array_rand($morpheme_coda_consonant_values)];
					}
					else{ 	
						$third_sound = $morpheme_cluster_values_coda[array_rand($morpheme_cluster_values_coda)];
					}
				}
				else {
					
					if ($clus_chance_coda <= $cutoff_coda) {
						$third_sound = $coda_consonant_values[array_rand($coda_consonant_values)];
					}
					else{ 	
						$third_sound = $cluster_values_coda[array_rand($cluster_values_coda)];
					}
				}
			//if this is the first syllable and the vowel initial chance is 1, a vowel will begin the morpheme
				if ($i == 0 && $vowel_initial_chance == 1) {
							
					//if the second sound is not in the non morpheme inital items array
					if (!in_array($second_sound, $non_i)) {
						$combo = $second_sound . $third_sound;
						}
						//a new more appropriate vowel is selected
						else {
							if($dip_chance <= $cutoff_dip) {
								$combo = $morpheme_onset_nucleus_diphthongs[array_rand($morpheme_onset_nucleus_diphthongs)] . $third_sound;
							}
							else {
								$combo = $morpheme_onset_nucleus_vowels[array_rand($morpheme_onset_nucleus_vowels)] . $third_sound;
							}
						}
								
				}
				else {
					$combo = $first_sound . $second_sound . $third_sound;
				}
			$syllable_array[] = $combo;
			}				
		}	
		
					//--------------//
					
			//if one of the user specified forbidden combinations is in the morpheme that value is pushed into the forbidden_things array
			$forbidden_things = [];
			if (is_array($vc_combo_values) && count($vc_combo_values) > 0) {
				
				foreach ($vc_combo_values as $value) {
					
					foreach ($syllable_array as $value2) {
						
						if (substr_count($value2, $value) > 0) {
							$forbidden_things[] = $value2;
						}
					}
					
					
				}
			}

	
		//if there is nothing in the forbidden things array then the morpheme is ready
		if (count($forbidden_things) == 0 && count($syllable_array) > 0) {	
			
			return implode("", $syllable_array);
			
		}
		else {
			//this is a recursive function call that happens if there is a forbidden vowel/consonant combination in the morpheme
			//creates a sort of while loop until a correct morpheme is generated. Not super efficient for the computer but time effective for me
			//will replace if possible. may be occasionally letting a wrong value through**  
			
			syllable ($onset_consonant_values, $coda_consonant_values, $vowel, $diphthongs, $cluster_values_onset, $cluster_values_coda, $number, $non_initial_values, $non_final_values, $syllables, $vc_combo_values);

		}
		
}
//-------------------------------------------------------------------------------------------------------
//this function checks to see if a translation for an english word has already been create.
//if it has, it pulls that morpheme from the database. If there is no translation, the function 
//calls another function (the above syllable function) to generate a new morpheme and then saves that morpheme to the database
function vocab_gen ($username, $pos, $lang_id, $pos_array, $query, $table, $onset_consonant_values, $coda_consonant_values, $vowels, $diphthongs, $cluster_values_onset, $cluster_values_coda, $connect, $non_initial_values, $non_final_values, $syllables, $vc_combo_values) {
	

	
	//array for new root forms of words
	$array_new_root = [];
		
	//checks the database for stored vocab
	$retrieve_result = $connect->query($query); 
		if ($retrieve_result->num_rows > 0) { //if there is anything in the table
			foreach ($retrieve_result as $value) {
				//if an english word stored in the db matches one that a user entered
				if (in_array($value['eng'], $pos_array)) {
					
					//creates an entry in new root array consisting of the english word as key and the new word root as value
					$array_new_root[$value['eng']] = $value['new_root'];
						
				}
			}
		
		foreach ($pos_array as $value) {
			// if the value from the array for the specified part pf speach is not yet in the new root array
			if (array_key_exists($value, $array_new_root) === FALSE) {
				
				//sets the number of syllables for each type of word
		//all random ranges are chosen to my own liking, eventually I would like to give the user the ability to define them
	switch ($pos) {
    case "v": 
        $syllable_number = rand(0,2);
        break;
	case "pro":
        $syllable_number = rand(0,2);
        break;
    case "n":
        $syllable_number = rand(1,3);
        break;
    case "adj":
        $syllable_number = rand(0,2);
        break;
	case "adv":
        $syllable_number = rand(0,2);
        break;
	case "prep":
        $syllable_number = rand(0,1);
        break;
	case "det":
        $syllable_number = rand(0,1);
        break;
	case "conj":
        $syllable_number = rand(0,1);
        break;
	case "i":
        $syllable_number = rand(0,1);
        break;
	case "suf":
        $syllable_number = rand(0,1);
        break;
	case "pre":
        $syllable_number = rand(0,1);
        break;
	case "int":
        $syllable_number = rand(0,1);
        break;
	case "cls":
        $syllable_number = rand(0,1);
        break;
	case "case":
        $syllable_number = rand(0,1);
        break;
	case "af":
        $syllable_number = rand(0,1);
        break;
	};
				
				//randomly generates a new word to add to the new root array 
		
				$array_new_root[$value] =  syllable($onset_consonant_values, $coda_consonant_values, $vowels, $diphthongs, $cluster_values_onset, $cluster_values_coda, $syllable_number, $non_initial_values, $non_final_values, $syllables, $vc_combo_values);
			
			if (count($array_new_root[$value]) > 0)	{		
					//inserts new info into the database
					$insert_query = "INSERT INTO `$username`.`$table` SET
								`".$pos."_id` = '".$lang_id."',
								`eng` = '".$value."',
								`new_root` = '".$array_new_root[$value]."'";
				
				//runs the insert query
				$connect->query($insert_query);
			}
			
			}
		}
		}
  //if nothing is in the database
  else {
	  
	foreach ($pos_array as $value) {
		
		//sets the number of syllables for each type of word
		//all random ranges are chosen to my own liking, eventually I would like to give the user the ability to define them
	switch ($pos) {
    case "v": 
        $syllable_number = rand(0,2);
        break;
	case "pro":
        $syllable_number = rand(0,2);
        break;
    case "n":
        $syllable_number = rand(1,3);
        break;
    case "adj":
        $syllable_number = rand(0,2);
        break;
	case "adv":
        $syllable_number = rand(0,2);
        break;
	case "prep":
        $syllable_number = rand(0,1);
        break;
	case "det":
        $syllable_number = rand(0,1);
        break;
	case "conj":
        $syllable_number = rand(0,1);
        break;
	case "i":
        $syllable_number = rand(0,1);
        break;
	case "suf":
        $syllable_number = rand(0,1);
        break;
	case "pre":
        $syllable_number = rand(0,1);
        break;
	case "int":
        $syllable_number = rand(0,1);
        break;
	case "cls":
        $syllable_number = rand(0,1);
        break;
	case "case":
        $syllable_number = rand(0,1);
        break;
	case "af":
        $syllable_number = rand(0,1);
        break;
	};
	
    	
		//randomly generates a new word to add to the new root array 	
		$array_new_root[$value] =  syllable($onset_consonant_values, $coda_consonant_values, $vowels, $diphthongs, $cluster_values_onset, $cluster_values_coda, $syllable_number, $non_initial_values, $non_final_values, $syllables, $vc_combo_values);
	
		if (count($array_new_root[$value]) > 0)	{						
			//inserts new info into the database
			$insert_query = "INSERT INTO `$username`.`$table` SET
				`".$pos."_id` = '".$lang_id."',
				`eng` = '".$value."',
				`new_root` = '".$array_new_root[$value]."'";
			
		//runs the insert query
		$connect->query($insert_query);
		}
		
	}
  }
 
	//returns the new root array
	
	return $array_new_root;
	
  
  
};	
//-----------------------------------------------------------------

//-----------------------------------------------------------------------------------------

//this function exchanges newly generated words with their english equivelent and returns a sentence in the new language
function s_gen ($sentence, $v_gen, $n_gen, $adj_gen, $adv_gen, $i_gen, $prep_gen, $pro_gen, $conj_gen, $int_gen, $det_gen, $cls_gen, $case_gen, $af_gen, $num_gen) {
	
	//returns a multidimensional array of the user entered sentence (sub arrays represent morphemes concatinated with -) 
	$processed_setence = text_array_processor($sentence, 'half');
	
	foreach ($processed_setence as $key => $value) {
	
    //if a value of the main array is an array (this happens when something, like an inflection is joined with another word via a -)
	if (is_array($value) === TRUE) {
		
		foreach ($value as $key2 => $value2) {
		
			/*if the $value (an english word) is a key in one of the vocabulary generation arrays, the newly generated word corresponding
			to that key will replace an english word in the array*/
			switch ($value2) {
			case array_key_exists($value2, $v_gen):
					$value[$key2] = $v_gen[$value2];
				break;
			case array_key_exists($value2, $n_gen):
				$value[$key2] = $n_gen[$value2];
				break;
			case array_key_exists($value2, $adj_gen): 
				$value[$key2] = $adj_gen[$value2];
				break;
			case array_key_exists($value2, $adv_gen): 
				$value[$key2] = $adv_gen[$value2];
				break;
			case array_key_exists($value2, $i_gen): 
				$value[$key2] = $i_gen[$value2];
				break;
			case array_key_exists($value2, $prep_gen): 
				$value[$key2] = $prep_gen[$value2];
				break;
			case array_key_exists($value2, $pro_gen): 
				$value[$key2] = $pro_gen[$value2];
				break;
			case array_key_exists($value2, $conj_gen): 
				$value[$key2] = $conj_gen[$value2];
				break;
			case array_key_exists($value2, $int_gen): 
				$value[$key2] = $int_gen[$value2];
				break;
			case array_key_exists($value2, $det_gen): 
				$value[$key2] = $det_gen[$value2];
				break;
			case array_key_exists($value2, $cls_gen): 
				$value[$key2] = $cls_gen[$value2];
				break;
			case array_key_exists($value2, $case_gen): 
				$value[$key2] = $case_gen[$value2];
				break;
			case array_key_exists($value2, $af_gen): 
				$value[$key2] = $af_gen[$value2];
				break;
			case array_key_exists($value2, $num_gen): 
				$value[$key2] = $num_gen[$value2];
				break;	
	
	};
		}
		
		//the sub array filled with new words is imploded and placed back into the processed_sentence array 
		$processed_setence[$key] = implode('', $value);
	}
			
	}
	foreach ($processed_setence as $key => $value) {
	
	//if the $value (an english word) is a key in one of the vocabulary generation arrays, the newly generated word corresponding
	//to that key will replace an english word in the array. if it is not, one of the previously imploded values (from the sub array) will be added back into the array
	switch ($value) {
    case array_key_exists($value, $v_gen):
			$processed_sentence[$key] = $v_gen[$value];
        break;
	case array_key_exists($value, $n_gen):
        $processed_sentence[$key] = $n_gen[$value];
        break;
	case array_key_exists($value, $adj_gen): 
        $processed_sentence[$key] = $adj_gen[$value];
        break;
	case array_key_exists($value, $adv_gen): 
        $processed_sentence[$key] = $adv_gen[$value];
        break;
	case array_key_exists($value, $i_gen): 
       $processed_sentence[$key] = $i_gen[$value];
        break;
	case array_key_exists($value, $prep_gen): 
        $processed_sentence[$key] = $prep_gen[$value];
        break;
	case array_key_exists($value, $pro_gen): 
        $processed_sentence[$key] = $pro_gen[$value];
        break;
	case array_key_exists($value, $conj_gen): 
        $processed_sentence[$key] = $conj_gen[$value];
        break;
	case array_key_exists($value, $int_gen): 
        $processed_sentence[$key] = $int_gen[$value];
        break;
	case array_key_exists($value, $det_gen): 
        $processed_sentence[$key] = $det_gen[$value];
        break;
	case array_key_exists($value, $cls_gen): 
		$processed_sentence[$key] = $cls_gen[$value];
		break;
	case array_key_exists($value, $case_gen): 
		$processed_sentence[$key] = $case_gen[$value];
		break;
	case array_key_exists($value, $af_gen): 
		$processed_sentence[$key] = $af_gen[$value];
		break;
	case array_key_exists($value, $num_gen): 
		$processed_sentence[$key] = $num_gen[$value];
		break;	
	case !array_key_exists($value, $det_gen) || !array_key_exists($value, $det_gen) || !array_key_exists($value, $conj_gen) || 
		 !array_key_exists($value, $pro_gen) || !array_key_exists($value, $prep_gen) || !array_key_exists($value, $i_gen) || 
		 !array_key_exists($value, $adv_gen) || !array_key_exists($value, $adj_gen) || !array_key_exists($value, $n_gen) || 
		 !array_key_exists($value, $v_gen) || !array_key_exists($value, $cls_gen) || !array_key_exists($value, $case_gen) || 
		 !array_key_exists($value, $af_gen) || !array_key_exists($value, $num_gen): 
        $processed_sentence[$key] = $value;
        break;
	
	};
	}
	
	return $processed_sentence;
}





//-----------------------------------------------------------------------------------------
//this function changes the word order of the entered sentence. It can also change prepositions into postpostitions
function order_changer ($order, $sbj_array, $obj_array, $vp_array, $pp_array, $addpos) {
	//sets oder to sov
	if ($order == "SOV") {	
		$ordered_array = []; 
		//the words tagged in the sbj phrase are pushed into the ordered_array as the first key/value pair
		if(count($sbj_array) > 1) {
			$ordered_array['sbj'] = $sbj_array[1][0];
		}
		//if a pp exisits
		if(count($pp_array) > 1) {
			if (array_key_exists(0, $pp_array[1])) {
			//if the language has post positions 
				if ($addpos == 1) {	
				
					//turns the pp into an array
					$prep_phrase_array = explode(' ', $pp_array[1][0]);
					//gets the value of just the preposition
					$prep_alone = $prep_phrase_array[0];
					//removes the preposition from the array
					unset($prep_phrase_array[0]);
					//turns what is left of the array back into a string 
					$phrase = implode(" ", $prep_phrase_array);
					//creates an entry in an array with the preposition (now a post position) after the phrase.
					$ordered_array['addpos'] = $phrase . " " . $prep_alone;
				}
				else {
					
						//keeps pp with preposition first
						$ordered_array['addpos'] = $pp_array[1][0];
				}
			}
		}
		//object phrase pushed into the ordered array as the second key/value pair 
		if(count($obj_array) > 1) {	
			$ordered_array['obj'] = $obj_array[1][0];
		}
		//verb phrase pushed into the ordered array as the third key/value pair
		if(count($vp_array) > 1) {
			$ordered_array['v'] = $vp_array[1][0];
		}
		//new text string consisting of reordered elements
		$gen_text = implode(" ", $ordered_array);
		//special characters removed
		$clean_gen_text = preg_replace('/[^\sa-z0-9\-]/', '', $gen_text);
		//array created from cleaned text
		$clean_gen_array = preg_split('/[\s]/', $clean_gen_text, NULL, PREG_SPLIT_NO_EMPTY);
		return $clean_gen_array;
	}
	//sets order to svo
	if ($order == "SVO") {	
		$ordered_array = []; 
		//the words tagged in the sbj phrase are pushed into the ordered_array as the first key/value pair
		if(count($sbj_array) > 1) {
			$ordered_array['sbj'] = $sbj_array[1][0];
		}
		//the words tagged in the verb phrase are pushed into the ordered_array as the second key/value pair
		if(count($vp_array) > 1) {
			$ordered_array['v'] = $vp_array[1][0];
		}
		//the words tagged in the object phrase are pushed into the ordered_array as the third key/value pair
		if(count($obj_array) > 1) {	
			$ordered_array['obj'] = $obj_array[1][0];
		}	
		if(count($pp_array) > 1) {	
			if (array_key_exists(0, $pp_array[1])) {
			//if the language has post positions 
				if ($addpos == 1) {	
				
					//turns the pp into an array
					$prep_phrase_array = explode(' ', $pp_array[1][0]);
					//gets the value of just the preposition
					$prep_alone = $prep_phrase_array[0];
					//removes the preposition from the array
					unset($prep_phrase_array[0]);
					//turns what is left of the array back into a string 
					$phrase = implode(" ", $prep_phrase_array);
					//creates an entry in an array with the preposition (now a post position) after the phrase.
					$ordered_array['addpos'] = $phrase . " " . $prep_alone;
				}
				else {
					
					
						$ordered_array['addpos'] = $pp_array[1][0];
				}
			}
		}
		
		//new text string consisting of ordered elements
		$gen_text = implode(" ", $ordered_array);
		$clean_gen_text = preg_replace('/[^\sa-z0-9\-]/', '', $gen_text);
		$clean_gen_array = preg_split('/[\s]/', $clean_gen_text, NULL, PREG_SPLIT_NO_EMPTY);
		return $clean_gen_array;
	}
		
		//sets the word order to vso 
	if ($order == "VSO") {	
		$ordered_array = []; 
		
		//if a pp exisits (pps are contained in the vp)
		if(count($pp_array) > 1) {
			if (array_key_exists(0, $pp_array[1])) {
			
						//keeps pp with preposition first
						$ordered_array['addpos'] = $pp_array[1][0];
				
			}
		}
		//verb phrase pushed into the ordered array as the first key/value pair
		if(count($vp_array) > 1) {
			$ordered_array['v'] = $vp_array[1][0];
		}	
		//the words tagged in the sbj phrase are pushed into the ordered_array as the second key/value pair
		if(count($sbj_array) > 1) {	
			$ordered_array['sbj'] = $sbj_array[1][0];
		}
		//object phrase pushed into the ordered array as the third key/value pair 
		if(count($obj_array) > 1) {
			$ordered_array['obj'] = $obj_array[1][0];
		}
		//new text string consisting of reordered elements
		$gen_text = implode(" ", $ordered_array);
		//special characters removed
		$clean_gen_text = preg_replace('/[^\sa-z0-9\-]/', '', $gen_text);
		//array created from cleaned text
		$clean_gen_array = preg_split('/[\s]/', $clean_gen_text, NULL, PREG_SPLIT_NO_EMPTY);
		return $clean_gen_array;
	}
};
//--------------------------------------------------------------------------

  //this function builds consonant clusters
  function clusterator ($cluster_values, $consonant_values) {
	//declaring arrays that are used later on
	  $replace_array = [];
	  $ready_array = [];
	  $clusters_exceptions = [];
	  $no_ex_exceptions = [];
	  $no_exclamation_clusters = [];
	  $real_clusters = [];
	  $final_clusters = [];
	  $final_clusters_2 = [];
	  $final_clusters_front_single = [];
	  $temp_final = [];
	  $final_clus_temp = [];
	
	//if the user submitted any clusters to be used
	if (count($cluster_values) > 0) {
		  

		  foreach ($cluster_values as $value) {
			 
			 //if there is an * in the cluster (which represents all consonants) the cluster is placed into the replace array
			 if (strpos($value, '*')) {
				 
				  $replace_array[] = $value;
			  }
			
			else {
				  //this double checks for an * at the beginning of the string, strpos misses these for some reason
				  if ($value[0] != '*') {
					 
					//if there is no * the cluster is sent to the ready array
					$ready_array[] = str_replace('+', '', $value);
				  }
				  //if there is an * in the cluster (which represents all consonants) the cluster is placed into the replace array
				  else {
					
					  $replace_array[] = $value;
				  }
			}
		   }
		   //the cluster is exploded at + creating a sub array of individual characters (with the exception of items containing an !)
					$exploded_cluster = explode("+", $value);
		  //for the clusters that do contain a * 
		  foreach ($replace_array as $value) {
			 
			 //if the cluster string does not contain a ! (! marks exceptions to the all consonant rule)
			 if (!strpos($value, '!')) {
				 //for each consonant that the user selected 
				foreach ($consonant_values as $value2) {
					//the * in the cluster string is replaced by a user selected consonant
					$final_clus_temp[] = str_replace('*', $value2, $value);
					//$semi_final_clusters[] = str_replace('+', '', $final_clus_temp);
				}
				foreach ($final_clus_temp as $value2) {
					$exploded_clus_temp = explode('+', $value2);
					//removes duplicate phonemes from clusters 
					$final_clus_temp_2 = implode("", array_unique($exploded_clus_temp));
					//only strings containing two or more phonemes
					if (strlen($final_clus_temp_2) > 1) {
						$final_clusters[] = $final_clus_temp_2;
					}
				}
			}
			else {
					//the cluster is exploded at + creating a sub array of individual characters (with the exception of items containing an !)
					$exploded_cluster = explode("+", $value);
					
					//for each user selected consonant
					foreach ($consonant_values as $value2) {
						//for each ipa character or ! and ipa character in a cluster string
						foreach ($exploded_cluster as $key3 => $value3) {
						
						//if there is an ! in the string 
						if (substr_count($value3, '!') > 0) {
							//cluster characters with !
							$clus[] = $value3;
							//cluster characters with ! removed
							$no_ex_clus[] = str_replace('!', '', $value3);
							
							//exceptions to the all consonant rule are added to an array with the full raw cluster as key. Array unique is used to insure no repeat values
							$clusters_exceptions[$value] = array_unique($clus);
							//exceptions - ! are added to and array with the full raw cluster data as key.
							$no_ex_exceptions[$value] = array_unique($no_ex_clus);
							//the above creates a sub array for each key, so that all exceptions to the all consonant rule created by using an * for each specific cluster are stored
						}
							
							
								
						}
						
						//cluster exceptions is a multi dimensional array where the value for each key is a sub array containing exceptions to the all consonant rule created by using an *
						foreach ($clusters_exceptions as $key3 => $value3) {
							
							//* are replaced in the cluster string by a consonant that the user has selected after the exceptions have been removed from the string 
							//str_replace here is using an imploded array_diff of the exploded cluster array compared against arrays of exeptions to the all consonants rule. 
							//This means that only characters that are in the $exploded cluster array and NOT in the array of exceptions are imploded into the string which then has its * replaced.
							$no_asterix = str_replace('*', $value2, implode("+", array_diff($exploded_cluster, $value3)));
							
						
							//the value of $no_asterix (a string with a * replaced by a user selected consonant and all excepted characters removed) is pushed into the real clusters array.
							$real_clusters[] = $no_asterix;
							
							
						}
					}
					
			}
			
		  }
			
			//for each cluster
			foreach ($real_clusters as $key => $value) {
				//clusters are exploded into arrays at +
				$exploded_real_clusters = explode('+', $value);
				
				
				//for each excemted character - its !
				foreach ($no_ex_exceptions as $value2) {
						//only characters that are not characters from the no_ex_exceptions array (using aray diff) are imploded into the temp final array as strings 
						// (any character that is a forbidden character is disregarded and only relavent ones are pushed into $temp_final[] in clusters)
						//doubles of the same consonant are also removed
							$temp_final_2[] = implode("", array_diff(array_unique($exploded_real_clusters), $value2));
						
						
				}
				
				foreach ($temp_final_2 as $value) {
					
					//checks to see that there is more than one character in the "cluster". this removes any leftover 2 consonant clusters 
					//that had an excemted character removed, leacving them as a single consonant. 
					if (strlen($value) > 1) {
						
							//all processed clusters are added to this array 
							$final_clusters_2[] = $value;
						
						
					}

				}
				
			}
			
			
			//the array of clusters that contained an * (all consonant rule) and the array of clusters that contained dedicated consonants are merged
			$full_final_clusters = array_merge(array_unique($final_clusters), array_unique($final_clusters_2), array_unique($ready_array));
			
		
			//the merged array of all clusters is returned
			return $full_final_clusters;
	}
	
  }