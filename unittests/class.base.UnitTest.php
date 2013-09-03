<?php
//error_log(__FILE__ . ' ' . __LINE__);
//error_log('getcwd()=' . print_r(getcwd(), true));

require_once('/common/common.php');

/*
 * @todo move this...
 */
if (!defined('UNITESTS_LOG_ALL'))
{
	// set to true to log all
	define('UNITESTS_LOG_ALL', true);
}

class BaseUnitTest
{
	function PassFail($passfail)
	{
		if ($passfail)
		{
			return 'passed';
		}
		return 'failed';
	}

	function GetExpectedDeck()
	{
		$expectedDeck = array();
		$suits = array('s', 'h', 'd', 'c');
		$ranks = explode(',', 'A,2,3,4,5,6,7,8,9,10,J,Q,K');
		
		foreach ($suits as $suit)
		{
			foreach ($ranks as $rank)
			{
				$expectedDeck[] = new Card($suit, $rank);
			}
		}
		
		return $expectedDeck;
	}


	function UnitTestLog($message, $file, $class, $function, $line)
	{
		if (UNITESTS_LOG_ALL)
		{
			error_log($message . " Logged from $file, line $line");
		}
	}

	function UnitTestOutput($file, $output)
	{
		echo "Testing file $file...\n";
		echo date('Y/m/d H:i:s') . "\n";
		if (count($output) > 0)
		{
			echo implode('', $output);
		}
		else
		{
			echo "All tests passed!\n";
		}
	}

}

?>