<?php

require "vendor/autoload.php";
header('Content-type:text/plain');
$deck = new Shuffler\Deck();
$system = false;
if (!isset($argv[1])) {
    $system = true;
    echo "Warning! no input was provided generating random, the output you see is for system generated random cards.\n";
    $deck->shuffle();
    $randomCards = $deck->getRandomCards(5);    
} else {
    $randomCards = explode(',', $argv[1]);
    if(count($randomCards) !=5){
        echo "Script execution failed, missing input parameters, check usage and retry.\n";
        exit;
    }
}
echo "Straight = ";
var_dump($deck->isStraight($randomCards));
echo "Flush = ";
var_dump($deck->isFlush($randomCards));
echo "StraightFlush = ";
var_dump($deck->isStraightFlush($randomCards));

if($system) {
    echo "Following array was passed:\n";
    var_dump($randomCards);
}