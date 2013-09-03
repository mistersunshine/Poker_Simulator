<?php
require_once 'class.base.UnitTest.php';
require_once '/class.Card.php';

class CardUnitTest extends BaseUnitTest
{

	public function RunAllTests()
	{
		$output = array();

		echo "Testing __construct...\n";
		$unitTestResult = $this->PassFail($this->testCardConstruct());
		if ($unitTestResult == 'failed')
		{
			$output[] = "test __construct(): $unitTestResult\n";
		}

		echo "Testing toString...\n";
		$unitTestResult = $this->PassFail($this->testToString());
		if ($unitTestResult == 'failed')
		{
			$output[] = "test toString(): $unitTestResult\n";
		}

		$this->UnitTestOutput(__FILE__, $output);

		/* unit tests */
	}

	public function testCardConstruct()
	{
		/* test all valid suit/rank combos */
		$expectedSuits = array('s', 'h', 'd', 'c');
		$expectedRanks = explode(',', '2,3,4,5,6,7,8,9,10,J,Q,K,A');
		foreach ($expectedSuits as $suit)
		{
			foreach ($expectedRanks as $rank)
			{
				try
				{
					$Card = new Card($suit, $rank);
				}
				catch (Exception $e)
				{
					return false;
				}
			}
		}
		
		/* test invalid suit */
		$suit = 'x';
		$rank = '10';
		try
		{
			$Card = new Card($suit, $rank);
			return false;
		}
		catch (Exception $e)
		{
			// do nothing
		}
		
		/* test invalid rank (explicit) */
		$suit = 's';
		$rank = '11';
		try
		{
			$Card = new Card($suit, $rank);
			return false;
		}
		catch (Exception $e)
		{
			// do nothing
		}
		
		// all tests passed
		return true;
	}

	public function testToString()
	{
		/* test single digit */
		$suit = 's';
		$rank = '2';
		$Card = new Card($suit, $rank);
		if ($Card->__toString() !== ($rank . $suit))
		{
			return false;
		}
		if ("$Card" !== ($rank . $suit))
		{
			return false;
		}
		
		/* test 10 */
		$suit = 's';
		$rank = '10';
		$Card = new Card($suit, $rank);
		if ($Card->__toString() !== ($rank . $suit))
		{
			return false;
		}
		if ("$Card" !== ($rank . $suit))
		{
			return false;
		}
		
		/* test face card */
		$suit = 's';
		$rank = 'A';
		$Card = new Card($suit, $rank);
		if ($Card->__toString() !== ($rank . $suit))
		{
			return false;
		}
		if ("$Card" !== ($rank . $suit))
		{
			return false;
		}
		
		// all tests passed
		return true;
		
	}
}
?>