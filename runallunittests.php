<?php
require_once('/unittests/class.PokerSimulator.UnitTest.php');
$PokerSimulatorUnitTest = new PokerSimulatorUnitTest();
$PokerSimulatorUnitTest->RunAllTests();

require_once('/unittests/class.Card.UnitTest.php');
$CardUnitTest = new CardUnitTest();
$CardUnitTest->RunAllTests();

require_once('/unittests/class.Deck.UnitTest.php');
$DeckUnitTest = new DeckUnitTest();
$DeckUnitTest->RunAllTests();

?>