<?php
require_once('common/common.php');
class Card
{
	const SUIT_SPADES = 's';
	const SUIT_HEARTS = 'h';
	const SUIT_DIAMONDS = 'd';
	const SUIT_CLUBS = 'c';

	const RANK_JACK = 'J';
	const RANK_QUEEN = 'Q';
	const RANK_KING = 'K';
	const RANK_ACE = 'A';
	
	const SUITCOLOR_RED = 'red';
	const SUITCOLOR_BLACK = 'black';
	const SUITCOLOR_BLUE = 'blue';
	const SUITCOLOR_GREEN = 'green';

	private $suit;
	private $rank;
	
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $suit
	 * @param unknown_type $rank
	 * @see test CardConstruct
	 */
	function __construct($suit, $rank)
	{
		$this->setSuit($suit); // runs data validation
		$this->setRank($rank); // runs data validation
	}
	
	public function setSuit($suit)
	{
		$validSuits = array(self::SUIT_SPADES, self::SUIT_HEARTS, self::SUIT_DIAMONDS, self::SUIT_CLUBS);
		if (in_array($suit, $validSuits))
		{
			$this->suit = "$suit";
		}
		else
		{
			throw new Exception('Invalid suit given for ' . __CLASS__ . '::' . __FUNCTION__ . '()');
		}
	}
	
	public function getSuit()
	{
		return $this->suit;
	}
	
	public function setRank($rank)
	{
		$validRanks = explode(',', '2,3,4,5,6,7,8,9,10,' . self::RANK_JACK . ',' . self::RANK_QUEEN . ',' . self::RANK_KING . ',' . self::RANK_ACE . '');
		if (in_array($rank, $validRanks))
		{
			$this->rank = "$rank";
		}
		else
		{
			throw new Exception('Invalid rank given for ' . __CLASS__ . '::' . __FUNCTION__ . '()');
		}
	}
	
	public function getRank()
	{
		return $this->rank;
	}
	
	/**
	 *
	 * Enter description here ...
	 * @return string
	 * @see testToString
	 */
	function __toString()
	{
		return "$this->rank$this->suit";
	}
}

?>