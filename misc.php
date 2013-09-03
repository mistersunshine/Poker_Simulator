<?php
class Misc
{
	
	public function GetFoldValue($foldPoint, $blind, $smallBetRoundTotal, $bigBetRoundTotal)
	{
		// value of each round the player stays in
		$foldValues = array
		(
			$blind,
			$smallBetRoundTotal,
			$smallBetRoundTotal,
			$bigBetRoundTotal,
			$bigBetRoundTotal
		);
		
		// number of rounds for each fold point
		$foldPointValues = array
		(
			'preflop' => 0,
			'flop' => 1,
			'turn' => 2,
			'river' => 3
		);
		
		// note the number of rounds based on $foldPoint
		if (isset($foldPointValues[$foldPoint]))
		{
			$foldPointValue = $foldPointValues[$foldPoint];
		}
		else
		{
			$foldPointValue = 4;
		}
		
		// total up the cost of all rounds the player stayed in
		$total = 0;
		for ($i = 0; $i <= $foldPointValue; $i++)
		{
			$total += $foldValues[$i];
		}
		
		return $total;
	}
	
	public function testGetFoldValue(PokerSimulator $PokerSimulator)
	{
		// no need to test other blinds; i would simply be checking for the same value both ways
		/* fold preflop, blind = 4 */
		$foldPoint = 'preflop';
		$blind = 4;
		$smallBetRoundTotal = 8;
		$bigBetRoundTotal = 16;
		$actualResult = $PokerSimulator->GetFoldValue($foldPoint, $blind, $smallBetRoundTotal, $bigBetRoundTotal);
		$expectedResult = $blind;
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
		
		/* fold flop, blind = 4 */
		$foldPoint = 'flop';
		$blind = 4;
		$smallBetRoundTotal = 8;
		$bigBetRoundTotal = 16;
		$actualResult = $PokerSimulator->GetFoldValue($foldPoint, $blind, $smallBetRoundTotal, $bigBetRoundTotal);
		$expectedResult = $blind + $smallBetRoundTotal;
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}
			
		/* fold turn, blind = 4 */
		$foldPoint = 'turn';
		$blind = 4;
		$smallBetRoundTotal = 8;
		$bigBetRoundTotal = 16;
		$actualResult = $PokerSimulator->GetFoldValue($foldPoint, $blind, $smallBetRoundTotal, $bigBetRoundTotal);
		$expectedResult = $blind + ($smallBetRoundTotal * 2);
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}

		/* fold river, blind = 4 */
		$foldPoint = 'river';
		$blind = 4;
		$smallBetRoundTotal = 8;
		$bigBetRoundTotal = 16;
		$actualResult = $PokerSimulator->GetFoldValue($foldPoint, $blind, $smallBetRoundTotal, $bigBetRoundTotal);
		$expectedResult = $blind + ($smallBetRoundTotal * 2) + $bigBetRoundTotal;
		if ($actualResult !== $expectedResult)
		{
			$this->UnitTestLog('$actualResult=' . var_export($actualResult, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return false;
		}

		/* showdown, blind = 4 */
		$foldPoint = '';
		$blind = 4;
		$smallBetRoundTotal = 8;
		$bigBetRoundTotal = 16;
		$actualResult = $PokerSimulator->GetFoldValue($foldPoint, $blind, $smallBetRoundTotal, $bigBetRoundTotal);
		$expectedResult = $blind + ($smallBetRoundTotal * 2) + ($bigBetRoundTotal * 2);
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