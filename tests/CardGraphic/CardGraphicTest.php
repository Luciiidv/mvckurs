<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardGraphicTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateCardGraphic()
    {
        $cardGraphicSpades = new CardGraphic(1, 'Spades');
        $cardGraphicHearts = new CardGraphic(1, 'Hearts');
        $cardGraphicDiamonds = new CardGraphic(1, 'Diamonds');
        $cardGraphicClubs = new CardGraphic(1, 'Clubs');

        //Check instance
        $this->assertInstanceOf("\App\Card\CardGraphic", $cardGraphicSpades);

        //Test color and value.
        $expSymbolSpades = '[' . 1 . $cardGraphicSpades->getSymbol('Spades') . ']';
        $expSymbolHearts = '[' . 1 . $cardGraphicHearts->getSymbol('Hearts') . ']';
        $expSymbolDiamonds = '[' . 1 . $cardGraphicDiamonds->getSymbol('Diamonds') . ']';
        $expSymbolClubs = '[' . 1 . $cardGraphicClubs->getSymbol('Clubs') . ']';

        $this->assertEquals($expSymbolSpades, $cardGraphicSpades->getAsString());
        $this->assertEquals($expSymbolHearts, $cardGraphicHearts->getAsString());
        $this->assertEquals($expSymbolDiamonds, $cardGraphicDiamonds->getAsString());
        $this->assertEquals($expSymbolClubs, $cardGraphicClubs->getAsString());
    }

    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testDeckToString()
    {
        $deck = new DeckOfCards();
        $deckToStringTest = new CardGraphic();

        $deckToStringTest->deckToString($deck);

        //Expectation..
        $exp = '[' . 'A' . "\u{2660}" . ']';

        $this->assertEquals($exp, $deckToStringTest->deckGraphic[0]);
    }

    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testAddArray()
    {
        $deckOfOneArray = new CardGraphic();

        //Array.
        $array = ['hej', "där"];

        //Add array.
        $deckOfOneArray->addArray($array);

        //Expectation.
        $exp = 'hej';

        $this->assertEquals($exp, $deckOfOneArray->deckGraphic[0]);
    }


    /**
     * Testing shuffle card deck function.
     */
    public function testShuffleCardDeck()
    {
        $deck = new DeckOfCards();
        $cardDeck = new CardGraphic();

        //Original deck
        $cardDeck->deckToString($deck);

        // Shuffled deck
        $shuffledDeck = $cardDeck->shuffleCardDeck();

        $this->assertNotEquals($cardDeck->deckGraphic, $shuffledDeck);
    }
}
