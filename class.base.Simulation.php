<?php
abstract class BaseSimulation
{
	const MAX_VERBOSE_SIMULATIONS = 100;
	
	public function Run($numPlayers = 10, $numSimulations = 100)
	{
		if (!is_int($numSimulations))
		{
			throw new Exception('Int expected for number of simulations');
		}
		if (!is_int($numPlayers))
		{
			throw new Exception('Int expected for number of players');
		}
		
		$winnersLosers = array
		(
			'premium' 		=> array('win' => $this->GetPremiumHands(), 'loss' => $this->GetPremiumHands())
			, 'subpremium' 	=> array('win' => $this->GetSubPremiumHands(), 'loss' => $this->GetSubPremiumHands())
		);
		
		// add in other hands, need already specified hands to compare against
		$otherHands = $this->GetOtherHands($winnersLosers);
		$winnersLosers['other'] = array('win' => $otherHands, 'loss' => $otherHands);
		
		DebugLog('$winnersLosers=' . print_r($winnersLosers, true), __FILE__, '', '', __LINE__);
		
		$PokerSimulator = new PokerSimulator();
		if (CLEAR_SIMULATION_LOG_ALWAYS)
		{
			$fp = fopen('log/simulation_results_log.txt', 'w');
		}
		else
		{
			$fp = fopen('log/simulation_results_log.txt', 'a');
		}
		
		$verbose = (SIMULATION_LOG_VERBOSE && ($numSimulations <= static::MAX_VERBOSE_SIMULATIONS));
		if ($numSimulations > static::MAX_VERBOSE_SIMULATIONS)
		{
			echo 'Too many simulations for verbose max of ' . static::MAX_VERBOSE_SIMULATIONS . ", use less simulations for verbose\n";
		}
		
		for ($i = 0; $i < $numSimulations; $i++)
		{
			// get simulation results
			$results = $PokerSimulator->Simulation($numPlayers);
			
			// update winning/losing hand counts
			$this->UpdateWinnersLosers($winnersLosers, $results['dealtCards']['playerHands'], $results['winnerInfo']['playerId']);
			
			if ($verbose)
			{
				// begin output
				$outputResults = unserialize(serialize($results));
				
				// for output, do toString on player hands
				foreach ($outputResults['dealtCards']['playerHands'] as $playerId => $playerCards)
				{
					$outputResults['dealtCards']['playerHands'][$playerId] = $playerCards[0] . $playerCards[1];
				}
				
				// for output, do toString on board
				$board = array();
				foreach ($outputResults['dealtCards']['board'] as $cardId => $Card)
				{
					$board[] = (string)$Card;
				}
				$outputResults['dealtCards']['board'] = implode(' ', $board);
				
				// output to log
				fwrite($fp, '[' . date('Y/m/d H:i:s') . '] ');
				fwrite($fp, print_r($outputResults, true));
			}
		
		}
		
		$outputWinnersLosers = unserialize(serialize($winnersLosers));
		
		foreach ($outputWinnersLosers as $handType => $winLoss)
		{
			foreach ($winLoss as $hands)
			{
				foreach ($hands as $hand => $count)
				{
					$wins = $outputWinnersLosers[$handType]['win'][$hand];
					$losses = $outputWinnersLosers[$handType]['loss'][$hand];
					if ($wins + $losses == 0)
					{
						$outputWinnersLosers['tally'][$handType][$hand] = 'N/A';
					}
					else
					{
						$percent = (number_format(100 * ($wins / ($losses + $wins)), 2)) . '%';
						$outputWinnersLosers['tally'][$handType][$hand] = "$percent win ($wins / " . ($losses + $wins) . ")";
					}
				}
			}
		}
		
		fwrite($fp, '[' . date('Y/m/d H:i:s') . '] ');
		fwrite($fp, print_r($outputWinnersLosers, true));
		
		fclose($fp);
		echo 'Finished ' . $numSimulations . ' simluation(s) at ' . date('Y/m/d H:i:s');
	}
	
	public function UpdateWinnersLosers(&$winnersLosers, $playerHands, $winnerId)
	{
		$winnerHand = $this->ConvertCardCollectionToSuitedHand($playerHands[$winnerId]);
		$winnerHandReversed = $this->ConvertCardCollectionToSuitedHand($playerHands[$winnerId], true);
		
		if (isset($winnersLosers[$this->GetHandType($winnersLosers, $winnerHand)]['win'][$winnerHand]))
		{
			$winnersLosers[$this->GetHandType($winnersLosers, $winnerHand)]['win'][$winnerHand]++;
		}
		elseif (isset($winnersLosers[$this->GetHandType($winnersLosers, $winnerHandReversed)]['win'][$winnerHandReversed]))
		{
			$winnersLosers[$this->GetHandType($winnersLosers, $winnerHandReversed)]['win'][$winnerHandReversed]++;
		}
		else
		{
			$winnersLosers[$this->GetHandType($winnersLosers, $winnerHand)]['win'][$winnerHand] = 1;
		}
		
		foreach ($playerHands as $playerId => $playerHand)
		{
			if ($playerId != $winnerId)
			{
				$convertedPlayerHand = $this->ConvertCardCollectionToSuitedHand($playerHand);
				$convertedPlayerHandReversed = $this->ConvertCardCollectionToSuitedHand($playerHand, true);
				if (isset($winnersLosers[$this->GetHandType($winnersLosers, $convertedPlayerHand)]['loss'][$convertedPlayerHand]))
				{
					$winnersLosers[$this->GetHandType($winnersLosers, $convertedPlayerHand)]['loss'][$convertedPlayerHand]++;
				}
				elseif (isset($winnersLosers[$this->GetHandType($winnersLosers, $convertedPlayerHandReversed)]['loss'][$convertedPlayerHandReversed]))
				{
					$winnersLosers[$this->GetHandType($winnersLosers, $convertedPlayerHandReversed)]['loss'][$convertedPlayerHandReversed]++;
				}
				else
				{
					$winnersLosers[$this->GetHandType($winnersLosers, $convertedPlayerHand)]['loss'][$convertedPlayerHand] = 1;
				}
			}
		}
	}
	
	/**
	 * convert array(0 => Card, 1 => Card) to '00s' or '00'
	 * @param unknown_type $playerHand
	 */
	public function ConvertCardCollectionToSuitedHand($playerHand, $reversed = false)
	{
		$suited = '';
		if ($playerHand[0]->getSuit() == $playerHand[1]->getSuit())
		{
			$suited = 's';
		}
		
		if ($reversed)
		{
			return $playerHand[1]->getRank() . $playerHand[0]->getRank() . $suited;
		}
		
		return $playerHand[0]->getRank() . $playerHand[1]->getRank() . $suited;
	}
	
	public function GetHandType($winnersLosers, $hand)
	{
		foreach ($winnersLosers as $handType => $winLossArray)
		{
			// win/loss doesn't matter; both should be the same
			if (isset($winLossArray['win'][$hand]))
			{
					return $handType;
			}
		}
		
		// first time card encountered (may be deprecated later)
		return 'other';
	}
	
	abstract public function GetPremiumHands();
	
	abstract public function GetSubPremiumHands();

	public function GetOtherHands($winnersLosers)
	{
		$hands = array();
		$ranks = explode(',', '2,3,4,5,6,7,8,9,10,J,Q,K,A');
		$rankLookup = array_flip($ranks);
		foreach ($ranks as $rank1)
		{
			foreach ($ranks as $rank2)
			{
				foreach (array('s', '') as $suited)
				{
					if
					(
						$rankLookup[$rank1] > $rankLookup[$rank2]
						&& !isset($winnersLosers['premium']['win'][$rank1 . $rank2 . $suited])
						&& !isset($winnersLosers['premium']['win'][$rank2 . $rank1 . $suited])
						&& !isset($winnersLosers['subpremium']['win'][$rank1 . $rank2 . $suited])
						&& !isset($winnersLosers['subpremium']['win'][$rank2 . $rank1 . $suited])
					)
					{
						$hands[$rank1 . $rank2 . $suited] = 0;
					}
				}
			}
		}
		
		return $hands;
	}
}
?>