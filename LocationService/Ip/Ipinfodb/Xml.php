<?php
/**
 * Blogama IP geolocation in XML format
 *
 * @package    Core
 * @subpackage LocationService/Ip
 * @author     Jeremy MOULIN <jeremy.moulin@doonoyz.com>
 * @copyright  2008-2009 Doonoyz
 * @version    Paper
 */
class Gears_LocationService_Ip_Ipinfodb_Xml implements Gears_LocationService_Ip_Interface {
	/**
	 * Returns informations for an IP
	 *
	 * @param string $ip IP to check
	 *
	 * @return array Array containing informations
	 */
	static public function getIpInfos($ip = null) {
		if ($ip == null) {
			$ip = Gears_Utile::getIp();
		}
		$client = new Zend_Rest_Client('http://ipinfodb.com/ip_query.php');
		$result = $client->output('xml')->ip($ip)->get();
		$return = array();
		$return ['IP'] = $result->Ip();
		$return ['STATUS'] = $result->Status();
		$return ['COUNTRYCODE'] = $result->CountryCode();
		$return ['COUNTRY'] = $result->CountryName();
		$return ['REGIONCODE'] = $result->RegionCode();
		$return ['REGIONNAME'] = $result->RegionName();
		$return ['CITY'] = $result->City();
		$return ['ZIPCODE'] = $result->ZipPostalCode();
		$return ['LATITUDE'] = $result->Latitude();
		$return ['LONGITUDE'] = $result->Longitude();
		return ($return);
	}
}