<?php

use PHPUnit\Framework\TestCase;

class CardTest extends TestCase {
    
    public function testCardObject() {
        $card = new Shuffler\Card('d','4',3);
        $this->assertIsObject($card);
    }
    
    public function testCardValuesAreSet() {
        $card = new Shuffler\Card('D', '5', 4);
        $this->assertEquals('D', $card->suit());
        $this->assertEquals('5', $card->number());
        $this->assertEquals(4, $card->rank());
        
        $this->assertIsNotNumeric($card->suit());
        $this->assertRegExp('/[0-9a-zA-Z]/', $card->number());
        $this->assertIsNumeric($card->rank());
    }
}