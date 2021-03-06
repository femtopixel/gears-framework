<?php
/**
 * Collection of function usefull anywhere in the code
 *
 * @package   Core
 * @author    Jeremy MOULIN <jeremy.moulin@doonoyz.com>
 * @copyright 2008-2009 Doonoyz
 * @version   Paper
 */
class Gears_Utile {

	/**
	 * Prevent from HTML injections
	 *
	 * @param String|Array $results String or Array to securize
	 *
	 * @return String|Array Securized data
	 */
	static public function dbResultHtmlSecurise($results) {
		if (is_array($results)) {
			foreach ($results as $key => $val) {
				$results[$key] = self::dbResultHtmlSecurise($val);
			}
			return ($results);
		} else {
			return htmlentities($results);
		}
	}
	
	/**
	 * Clean HTML code by removing id and class attributes and JS injections
	 *
	 * @param string $string HTML Code to clean
	 * @return string Cleaned HTML code
	 */
	static public function cleanHtml($string) {
		$string = preg_replace ( "/id='[^']*'/u", "", $string );
		$string = preg_replace ( "/id=[\"][^\"]*[\"]/u", "", $string );
		$string = preg_replace ( "/class=[\"][^\"]*[\"]/u", "", $string );
		$string = preg_replace ( "/class='[^']*'/u", "", $string );
		$string = preg_replace ( "/id=[^ ]+/u", "", $string );
		$string = preg_replace ( "/class=[^ ]+/u", "", $string );
		$string = preg_replace ( "/<script.*>.*<\/script>/", "", $string );
		$string = preg_replace ( "/<script.*>/", "", $string );
		return ($string);
	}

	/**
	 * Remove all accents to their ANSI equivalent ex : "ü" => "u"
	 *
	 * @param string $string String containing accent
	 * @return string String without accent
	 */
	static public function removeAccent($string) {
		$Caracs = array("¥" => "Y", "µ" => "u", "À" => "A", "Á" => "A",
				"Â" => "A", "Ã" => "A", "Ä" => "A", "Å" => "A",
				"Æ" => "AE", "Ç" => "C", "È" => "E", "É" => "E",
				"Ê" => "E", "Ë" => "E", "Ì" => "I", "Í" => "I",
				"Î" => "I", "Ï" => "I", "Ð" => "D", "Ñ" => "N",
				"Ò" => "O", "Ó" => "O", "Ô" => "O", "Õ" => "O",
				"Ö" => "O", "Ø" => "O", "Ù" => "U", "Ú" => "U",
				"Û" => "U", "Ü" => "U", "Ý" => "Y", "ß" => "s",
				"à" => "a", "á" => "a", "â" => "a", "ã" => "a",
				"ä" => "a", "å" => "a", "æ" => "ae", "ç" => "c",
				"è" => "e", "é" => "e", "ê" => "e", "ë" => "e",
				"ì" => "i", "í" => "i", "î" => "i", "ï" => "i",
				"ð" => "o", "ñ" => "n", "ò" => "o", "ó" => "o",
				"ô" => "o", "õ" => "o", "ö" => "o", "ø" => "o",
				"ù" => "u", "ú" => "u", "û" => "u", "ü" => "u",
				"ý" => "y", "ÿ" => "y");

		return (strtr ( "$string", $Caracs ));
	}
	
	/**
	 * Decode a password encoded string
	 *
	 * @param string $password Password to decode
	 * @param string $str      String to decode
	 *
	 * @return string String password decoded
	 */
	static public function passwordDecode($password, $str) {
		$password = md5($password);
		$letter = -1;
		$newstr = '';
		$str = base64_decode($str);
		$strlen = strlen($str);
		for ( $i = 0; $i < $strlen; $i++ ) {
			$letter++;
			if ( $letter > 31 ) {
				$letter = 0;
			}
			$neword = ord($str{$i}) - ord($password{$letter});
			if ( $neword < 1 ) {
				$neword += 256;
			}
			$newstr .= chr($neword);
		}
		return $newstr;
	}

	/**
	 * Encode a string with a password
	 *
	 * @param string $password Password to use
	 * @param string $str      String to encode
	 *
	 * @return string String password encoded
	 */
	static public function passwordEncode($password, $str) {
		$password = md5($password);
		$letter = -1;
		$newstr = '';
		$strlen = strlen($str);
		for ( $i = 0; $i < $strlen; $i++ ) {
			$letter++;
			if ( $letter > 31 ) {
				$letter = 0;
			}
			$neword = ord($str{$i}) + ord($password{$letter});
			if ( $neword > 255 ) {
				$neword -= 256;
			}
			$newstr .= chr($neword);
		}
		return base64_encode($newstr);
	}
	
	/**
	 * Strtolower in UTF8
	 *
	 * @param string $inputString String to lower
	 *
	 * @return string String to lower
	 */
	static public function strtolower_utf8($inputString) {
		$outputString    = utf8_decode($inputString);
		$outputString    = strtolower($outputString);
		$outputString    = utf8_encode($outputString);
		return $outputString;
	}
	
	/**
	 * Strtoupper in UTF8
	 *
	 * @param string $inputString String to upper
	 *
	 * @return string String to upper
	 */
	static public function strtoupper_utf8($inputString) {
		$outputString    = utf8_decode($inputString);
		$outputString    = strtoupper($outputString);
		$outputString    = utf8_encode($outputString);
		return $outputString;
	}
	
	/**
	 * Set the session id for flash use
	 */
	static public function getFlashSession() {
		$credentials = Array('sessid' => Zend_Session::getId(), "secret" => uniqid());
		$value = self::passwordEncode('IKnowThisIsntSecureBecauseItsMySessionId', serialize($credentials));
		return ( urlencode ( $value ) );
	}
	
	/**
	 * Set the session id for flash use
	 */
	static public function setFlashSession() {
		if (isset($_GET['flashsession']) && strlen($_GET['flashsession'])) {
			try {
				$values = self::passwordDecode('IKnowThisIsntSecureBecauseItsMySessionId', $_GET['flashsession']);
				$values = unserialize($values);
				Zend_Session::setId($values['sessid']);
			} catch (Exception $e) {
			}
		}
	}
	
	/**
	 * Test if the IP is valid
	 *
	 * @param string $ip IP to test
	 *
	 * @return bool Valid IP ?
	 */
	private static function _testValidIp($ip) {
		if (!empty ( $ip ) && ip2long ( $ip ) != -1) {
			$reserved_ips = array (
				array('0.0.0.0','2.255.255.255'),
				array('10.0.0.0','10.255.255.255'),
				array('127.0.0.0','127.255.255.255'),
				array('169.254.0.0','169.254.255.255'),
				array('172.16.0.0','172.31.255.255'),
				array('192.0.2.0','192.0.2.255'),
				array('192.168.0.0','192.168.255.255'),
				array('255.255.255.0','255.255.255.255')
			);

			foreach ($reserved_ips as $r) {
				$min = ip2long($r[0]);
				$max = ip2long($r[1]);
				if ((ip2long ( $ip ) >= $min) && ( ip2long ( $ip ) <= $max)) {
					return false;
				}
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Retrieve client IP, even behind a proxy
	 *
	 * @return string Client IP
	 */
	public static function getIp() {
		if (isset($_SERVER["HTTP_CLIENT_IP"]) && self::_testValidIp($_SERVER["HTTP_CLIENT_IP"])) {
			return $_SERVER["HTTP_CLIENT_IP"];
		}
		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			foreach (explode(",",$_SERVER["HTTP_X_FORWARDED_FOR"]) as $ip) {
				if ( self::_testValidIp ( trim ( $ip ) ) ) {
					return $ip;
				}
			}
		}
		if ( isset ($_SERVER["HTTP_X_FORWARDED"]) && self::_testValidIp ( $_SERVER["HTTP_X_FORWARDED"] )) {
			return $_SERVER["HTTP_X_FORWARDED"];
		} elseif ( isset($_SERVER["HTTP_FORWARDED_FOR"]) && self::_testValidIp ($_SERVER["HTTP_FORWARDED_FOR"])) {
			return $_SERVER["HTTP_FORWARDED_FOR"];
		} elseif ( isset($_SERVER["HTTP_FORWARDED"]) && self::_testValidIp ($_SERVER["HTTP_FORWARDED"])) {
			return $_SERVER["HTTP_FORWARDED"];
		} elseif ( isset($_SERVER["HTTP_X_FORWARDED"]) && self::_testValidIp ($_SERVER["HTTP_X_FORWARDED"])) {
			return $_SERVER["HTTP_X_FORWARDED"];
		} else {
			return $_SERVER["REMOTE_ADDR"];
		}
	}
	
	/**
	 * Detect if the browser comes from a mobile device
	 *
	 * @return string|bool Detected browser, or true if only mobile detected, or false if not detected
	 */
	static function isMobileDevice() {
		$mobileDevices = array('1207' => '1207', 
								'3gso' => '3gso',
								'4thp' => '4thp',
								'501i' => '501i',
								'502i' => '502i',
								'503i' => '503i',
								'504i' => '504i',
								'505i' => '505i',
								'506i' => '506i',
								'6310' => '6310',
								'6590' => '6590',
								'770s' => '770s',
								'802s' => '802s',
								'a wa' => 'a wa',
								'acer' => 'acer',
								'acs-' => 'acs-',
								'airn' => 'airn',
								'alav' => 'alav',
								'asus' => 'asus',
								'attw' => 'attw',
								'au-m' => 'au-m',
								'aur ' => 'aur ',
								'aus ' => 'aus ',
								'abac' => 'abac',
								'acoo' => 'acoo',
								'aiko' => 'aiko',
								'alco' => 'alco',
								'alca' => 'alca',
								'amoi' => 'amoi',
								'anex' => 'anex',
								'anny' => 'anny',
								'anyw' => 'anyw',
								'aptu' => 'aptu',
								'arch' => 'arch',
								'argo' => 'argo',
								'bell' => 'bell',
								'bird' => 'bird',
								'bw-n' => 'bw-n',
								'bw-u' => 'bw-u',
								'beck' => 'beck',
								'benq' => 'benq',
								'bilb' => 'bilb',
								'blac' => 'blac',
								'c55/' => 'c55/',
								'cdm-' => 'cdm-',
								'chtm' => 'chtm',
								'capi' => 'capi',
								'comp' => 'comp',
								'cond' => 'cond',
								'craw' => 'craw',
								'dall' => 'dall',
								'dbte' => 'dbte',
								'dc-s' => 'dc-s',
								'dica' => 'dica',
								'ds-d' => 'ds-d',
								'ds12' => 'ds12',
								'dait' => 'dait',
								'devi' => 'devi',
								'dmob' => 'dmob',
								'doco' => 'doco',
								'dopo' => 'dopo',
								'el49' => 'el49',
								'erk0' => 'erk0',
								'esl8' => 'esl8',
								'ez40' => 'ez40',
								'ez60' => 'ez60',
								'ez70' => 'ez70',
								'ezos' => 'ezos',
								'ezze' => 'ezze',
								'elai' => 'elai',
								'emul' => 'emul',
								'eric' => 'eric',
								'ezwa' => 'ezwa',
								'fake' => 'fake',
								'fly-' => 'fly-',
								'fly_' => 'fly_',
								'g-mo' => 'g-mo',
								'g1 u' => 'g1 u',
								'g560' => 'g560',
								'gf-5' => 'gf-5',
								'grun' => 'grun',
								'gene' => 'gene',
								'go.w' => 'go.w',
								'good' => 'good',
								'grad' => 'grad',
								'hcit' => 'hcit',
								'hd-m' => 'hd-m',
								'hd-p' => 'hd-p',
								'hd-t' => 'hd-t',
								'hei-' => 'hei-',
								'hp i' => 'hp i',
								'hpip' => 'hpip',
								'hs-c' => 'hs-c',
								'htc ' => 'htc ',
								'htc-' => 'htc-',
								'htca' => 'htca',
								'htcg' => 'htcg',
								'htcp' => 'htcp',
								'htcs' => 'htcs',
								'htct' => 'htct',
								'htc_' => 'htc_',
								'haie' => 'haie',
								'hita' => 'hita',
								'huaw' => 'huaw',
								'hutc' => 'hutc',
								'i-20' => 'i-20',
								'i-go' => 'i-go',
								'i-ma' => 'i-ma',
								'i230' => 'i230',
								'iac' => 'iac',
								'iac-' => 'iac-',
								'iac/' => 'iac/',
								'ig01' => 'ig01',
								'im1k' => 'im1k',
								'inno' => 'inno',
								'iris' => 'iris',
								'jata' => 'jata',
								'java' => 'java',
								'kddi' => 'kddi',
								'kgt' => 'kgt',
								'kgt/' => 'kgt/',
								'kpt ' => 'kpt ',
								'kwc-' => 'kwc-',
								'klon' => 'klon',
								'lexi' => 'lexi',
								'lg g' => 'lg g',
								'lg-a' => 'lg-a',
								'lg-b' => 'lg-b',
								'lg-c' => 'lg-c',
								'lg-d' => 'lg-d',
								'lg-f' => 'lg-f',
								'lg-g' => 'lg-g',
								'lg-k' => 'lg-k',
								'lg-l' => 'lg-l',
								'lg-m' => 'lg-m',
								'lg-o' => 'lg-o',
								'lg-p' => 'lg-p',
								'lg-s' => 'lg-s',
								'lg-t' => 'lg-t',
								'lg-u' => 'lg-u',
								'lg-w' => 'lg-w',
								'lg/k' => 'lg/k',
								'lg/l' => 'lg/l',
								'lg/u' => 'lg/u',
								'lg50' => 'lg50',
								'lg54' => 'lg54',
								'lge-' => 'lge-',
								'lge/' => 'lge/',
								'lynx' => 'lynx',
								'leno' => 'leno',
								'm1-w' => 'm1-w',
								'm3ga' => 'm3ga',
								'm50/' => 'm50/',
								'maui' => 'maui',
								'mc01' => 'mc01',
								'mc21' => 'mc21',
								'mcca' => 'mcca',
								'medi' => 'medi',
								'meri' => 'meri',
								'mio8' => 'mio8',
								'mioa' => 'mioa',
								'mo01' => 'mo01',
								'mo02' => 'mo02',
								'mode' => 'mode',
								'modo' => 'modo',
								'mot ' => 'mot ',
								'mot-' => 'mot-',
								'mt50' => 'mt50',
								'mtp1' => 'mtp1',
								'mtv ' => 'mtv ',
								'mate' => 'mate',
								'maxo' => 'maxo',
								'merc' => 'merc',
								'mits' => 'mits',
								'mobi' => 'mobi',
								'motv' => 'motv',
								'mozz' => 'mozz',
								'n100' => 'n100',
								'n101' => 'n101',
								'n102' => 'n102',
								'n202' => 'n202',
								'n203' => 'n203',
								'n300' => 'n300',
								'n302' => 'n302',
								'n500' => 'n500',
								'n502' => 'n502',
								'n505' => 'n505',
								'n700' => 'n700',
								'n701' => 'n701',
								'n710' => 'n710',
								'nec-' => 'nec-',
								'nem-' => 'nem-',
								'newg' => 'newg',
								'neon' => 'neon',
								'netf' => 'netf',
								'noki' => 'noki',
								'nzph' => 'nzph',
								'o2 x' => 'o2 x',
								'o2-x' => 'o2-x',
								'opwv' => 'opwv',
								'owg1' => 'owg1',
								'opti' => 'opti',
								'oran' => 'oran',
								'p800' => 'p800',
								'pand' => 'pand',
								'pg-1' => 'pg-1',
								'pg-2' => 'pg-2',
								'pg-3' => 'pg-3',
								'pg-6' => 'pg-6',
								'pg-8' => 'pg-8',
								'pg-c' => 'pg-c',
								'pg13' => 'pg13',
								'phil' => 'phil',
								'pn-2' => 'pn-2',
								'pt-g' => 'pt-g',
								'palm' => 'palm',
								'pana' => 'pana',
								'pire' => 'pire',
								'pock' => 'pock',
								'pose' => 'pose',
								'psio' => 'psio',
								'qa-a' => 'qa-a',
								'qc-2' => 'qc-2',
								'qc-3' => 'qc-3',
								'qc-5' => 'qc-5',
								'qc-7' => 'qc-7',
								'qc07' => 'qc07',
								'qc12' => 'qc12',
								'qc21' => 'qc21',
								'qc32' => 'qc32',
								'qc60' => 'qc60',
								'qci-' => 'qci-',
								'qwap' => 'qwap',
								'qtek' => 'qtek',
								'r380' => 'r380',
								'r600' => 'r600',
								'raks' => 'raks',
								'rim9' => 'rim9',
								'rove' => 'rove',
								's55/' => 's55/',
								'sage' => 'sage',
								'sams' => 'sams',
								'sc01' => 'sc01',
								'sch-' => 'sch-',
								'scp-' => 'scp-',
								'sdk/' => 'sdk/',
								'se47' => 'se47',
								'sec-' => 'sec-',
								'sec0' => 'sec0',
								'sec1' => 'sec1',
								'semc' => 'semc',
								'sgh-' => 'sgh-',
								'shar' => 'shar',
								'sie-' => 'sie-',
								'sk-0' => 'sk-0',
								'sl45' => 'sl45',
								'slid' => 'slid',
								'smb3' => 'smb3',
								'smt5' => 'smt5',
								'sp01' => 'sp01',
								'sph-' => 'sph-',
								'spv ' => 'spv ',
								'spv-' => 'spv-',
								'sy01' => 'sy01',
								'samm' => 'samm',
								'sany' => 'sany',
								'sava' => 'sava',
								'scoo' => 'scoo',
								'send' => 'send',
								'siem' => 'siem',
								'smar' => 'smar',
								'smit' => 'smit',
								'soft' => 'soft',
								'sony' => 'sony',
								't-mo' => 't-mo',
								't218' => 't218',
								't250' => 't250',
								't600' => 't600',
								't610' => 't610',
								't618' => 't618',
								'tcl-' => 'tcl-',
								'tdg-' => 'tdg-',
								'telm' => 'telm',
								'tim-' => 'tim-',
								'ts70' => 'ts70',
								'tsm-' => 'tsm-',
								'tsm3' => 'tsm3',
								'tsm5' => 'tsm5',
								'tx-9' => 'tx-9',
								'tagt' => 'tagt',
								'talk' => 'talk',
								'teli' => 'teli',
								'topl' => 'topl',
								'tosh' => 'tosh',
								'up.b' => 'up.b',
								'upg1' => 'upg1',
								'utst' => 'utst',
								'v400' => 'v400',
								'v750' => 'v750',
								'veri' => 'veri',
								'vk-v' => 'vk-v',
								'vk40' => 'vk40',
								'vk50' => 'vk50',
								'vk52' => 'vk52',
								'vk53' => 'vk53',
								'vm40' => 'vm40',
								'vx98' => 'vx98',
								'virg' => 'virg',
								'vite' => 'vite',
								'voda' => 'voda',
								'vulc' => 'vulc',
								'w3c ' => 'w3c ',
								'w3c-' => 'w3c-',
								'wapj' => 'wapj',
								'wapp' => 'wapp',
								'wapu' => 'wapu',
								'wapm' => 'wapm',
								'wig ' => 'wig ',
								'wapi' => 'wapi',
								'wapr' => 'wapr',
								'wapv' => 'wapv',
								'wapy' => 'wapy',
								'wapa' => 'wapa',
								'waps' => 'waps',
								'wapt' => 'wapt',
								'winc' => 'winc',
								'winw' => 'winw',
								'wonu' => 'wonu',
								'x700' => 'x700',
								'xda2' => 'xda2',
								'xdag' => 'xdag',
								'yas-' => 'yas-',
								'your' => 'your',
								'zte-' => 'zte-',
								'zeto' => 'zeto',
								'acs-' => 'acs-',
								'alav' => 'alav',
								'alca' => 'alca',
								'amoi' => 'amoi',
								'aste' => 'aste',
								'audi' => 'audi',
								'avan' => 'avan',
								'benq' => 'benq',
								'bird' => 'bird',
								'blac' => 'blac',
								'blaz' => 'blaz',
								'brew' => 'brew',
								'brvw' => 'brvw',
								'bumb' => 'bumb',
								'ccwa' => 'ccwa',
								'cell' => 'cell',
								'cldc' => 'cldc',
								'cmd-' => 'cmd-',
								'dang' => 'dang',
								'doco' => 'doco',
								'eml2' => 'eml2',
								'eric' => 'eric',
								'fetc' => 'fetc',
								'hipt' => 'hipt',
								'http' => 'http',
								'ibro' => 'ibro',
								'idea' => 'idea',
								'ikom' => 'ikom',
								'inno' => 'inno',
								'ipaq' => 'ipaq',
								'jbro' => 'jbro',
								'jemu' => 'jemu',
								'java' => 'java',
								'jigs' => 'jigs',
								'kddi' => 'kddi',
								'keji' => 'keji',
								'kyoc' => 'kyoc',
								'kyok' => 'kyok',
								'leno' => 'leno',
								'lg-c' => 'lg-c',
								'lg-d' => 'lg-d',
								'lg-g' => 'lg-g',
								'lge-' => 'lge-',
								'libw' => 'libw',
								'm-cr' => 'm-cr',
								'maui' => 'maui',
								'maxo' => 'maxo',
								'midp' => 'midp',
								'mits' => 'mits',
								'mmef' => 'mmef',
								'mobi' => 'mobi',
								'mot-' => 'mot-',
								'moto' => 'moto',
								'mwbp' => 'mwbp',
								'mywa' => 'mywa',
								'nec-' => 'nec-',
								'newt' => 'newt',
								'nok6' => 'nok6',
								'noki' => 'noki',
								'o2im' => 'o2im',
								'opwv' => 'opwv',
								'palm' => 'palm',
								'pana' => 'pana',
								'pant' => 'pant',
								'pdxg' => 'pdxg',
								'phil' => 'phil',
								'play' => 'play',
								'pluc' => 'pluc',
								'port' => 'port',
								'prox' => 'prox',
								'qtek' => 'qtek',
								'qwap' => 'qwap',
								'rozo' => 'rozo',
								'sage' => 'sage',
								'sama' => 'sama',
								'sams' => 'sams',
								'sany' => 'sany',
								'sch-' => 'sch-',
								'sec-' => 'sec-',
								'send' => 'send',
								'seri' => 'seri',
								'sgh-' => 'sgh-',
								'shar' => 'shar',
								'sie-' => 'sie-',
								'siem' => 'siem',
								'smal' => 'smal',
								'smar' => 'smar',
								'sony' => 'sony',
								'sph-' => 'sph-',
								'symb' => 'symb',
								't-mo' => 't-mo',
								'teli' => 'teli',
								'tim-' => 'tim-',
								'tosh' => 'tosh',
								'treo' => 'treo',
								'tsm-' => 'tsm-',
								'upg1' => 'upg1',
								'upsi' => 'upsi',
								'vk-v' => 'vk-v',
								'voda' => 'voda',
								'vx52' => 'vx52',
								'vx53' => 'vx53',
								'vx60' => 'vx60',
								'vx61' => 'vx61',
								'vx70' => 'vx70',
								'vx80' => 'vx80',
								'vx81' => 'vx81',
								'vx83' => 'vx83',
								'vx85' => 'vx85',
								'wap-' => 'wap-',
								'wapa' => 'wapa',
								'wapi' => 'wapi',
								'wapp' => 'wapp',
								'wapr' => 'wapr',
								'webc' => 'webc',
								'whit' => 'whit',
								'winw' => 'winw',
								'wmlb' => 'wmlb',
								'xda-' => 'xda-',
								);
		$mobile_browser	= false;
		$user_agent	= $_SERVER['HTTP_USER_AGENT'];
		$accept	= $_SERVER['HTTP_ACCEPT'];
		
		switch (true) {
			case (strpos('ipod', $user_agent) || strpos('iphone', $user_agent));
				$mobile_browser = "iphone";
			break;

			case (strpos('android', $user_agent));
				$mobile_browser = "android";
				break;

			case (strpos('opera mini', $user_agent));
				$mobile_browser = "opera";
				break;

			case (strpos('blackberry', $user_agent));
				$mobile_browser = "blackberry";
				break;

			case (preg_match('/(palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine)/i', $user_agent));
				$mobile_browser = "palm";
				break;

			case (preg_match('/(windows ce; ppc;|windows ce; smartphone;|windows ce; iemobile)/i', $user_agent));
				$mobile_browser = "windows";
				break;

			case (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|pda|psp|treo)/i', $user_agent));
				$mobile_browser = true;
				break;

			case ((strpos($accept, 'text/vnd.wap.wml') > 0) || (strpos($accept, 'application/vnd.wap.xhtml+xml') > 0));
				$mobile_browser = true;
				break;

			case (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE']));
				$mobile_browser = true;
				break;

			case (in_array(strtolower(substr($user_agent, 0, 4)), $mobileDevices));
				$mobile_browser = true;
				break;
		}
		return $mobile_browser;
	}
}
