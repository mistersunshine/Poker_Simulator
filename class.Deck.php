<?php
require_once 'class.Card.php';

class Deck
{
	
	/**
	 * @var $suitColors array
	 */
	private $suitColors;

	private $deck;

	function __construct($fourColors = false)
	{
		$this->InitSuitColors($fourColors);
	}

	/**
	 * Sets colors for display layer
	 * 
	 * @param bool $fourColors
	 * @see testSetSuitColors()
	 */
	public function InitSuitColors($fourColors)
	{
		$this->suitColors = array
		(
			Card::SUIT_SPADES => Card::SUITCOLOR_BLACK,
			Card::SUIT_HEARTS => Card::SUITCOLOR_RED
		);

		if ($fourColors)
		{
			$this->suitColors[Card::SUIT_DIAMONDS] = Card::SUITCOLOR_BLUE;
			$this->suitColors[Card::SUIT_CLUBS] = Card::SUITCOLOR_GREEN;
		}
		else
		{
			$this->suitColors[Card::SUIT_DIAMONDS] = Card::SUITCOLOR_RED;
			$this->suitColors[Card::SUIT_CLUBS] = Card::SUITCOLOR_BLACK;
		}
	}


	/**
	 * build deck
	 *
	 * populates array with 52 cards in "ascending" order from ace of spades to king of clubs
	 * @return array
	 * @see testBuildDeck()
	 */
	public function BuildDeck()
	{
		// initialize
		$suits = array_keys($this->suitColors);
		$faceCards = array(Card::RANK_JACK, Card::RANK_QUEEN, Card::RANK_KING);
		$this->deck = array();
		
		// populate array
		for ($i = 0; $i < 52; $i++)
		{
			// get suit
			$currentSuit = $suits[floor($i / 13)];
			
			// get initial value, make 13 for king
			$currentValue = ($i + 1) % 13;
			if ($currentValue === 0)
			{
				$currentValue = 13;
			}
			
			// determine final card value
			if ($currentValue > 10)
			{
				$currentValue = $faceCards[$currentValue - 11];
			}
			else if ($currentValue == 1)
			{
				$currentValue = Card::RANK_ACE;
			}
			
			// add to deck
			$this->deck[$i] = new Card($currentSuit, $currentValue);

		}
	}

	/**
	 * @return array
	 * @see testShuffleDeck()
	 */
	public function ShuffleDeck()
	{
		// initialize
		$shuffledDeck = array();
		$previousCards = array();
		
		// populate array
		for ($i = 0; $i < 52; $i++)
		{
			// select random number not already in array
			do
			{
				$tempRnd = mt_rand(0, 51);
			} while (in_array($tempRnd, $previousCards));
			
			// add to array
			$previousCards[] = $tempRnd;
			$shuffledDeck[$i] = $this->deck[$tempRnd];
		}
		$this->deck = $shuffledDeck;
	}

	public function getDeck()
	{
		return $this->deck;
	}
	
	public function getSuitColors()
	{
		return $this->suitColors;
	}
}

?>