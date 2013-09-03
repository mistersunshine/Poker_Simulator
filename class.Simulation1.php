<?php
require_once 'class.base.Simulation.php';

class Simulation1 extends BaseSimulation
{
	public function GetPremiumHands()
	{
		$hands = array();
		$pairRanks = explode(',', 'A,K,Q,J,10,9');
		foreach ($pairRanks as $pairRank)
		{
			$hands[$pairRank . $pairRank] = 0;
		}
		$hands['AKs'] = 0;
		$hands['AK'] = 0;
		$hands['AQs'] = 0;
		$hands['AJs'] = 0;
		$hands['A10s'] = 0;
		
		return $hands;
	}
	
	public function GetSubPremiumHands()
	{
		$hands = array();
		$pairRanks = explode(',', '8,7,6,5,4,3,2');
		foreach ($pairRanks as $pairRank)
		{
			$hands[$pairRank . $pairRank] = 0;
		}
		
		$hands['AQ'] = 0;
		$hands['AJ'] = 0;
		$hands['A10'] = 0;
		$hands['KQs'] = 0;
		$hands['KJs'] = 0;
		$hands['K10s'] = 0;
		$hands['QJs'] = 0;
		$hands['Q10s'] = 0;
		$hands['J10s'] = 0;
		$hands['KQ'] = 0;
		$hands['KJ'] = 0;
		$hands['K10'] = 0;
		$hands['QJ'] = 0;
		$hands['Q10'] = 0;
		$hands['J10'] = 0;
		$hands['A9s'] = 0;
		$hands['A8s'] = 0;
		$hands['A7s'] = 0;
		$hands['A6s'] = 0;
		$hands['A5s'] = 0;
		$hands['A4s'] = 0;
		$hands['A3s'] = 0;
		$hands['A2s'] = 0;
		
		return $hands;
	}

}
?>