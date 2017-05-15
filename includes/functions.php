<?php
	function sumHours($times) {
		$seconds = 0;
		foreach ($times as $time) {
			list($hour,$minute,$second) = explode(':', $time);
			$seconds += $hour * 3600;
			$seconds += $minute * 60;
			$seconds += $second;
		}

		$hours = floor($seconds / 3600);
		$seconds -= $hours * 3600;
		$minutes = floor($seconds / 60);
		$seconds -= $minutes * 60;

		return sprintf('%02d:%02d', $hours, $minutes);
	}

	function decimalHours($time) {
		$hms = explode(":", $time);
		return round(($hms[0] + ($hms[1]/60)), 2);
	}

	function getIsoWeeksInYear($year) {
		$date = new DateTime;
		$date->setISODate($year, 53);
		return ($date->format("W") === "53" ? 53 : 52);
	}

	function getWeekNo($date) {
		$week = date('W',strtotime($date));
		$day = date('N',strtotime($date));
		$max_weeks = getIsoWeeksInYear(date('Y',strtotime($date)));

		if($day == 7 && $week != $max_weeks) {
			return ++$week;
		} else if ($day == 7) {
			return '01';
		} else {
			return $week;
		}
	}

	function getFirstLastDates($theDate) {
		$theWeek = date('w', $theDate);
		if ($theWeek > 1) {
			$dayOne  = $theDate - (($theWeek - 1)*24*60*60) - (24*60*60);
			$dayTwo = $theDate + ((7 - $theWeek)*24*60*60)- (24*60*60);
		} else if ($theWeek == 1) {
			$dayOne  = $theDate - (24*60*60);
			$dayTwo = $theDate + ((7 - $theWeek)*24*60*60)- (24*60*60);
		} else if ($theWeek == 0) {
			$dayOne  = $theDate - (6*24*60*60) - (24*60*60);
			$dayTwo = $theDate - (24*60*60);
		}

		$dayOne = date('Y-m-d',$dayOne);
		$dayTwo = date('Y-m-d',$dayTwo);

		$val = array();
		$val['sdoflw'] = $dayOne;
		$val['edoflw'] = $dayTwo;

		return $val;
	}

	function getAuth($uid) {
		global $mysqli;
		$auths = array();

		$authqry = "SELECT * FROM appauths WHERE userId = ".$uid;
		$authres = mysqli_query($mysqli, $authqry) or die('Error: getAdminAuth() Function'.mysqli_error());

		while($authrow = mysqli_fetch_assoc($authres)) {
			$authrows = array_map(null, $authrow);
			$auths[] = $authrows;
		}
		return $auths;
	}


	function checkArray($val, $arr) {
		if (in_array($val, $arr)) {
			return true;
		}
		foreach($arr as $k) {
			if (is_array($k) && checkArray($val, $k)) {
				return true;
			}
		}
		return false;
	}


	function dateFormat($v) {
		$theDate = date("F d, Y",strtotime($v));			
		return $theDate;
	}
	function dateTimeFormat($v) {
		$theDateTime = date("F d, Y g:i a",strtotime($v));	
		return $theDateTime;
	}
	function shortMonthFormat($v) {
		$theDate = date("M d, Y",strtotime($v));		
		return $theDate;
	}
	function shortDateTimeFormat($v) {
		$theDateTime = date("m/d/Y g:i a",strtotime($v));	
		return $theDateTime;
	}
	function dbDateFormat($v) {
		$theTime = date("Y-m-d",strtotime($v));			
		return $theTime;
	}
	function dbTimeFormat($v) {
		$theTime = date("H:i",strtotime($v));		
		return $theTime;
	}
	function timeFormat($v) {
		$theTime = date("g:i a",strtotime($v));			
		return $theTime;
	}
	function dbDateTimeFormat($v) {
		$theTime = date("Y-m-d H:i",strtotime($v));		
		return $theTime;
	}

    function alertBox($msg, $icon = "", $type = "") {
        return "
				<div class=\"alertMsg $type\">
					<div class=\"msgIcon pull-left\">$icon</div>
					$msg
					<a class=\"msgClose\" title=\"Close\" href=\"#\"><i class=\"fa fa-times\"></i></a>
				</div>
			";
    }

    function ellipsis($text, $max = '', $append = '&hellip;') {
        if (strlen($text) <= $max) {
			return $text;
		}

        $replacements = array(
            '|<br /><br />|' => ' ',
            '|&nbsp;|' => ' ',
            '|&rsquo;|' => '\'',
            '|&lsquo;|' => '\'',
            '|&ldquo;|' => '"',
            '|&rdquo;|' => '"',
        );

        $patterns = array_keys($replacements);
        $replacements = array_values($replacements);

        $text = preg_replace($patterns, $replacements, $text);

        $text = strip_tags($text);

        $out = substr($text, 0, $max);
        if (strpos($text, ' ') === false) {
			return $out.$append;
		}

        return preg_replace('/(\W)&(\W)/', '$1&amp;$2', (preg_replace('/\W+$/', ' ', preg_replace('/\w+$/', '', $out)))).$append;
    }


	define('SALT', 'DvHtl3CGp4QLuuOEtBQ2AS4QLuuOEt');

	function encodeIt($value) {
		return trim(
			base64_encode(
				mcrypt_encrypt(
					MCRYPT_RIJNDAEL_256,
					SALT,
					$value,
					MCRYPT_MODE_ECB,
					mcrypt_create_iv(
						mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB),
						MCRYPT_RAND
					)
				)
			)
		);
	}

	function decodeIt($value) {
		return trim(
			mcrypt_decrypt(
				MCRYPT_RIJNDAEL_256,
				SALT,
				base64_decode($value),
				MCRYPT_MODE_ECB,
				mcrypt_create_iv(
					mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB),
					MCRYPT_RAND
				)
			)
		);
	}

	function clean($value) {
		$str = str_replace('\\', '', $value);

		return $str;
	}


	function strip($value) {
		$str = preg_replace('/[\W]+/', ' ', $value);

		return $str;
	}

	function updateActivity($uid,$type,$title) {
		global $mysqli;

		$activityIp = $_SERVER['REMOTE_ADDR'];

		$stmt = $mysqli->prepare("
							INSERT INTO
								activity(
									userId,
									activityType,
									activityTitle,
									activityDate,
									ipAddress
								) VALUES (
									?,
									?,
									?,
									NOW(),
									?
								)
		");
		$stmt->bind_param('ssss',
							$uid,
							$type,
							$title,
							$activityIp
		);
		$stmt->execute();
		$stmt->close();
	}