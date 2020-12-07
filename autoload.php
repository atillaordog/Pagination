<?php
/**
 * Autoload file that needs to be laoded to use the ValidationWall
 */

if (!defined('PAGINATION_ROOT')) {
    define('PAGINATION_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);
}

spl_autoload_register('pagination_autoload');

function pagination_autoload($class)
{
	if ( class_exists($class,FALSE) ) {
		//    Already loaded
		return FALSE;
	}
	
	$class = str_replace('Pagination\\', '', $class);
	$class = str_replace('\\', '/', $class);
	
	$is_file = false;
	if ( file_exists(PAGINATION_ROOT.$class.'.php') )
	{
		$is_file = PAGINATION_ROOT.$class.'.php';
	}
	
	if ( $is_file !== false )
	{
		require($is_file);
	}
	
	return false;
}
