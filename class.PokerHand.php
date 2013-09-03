<?php
/**
 *
 * 		$handValue = '';
		$highValues = array();
		$playsBoard = true;
		$whenMade = PokerSimulator::HANDMADE_RIVER;
 *
 */
class PokerHand
{
	const VALUE_ROYALFLUSH = 9;
	const VALUE_STRAIGHTFLUSH = 8;
	const VALUE_QUADS = 7;
	const VALUE_FULLHOUSE = 6;
	const VALUE_FLUSH = 5;
	const VALUE_STRAIGHT = 4;
	const VALUE_TRIPS = 3;
	const VALUE_TWOPAIR = 2;
	const VALUE_PAIR = 1;
	const VALUE_HIGHCARD = 0;

	const MADE_FLOP = 'flop';
	const MADE_TURN = 'turn';
	const MADE_RIVER = 'river';
	
	private $handValue;
	
	private $highValues;
	
	private $playsBoard;
	
	private $whenMade;
	
	function __construct($handValue = '', $highValues = array(), $playsBoard = true, $whenMade = self::MADE_RIVER)
	{
		$this->setProperty('handValue', $handValue);
		$this->setProperty('highValues', $highValues);
		$this->setProperty('playsBoard', $playsBoard);
		$this->setProperty('whenMade', $whenMade);
	}
	
	/**
	 * move to base class later
	 * Enter description here ...
	 * @param $propertyName
	 */
	public function getProperty($propertyName)
	{
		if (isset($this->$propertyName))
		{
			return $this->$propertyName;
		}
		
		throw new Exception('Property "' . $propertyName . '" does not exist for class ' . __CLASS__);
	}
	
	public function setProperty($propertyName, $value)
	{
		// validate value based on property
		switch ($propertyName)
		{
			case 'handValue':
				if ($value !== "$value")
				{
				}
				break;
		}
	}

	public function GetHandRanks()
	{
		return array
		(
			static::VALUE_HIGHCARD,
			static::VALUE_PAIR,
			static::VALUE_TWOPAIR,
			static::VALUE_TRIPS,
			static::VALUE_STRAIGHT,
			static::VALUE_FLUSH,
			static::VALUE_FULLHOUSE,
			static::VALUE_QUADS,
			static::VALUE_STRAIGHTFLUSH,
			static::VALUE_ROYALFLUSH,
		);
	}
	
	public function HandValueToString($value)
	{
		$strings = array
		(
			static::VALUE_HIGHCARD => 'High card',
			static::VALUE_PAIR => 'Pair',
			static::VALUE_TWOPAIR => 'Two pair',
			static::VALUE_TRIPS => 'Trips',
			static::VALUE_STRAIGHT => 'Straight',
			static::VALUE_FLUSH => 'Flush',
			static::VALUE_FULLHOUSE => 'Full house',
			static::VALUE_QUADS => 'Quads',
			static::VALUE_STRAIGHTFLUSH => 'Straight flush',
			static::VALUE_ROYALFLUSH => 'Royal flush',
		);
		
		if (isset($strings[$value]))
		{
			return $strings[$value];
		}

		throw new Exception('Undefined hand $value index: "' . $value . '"');
	}
}
?>