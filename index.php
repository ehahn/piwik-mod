<?php
/**
 * Piwik - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html Gpl v3 or later
 * @version $Id: index.php 1676 2009-12-13 06:24:21Z vipsoft $
 * 
 * @package Piwik
 */

if(file_exists('bootstrap.php'))
{
	require_once 'bootstrap.php';
}

error_reporting(E_ALL|E_NOTICE);
if(!defined('PIWIK_DISPLAY_ERRORS') || PIWIK_DISPLAY_ERRORS)
{
	@ini_set('display_errors', 1);
}
@ini_set('magic_quotes_runtime', 0);

define('PIWIK_DOCUMENT_ROOT', dirname(__FILE__)=='/'?'':dirname(__FILE__));
if(!defined('PIWIK_USER_PATH'))
{
	define('PIWIK_USER_PATH', PIWIK_DOCUMENT_ROOT);
}
if(!defined('PIWIK_INCLUDE_PATH'))
{
	define('PIWIK_INCLUDE_PATH', PIWIK_DOCUMENT_ROOT);
}

if(!defined('PIWIK_INCLUDE_SEARCH_PATH'))
{
	define('PIWIK_INCLUDE_SEARCH_PATH', PIWIK_INCLUDE_PATH . '/core'
		. PATH_SEPARATOR . PIWIK_INCLUDE_PATH . '/libs'
		. PATH_SEPARATOR . PIWIK_INCLUDE_PATH . '/plugins');
	@ini_set('include_path', PIWIK_INCLUDE_SEARCH_PATH);
	@set_include_path(PIWIK_INCLUDE_SEARCH_PATH);
}

if(!defined('PIWIK_SESSION_NAME'))
{
	define('PIWIK_SESSION_NAME', 'PIWIK_SESSID');
}
@ini_set('session.name', PIWIK_SESSION_NAME);
if(ini_get('session.save_handler') == 'user')
{
	@ini_set('session.save_handler', 'files');
	@ini_set('session.save_path', '');
}
if(ini_get('session.save_handler') == 'files')
{
	$sessionPath = ini_get('session.save_path');
	if(preg_match('/^[0-9]+;(.*)/', $sessionPath, $matches))
	{
		$sessionPath = $matches[1];
	}
	if(ini_get('safe_mode') || ini_get('open_basedir') || empty($sessionPath) || !@is_writable($sessionPath))
	{
		$sessionPath = PIWIK_USER_PATH . '/tmp/sessions';
		@ini_set('session.save_path', $sessionPath);
		if(!is_dir($sessionPath))
		{
			@mkdir($sessionPath, 0755, true);
			if(!is_dir($sessionPath))
			{
				die("Error: Unable to mkdir $sessionPath");
			}
		}
		else if(!@is_writable($sessionPath))
		{
			die("Error: $sessionPath is not writable");
		}
	}
}

require_once PIWIK_INCLUDE_PATH . '/core/testMinimumPhpVersion.php';

// NOTE: the code above this comment must be PHP4 compatible

session_cache_limiter('nocache');
@date_default_timezone_set(date_default_timezone_get());
require_once PIWIK_INCLUDE_PATH .'/core/Loader.php';

if(!defined('PIWIK_ENABLE_SESSION_START') || PIWIK_ENABLE_SESSION_START)
{
	Zend_Session::start();
}

if(!defined('PIWIK_ENABLE_ERROR_HANDLER') || PIWIK_ENABLE_ERROR_HANDLER)
{
	require_once PIWIK_INCLUDE_PATH .'/core/ErrorHandler.php';
	require_once PIWIK_INCLUDE_PATH .'/core/ExceptionHandler.php';
	set_error_handler('Piwik_ErrorHandler');
	set_exception_handler('Piwik_ExceptionHandler');
}

if(!defined('PIWIK_ENABLE_DISPATCH') || PIWIK_ENABLE_DISPATCH)
{
	$controller = Piwik_FrontController::getInstance();
	$controller->init();
	$controller->dispatch();
}
