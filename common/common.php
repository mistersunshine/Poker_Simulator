<?php
date_default_timezone_set('America/Chicago');
/**
 * @todo set log location to /log
 */

if (!defined('LOG_ALL'))
{
	// set to true to log all
	define('LOG_ALL', false);
}

if (!defined('SIMULATION_LOG_VERBOSE'))
{
	// set to true to log all
//	define('SIMULATION_LOG_VERBOSE', false);
define('SIMULATION_LOG_VERBOSE', true);
}

if (!defined('CLEAR_SIMULATION_LOG_ALWAYS'))
{
	// set to true to log all
//	define('SIMULATION_LOG_VERBOSE', false);
define('CLEAR_SIMULATION_LOG_ALWAYS', true);
}

if (!defined('LOG_SPECIFIC_FUNCTION'))
{
	define('LOG_SPECIFIC_FUNCTION', false);
//	define('LOG_SPECIFIC_FUNCTION', 'GetBestPairedHand');
}

function DebugLog($message, $file, $class, $function, $line, $showNow = false)
{
	$logToFile = false;
	
	if ($showNow === true)
	{
		// SHOW IT. SHOW IT NOW.
		$logToFile = true;
	}
	elseif (LOG_ALL && ((LOG_SPECIFIC_FUNCTION === false) || (LOG_SPECIFIC_FUNCTION === $function)))
	{
		// log_all on and log passed function filter
		$logToFile = true;
	}
	
	if ($logToFile)
	{
		// this log is worthy of being seen
		error_log($message . " Logged from $file, line $line");
	}
}
?>