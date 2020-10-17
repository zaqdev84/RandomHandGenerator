<?php

use PHPUnit\Framework\TestCase;

class CardRandomizerTest extends TestCase {
 
    public function testArrayValidate() {
        $this->assertEquals(array(2,5), array(2,5));
    }
    
}
