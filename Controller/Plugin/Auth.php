<?php
/**
 * Plugin d'authentification
 * 
 */

class Gears_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract	{
	/**
	 * @var Zend_Auth instance 
	 */
	private $_auth;
	
	/**
	 * @var Zend_Acl instance 
	 */
	private $_acl;
		
	/**
	 * Constructeur
	 */
	public function __construct()	{
		$this->_acl  = Zend_Registry::getInstance()->acl;
		$this->_auth = Zend_Auth::getInstance();
	}
	
	/**
	 * V�rifie les autorisations
	 * Utilise _request et _response h�rit�s et inject�s par le FC
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)	{
		// is the user authenticated
		if ($this->_auth->hasIdentity()) {
		  // yes ! we get his role
		  $user = $this->_auth->getStorage()->read() ;
		  $role = $user['role'] ;
		} else {
		  // no = guest user
		  $role = 'guest';
		}
		
		$module 	= $request->getModuleName() ;
		$controller = $request->getControllerName() ;
		$action     = $request->getActionName() ;
		
		$front = Zend_Controller_Front::getInstance() ;
		$default = $front->getDefaultModule() ;
		
		// compose le nom de la ressource
		if ($module == $default)	{
			$resource = $controller;
		} else {
			$resource = $module.'_'.$controller ;
		}
    
		// est-ce que la ressource existe ?
		if (!$this->_acl->has($resource)) {
			$resource = null;
		}
		
		// contr�le si l'utilisateur est autoris�
		if (!$this->_acl->isAllowed($role, $resource, $action)) {
			// l'utilisateur n'est pas autoris� � acc�der � cette ressource
			// on va le rediriger
			if (!$this->_auth->hasIdentity()) {
				// il n'est pas identifi� -> module de login
				$module = Zend_Registry::getInstance()->config->auth->login->module;
				$controller = Zend_Registry::getInstance()->config->auth->login->controller;
				$action = Zend_Registry::getInstance()->config->auth->login->action;
			} else {
				// il est identifi� -> error de privil�ges
				$module = Zend_Registry::getInstance()->config->auth->rights->module;
				$controller = Zend_Registry::getInstance()->config->auth->rights->controller;
				$action = Zend_Registry::getInstance()->config->auth->rights->action;
			}
		}

		$request->setModuleName($module) ;
		$request->setControllerName($controller) ;
		$request->setActionName($action) ;
	}
}
