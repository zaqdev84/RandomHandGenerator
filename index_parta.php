<?php
require "vendor/autoload.php";
header('Content-type:text/plain');
$deck = new Shuffler\Deck();
$deck->shuffle();
$randomCards = $deck->getRandomCards(5);
echo "array('" . $randomCards[0] . "','" . $randomCards[1] . "','" . $randomCards[2] . "','" . $randomCards[3] . "','" . $randomCards[4] . "');\n";