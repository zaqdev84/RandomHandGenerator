<?php

use PHPUnit\Framework\TestCase;

class DeckTest extends TestCase {
    
    private $deck;
    
    protected function setUp(): void {
        $this->deck = new Shuffler\Deck();
    }
    
    public function testDeckObject() {
        
        $this->assertIsObject($this->deck);
    }

    public function testGenerateRandomCardsReturnsArray() {        
        $cards = $this->deck->getRandomCards();
        $this->assertIsArray($cards);        
    }
    
    public function testGenerateRandomCardsDoesNotReturnsArray() {

        //Must not be able to get zero cards.
        $cards = $this->deck->getRandomCards(0);
        $this->assertIsNotArray($cards);

        //Must not be able to get less than zero.
        $cardsBelowZero = $this->deck->getRandomCards(-1);
        $this->assertIsNotArray($cardsBelowZero);
        $this->assertFalse($cardsBelowZero);

        //Must not be able to get more than 52 cards.
        $cardsOver52 = $this->deck->getRandomCards(100);
        $this->assertIsNotArray($cardsOver52);
        $this->assertFalse($cardsOver52);
    }
    
    public function testGenerateRandomRankedCardsReturnsArray() {        
        
        $cards = $this->deck->getRandomCards(5);
        $randomCards = $this->deck->getRandomRankedCards();
        $this->assertIsArray($randomCards);

        //Returns array no matter what.
        $cardsBelowZero = $this->deck->getRandomCards(-1);
        $cardsBelowZeroRandom = $this->deck->getRandomRankedCards();
        $this->assertIsArray($cardsBelowZeroRandom);
    }
    
    public function testIsStraight() {

        $straightCards1 = ['Qh', 'Ad', 'Kc', 'Js', '10s'];
        $this->assertTrue($this->deck->isStraight($straightCards1));

        $straightCards2 = ['9h', '7d', '6c', '5s', '8s'];
        $this->assertTrue($this->deck->isStraight($straightCards2));

        $straightCards3 = ['5h', 'Ad', '3c', '2s', '4s'];
        $this->assertTrue($this->deck->isStraight($straightCards3));

        //shorter parameters
        $straightCards4 = ['Qh', 'Ad', 'Kc', 'Js'];
        $this->assertTrue($this->deck->isStraight($straightCards4));

        //extra long
        $straightCards5 = ['Qh', 'Ad', 'Kc', 'Js', '10s', '7c', '5d', '9h', '6s', '8h'];
        $this->assertTrue($this->deck->isStraight($straightCards5));

        //Not Straight
        $notStraightCards = ['Qh', 'Ad', 'Kc', 'Js', '9s'];
        $this->assertFalse($this->deck->isStraight($notStraightCards));
        
    }
    
    public function testIsFlush() {
        
        // valid flush
        $flushCards = ['Qc', 'Ac', 'Kc', 'Jc', '10c'];
        $this->assertTrue($this->deck->isFlush($flushCards));
        
        //shorter parameters, valid flush
        $shorterParamFlush = ['Qd', 'Ad', 'Kd', 'Jd'];
        $this->assertTrue($this->deck->isStraight($shorterParamFlush));

        //extra long, valid flush
        $extraLongParamFlush = ['Qh', 'Ah', 'Kh', 'Jh', '10h', '7h', '5h', '9h', '6h', '8h'];
        $this->assertTrue($this->deck->isStraight($extraLongParamFlush));

        // invalid flush
        $nonFlushCards = ['9h', '7d', '6c', '5s', '8s'];
        $this->assertFalse($this->deck->isFlush($nonFlushCards));        
    }
        
    public function testIsStraightFlush() {
        
        $straightFlushCards = ['Qc', 'Ac', 'Kc', 'Jc', '10c'];
        
        $this->assertTrue($this->deck->isStraightFlush($straightFlushCards));
        
        //non-straight but flush
        $straightFlushCards = ['5c', 'Ac', 'Kc', 'Jc', '10c'];
        
        $this->assertFalse($this->deck->isStraightFlush($straightFlushCards));
        
        
        //non-flush but straight
        $straightFlushCards = ['Qc', 'Ad', 'Kc', 'Jc', '10c'];
        
        $this->assertFalse($this->deck->isStraightFlush($straightFlushCards));
        
        
    }

}
