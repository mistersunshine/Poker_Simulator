<?php
require_once('common/common.php');
require_once('class.PokerHand.php');
require_once('class.Card.php');
require_once('class.Deck.php');

class PokerSimulator
{
	/**
	 * Number of players at the table
	 */
	const NUM_PLAYERS_DEFAULT = 10;
	
	/**
	 * Deck of cards
	 * 
	 * @var Deck $Deck
	 */
	private $Deck;

	function __construct()
	{
		$this->Initialize();
	}
	
	/**
	 * Resets this object as if it was instantiated for the first time.
	 * 
	 * @param string $fourColors Determines if we use two or four colors for suit display in output.
	 */
	public function Initialize($fourColors = false)
	{
		$this->Deck = new Deck($fourColors);
		$this->Deck->BuildDeck();
	}
	
	/**
	 * Main processing loop.
	 * 
	 * @param int $numPlayers
	 * @throws Exception
	 * @return array
	 */
	public function Simulation($numPlayers = self::NUM_PLAYERS_DEFAULT)
	{
		// Validate input
		if (!is_int($numPlayers))
		{
			throw new Exception('Int expected for number of players');
		}
		
		// Prep deck
		$this->Deck->BuildDeck();
		$this->Deck->ShuffleDeck();
		
		// Process hand
		$dealtCards = $this->GetDealtCards($this->Deck->getDeck(), $numPlayers);
		$handResults = $this->GetHandResults($dealtCards['playerHands'], $dealtCards['board']);
		$winnerInfo = $this->GetWinnerInfo($dealtCards['playerHands'], $handResults);
		
		// Return hand results
		return array
		(
			'dealtCards' 		=> $dealtCards, 
			'handResults' 		=> $handResults, 
			'convertedHand' 	=> $this->ConvertHandResultForOutput($winnerInfo['playerResults']), 
			'winnerInfo' 		=> $winnerInfo
		);
	}

	/**
	 * Converts an "encoded" hand result into something readable
	 * 
	 * @param string $handResult
	 * @return string
	 */
	private function ConvertHandResultForOutput($handResult)
	{
		// Get hand value
		$PokerHand = new PokerHand();
		$handValue = $PokerHand->HandValueToString(substr($handResult, 0, 1));
		
		// Convert cards to normal card values rather than hexadecimal
		$hexValues = array_flip($this->GetCardValuesByRank('hex'));
		$cards = array();
		for ($i = 1; $i <= 5; $i++)
		{
			$cards[] = $hexValues[substr($handResult, $i, 1)];
		}

		// Return readable string
		return implode($cards, '') . ': ' . $handValue;
	}

	/**
	 * Get relevant info about winner
	 *
	 * @param array $playerHands
	 * @param array $handResults
	 * @return array
	 */
	public function GetWinnerInfo($playerHands, $handResults)
	{
		$winnerInfo = array();
		
		// Sort by hand value and keep array association to keep player id, get best hand value info from top of array
		$handResults = array_flip($handResults);
		krsort($handResults);
		
		// Note: need both key and value
		$winner = each($handResults);
		$winnerInfo['playerId'] = $winner['value'];
		$winnerInfo['playerResults'] = $winner['key'];
		
		// For easier reading in output, convert player hand to string
		$winnerInfo['playerHand'] = $playerHands[$winnerInfo['playerId']][0] . $playerHands[$winnerInfo['playerId']][1];
		
		return $winnerInfo;
	}
	
	/**
	 * Gets the best possible five card hand for each player based on their hands and the board
	 * 
	 * @param array $playerHands
	 * @param array $board
	 * @return array
	 */
	public function GetHandResults($playerHands, $board)
	{
		$bestPlayerHands = array();
		foreach ($playerHands as $playerId => $playerHand)
		{
			$bestPlayerHands[$playerId] = $this->GetBestHandForPlayer($playerHand, $board);
		}
		
		return $bestPlayerHands;
	}

	/**
	 * Deals the players hands and the community cards.
	 * 
	 * @param array $shuffledDeck
	 * @param int $numPlayers
	 * @return array
	 * @todo alternate: use some kind of pointer that is moved while dealing cards
	 */
	public function GetDealtCards(array $shuffledDeck, $numPlayers)
	{
		// Initialize
		$playerHands = array();
		$board = array();

		// Deal player hands
		for ($i = 0; $i < $numPlayers; $i++)
		{
			$playerHands[$i] = array($shuffledDeck[$i], $shuffledDeck[$i + $numPlayers]);
		}

		// Deal flop including (+1 for burn card)
		$deckPointer = ($numPlayers * 2) + 1;
		$board[] = $shuffledDeck[$deckPointer++];
		$board[] = $shuffledDeck[$deckPointer++];
		$board[] = $shuffledDeck[$deckPointer++];

		// Deal turn (+1 for burn card)
		$deckPointer++;
		$board[] = $shuffledDeck[$deckPointer++];

		// Deal river (+1 for burn card)
		$deckPointer++;
		$board[] = $shuffledDeck[$deckPointer];

		return array
		(
			'playerHands' => $playerHands,
			'board' => $board,
		);
	}

	/**
	 * Attaches a value to each card rank, 2 through Ace.
	 * 
	 * Returns either a decimal value or hex value.
	 * 
	 * @param string $returnType
	 * @throws Exception
	 * @return array
	 */
	public function GetCardValuesByRank($returnType = 'dec')
	{
		if ($returnType === 'hex')
		{
			// Requested hex value
			return array
			(
				'2' => 2,
				'3' => 3,
				'4' => 4,
				'5' => 5,
				'6' => 6,
				'7' => 7,
				'8' => 8,
				'9' => 9,
				'10' => 'a',
				Card::RANK_JACK => 'b',
				Card::RANK_QUEEN => 'c',
				Card::RANK_KING => 'd',
				Card::RANK_ACE => 'e'
			);
		}
		elseif ($returnType !== 'dec')
		{
			throw new Exception('Unexpected $returnType for ' . __METHOD__ . ': "' . $returnType . '"');
		}

		// Requested decimal value
		return array
		(
			'2' => 2,
			'3' => 3,
			'4' => 4,
			'5' => 5,
			'6' => 6,
			'7' => 7,
			'8' => 8,
			'9' => 9,
			'10' => 10,
			Card::RANK_JACK => 11,
			Card::RANK_QUEEN => 12,
			Card::RANK_KING => 13,
			Card::RANK_ACE => 14
		);
	}
	
	/**
	 * usort callback - get highest ranked card of those passed in
	 *
	 * Does not take ace low in straight into account; handled in a separate process
	 * 
	 * @param string|Card $card1
	 * @param string|Card $card2
	 * @throws Exception
	 * @return int
	 */
	public function SortByCardRank($card1, $card2)
	{
		// Get number value for card
		$cardValues = $this->GetCardValuesByRank();
		
		if (in_array($card1, array_keys($cardValues)) && in_array($card1, array_keys($cardValues)))
		{
			// Cards are recognized, return a value for usort depending on which is greater
			if ($cardValues[$card1] == $cardValues[$card2])
			{
				return 0;
			}
			elseif ($cardValues[$card1] > $cardValues[$card2])
			{
				return -1;
			}
			
			return 1;
		}
		else
		{
			throw new Exception('Card rank passed to ' . __FUNCTION__ . ' was not recognized. Parameters: ' . implode(', ', array($card1, $card2)));
		}

	}

	/**
	 *
	 * Find the best five card hand, convert to string format
	 * Format: rccccc, where r = hand rank and c = card hex value
	 * Example: 1cceda
	 *
	 * Note that ace (hex value e) could come last in a "wheel" (5432e)
	 *
	 * No need to worry about suits; if there's a flush, only one suit could have it, and then the winner is based on card rank
	 *
	 * @param array $playerHand array of two Card objects
	 * @param array $board array of five Card objects
	 */
	public function GetBestHandForPlayer(array $playerHand, array $board)
	{
		// Collect straights and flushes
		$straights = $this->GetAllStraights($playerHand, $board);
		$flushes = $this->GetAllFlushes($playerHand, $board);
		
		// Check for royal and straight flush
		if (count($straights) > 0)
		{
			if (count($flushes) > 0)
			{
				$straightFlushes = array_intersect($straights, $flushes);
				if (count($straightFlushes) > 0)
				{
					$bestStraightFlush = array_shift($straightFlushes);
					
					if ($bestStraightFlush === 'ebcda')
					{
						// royal flush
						return PokerHand::VALUE_ROYALFLUSH . 'ebcda';
					}
					
					// straight flush
					return PokerHand::VALUE_STRAIGHTFLUSH . $bestStraightFlush;
				}
			}
		}
		
		// collect "paired" hands e.g. quads, full house, trips, 2 pair, pair
		$bestPairedHand = $this->GetBestPairedHand($playerHand, $board);
		if (count($flushes) > 0)
		{
			$bestFlush = PokerHand::VALUE_FLUSH . $flushes[0];
			if ($bestFlush > $bestPairedHand)
			{
				// best flush is better than best paired hand
				return $bestFlush;
			}
		}
		if (count($straights) > 0)
		{
			$bestStraight = PokerHand::VALUE_STRAIGHT . $straights[0];
			if ($bestStraight > $bestPairedHand)
			{
				// best straight is better than best paired hand
				return $bestStraight;
			}
		}
		
		// everything else is out of the way, return best paired hand
		return $bestPairedHand;

	}
	
	/**
	 *
	 * get all possible straights given a player hand and board
	 *
	 * we need all of the straights to compare with flushes to see if there is a straight flush
	 *
	 * @param array $playerHand array of Card objects
	 * @param array $board array of Card objects
	 * @return array straights found, format of 012345, in order of strength
	 * @todo use vo to convert card objects to 012345 format
	 * @see testGetAllStraights()
	 */
	public function GetAllStraights(array $playerHand, array $board)
	{
		// initialize return value
		$straightsFound = array();
		
		// combine cards and sort by rank
		$allCards = array_merge($playerHand, $board);
		$allCardRanks = $this->GetAllRanks($allCards);
		
		DebugLog('$allCardRanks = ' . print_r($allCardRanks, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		usort($allCardRanks, array('PokerSimulator', 'SortByCardRank'));
		DebugLog('$allCardRanks = ' . print_r($allCardRanks, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);

		// initialize loop variables
		$previousCardValue = false;
		$cardsChecked = 0;
		$straightCardsFound = 1;
		foreach ($allCardRanks as $cardRank)
		{
			// need a value to compare against for straights, i.e. straight cards are only separated by a value of 1
			$currentCardValue = $this->ConvertRankToValue($cardRank);
			
			// do not process if first card or this card is same rank as previous
			if (($previousCardValue !== false) && ($previousCardValue != $currentCardValue))
			{
				// not the first card, process
				if ($previousCardValue - $currentCardValue == 1)
				{
					// this card is exactly one below previous, possible straight continues
					$straightCardsFound++;
				}
				elseif (($currentCardValue == 14) && ($previousCardValue == 2))
				{
					// A is lower than 2 in 5432A
					$straightCardsFound++;
				}
				else
				{
					// this card is more than one below previous, reset straight counter
					$straightCardsFound = 1;
				}
			}
			
			$previousCardValue = $currentCardValue;
			$cardsChecked++;
			
			DebugLog('$straightCardsFound = ' . print_r($straightCardsFound, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			DebugLog('$currentCardValue = ' . print_r($currentCardValue, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			DebugLog('$cardsChecked = ' . print_r($cardsChecked, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			
			if ($cardsChecked > 3 && $straightCardsFound < 2)
			{
				// beyond 3rd card with insufficient straight cards, no straights could possibly exist
				return $straightsFound;
			}
			
			if ($straightCardsFound == 5)
			{
				// capture current straight
				$straightValue = '';
				for ($i = 4; $i >= 0; $i--)
				{
					$straightValue .= $this->ConvertCardValueToStoredValue($currentCardValue + $i);
				}
				$straightsFound[] = $straightValue;
				
				// reset counter to 4 for next straight
				$straightCardsFound = 4;
			}
		}
		
		return $straightsFound;
	}
	
	/**
	 *
	 * get all flushes; best flush may be straight flush, which beats ace-high flush
	 * @param array $playerHand
	 * @param array $board
	 * @return Ambigous <multitype:, string>
	 * @see testGetAllFlushes
	 */
	public function GetAllFlushes(array $playerHand, array $board)
	{
		// initialize return value
		$flushesFound = array();
		
		// combine cards
		$allCards = array_merge($playerHand, $board);
		
		// initialize loop vars
		$suitCounter = array(Card::SUIT_CLUBS => array(), Card::SUIT_DIAMONDS => array(), Card::SUIT_SPADES => array(), Card::SUIT_HEARTS => array());
		foreach ($allCards as $Card)
		{
			$suitCounter[$Card->getSuit()][] = $Card;
		}
		
		// get flush from whichever suit has > 5 cards
		$flushCards = array();
		if (count($suitCounter[Card::SUIT_CLUBS]) >= 5)
		{
			$flushCards = $suitCounter[Card::SUIT_CLUBS];
		}
		elseif (count($suitCounter[Card::SUIT_SPADES]) >= 5)
		{
			$flushCards = $suitCounter[Card::SUIT_SPADES];
		}
		elseif (count($suitCounter[Card::SUIT_DIAMONDS]) >= 5)
		{
			$flushCards = $suitCounter[Card::SUIT_DIAMONDS];
		}
		elseif (count($suitCounter[Card::SUIT_HEARTS]) >= 5)
		{
			$flushCards = $suitCounter[Card::SUIT_HEARTS];
		}
		
		if (count($flushCards) > 0)
		{
			DebugLog('$flushCards=' . var_export($flushCards, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			$flushCardRanks = $this->GetAllRanks($flushCards);
			DebugLog('$flushCardRanks=' . var_export($flushCardRanks, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			usort($flushCardRanks, array('PokerSimulator', 'SortByCardRank'));
			DebugLog('$flushCardRanks=' . var_export($flushCardRanks, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			// collect each 5 card combo as a flush, which will catch straights
			for ($i = 0; $i < (count($flushCardRanks) - 4); $i++)
			{
				$flushValue = '';
				for ($j = 0; $j < 5; $j++)
				{
					$flushValue .= $this->ConvertCardValueToStoredValue($this->ConvertRankToValue($flushCardRanks[$i + $j]));
				}
				$flushesFound[] = $flushValue;
				
			}
		}
		
		return $flushesFound;
	}
	
	/**
	 *
	 * Enter description here ...
	 * @param array $playerHand
	 * @param array $board
	 */
	public function GetBestPairedHand(array $playerHand, array $board)
	{
		// combine cards
		$allCards = array_merge($playerHand, $board);
		
		$cardsByFreq = $this->SortCardCollectionByFrequency($allCards);
		DebugLog('$cardsByFreq = ' . print_r($cardsByFreq, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		
		/*
		 * these hands are just different enough to prevent use of a common method
		 *
		 * note that the zero index in $cardsByFreq[freq] pulls off the highest rank for that frequency
		 *
		 * @todo jhv 2011-08-18 there is a pattern we can extract: AssembleHand($cardsByFreq, array(3, 2))
		 * 			this method would handle both 3 + 2 and 3 + 3 + 1 full houses
		 */
		if (isset($cardsByFreq[4]) && count($cardsByFreq[4]) > 0)
		{
			DebugLog('quads', __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			// "4" of a kind
			$bestPairedHand = PokerHand::VALUE_QUADS; // rank from high card (0) to royal (9)
			
			$bestPairedHand .= $this->GetPairedCardStoredValueString($cardsByFreq[4][0], 4);
			
			$kickers = $this->CombineKickers($cardsByFreq, array(3,2,1));
			return $bestPairedHand . $this->GetPairedHandKickerStoredValue($kickers, 1);
		}
		elseif (isset($cardsByFreq[3]) && count($cardsByFreq[3]) > 0)
		{
			// "3" of a kind
			if (count($cardsByFreq[3]) > 1)
			{
				DebugLog('fullhouse 3 + 3 + 1', __FILE__, __CLASS__, __FUNCTION__, __LINE__);
				// full house: 3 + 3 + 1
				$bestPairedHand = PokerHand::VALUE_FULLHOUSE;  // rank from high card (0) to royal (9)
				
				$bestPairedHand .= $this->GetPairedCardStoredValueString($cardsByFreq[3][0], 3);
				
				// (gets only 2 of lower trips)
				return $bestPairedHand . $this->GetPairedCardStoredValueString($cardsByFreq[3][1], 2);
			}
			elseif (isset($cardsByFreq[2]) && count($cardsByFreq[2]) > 0)
			{
				DebugLog('fullhouse 3 + 2', __FILE__, __CLASS__, __FUNCTION__, __LINE__);
				// full house: 3 + 2
				$bestPairedHand = PokerHand::VALUE_FULLHOUSE;  // rank from high card (0) to royal (9)
				
				$bestPairedHand .= $this->GetPairedCardStoredValueString($cardsByFreq[3][0], 3);
				
				return $bestPairedHand . $this->GetPairedCardStoredValueString($cardsByFreq[2][0], 2);
			}
			
			DebugLog('trips', __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			// no full house
			$bestPairedHand = PokerHand::VALUE_TRIPS;  // rank from high card (0) to royal (9)
			
			$bestPairedHand .= $this->GetPairedCardStoredValueString($cardsByFreq[3][0], 3);
			
			$kickers = $this->CombineKickers($cardsByFreq, array(2,1));
			return $bestPairedHand . $this->GetPairedHandKickerStoredValue($kickers, 2);
		}
		elseif (isset($cardsByFreq[2]) && count($cardsByFreq[2]) > 0)
		{
			// "2" of a kind
			if (count($cardsByFreq[2]) > 1)
			{
				DebugLog('two pair', __FILE__, __CLASS__, __FUNCTION__, __LINE__);
				// two pair
				$bestPairedHand = PokerHand::VALUE_TWOPAIR;  // rank from high card (0) to royal (9)
				$bestPairedHand .= $this->GetPairedCardStoredValueString($cardsByFreq[2][0], 2);
				$bestPairedHand .= $this->GetPairedCardStoredValueString($cardsByFreq[2][1], 2);
				
				// to get kickers, need to remove top two pairs (may be third)
				unset($cardsByFreq[2][0]);
				unset($cardsByFreq[2][1]);
				
				$kickers = $this->CombineKickers($cardsByFreq, array(2,1));
				return $bestPairedHand . $this->GetPairedHandKickerStoredValue($kickers, 1);
			}
			
			DebugLog('one pair', __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			// one pair
			$bestPairedHand = PokerHand::VALUE_PAIR;  // rank from high card (0) to royal (9)
			
			
			$bestPairedHand .= $this->GetPairedCardStoredValueString($cardsByFreq[2][0], 2);
			
			return $bestPairedHand . $this->GetPairedHandKickerStoredValue($cardsByFreq[1], 3);
		}
		
		DebugLog('high card', __FILE__, __CLASS__, __FUNCTION__, __LINE__);

		/*
		 * high card (this method is cheating)
		 * @todo proper method
		 */
		return PokerHand::VALUE_HIGHCARD . $this->GetPairedHandKickerStoredValue($cardsByFreq[1], 5);
	}
	
	/**
	 * convert cards to stored value
	 * @param unknown_type $pairedCardRank
	 * @param unknown_type $count
	 * @return string
	 * @notest
	 */
	public function GetPairedCardStoredValueString($pairedCardRank, $count)
	{
			$pairedCardRankValue = $this->ConvertRankToValue($pairedCardRank);
			return str_repeat($this->ConvertCardValueToStoredValue($pairedCardRankValue), $count);
	}
	
	/**
	 * simple method to remove redundancy
	 *
	 * @param unknown_type $kickers
	 * @param unknown_type $numNeeded
	 */
	public function GetPairedHandKickerStoredValue($kickers, $numNeeded)
	{
		usort($kickers, array('PokerSimulator', 'SortByCardRank'));
		$kickers = array_slice($kickers, 0, $numNeeded);
		
		$kickerValue = '';
		foreach ($kickers as $kicker)
		{
			$kickerValue .= $this->ConvertCardValueToStoredValue($this->ConvertRankToValue($kicker));
		}
		
		return $kickerValue;
	}
	
	/**
	 * combines all needed kickers, e.g. if 4 of a kind, collect any 3, 2, or 1 of a kind
	 * @param array $cardsByFreq
	 * @param array $frequenciesToCollect
	 * @return array:
	 */
	public function CombineKickers(array $cardsByFreq, array $frequenciesToCollect)
	{
		$kickers = array();
		foreach ($frequenciesToCollect as $freq)
		{
			if (isset($cardsByFreq[$freq]) && count($cardsByFreq[$freq]) > 0)
			{
				$kickers = array_merge($kickers, $cardsByFreq[$freq]);
			}
		}
		return $kickers;
	}
	
	public function GetAllRanks(array $CardArray)
	{
		$allRanks = array();
		foreach ($CardArray as $Card)
		{
			$allRanks[] = $Card->getRank();
		}
		return $allRanks;
	}
	
	/**
	 * converts rank (2-A) to value (2-14)
	 * @param string $rank
	 * @throws Exception
	 */
	public function ConvertRankToValue($rank)
	{
		switch ($rank)
		{
			case 'A':
				$rankValue = 14;
				break;
			case 'K':
				$rankValue = 13;
				break;
			case 'Q':
				$rankValue = 12;
				break;
			case 'J':
				$rankValue = 11;
				break;
			default:
				$rankValue = (int)$rank;
				break;
		}
		DebugLog('$rankValue = ' . print_r($rankValue, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		if (($rankValue > 14) || ($rankValue < 2))
		{
			DebugLog('exception', __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			throw new Exception('Invalid rank value (' . $rankValue . ') in ' . __FUNCTION__ . '()');
		}
		
		return $rankValue;
	}
	
	/**
	 *
	 * Applies ConvertRankToValue() to an entire array, returns results
	 * @param array $ranks
	 */
	public function ConvertAllRanksToValues(array $ranks)
	{
		$values = array();
		foreach ($ranks as $rank)
		{
			$values[] = $this->ConvertRankToValue($rank);
		}
		return $values;
	}
	
	/**
	 *
	 * Applies ConvertValueToRank() to an entire array, returns results
	 * @param array $values
	 */
	public function ConvertAllValuesToRanks(array $values)
	{
		$ranks = array();
		foreach ($values as $value)
		{
			$ranks[] = $this->ConvertValueToRank($value);
		}
		return $ranks;
	}
	
	/**
	 * converts value (2-14) to card rank (2-A)
	 * @param int $value
	 * @throws Exception
	 */
	public function ConvertValueToRank($value)
	{
		$value = (int)$value;
		DebugLog('$value = ' . print_r($value, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		if (($value > 1) && ($value < 11))
		{
			return "$value";
		}
		
		switch ($value)
		{
			case 11:
				return 'J';
				break;
			case 12:
				return 'Q';
				break;
			case 13:
				return 'K';
				break;
			case 14:
				return 'A';
				break;
		}
		
		throw new Exception('Invalid value (' . $value . ') in ' . __FUNCTION__ . '()');
	}
	
	/**
	 * convert 2-14 (dec) to stored value for card rank
	 *
	 * current stored value is hex
	 *
	 * @param unknown_type $cardValue
	 * @return string
	 * @see testConvertCardValueToStoredValue()
	 */
	public function ConvertCardValueToStoredValue($cardValue)
	{
		return dechex($cardValue);
	}
	
	/**
	 * sorts cards by rank according to the following:
	 * - highest frequency
	 * - highest rank
	 * - lowest frequency
	 * - lowest rank
	 *
	 * algorithm
	 * - first, attach a count to each rank
	 * - then, sort by count
	 * - then, get all rank values for each count
	 * - then, sort those ranks and add to sorted cards
	 *
	 * note: return value is in descending order of count, then rank
	 *
	 * @param array $cards array of Card objects
	 * @return array Card objects
	 * @see testSortCardCollectionByFrequency()
	 */
	public function SortCardCollectionByFrequency(array $cards)
	{
		// initialize
		$sortedCards = array();
		
		// get rank values, sort
		$allRanks = $this->GetAllRanks($cards);
		$allRankValues = $this->ConvertAllRanksToValues($allRanks);
		
		// count ranks
		$rankValuesByCount = array();
		foreach ($allRankValues as $rankValue)
		{
			if (!isset($rankValuesByCount[$rankValue]))
			{
				$rankValuesByCount[$rankValue] = 0;
			}
			$rankValuesByCount[$rankValue]++;
		}
		
		// sort by descending counts (e.g. quads comes before trips)
		arsort($rankValuesByCount);
		
		DebugLog('$rankValuesByCount = ' . print_r($rankValuesByCount, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		
		// for each tier of count, add to sortedCards
		$previousCount = null;
		$rankValuesInThisCount = array();
		foreach ($rankValuesByCount as $rankValue => $count)
		{
			if (!is_null($previousCount))
			{
				// not first run
				if ($previousCount != $count)
				{
					// we are in a new tier of count, so add this tier
					$rankValuesByCount = $this->CollectRankValuesByCount($rankValuesInThisCount, $previousCount);
					$sortedCards[$previousCount] = $this->ConvertAllValuesToRanks($rankValuesByCount);
					
					// reset collected rank value array and add current
					$rankValuesInThisCount = array();
				}
			}
			
			// collect rank values by count
			$rankValuesInThisCount[] = $rankValue;
			$previousCount = $count;
		}
		
		// need to handle remaining cards
		$rankValuesByCount = $this->CollectRankValuesByCount($rankValuesInThisCount, $previousCount);
		$sortedCards[$previousCount] = $this->ConvertAllValuesToRanks($rankValuesByCount);
		
		DebugLog('$sortedCards = ' . print_r($sortedCards, true), __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		return $sortedCards;
	}
	
	/**
	 * Takes a tier of counted rank values, sorts them desc
	 *
	 * Example: pass in array(13, 7, 12, 8, 13), get back array(13, 12, 8, 7)
	 *
	 * @param array $rankValuesInThisCount
	 * @return array $sortedCards
	 * @see testCollectRankValuesByCount()
	 */
	public function CollectRankValuesByCount(array $rankValuesInThisCount)
	{
		$sortedCards = array();
		
		rsort($rankValuesInThisCount);
		foreach ($rankValuesInThisCount as $countedRankValue)
		{
			if (!in_array($countedRankValue, $sortedCards))
			{
				$sortedCards[] = $countedRankValue;
			}
		}
		return $sortedCards;
	}
	
	/* getters/setters */

	public function getDeck()
	{
		return $this->Deck;
	}

}

?>