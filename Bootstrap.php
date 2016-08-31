<?php
/**
 * General bootstrap
 *
 * @package   Core
 * @author    Jeremy MOULIN <jeremy.moulin@doonoyz.com>
 * @copyright 2008-2009 Doonoyz
 * @version   Paper
 */

class Gears_Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
	
	protected function _initHeader() {
		error_reporting(E_ALL|E_STRICT);
		date_default_timezone_set ( 'Europe/Paris' );
	}
	
	protected function _initSession() {
		Zend_Session::start();
	}
	
	protected function _initRegistry() {
		$registry = new Zend_Registry(array(), ArrayObject::ARRAY_AS_PROPS);
		Zend_Registry::setInstance($registry);
		return $registry;
	}
	
	protected function _initConfig() {
		$config = new Zend_Config_Ini ( ROOT_DIR . 'application/application.ini', ENVIRONMENT );
		$registry = $this->getResource('registry');
		$registry->set ( 'config', $config );
		return $config;
	}
	
	protected function _initAcl() {
		$config = $this->getResource('config');
		$registry = $this->getResource('registry');
		$registry->acl = new Gears_Acl();
		return $registry->acl;
	}
	
	protected function _initDatabase() {
		$registry = $this->getResource('registry');
		$db = $this->getPluginResource('db')->getDbAdapter();
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$registry->database = $db;
		Zend_Db_Table::setDefaultAdapter($db);
		return $registry->database;
	}
	
	protected function _initCache() {
		$config = $this->getResource('config');
		$cache = Zend_Cache::factory ( $config->cache->front->driver, $config->cache->back->driver, $config->cache->front->options->toArray(), $config->cache->back->options->toArray() );
		$registry = $this->getResource('registry');
		$registry->cache = $cache;
		return $cache;
	}
	
	protected function _initLanguage() {
		$registry = $this->getResource('registry');
		$config = $this->getResource('config');
		$cache = $this->getResource('cache');
		$registry->languages = $config->language->toArray();
		$session = new Zend_Session_Namespace("Gears_Bootstrap_Language");

		if ($config->use_language) {
			
			Zend_Translate::setCache ( $cache );

			$tr = new Zend_Translate ( 'Zend_Translate_Adapter_Gettext', ROOT_DIR . 'application/languages/lang.en.mo', 'en' );
			
			foreach ($registry->languages as $lang => $text) {
				$tr->addTranslation ( ROOT_DIR . 'application/languages/lang.' . $lang . '.mo', $lang );
			}

			try {
				$locale = new Zend_Locale ( $session->locale );
			} catch ( Zend_Locale_Exception $e ) {
				$locale = new Zend_Locale ( 'en' );
			}
			try {
				$tr->setLocale ( $locale->getLanguage () );
			} catch ( Exception $e ) {
				$locale = new Zend_Locale ( 'en' );
				$tr->setLocale ( $locale->getLanguage () );
			}

			$session->locale = $locale->getLanguage ();

			$registry->translate = $tr;

			if (! function_exists ( 'tr' )) {
				function tr($text) {
					$tr = Zend_Registry::getInstance ()->translate;
					return $tr->_ ( $text );
				}
			}
		} else {
			$session->locale = 'en';
			if (! function_exists ( 'tr' )) {
				function tr($text) {
					return $text;
				}
			}
		}
	}
	
	protected function _initMail() {
		$config = $this->getResource('config');
		$tr = new Zend_Mail_Transport_Smtp ( $config->mail->smtp, $config->mail->config->toArray () );
		Zend_Mail::setDefaultTransport ( $tr );
	}
	
	protected function _initLayout() {
		$config = new Zend_Config_Ini ( ROOT_DIR . 'application/application.ini', ENVIRONMENT );
		$layout = Zend_Layout::startMvc($config->views);
		return $layout;
	}
	
	protected function _initFront() {
		$config = $this->getResource('config');
		if ($config->router) {
			// setup controller
			$frontController = Zend_Controller_Front::getInstance ();

			// load routing rules configuration
			$config = new Zend_Config_Ini ( ROOT_DIR . 'application/routes.ini', 'all' );
			$router = $frontController->getRouter ();
			$router->addConfig ( $config, 'routes' );

			$frontController->setRouter ( $router );
		}
	}
}