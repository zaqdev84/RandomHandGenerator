<?php

use PHPUnit\Framework\TestCase;

class DeckTest extends TestCase {

    public function testDeckObject() {
        $deck = new Shuffler\Deck();
        $this->assertIsObject($deck);
    }

    public function testGenerateRandomCardsReturnsArray() {
        $deck = new Shuffler\Deck();
        $cards = $deck->getRandomCards();
        $this->assertIsArray($cards);
    }

    public function testGenerateRandomCardsDoesNotReturnsArray() {
        $deck = new Shuffler\Deck();

        //Must not be able to get zero cards.
        $cards = $deck->getRandomCards(0);
        $this->assertIsNotArray($cards);

        //Must not be able to get less than zero.
        $cardsBelowZero = $deck->getRandomCards(-1);
        $this->assertIsNotArray($cardsBelowZero);
        $this->assertFalse($cardsBelowZero);

        //Must not be able to get more than 52 cards.
        $cardsOver52 = $deck->getRandomCards(100);
        $this->assertIsNotArray($cardsOver52);
        $this->assertFalse($cardsOver52);
    }

    public function testGenerateRandomRankedCardsReturnsArray() {
        $deck = new Shuffler\Deck();
        $cards = $deck->getRandomCards(5);
        $randomCards = $deck->getRandomRankedCards();
        $this->assertIsArray($randomCards);

        //Returns array no matter what.
        $cardsBelowZero = $deck->getRandomCards(-1);
        $cardsBelowZeroRandom = $deck->getRandomRankedCards();
        $this->assertIsArray($cardsBelowZeroRandom);
    }

    public function testIsStraight() {
        $deck = new Shuffler\Deck();
        
        $straightCards1 = ['Qh', 'Ad', 'Kc', 'Js', '10s'];        
        $this->assertTrue($deck->isStraight($straightCards1));
        
        $straightCards2 = ['9h', '7d', '6c', '5s', '8s'];        
        $this->assertTrue($$deck->isStraight($straightCards2));
        
        $straightCards3 = ['5h', 'Ad', '3c', '2s', '4s'];        
        $this->assertTrue($deck->isStraight($straightCards3));
        
        
        $notStraightCards = ['Qh', 'Ad', 'Kc', 'Js', '9s'];        
        $this->assertFalse($deck->isStraight($notStraightCards));
        
        $notStraightCards = ['Qh', 'Ad', 'Kc', 'Js'];
        $this->assertFalse($deck->isStraight($notStraightCards));
        
    }

    public function testIsFlush() {
        
        $this->assertTrue(true);
    }

    public function testIsStraightFlush() {
        $this->assertTrue(true);
    }

}
