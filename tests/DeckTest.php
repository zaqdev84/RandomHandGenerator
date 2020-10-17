<?php

use PHPUnit\Framework\TestCase;

class DeckTest extends TestCase {
    public function testDeckObject() {
        $deck = new Shuffler\Deck();                
        $this->assertIsObject($deck);
    }
}