<?php
require_once 'class.base.UnitTest.php';
require_once '/class.Deck.php';

class DeckUnitTest extends BaseUnitTest
{

	public function RunAllTests()
	{
		// passing in object rather than creating for each test
		$Deck = new Deck();

		$output = array();

		echo "Testing InitSuitColors...\n";
		$unitTestResult = $this->PassFail($this->testInitSuitColors($Deck));
		if ($unitTestResult == 'failed')
		{
			$output[] = "test InitSuitColors(): $unitTestResult\n";
		}

		echo "Testing BuildDeck...\n";
		$unitTestResult = $this->PassFail($this->testBuildDeck($Deck));
		if ($unitTestResult == 'failed')
		{
			$output[] = "test BuildDeck(): $unitTestResult\n";
		}

		echo "Testing ShuffleDeck...\n";
		$unitTestResult = $this->PassFail($this->testShuffleDeck($Deck));
		if ($unitTestResult == 'failed')
		{
			$output[] = "test ShuffleDeck(): $unitTestResult\n";
		}

		$this->UnitTestOutput(__FILE__, $output);
	}

	/* unit tests */

	public function testInitSuitColors(Deck $Deck)
	{
		/* test four color */
		$fourColors = true;
		$Deck->InitSuitColors($fourColors);
		$actualResult = $Deck->getSuitColors();
		$expectedResult = array
		(
			Card::SUIT_SPADES => 'black',
			'h' => 'red',
			'd' => 'blue',
			'c' => 'green'
		);
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test 2 color */
		$fourColors = false;
		$Deck->InitSuitColors($fourColors);
		$actualResult = $Deck->getSuitColors();
		$expectedResult = array
		(
			Card::SUIT_SPADES => 'black',
			'h' => 'red',
			'd' => 'red',
			'c' => 'black'
		);
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		// all tests passed
		return true;
	}

	public function testBuildDeck(Deck $Deck)
	{
		/* baseline: test building of deck */
		$Deck->BuildDeck();
		$actualResult = $Deck->getDeck();
/*
* @todo do we need a function here?
*/
		$expectedResult = $this->GetExpectedDeck();
		
		if ($actualResult != $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}

		// all tests passed
		return true;
	}

	public function testShuffleDeck(Deck $Deck)
	{
		/* baseline: test that supplied deck source is shuffled i.e. not same as input */
		$Deck->BuildDeck();
		$actualResult = $Deck->ShuffleDeck($Deck->getDeck());
/*
* @todo do we need a function here?
*/
		$expectedResult = $this->GetExpectedDeck();
		if ($actualResult == $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* need to test that at least the structure is as expected */
		
		// all tests passed
		return true;
	}
}
?>