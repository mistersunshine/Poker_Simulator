<?php
require_once('common/common.php');
require_once('class.PokerSimulator.php');
require_once('class.Card.php');
require_once('class.Simulation1.php');

$Simulation1 = new Simulation1();
$Simulation1->Run(9, 10);
//$Simulation1->Run(9, 20000);

exit;


// check hero fold point
// if hero folds
	// subtract fold value from hero stack
// if hero loses showdown
	// subtract showdown value from hero stack
	// Loss Metrics e.g. when did villian hit hand
// for each other player
	// fold?
	// add showdown value to pot
	// metrics?
// add pot to hero stack
		
// straight draw
	// order by rank
	// if >= 4, return true
	// return false

// flush draw
	// make array of suits
	// group by suit
	// if any group >= 4, return true
	// return false
	
// pair or better
	// make array of ranks
	// group by rank
	// if any rank >= 2, return true
	// return false
	
?>