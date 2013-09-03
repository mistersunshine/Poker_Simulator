<?php
require_once 'class.base.UnitTest.php';
require_once '/class.PokerSimulator.php';

class PokerSimulatorUnitTest extends BaseUnitTest
{

	public function RunAllTests()
	{
		// passing in object rather than creating for each test
		$PokerSimulator = new PokerSimulator();

		$output = array();

		echo "Testing GetDealtCards...\n";
		$unitTestResult = $this->PassFail($this->testGetDealtCards($PokerSimulator));
		if ($unitTestResult == 'failed')
		{
			$output[] = "test GetDealtCards(): $unitTestResult\n";
		}

		echo "Testing GetAllStraights...\n";
		$unitTestResult = $this->PassFail($this->testGetAllStraights($PokerSimulator));
		if ($unitTestResult == 'failed')
		{
			$output[] = "test GetAllStraights(): $unitTestResult\n";
		}

		echo "Testing GetAllFlushes...\n";
		$unitTestResult = $this->PassFail($this->testGetAllFlushes($PokerSimulator));
		if ($unitTestResult == 'failed')
		{
			$output[] = "test GetAllFlushes(): $unitTestResult\n";
		}

		echo "Testing SortCardCollectionByFrequency...\n";
		$unitTestResult = $this->PassFail($this->testSortCardCollectionByFrequency($PokerSimulator));
		if ($unitTestResult == 'failed')
		{
			$output[] = "test SortCardCollectionByFrequency(): $unitTestResult\n";
		}

		echo "Testing CollectRankValuesByCount...\n";
		$unitTestResult = $this->PassFail($this->testCollectRankValuesByCount($PokerSimulator));
		if ($unitTestResult == 'failed')
		{
			$output[] = "test CollectRankValuesByCount(): $unitTestResult\n";
		}

		echo "Testing ConvertCardValueToStoredValue...\n";
		$unitTestResult = $this->PassFail($this->testConvertCardValueToStoredValue($PokerSimulator));
		if ($unitTestResult == 'failed')
		{
			$output[] = "test ConvertCardValueToStoredValue(): $unitTestResult\n";
		}

		echo "Testing ConvertValueToRank...\n";
		$unitTestResult = $this->PassFail($this->testConvertValueToRank($PokerSimulator));
		if ($unitTestResult == 'failed')
		{
			$output[] = "test ConvertValueToRank(): $unitTestResult\n";
		}

		echo "Testing GetBestPairedHand...\n";
		$unitTestResult = $this->PassFail($this->testGetBestPairedHand($PokerSimulator));
		if ($unitTestResult == 'failed')
		{
			$output[] = "test GetBestPairedHand(): $unitTestResult\n";
		}

		echo "Testing GetBestHandForPlayer...\n";
		$unitTestResult = $this->PassFail($this->testGetBestHandForPlayer($PokerSimulator));
		if ($unitTestResult == 'failed')
		{
			$output[] = "test GetBestHandForPlayer(): $unitTestResult\n";
		}

		echo "Testing GetHandResults...\n";
		$unitTestResult = $this->PassFail($this->testGetHandResults($PokerSimulator));
		if ($unitTestResult == 'failed')
		{
			$output[] = "test GetHandResults(): $unitTestResult\n";
		}

		echo "Testing GetWinnerInfo...\n";
		$unitTestResult = $this->PassFail($this->testGetWinnerInfo($PokerSimulator));
		if ($unitTestResult == 'failed')
		{
			$output[] = "test GetWinnerInfo(): $unitTestResult\n";
		}

		echo "Testing SortCardByRank...\n";
		$unitTestResult = $this->PassFail($this->testSortCardByRank($PokerSimulator));
		if ($unitTestResult == 'failed')
		{
			$output[] = "test SortCardByRank(): $unitTestResult\n";
		}

		echo "Testing CombineKickers...\n";
		$unitTestResult = $this->PassFail($this->testCombineKickers($PokerSimulator));
		if ($unitTestResult == 'failed')
		{
			$output[] = "test CombineKickers(): $unitTestResult\n";
		}

		echo "Testing GetAllRanks...\n";
		$unitTestResult = $this->PassFail($this->testGetAllRanks($PokerSimulator));
		if ($unitTestResult == 'failed')
		{
			$output[] = "test GetAllRanks(): $unitTestResult\n";
		}

		echo "Testing ConvertRankToValue...\n";
		$unitTestResult = $this->PassFail($this->testConvertRankToValue($PokerSimulator));
		if ($unitTestResult == 'failed')
		{
			$output[] = "test ConvertRankToValue(): $unitTestResult\n";
		}

		// need to make sure given cards are ok, e.g. suited pairs
		//$unitTestResult = $this->PassFail(testVerifyCards($PokerSimulator));
		//if ($unitTestResult == 'failed')
		//{
		//	$output[] = "test VerifyCards(): $unitTestResult\n";
		//}

		$this->UnitTestOutput(__FILE__, $output);
	}

	/* unit tests */

	/**
	 * @todo use Card objects
	 */
	public function testGetDealtCards(PokerSimulator $PokerSimulator)
	{
		/* baseline: test getting hand results from shuffled deck */
		$PokerSimulator->Initialize();
		$numPlayers = 10;
		
		$shuffledDeck = $shuffledDeck = array (0 => '4s', 1 => '2s', 2 => '6s', 3 => '9h', 4 => '7d', 5 => '4h', 6 => 'Ks', 7 => '6h', 8 => 'Js', 9 => '3c', 10 => '6c', 11 => '2d', 12 => '8h', 13 => 'Jd', 14 => 'Jh', 15 => '10s', 16 => '5c', 17 => '6d', 18 => '4d', 19 => '5d', 20 => '4c', 21 => '8s', 22 => 'Ah', 23 => '10d', 24 => '10c', 25 => '7s', 26 => '8c', 27 => '7c', 28 => 'Ad', 29 => '3s', 30 => '3d', 31 => '7h', 32 => 'Kh', 33 => 'Kd', 34 => '2h', 35 => 'Qc', 36 => '10h', 37 => 'Qh', 38 => '9s', 39 => 'Qs', 40 => 'Ac', 41 => 'Kc', 42 => '9d', 43 => '5s', 44 => 'Qd', 45 => 'As', 46 => '3h', 47 => '9c', 48 => '2c', 49 => '5h', 50 => '8d', 51 => 'Jc');
		$actualResult = $PokerSimulator->GetDealtCards($shuffledDeck, $numPlayers);
		$expectedResult = array
		(
			'playerHands' => array
			(
				0 => array('4s', '6c'),
				1 => array('2s', '2d'),
				2 => array('6s', '8h'),
				3 => array('9h', 'Jd'),
				4 => array('7d', 'Jh'),
				5 => array('4h', '10s'),
				6 => array('Ks', '5c'),
				7 => array('6h', '6d'),
				8 => array('Js', '4d'),
				9 => array('3c', '5d'),
			),
			'board' => array('8s', 'Ah', '10d', '7s', '7c')
		);
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		// all tests passed
		return true;
	}

	public function testGetAllStraights(PokerSimulator $PokerSimulator)
	{
		/* baseline test */
		$playerHand = array(new Card(Card::SUIT_SPADES, Card::RANK_JACK), new Card(Card::SUIT_CLUBS, '9'));
		
		$board = array
		(
			new Card(Card::SUIT_SPADES, '8')
			, new Card(Card::SUIT_HEARTS, Card::RANK_ACE)
			, new Card(Card::SUIT_DIAMONDS, '10')
			, new Card(Card::SUIT_SPADES, '7')
			, new Card(Card::SUIT_CLUBS, '7')
		);
		
		$actualResult = $PokerSimulator->GetAllStraights($playerHand, $board);
		$expectedResult = array('ba987');
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test duplicated cards in middle of straight */
		$playerHand = array(new Card(Card::SUIT_SPADES, Card::RANK_JACK), new Card(Card::SUIT_CLUBS, '9'));
		
		$board = array
		(
			new Card(Card::SUIT_SPADES, '8')
			, new Card(Card::SUIT_HEARTS, '8')
			, new Card(Card::SUIT_DIAMONDS, '7')
			, new Card(Card::SUIT_SPADES, Card::RANK_ACE)
			, new Card(Card::SUIT_CLUBS, '10')
		);
		
		$actualResult = $PokerSimulator->GetAllStraights($playerHand, $board);
		$expectedResult = array('ba987');
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test multiple straights */
		$playerHand = array(new Card(Card::SUIT_SPADES, Card::RANK_JACK), new Card(Card::SUIT_CLUBS, '9'));
		
		$board = array
		(
			new Card(Card::SUIT_SPADES, '8')
			, new Card(Card::SUIT_HEARTS, '6')
			, new Card(Card::SUIT_DIAMONDS, '7')
			, new Card(Card::SUIT_SPADES, Card::RANK_ACE)
			, new Card(Card::SUIT_CLUBS, '10')
		);
		
		$actualResult = $PokerSimulator->GetAllStraights($playerHand, $board);
		$expectedResult = array('ba987', 'a9876');
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test no straight */
		$playerHand = array(new Card(Card::SUIT_SPADES, Card::RANK_JACK), new Card(Card::SUIT_CLUBS, '9'));
		
		$board = array
		(
			new Card(Card::SUIT_SPADES, Card::RANK_ACE)
			, new Card(Card::SUIT_HEARTS, Card::RANK_ACE)
			, new Card(Card::SUIT_DIAMONDS, Card::RANK_KING)
			, new Card(Card::SUIT_SPADES, Card::RANK_ACE)
			, new Card(Card::SUIT_CLUBS, '10')
		);
		
		$actualResult = $PokerSimulator->GetAllStraights($playerHand, $board);
		$expectedResult = array();
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test one card straight, immediate straight followed by no straights */
		$playerHand = array(new Card(Card::SUIT_SPADES, Card::RANK_JACK), new Card(Card::SUIT_CLUBS, '8'));
		
		$board = array
		(
			new Card(Card::SUIT_SPADES, Card::RANK_ACE)
			, new Card(Card::SUIT_HEARTS, Card::RANK_QUEEN)
			, new Card(Card::SUIT_DIAMONDS, Card::RANK_KING)
			, new Card(Card::SUIT_SPADES, '7')
			, new Card(Card::SUIT_CLUBS, '10')
		);
		
		$actualResult = $PokerSimulator->GetAllStraights($playerHand, $board);
		$expectedResult = array('edcba');
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		// all tests passed
		return true;
		
	}

	public function testGetAllFlushes(PokerSimulator $PokerSimulator)
	{
		/* baseline test */
		$playerHand = array(new Card(Card::SUIT_SPADES, Card::RANK_JACK), new Card(Card::SUIT_SPADES, '8'));
		
		$board = array
		(
			new Card(Card::SUIT_SPADES, Card::RANK_ACE)
			, new Card(Card::SUIT_HEARTS, Card::RANK_QUEEN)
			, new Card(Card::SUIT_SPADES, Card::RANK_KING)
			, new Card(Card::SUIT_SPADES, '7')
			, new Card(Card::SUIT_CLUBS, '10')
		);
		
		$actualResult = $PokerSimulator->GetAllFlushes($playerHand, $board);
		$expectedResult = array('edb87');
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test board flush (same as normal flush really) */
		$playerHand = array(new Card(Card::SUIT_HEARTS, Card::RANK_JACK), new Card(Card::SUIT_CLUBS, '8'));
		
		$board = array
		(
			new Card(Card::SUIT_SPADES, Card::RANK_ACE)
			, new Card(Card::SUIT_SPADES, Card::RANK_QUEEN)
			, new Card(Card::SUIT_SPADES, Card::RANK_KING)
			, new Card(Card::SUIT_SPADES, '7')
			, new Card(Card::SUIT_SPADES, '10')
		);
		
		$actualResult = $PokerSimulator->GetAllFlushes($playerHand, $board);
		$expectedResult = array('edca7');
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* multiple flush test */
		$playerHand = array(new Card(Card::SUIT_SPADES, Card::RANK_JACK), new Card(Card::SUIT_SPADES, '8'));
		
		$board = array
		(
			new Card(Card::SUIT_SPADES, Card::RANK_ACE)
			, new Card(Card::SUIT_HEARTS, Card::RANK_QUEEN)
			, new Card(Card::SUIT_SPADES, '10')
			, new Card(Card::SUIT_SPADES, '7')
			, new Card(Card::SUIT_SPADES, '9')
		);
		
		$actualResult = $PokerSimulator->GetAllFlushes($playerHand, $board);
		$expectedResult = array('eba98', 'ba987');
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* no flush test */
		$playerHand = array(new Card(Card::SUIT_SPADES, Card::RANK_JACK), new Card(Card::SUIT_SPADES, '8'));
		
		$board = array
		(
			new Card(Card::SUIT_DIAMONDS, Card::RANK_ACE)
			, new Card(Card::SUIT_HEARTS, Card::RANK_QUEEN)
			, new Card(Card::SUIT_CLUBS, '10')
			, new Card(Card::SUIT_SPADES, '7')
			, new Card(Card::SUIT_SPADES, '9')
		);
		
		$actualResult = $PokerSimulator->GetAllFlushes($playerHand, $board);
		$expectedResult = array();
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		// all tests passed
		return true;
	}

	public function testSortCardCollectionByFrequency(PokerSimulator $PokerSimulator)
	{
		/* test one pair (not top rank )*/
		$cards = array
		(
			new Card(Card::SUIT_SPADES, Card::RANK_JACK)
			, new Card(Card::SUIT_SPADES, '8')
			, new Card(Card::SUIT_CLUBS, Card::RANK_JACK)
			, new Card(Card::SUIT_HEARTS, Card::RANK_QUEEN)
			, new Card(Card::SUIT_DIAMONDS, Card::RANK_KING)
			, new Card(Card::SUIT_SPADES, '7')
			, new Card(Card::SUIT_CLUBS, '10')
		);
		
		$actualResult = $PokerSimulator->SortCardCollectionByFrequency($cards);
		$expectedResult = array(2 => array(Card::RANK_JACK), 1 => array(Card::RANK_KING, Card::RANK_QUEEN, '10', '8', '7'));
			
		if ($actualResult != $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test one pair (top rank) */
		$cards = array
		(
			new Card(Card::SUIT_SPADES, Card::RANK_ACE)
			, new Card(Card::SUIT_SPADES, '8')
			, new Card(Card::SUIT_CLUBS, Card::RANK_ACE)
			, new Card(Card::SUIT_HEARTS, Card::RANK_QUEEN)
			, new Card(Card::SUIT_DIAMONDS, Card::RANK_KING)
			, new Card(Card::SUIT_SPADES, '7')
			, new Card(Card::SUIT_CLUBS, '10')
		);
		
		$actualResult = $PokerSimulator->SortCardCollectionByFrequency($cards);
		$expectedResult = array(2 => array(Card::RANK_ACE), 1 => array(Card::RANK_KING, Card::RANK_QUEEN, '10', '8', '7'));
			
		if ($actualResult != $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test two pair (2 + 2) */
		$cards = array
		(
			new Card(Card::SUIT_SPADES, '8')
			, new Card(Card::SUIT_CLUBS, '8')
			, new Card(Card::SUIT_HEARTS, Card::RANK_ACE)
			, new Card(Card::SUIT_DIAMONDS, Card::RANK_ACE)
			, new Card(Card::SUIT_SPADES, Card::RANK_KING)
			, new Card(Card::SUIT_CLUBS, '7')
			, new Card(Card::SUIT_HEARTS, '10')
		);
		
		$actualResult = $PokerSimulator->SortCardCollectionByFrequency($cards);
		$expectedResult = array(2 => array(Card::RANK_ACE, '8'), 1 => array(Card::RANK_KING, '10', '7'));
			
		if ($actualResult != $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test two pair (2 + 2 + 2) */
		$cards = array
		(
			new Card(Card::SUIT_SPADES, Card::RANK_ACE)
			, new Card(Card::SUIT_CLUBS, '8')
			, new Card(Card::SUIT_HEARTS, Card::RANK_ACE)
			, new Card(Card::SUIT_DIAMONDS, '8')
			, new Card(Card::SUIT_SPADES, '7')
			, new Card(Card::SUIT_CLUBS, '7')
			, new Card(Card::SUIT_HEARTS, '10')
		);
		
		$actualResult = $PokerSimulator->SortCardCollectionByFrequency($cards);
		$expectedResult = array(2 => array(Card::RANK_ACE, '8', '7'), 1 => array('10'));
			
		if ($actualResult != $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test trips (not top) */
		$cards = array
		(
			new Card(Card::SUIT_SPADES, Card::RANK_ACE)
			, new Card(Card::SUIT_CLUBS, '8')
			, new Card(Card::SUIT_HEARTS, '8')
			, new Card(Card::SUIT_DIAMONDS, '8')
			, new Card(Card::SUIT_SPADES, '7')
			, new Card(Card::SUIT_CLUBS, Card::RANK_KING)
			, new Card(Card::SUIT_HEARTS, '10')
		);
		
		$actualResult = $PokerSimulator->SortCardCollectionByFrequency($cards);
		$expectedResult = array(3 => array('8'), 1 => array(Card::RANK_ACE, Card::RANK_KING, '10', '7'));
			
		if ($actualResult != $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test trips (top) */
		$cards = array
		(
			new Card(Card::SUIT_SPADES, '8')
			, new Card(Card::SUIT_CLUBS, Card::RANK_ACE)
			, new Card(Card::SUIT_HEARTS, Card::RANK_ACE)
			, new Card(Card::SUIT_DIAMONDS, Card::RANK_ACE)
			, new Card(Card::SUIT_SPADES, '7')
			, new Card(Card::SUIT_CLUBS, Card::RANK_KING)
			, new Card(Card::SUIT_HEARTS, '10')
		);
		
		$actualResult = $PokerSimulator->SortCardCollectionByFrequency($cards);
		$expectedResult = array(3 => array(Card::RANK_ACE), 1 => array(Card::RANK_KING, '10', '8', '7'));
			
		if ($actualResult != $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test full house (3 + 2) */
		$cards = array
		(
			new Card(Card::SUIT_SPADES, Card::RANK_ACE)
			, new Card(Card::SUIT_CLUBS, Card::RANK_ACE)
			, new Card(Card::SUIT_HEARTS, Card::RANK_ACE)
			, new Card(Card::SUIT_DIAMONDS, '8')
			, new Card(Card::SUIT_SPADES, '8')
			, new Card(Card::SUIT_CLUBS, Card::RANK_KING)
			, new Card(Card::SUIT_HEARTS, '10')
		);
		
		$actualResult = $PokerSimulator->SortCardCollectionByFrequency($cards);
		$expectedResult = array(3 => array(Card::RANK_ACE), 2 => array('8'), 1 => array(Card::RANK_KING, '10'));
			
		if ($actualResult != $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test full house (3 + 2 + 2) */
		$cards = array
		(
			new Card(Card::SUIT_SPADES, Card::RANK_KING)
			, new Card(Card::SUIT_CLUBS, Card::RANK_KING)
			, new Card(Card::SUIT_HEARTS, '8')
			, new Card(Card::SUIT_DIAMONDS, '8')
			, new Card(Card::SUIT_SPADES, Card::RANK_ACE)
			, new Card(Card::SUIT_CLUBS, Card::RANK_ACE)
			, new Card(Card::SUIT_HEARTS, Card::RANK_ACE)
		);
		
		$actualResult = $PokerSimulator->SortCardCollectionByFrequency($cards);
		$expectedResult = array(3 => array(Card::RANK_ACE), 2 => array(Card::RANK_KING, '8'));
			
		if ($actualResult != $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test full house (3 + 3) */
		$cards = array
		(
			new Card(Card::SUIT_SPADES, Card::RANK_KING)
			, new Card(Card::SUIT_CLUBS, Card::RANK_KING)
			, new Card(Card::SUIT_HEARTS, Card::RANK_KING)
			, new Card(Card::SUIT_DIAMONDS, '8')
			, new Card(Card::SUIT_SPADES, Card::RANK_ACE)
			, new Card(Card::SUIT_CLUBS, Card::RANK_ACE)
			, new Card(Card::SUIT_HEARTS, Card::RANK_ACE)
		);
		
		$actualResult = $PokerSimulator->SortCardCollectionByFrequency($cards);
		$expectedResult = array(3 => array(Card::RANK_ACE, Card::RANK_KING), 1 => array('8'));
			
		if ($actualResult != $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test quads (4 + 1 + 1 + 1) */
		$cards = array
		(
			new Card(Card::SUIT_SPADES, Card::RANK_ACE)
			, new Card(Card::SUIT_CLUBS, Card::RANK_ACE)
			, new Card(Card::SUIT_HEARTS, Card::RANK_ACE)
			, new Card(Card::SUIT_DIAMONDS, Card::RANK_ACE)
			, new Card(Card::SUIT_SPADES, Card::RANK_KING)
			, new Card(Card::SUIT_CLUBS, '10')
			, new Card(Card::SUIT_HEARTS, '8')
		);
		
		$actualResult = $PokerSimulator->SortCardCollectionByFrequency($cards);
		$expectedResult = array(4 => array(Card::RANK_ACE), 1 => array(Card::RANK_KING, '10', '8'));
			
		if ($actualResult != $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test quads (4 + 2 + 1) */
		$cards = array
		(
			new Card(Card::SUIT_SPADES, Card::RANK_ACE)
			, new Card(Card::SUIT_CLUBS, Card::RANK_ACE)
			, new Card(Card::SUIT_HEARTS, Card::RANK_ACE)
			, new Card(Card::SUIT_DIAMONDS, Card::RANK_ACE)
			, new Card(Card::SUIT_SPADES, Card::RANK_KING)
			, new Card(Card::SUIT_CLUBS, Card::RANK_KING)
			, new Card(Card::SUIT_HEARTS, '8')
		);
		
		$actualResult = $PokerSimulator->SortCardCollectionByFrequency($cards);
		$expectedResult = array(4 => array(Card::RANK_ACE), 2 => array(Card::RANK_KING), 1 => array('8'));
			
		if ($actualResult != $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		// all tests passed
		return true;
		
	}

	public function testGetBestPairedHand(PokerSimulator $PokerSimulator)
	{
		/* test one pair (not top) */
		$playerHand = array(new Card(Card::SUIT_SPADES, Card::RANK_JACK), new Card(Card::SUIT_SPADES, '8'));
		
		$board = array
		(
			new Card(Card::SUIT_SPADES, Card::RANK_JACK)
			, new Card(Card::SUIT_HEARTS, Card::RANK_QUEEN)
			, new Card(Card::SUIT_DIAMONDS, Card::RANK_KING)
			, new Card(Card::SUIT_SPADES, '7')
			, new Card(Card::SUIT_CLUBS, '10')
		);
		
		$actualResult = $PokerSimulator->GetBestPairedHand($playerHand, $board);
		$expectedResult = '1bbdca';
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test one pair (top) */
		
		/* test two pair */
		$playerHand = array(new Card(Card::SUIT_SPADES, Card::RANK_JACK), new Card(Card::SUIT_SPADES, '8'));
		
		$board = array
		(
			new Card(Card::SUIT_SPADES, Card::RANK_JACK)
			, new Card(Card::SUIT_HEARTS, Card::RANK_QUEEN)
			, new Card(Card::SUIT_DIAMONDS, '8')
			, new Card(Card::SUIT_SPADES, '7')
			, new Card(Card::SUIT_CLUBS, '10')
		);
		
		$actualResult = $PokerSimulator->GetBestPairedHand($playerHand, $board);
		$expectedResult = '2bb88c';
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test two pair (2 + 2 + 2) */
		$playerHand = array(new Card(Card::SUIT_SPADES, Card::RANK_JACK), new Card(Card::SUIT_SPADES, '8'));
		
		$board = array
		(
			new Card(Card::SUIT_SPADES, Card::RANK_JACK)
			, new Card(Card::SUIT_HEARTS, Card::RANK_QUEEN)
			, new Card(Card::SUIT_DIAMONDS, '8')
			, new Card(Card::SUIT_SPADES, Card::RANK_QUEEN)
			, new Card(Card::SUIT_CLUBS, '10')
		);
		
		$actualResult = $PokerSimulator->GetBestPairedHand($playerHand, $board);
		$expectedResult = '2ccbba';
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test trips */
		$playerHand = array(new Card(Card::SUIT_SPADES, Card::RANK_JACK), new Card(Card::SUIT_CLUBS, Card::RANK_JACK));
		
		$board = array
		(
			new Card(Card::SUIT_HEARTS, Card::RANK_JACK)
			, new Card(Card::SUIT_HEARTS, Card::RANK_QUEEN)
			, new Card(Card::SUIT_DIAMONDS, Card::RANK_KING)
			, new Card(Card::SUIT_SPADES, '7')
			, new Card(Card::SUIT_CLUBS, '10')
		);
		
		$actualResult = $PokerSimulator->GetBestPairedHand($playerHand, $board);
		$expectedResult = '3bbbdc';
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test full house (3 + 2 + 1...) */
		$playerHand = array(new Card(Card::SUIT_SPADES, Card::RANK_JACK), new Card(Card::SUIT_SPADES, '8'));
		
		$board = array
		(
			new Card(Card::SUIT_DIAMONDS, Card::RANK_JACK)
			, new Card(Card::SUIT_HEARTS, Card::RANK_JACK)
			, new Card(Card::SUIT_DIAMONDS, '8')
			, new Card(Card::SUIT_SPADES, '7')
			, new Card(Card::SUIT_CLUBS, '10')
		);
		
		$actualResult = $PokerSimulator->GetBestPairedHand($playerHand, $board);
		$expectedResult = '6bbb88';
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test full house (3 + 2 + 2) */
		$playerHand = array(new Card(Card::SUIT_SPADES, Card::RANK_JACK), new Card(Card::SUIT_SPADES, '8'));
		
		$board = array
		(
			new Card(Card::SUIT_DIAMONDS, Card::RANK_JACK)
			, new Card(Card::SUIT_HEARTS, Card::RANK_JACK)
			, new Card(Card::SUIT_DIAMONDS, '8')
			, new Card(Card::SUIT_SPADES, '9')
			, new Card(Card::SUIT_CLUBS, '9')
		);
		
		$actualResult = $PokerSimulator->GetBestPairedHand($playerHand, $board);
		$expectedResult = '6bbb99';
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test full house (3 + 3 + 1) */
		$playerHand = array(new Card(Card::SUIT_SPADES, Card::RANK_JACK), new Card(Card::SUIT_SPADES, '8'));
		
		$board = array
		(
			new Card(Card::SUIT_DIAMONDS, Card::RANK_JACK)
			, new Card(Card::SUIT_HEARTS, Card::RANK_JACK)
			, new Card(Card::SUIT_DIAMONDS, '8')
			, new Card(Card::SUIT_SPADES, '8')
			, new Card(Card::SUIT_CLUBS, '9')
		);
		
		$actualResult = $PokerSimulator->GetBestPairedHand($playerHand, $board);
		$expectedResult = '6bbb88';
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test quads */
		$playerHand = array(new Card(Card::SUIT_SPADES, Card::RANK_JACK), new Card(Card::SUIT_SPADES, '8'));
		
		$board = array
		(
			new Card(Card::SUIT_DIAMONDS, Card::RANK_JACK)
			, new Card(Card::SUIT_HEARTS, Card::RANK_JACK)
			, new Card(Card::SUIT_CLUBS, Card::RANK_JACK)
			, new Card(Card::SUIT_SPADES, '7')
			, new Card(Card::SUIT_CLUBS, '10')
		);
		
		$actualResult = $PokerSimulator->GetBestPairedHand($playerHand, $board);
		$expectedResult = '7bbbba';
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test quads (4 + 2 + 1) */
		$playerHand = array(new Card(Card::SUIT_SPADES, Card::RANK_JACK), new Card(Card::SUIT_SPADES, '8'));
		
		$board = array
		(
			new Card(Card::SUIT_DIAMONDS, Card::RANK_JACK)
			, new Card(Card::SUIT_HEARTS, Card::RANK_JACK)
			, new Card(Card::SUIT_CLUBS, Card::RANK_JACK)
			, new Card(Card::SUIT_DIAMONDS, '8')
			, new Card(Card::SUIT_CLUBS, '9')
		);
		
		$actualResult = $PokerSimulator->GetBestPairedHand($playerHand, $board);
		$expectedResult = '7bbbb9';
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test quads (4 + 3) */
		$playerHand = array(new Card(Card::SUIT_SPADES, Card::RANK_JACK), new Card(Card::SUIT_SPADES, '8'));
		
		$board = array
		(
			new Card(Card::SUIT_DIAMONDS, Card::RANK_JACK)
			, new Card(Card::SUIT_HEARTS, Card::RANK_JACK)
			, new Card(Card::SUIT_CLUBS, Card::RANK_JACK)
			, new Card(Card::SUIT_DIAMONDS, '8')
			, new Card(Card::SUIT_CLUBS, '8')
		);
		
		$actualResult = $PokerSimulator->GetBestPairedHand($playerHand, $board);
		$expectedResult = '7bbbb8';
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		// all tests passed
		return true;
	}
		
	function testGetHandResults(PokerSimulator $PokerSimulator)
	{
		/* baseline test using live data */
		$playerHands = array
		(
			array(new Card(Card::SUIT_SPADES, '7'), new Card(Card::SUIT_HEARTS, '7'))
		);
		
		$board = array
		(
			new Card(Card::SUIT_CLUBS, '10')
			, new Card(Card::SUIT_HEARTS, '6')
			, new Card(Card::SUIT_CLUBS, Card::RANK_KING)
			, new Card(Card::SUIT_HEARTS, '5')
			, new Card(Card::SUIT_SPADES, Card::RANK_QUEEN)
		);
		
		$actualResult = $PokerSimulator->GetHandResults($playerHands, $board);
		$expectedResult = array('177dca');

		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		

		// all tests passed
		return true;
	}
		
	public function testGetBestHandForPlayer(PokerSimulator $PokerSimulator)
	{
		/* test one pair, flop */
		$playerHand = array(new Card(Card::SUIT_SPADES, '4'), new Card(Card::SUIT_CLUBS, '6'));
		$board = array
		(
			new Card(Card::SUIT_SPADES, '4')
			, new Card(Card::SUIT_HEARTS, Card::RANK_ACE)
			, new Card(Card::SUIT_DIAMONDS, '10')
		);
		
		$actualResult = $PokerSimulator->GetBestHandForPlayer($playerHand, $board);
		$expectedResult = '144ea6';
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test one pair, turn */
		$playerHand = array(new Card(Card::SUIT_SPADES, '4'), new Card(Card::SUIT_CLUBS, '6'));
		$board = array
		(
			new Card(Card::SUIT_SPADES, '8')
			, new Card(Card::SUIT_HEARTS, Card::RANK_ACE)
			, new Card(Card::SUIT_DIAMONDS, '10')
			, new Card(Card::SUIT_CLUBS, '4')
		);
		
		$actualResult = $PokerSimulator->GetBestHandForPlayer($playerHand, $board);
		$expectedResult = '144ea8';
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test one pair on board, river */
		$playerHand = array(new Card(Card::SUIT_SPADES, '4'), new Card(Card::SUIT_CLUBS, '6'));
		$board = array
		(
			new Card(Card::SUIT_SPADES, '8')
			, new Card(Card::SUIT_HEARTS, Card::RANK_ACE)
			, new Card(Card::SUIT_DIAMONDS, '10')
			, new Card(Card::SUIT_SPADES, '7')
			, new Card(Card::SUIT_CLUBS, '7')
		);
		
		$actualResult = $PokerSimulator->GetBestHandForPlayer($playerHand, $board);
		$expectedResult = '177ea8';
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		// all tests passed
		return true;
	}

	public function testCollectRankValuesByCount(PokerSimulator $PokerSimulator)
	{
		/* baseline test */
		$rankValuesInThisCount = array(10, 7, 12, 8, 13);
		$actualResult = $PokerSimulator->CollectRankValuesByCount($rankValuesInThisCount);
		$expectedResult = array(13, 12, 10, 8, 7);
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test with duplicate values */
		$rankValuesInThisCount = array(13, 7, 12, 8, 13);
		$actualResult = $PokerSimulator->CollectRankValuesByCount($rankValuesInThisCount);
		$expectedResult = array(13, 12, 8, 7);
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		// all tests passed
		return true;
	}

	public function testConvertCardValueToStoredValue(PokerSimulator $PokerSimulator)
	{
		/* test hex */
		$cardValue = '10';
		$actualResult = $PokerSimulator->ConvertCardValueToStoredValue($cardValue);
		$expectedResult = 'a';
		
		if ($actualResult != $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test non-hex */
		$cardValue = '9';
		$actualResult = $PokerSimulator->ConvertCardValueToStoredValue($cardValue);
		$expectedResult = '9';
		
		if ($actualResult != $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		// all tests passed
		return true;
		
	}

	public function testConvertValueToRank(PokerSimulator $PokerSimulator)
	{
		/* test ace */
		$cardValue = 14;
		$actualResult = $PokerSimulator->ConvertValueToRank($cardValue);
		$expectedResult = Card::RANK_ACE;
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test king */
		$cardValue = 13;
		$actualResult = $PokerSimulator->ConvertValueToRank($cardValue);
		$expectedResult = Card::RANK_KING;
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test queen */
		$cardValue = 12;
		$actualResult = $PokerSimulator->ConvertValueToRank($cardValue);
		$expectedResult = Card::RANK_QUEEN;
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test jack */
		$cardValue = 11;
		$actualResult = $PokerSimulator->ConvertValueToRank($cardValue);
		$expectedResult = Card::RANK_JACK;
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test 10 */
		$cardValue = 10;
		$actualResult = $PokerSimulator->ConvertValueToRank($cardValue);
		$expectedResult = '10';
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test 2 */
		$cardValue = 2;
		$actualResult = $PokerSimulator->ConvertValueToRank($cardValue);
		$expectedResult = '2';
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test exception < 2 */
		try
		{
			$cardValue = 1;
			$actualResult = $PokerSimulator->ConvertValueToRank($cardValue);
			$actualResult = false;
		}
		catch (Exception $e)
		{
			$actualResult = true;
		}
		$expectedResult = true;
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test exception > 14 */
		try
		{
			$cardValue = 15;
			$actualResult = $PokerSimulator->ConvertValueToRank($cardValue);
			$actualResult = false;
		}
		catch (Exception $e)
		{
			$actualResult = true;
		}
		$expectedResult = true;
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		// all tests passed
		return true;
		
	}

			
	public function testGetWinnerInfo(PokerSimulator $PokerSimulator)
	{
		/* baseline */
		$playerHands = array
		(
			0 => array(new Card('d', '9'), new Card('h', 'J')),
			1 => array(new Card('d', '2'), new Card('d', '6'))
		);
		$handResults = array(0 => '26655b',	1 => '666655');
		
		$actualResult = $PokerSimulator->GetWinnerInfo($playerHands, $handResults);
		$expectedResult = array('playerId' => 1, 'playerResults' => 666655, 'playerHand' => '2d6d');
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* reverse order */
		$playerHands = array
		(
			0 => array(new Card('d', '2'), new Card('d', '6')),
			1 => array(new Card('d', '9'), new Card('h', 'J'))
		);
		$handResults = array(0 => '666655', 1 => '26655b');
		
		$actualResult = $PokerSimulator->GetWinnerInfo($playerHands, $handResults);
		$expectedResult = array('playerId' => 0, 'playerResults' => 666655, 'playerHand' => '2d6d');
		
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}

		/* tie */
// 		$playerHands = array
// 		(
// 			0 => array(new Card('s', '10'), new Card('d', 'J')),
// 			1 => array(new Card('h', 'J'), new Card('s', '6'))
// 		);
// 		$handResults = array(0 => '2bb33e', 1 => '2bb33e');
		
// 		$actualResult = $PokerSimulator->GetWinnerInfo($playerHands, $handResults);
// 		$expectedResult = array('playerId' => 0, 'playerResults' => 666655, 'playerHand' => '2d6d');
		
// 		if ($actualResult !== $expectedResult)
// 		{
// 			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
// 			return false;
// 		}

		// all tests passed
		return true;
	}

	public function testSortCardByRank(PokerSimulator $PokerSimulator)
	{
		/* test -1, number and face */
		$actualResult = $PokerSimulator->SortByCardRank('A', 2);
		$expectedResult = -1;
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test 0, number and face */
		$actualResult = $PokerSimulator->SortByCardRank(2, 2);
		$expectedResult = 0;
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test 1, number and face */
		$actualResult = $PokerSimulator->SortByCardRank(2, 'A');
		$expectedResult = 1;
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test exception */
		try
		{
			$actualResult = $PokerSimulator->SortByCardRank('B', 17);
			$this->UnitTestLog('Expecting exception', __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		catch (Exception $e)
		{
			// do nothing, test pass
		}
		
		// all tests passed
		return true;
	}
		
	public function testCombineKickers(PokerSimulator $PokerSimulator)
	{
		/* test kickers for quads */
		$cardsByFreq = array(4 => array('9'), 2 => array('5'), 1 => array ('A'));
		$frequenciesToCollect = array(3, 2, 1);
		
		$actualResult = $PokerSimulator->CombineKickers($cardsByFreq, $frequenciesToCollect);
		$expectedResult = array('5', 'A');
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test trips */
		$cardsByFreq = array(2 => array(), 1 => array ('A', 'Q', '9'));
		$frequenciesToCollect = array(2, 1);
		
		$actualResult = $PokerSimulator->CombineKickers($cardsByFreq, $frequenciesToCollect);
		$expectedResult = array('A', 'Q', '9');
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test pair */
		$cardsByFreq = array(2 => array(), 1 => array ('A', 'Q', '9'));
		$frequenciesToCollect = array(1);
		
		$actualResult = $PokerSimulator->CombineKickers($cardsByFreq, $frequenciesToCollect);
		$expectedResult = array('A', 'Q', '9');
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		// all tests passed
		return true;
	}

	public function testGetAllRanks(PokerSimulator $PokerSimulator)
	{
		/* baseline */
		$CardArray = array
		(
			new Card('s', 'A'),
			new Card('d', 'K')
		);
		$actualResult = $PokerSimulator->GetAllRanks($CardArray);
		$expectedResult = array('A', 'K');
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* more than one of one rank */
		$CardArray = array
		(
			new Card('s', 'A'),
			new Card('d', 'A')
		);
		$actualResult = $PokerSimulator->GetAllRanks($CardArray);
		$expectedResult = array('A', 'A');
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		// all tests passed
		return true;	
	}

	public function testConvertRankToValue(PokerSimulator $PokerSimulator)
	{
		/* test face card */
		$rank = 'A';
		$actualResult = $PokerSimulator->ConvertRankToValue($rank);
		$expectedResult = 14;
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* test value */
		$rank = '2';
		$actualResult = $PokerSimulator->ConvertRankToValue($rank);
		$expectedResult = 2;
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		// all tests passed
		return true;
	}
}
?>