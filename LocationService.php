<?php
/**
 * This class retrieve all informations for searching a city by its name/country/postcode
 *
 * <code>
 * $result = Gears_LocationService::getInfos('reau', 'France');
 * $latitude = $result['LATITUDE'];
 * $longitude = $result['LONGITUDE'];
 *
 * $result = Gears_LocationService::getIpInfo('127.0.0.1');
 * $city = $result['CITY'];
 * $country = $result['COUNTRY'];
 * </code>
 *
 * @package   Core
 * @author    Jeremy MOULIN <jeremy.moulin@doonoyz.com>
 * @copyright 2008-2009 Doonoyz
 * @version   Paper
 */
class Gears_LocationService implements Gears_LocationService_Ip_Interface {
	/**
	 * All the countrie by their country code
	 *
	 */
	static private $_countries = array();
	/**
	 * Retrieve countries
	 *
	 * @return Array Array containing countries by their country code
	 */
	public static function getCountries() {
		self::$_countries = array();
		/*self::$_countries = Array('AD' => tr( "Andorra"),
								'AE' => tr( "United Arab Emirates"),
								'AF' => tr( "Afghanistan"),
								'AG' => tr( "Antigua and Barbuda"),
								'AI' => tr( "Anguilla"),
								'AL' => tr( "Albania"),
								'AM' => tr( "Armenia"),
								'AN' => tr( "Netherlands Antilles"),
								'AO' => tr( "Angola"),
								'AQ' => tr( "Antarctica"),
								'AR' => tr( "Argentina"),
								'AS' => tr( "American Samoa"),
								'AT' => tr( "Austria"),
								'AU' => tr( "Australia"),
								'AW' => tr( "Aruba"),
								'AX' => tr( "Aland Islands"),
								'AZ' => tr( "Azerbaijan"),
								'BA' => tr( "Bosnia and Herzegovina"),
								'BB' => tr( "Barbados"),
								'BD' => tr( "Bangladesh"),
								'BE' => tr( "Belgium"),
								'BF' => tr( "Burkina Faso"),
								'BG' => tr( "Bulgaria"),
								'BH' => tr( "Bahrain"),
								'BI' => tr( "Burundi"),
								'BJ' => tr( "Benin"),
								'BL' => tr( "Saint Barthelemy"),
								'BM' => tr( "Bermuda"),
								'BN' => tr( "Brunei"),
								'BO' => tr( "Bolivia"),
								'BR' => tr( "Brazil"),
								'BS' => tr( "Bahamas"),
								'BT' => tr( "Bhutan"),
								'BV' => tr( "Bouvet Island"),
								'BW' => tr( "Botswana"),
								'BY' => tr( "Belarus"),
								'BZ' => tr( "Belize"),
								'CA' => tr( "Canada"),
								'CC' => tr( "Cocos Islands"),
								'CD' => tr( "Democratic Republic of the Congo"),
								'CF' => tr( "Central African Republic"),
								'CG' => tr( "Republic of the Congo"),
								'CH' => tr( "Switzerland"),
								'CI' => tr( "Ivory Coast"),
								'CK' => tr( "Cook Islands"),
								'CL' => tr( "Chile"),
								'CM' => tr( "Cameroon"),
								'CN' => tr( "China"),
								'CO' => tr( "Colombia"),
								'CR' => tr( "Costa Rica"),
								'CU' => tr( "Cuba"),
								'CV' => tr( "Cape Verde"),
								'CX' => tr( "Christmas Island"),
								'CY' => tr( "Cyprus"),
								'CZ' => tr( "Czech Republic"),
								'DE' => tr( "Germany"),
								'DJ' => tr( "Djibouti"),
								'DK' => tr( "Denmark"),
								'DM' => tr( "Dominica"),
								'DO' => tr( "Dominican Republic"),
								'DZ' => tr( "Algeria"),
								'EC' => tr( "Ecuador"),
								'EE' => tr( "Estonia"),
								'EG' => tr( "Egypt"),
								'EH' => tr( "Western Sahara"),
								'ER' => tr( "Eritrea"),
								'ES' => tr( "Spain"),
								'ET' => tr( "Ethiopia"),
								'FI' => tr( "Finland"),
								'FJ' => tr( "Fiji"),
								'FK' => tr( "Falkland Islands"),
								'FM' => tr( "Micronesia"),
								'FO' => tr( "Faroe Islands"),
								'FR' => tr( "France"),
								'GA' => tr( "Gabon"),
								'GB' => tr( "United Kingdom"),
								'GD' => tr( "Grenada"),
								'GE' => tr( "Georgia"),
								'GF' => tr( "French Guiana"),
								'GG' => tr( "Guernsey"),
								'GH' => tr( "Ghana"),
								'GI' => tr( "Gibraltar"),
								'GL' => tr( "Greenland"),
								'GM' => tr( "Gambia"),
								'GN' => tr( "Guinea"),
								'GP' => tr( "Guadeloupe"),
								'GQ' => tr( "Equatorial Guinea"),
								'GR' => tr( "Greece"),
								'GS' => tr( "South Georgia and the South Sandwich Islands"),
								'GT' => tr( "Guatemala"),
								'GU' => tr( "Guam"),
								'GW' => tr( "Guinea-Bissau"),
								'GY' => tr( "Guyana"),
								'HK' => tr( "Hong Kong"),
								'HM' => tr( "Heard Island and McDonald Islands"),
								'HN' => tr( "Honduras"),
								'HR' => tr( "Croatia"),
								'HT' => tr( "Haiti"),
								'HU' => tr( "Hungary"),
								'ID' => tr( "Indonesia"),
								'IE' => tr( "Ireland"),
								'IL' => tr( "Israel"),
								'IM' => tr( "Isle of Man"),
								'IN' => tr( "India"),
								'IO' => tr( "British Indian Ocean Territory"),
								'IQ' => tr( "Iraq"),
								'IR' => tr( "Iran"),
								'IS' => tr( "Iceland"),
								'IT' => tr( "Italy"),
								'JE' => tr( "Jersey"),
								'JM' => tr( "Jamaica"),
								'JO' => tr( "Jordan"),
								'JP' => tr( "Japan"),
								'KE' => tr( "Kenya"),
								'KG' => tr( "Kyrgyzstan"),
								'KH' => tr( "Cambodia"),
								'KI' => tr( "Kiribati"),
								'KM' => tr( "Comoros"),
								'KN' => tr( "Saint Kitts and Nevis"),
								'KP' => tr( "North Korea"),
								'KR' => tr( "South Korea"),
								'KW' => tr( "Kuwait"),
								'KY' => tr( "Cayman Islands"),
								'KZ' => tr( "Kazakhstan"),
								'LA' => tr( "Laos"),
								'LB' => tr( "Lebanon"),
								'LC' => tr( "Saint Lucia"),
								'LI' => tr( "Liechtenstein"),
								'LK' => tr( "Sri Lanka"),
								'LR' => tr( "Liberia"),
								'LS' => tr( "Lesotho"),
								'LT' => tr( "Lithuania"),
								'LU' => tr( "Luxembourg"),
								'LV' => tr( "Latvia"),
								'LY' => tr( "Libya"),
								'MA' => tr( "Morocco"),
								'MC' => tr( "Monaco"),
								'MD' => tr( "Moldova"),
								'ME' => tr( "Montenegro"),
								'MF' => tr( "Saint Martin"),
								'MG' => tr( "Madagascar"),
								'MH' => tr( "Marshall Islands"),
								'MK' => tr( "Macedonia"),
								'ML' => tr( "Mali"),
								'MM' => tr( "Myanmar"),
								'MN' => tr( "Mongolia"),
								'MO' => tr( "Macao"),
								'MP' => tr( "Northern Mariana Islands"),
								'MQ' => tr( "Martinique"),
								'MR' => tr( "Mauritania"),
								'MS' => tr( "Montserrat"),
								'MT' => tr( "Malta"),
								'MU' => tr( "Mauritius"),
								'MV' => tr( "Maldives"),
								'MW' => tr( "Malawi"),
								'MX' => tr( "Mexico"),
								'MY' => tr( "Malaysia"),
								'MZ' => tr( "Mozambique"),
								'NA' => tr( "Namibia"),
								'NC' => tr( "New Caledonia"),
								'NE' => tr( "Niger"),
								'NF' => tr( "Norfolk Island"),
								'NG' => tr( "Nigeria"),
								'NI' => tr( "Nicaragua"),
								'NL' => tr( "Netherlands"),
								'NO' => tr( "Norway"),
								'NP' => tr( "Nepal"),
								'NR' => tr( "Nauru"),
								'NU' => tr( "Niue"),
								'NZ' => tr( "New Zealand"),
								'OM' => tr( "Oman"),
								'PA' => tr( "Panama"),
								'PE' => tr( "Peru"),
								'PF' => tr( "French Polynesia"),
								'PG' => tr( "Papua New Guinea"),
								'PH' => tr( "Philippines"),
								'PK' => tr( "Pakistan"),
								'PL' => tr( "Poland"),
								'PM' => tr( "Saint Pierre and Miquelon"),
								'PN' => tr( "Pitcairn"),
								'PR' => tr( "Puerto Rico"),
								'PS' => tr( "Palestinian Territory"),
								'PT' => tr( "Portugal"),
								'PW' => tr( "Palau"),
								'PY' => tr( "Paraguay"),
								'QA' => tr( "Qatar"),
								'RE' => tr( "Reunion"),
								'RO' => tr( "Romania"),
								'RS' => tr( "Serbia"),
								'RU' => tr( "Russia"),
								'RW' => tr( "Rwanda"),
								'SA' => tr( "Saudi Arabia"),
								'SB' => tr( "Solomon Islands"),
								'SC' => tr( "Seychelles"),
								'SD' => tr( "Sudan"),
								'SE' => tr( "Sweden"),
								'SG' => tr( "Singapore"),
								'SH' => tr( "Saint Helena"),
								'SI' => tr( "Slovenia"),
								'SJ' => tr( "Svalbard and Jan Mayen"),
								'SK' => tr( "Slovakia"),
								'SL' => tr( "Sierra Leone"),
								'SM' => tr( "San Marino"),
								'SN' => tr( "Senegal"),
								'SO' => tr( "Somalia"),
								'SR' => tr( "Suriname"),
								'ST' => tr( "Sao Tome and Principe"),
								'SV' => tr( "El Salvador"),
								'SY' => tr( "Syria"),
								'SZ' => tr( "Swaziland"),
								'TC' => tr( "Turks and Caicos Islands"),
								'TD' => tr( "Chad"),
								'TF' => tr( "French Southern Territories"),
								'TG' => tr( "Togo"),
								'TH' => tr( "Thailand"),
								'TJ' => tr( "Tajikistan"),
								'TK' => tr( "Tokelau"),
								'TL' => tr( "East Timor"),
								'TM' => tr( "Turkmenistan"),
								'TN' => tr( "Tunisia"),
								'TO' => tr( "Tonga"),
								'TR' => tr( "Turkey"),
								'TT' => tr( "Trinidad and Tobago"),
								'TV' => tr( "Tuvalu"),
								'TW' => tr( "Taiwan"),
								'TZ' => tr( "Tanzania"),
								'UA' => tr( "Ukraine"),
								'UG' => tr( "Uganda"),
								'UM' => tr( "United States Minor Outlying Islands"),
								'US' => tr( "United States"),
								'UY' => tr( "Uruguay"),
								'UZ' => tr( "Uzbekistan"),
								'VA' => tr( "Vatican"),
								'VC' => tr( "Saint Vincent and the Grenadines"),
								'VE' => tr( "Venezuela"),
								'VG' => tr( "British Virgin Islands"),
								'VI' => tr( "U.S. Virgin Islands"),
								'VN' => tr( "Vietnam"),
								'VU' => tr( "Vanuatu"),
								'WF' => tr( "Wallis and Futuna"),
								'WS' => tr( "Samoa"),
								'YE' => tr( "Yemen"),
								'YT' => tr( "Mayotte"),
								'ZA' => tr( "South Africa"),
								'ZM' => tr( "Zambia"),
								'ZW' => tr( "Zimbabwe"),
								'CS' => tr( "Serbia and Montenegro"));*/
		return (self::$_countries);
	}
	
	/**
	 * Compute distance between two cities in KM
	 *
	 * @param float	$lat1 Latitude starting point
	 * @param float	$lon1 Longitude starting point
	 * @param float	$lat2 Latitude arriving point
	 * @param float	$lon2 Longitude arriving point
	 *
	 * @return float distance between these points in Km
	 */
	static public function distance($lat1, $lon1, $lat2, $lon2) {
		$lat1 = deg2rad ( $lat1 );
		$lat2 = deg2rad ( $lat2 );
		$lon1 = deg2rad ( $lon1 );
		$lon2 = deg2rad ( $lon2 );

		$R = 6371;
		$dLat = $lat2 - $lat1;
		$dLong = $lon2 - $lon1;
		$var1 = $dLong / 2;
		$var2 = $dLat / 2;
		$a = pow ( sin ( $dLat / 2 ), 2 ) + cos ( $lat1 ) * cos ( $lat2 ) * pow ( sin ( $dLong / 2 ), 2 );
		$c = 2 * atan2 ( sqrt ( $a ), sqrt ( 1 - $a ) );
		$d = $R * $c;
		return $d;
	}
	
	/**
	 * Return informations for a city
	 *
	 * @param string $city    City to search
	 * @param string $country Country to search
	 * @param int    $postal  Zip code to search
	 *
	 * @return Array Array containing informations
	 */
	static public function getInfos($city, $country, $postal = NULL) {
		$servicesOrder = array('Gears_LocationService_Geonames_Xml');
		foreach ($servicesOrder as $servicesOrder) {
			try {
				$result = call_user_func_array(array($servicesOrder, 'getInfos'), array($city, $country, $postal));
				return ($result);
			} catch (Gears_LocationService_Exception $e) {
			}
		}
		throw new Gears_LocationService_Exception('not found');
	}
	
	/**
	 * Return informations for an IP
	 *
	 * @param string $ip IP for geolocation
	 *
	 * @return Array Array containing informations
	 */
	static public function getIpInfos($ip = null) {
		if ($ip == null) {
			$ip = Gears_Utile::getIp();
		}
		$servicesOrder = array('Gears_LocationService_Ip_Ipinfodb_Xml');
		foreach ($servicesOrder as $servicesOrder) {
			try {
				$result = call_user_func_array(array($servicesOrder, 'getIpInfos'), array($ip));
				if (isset($result['CITY']) && isset($result['COUNTRY'])) {
					return ($result);
				}
			} catch (Exception $e) {
			}
		}
		throw new Gears_LocationService_Exception('not found');
	}
}

class Gears_LocationService_Exception extends Zend_Exception {
}
