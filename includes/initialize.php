<?php

// Define the core paths
// Define them as absolute paths to make sure that require_once works as expected
//header('Content-Type: text/html; charset=utf-8');
// DIRECTORY_SEPARATOR is a PHP pre-defined constant
// (\ for Windows, / for Unix)
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
//defined('SITE_ROOT') ? null : define('SITE_ROOT', 'C:'.DS.'XAMPP'.DS.'htdocs'.DS.'kamila');
defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].DS.'kamila');
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'includes');
//defined('UPLOAD_DIR') ? null : define('UPLOAD_DIR','C:'.DS.'XAMPP'.DS.'tmp'); 
defined('UPLOAD_DIR') ? null : define('UPLOAD_DIR',"/uploaded/kamila"); 
set_time_limit(600);
// load config file first
require_once(LIB_PATH.DS.'config.php');

// load basic functions next so that everything after can use them
require_once(LIB_PATH.DS.'functions.php');

// load core objects
require_once(LIB_PATH.DS.'session.php');
require_once(LIB_PATH.DS.'database.php');
require_once(LIB_PATH.DS.'database_object.php');
require_once(LIB_PATH.DS.'pagination.php');

// load database-related classes
require_once(LIB_PATH.DS.'user.php');
require_once(LIB_PATH.DS.'country.php');
require_once(LIB_PATH.DS.'region.php');
require_once(LIB_PATH.DS.'state.php');
require_once(LIB_PATH.DS.'names.php');
require_once(LIB_PATH.DS.'references.php');
require_once(LIB_PATH.DS.'isotopes.php');
require_once(LIB_PATH.DS.'language.php');
require_once(LIB_PATH.DS.'subject.php');

// Setting the Content-Type header with charset

?>