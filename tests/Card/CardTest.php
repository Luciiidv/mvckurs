<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateCard()
    {
        $card = new Card();
        $this->assertInstanceOf("\App\Card\Card", $card);

        //test color and value.
        $exp = null;
        $this->assertEquals($exp, $card->getColor());
        $this->assertEquals($exp, $card->getValue());
    }
}
