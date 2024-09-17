<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardHandTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateCardHandClass()
    {
        $cardHand = new CardHand();

        //Test instance of class.
        $this->assertInstanceOf("\App\Card\CardHand", $cardHand);
    }

    // Creating a cardhand and testing to add cards with different methods and deck methods.
    public function testCreateCardHandAndDeck()
    {
        $cardHand = new CardHand();

        //Test to add a deck and cards to player.
        $deck = [1, 2, 3];
        $exp = [1];
        $cardHand->addCardHand(1, $deck);
        //Testing deck function.
        $expDeck = [2, 3];

        $this->assertEquals($exp, $cardHand->getCardHand());
        $this->assertEquals($expDeck, $cardHand->getCardDeck());

        //Testing AddToHand method.
        $cardHand->addToHand([4]);
        $expHand = [1, 4];

        $this->assertEquals($expHand, $cardHand->getCardHand());

        //Testing ApiCardHand method.
        $this->assertEquals($expHand, $cardHand->apiCardHand());

        //Testing ApiCardDeck method.
        $this->assertEquals($expDeck, $cardHand->apiCardDeck());
    }

    //Testing to add cards to the bank with different methods.
    public function testCreateBankHand()
    {
        $cardHand = new CardHand();

        //Test to add a cards until reach 17 <  to bank.
        $deck = ['[' . 'A' . "\u{2660}" . ']', '[' . '7' . "\u{2660}" . ']'];
        $exp = ['[' . 'A' . "\u{2660}" . ']', '[' . '7' . "\u{2660}" . ']'];

        $cardHand->addBankHand($deck);

        $this->assertEquals($exp, $cardHand->GetBankHand());

        //Testing AddToBankHand method.
        $cardHand->addToBankHand(['[' . '8' . "\u{2660}" . ']']);
        $expBank = ['[' . 'A' . "\u{2660}" . ']', '[' . '7' . "\u{2660}" . ']', '[' . '8' . "\u{2660}" . ']'];

        $this->assertEquals($expBank, $cardHand->getBankHand());
        $this->assertEquals($expBank, $cardHand->apiBankHand());
    }

    //Testing counting methods.
    public function testCountPoints()
    {
        $cardHand = new CardHand();

        $deck = ['[' . 'A' . "\u{2660}" . ']', '[' . '7' . "\u{2660}" . ']', '[' . '10' . "\u{2660}" . ']', '[' . 'J' . "\u{2660}" . ']', '[' . 'K' . "\u{2660}" . ']', '[' . 'Q' . "\u{2660}" . ']', '[' . 'A' . "\u{2665}" . ']'];
        $exp = 68;

        $cardHand->addToBankHand($deck);
        $cardHand->addToHand($deck);

        $this->assertEquals($exp, $cardHand->countPlayerPoints());
        $this->assertEquals($exp, $cardHand->countBankPoints());
    }

    //Testing Winner method.
    public function testWinner()
    {
        $cardHand = new CardHand();

        //Testing if bank wins.
        $bankWinnerDeck = ['[' . '7' . "\u{2660}" . ']'];
        $expBankWins = "Banken vann!";

        $cardHand->addToBankHand($bankWinnerDeck);
        $cardHand->addToHand($bankWinnerDeck);

        $this->assertEquals($expBankWins, $cardHand->winner());

        //Testing if player wins.
        $cardHand->addToHand(['[' . 'A' . "\u{2660}" . ']']);

        $expPlayerWins = "Spelaren vann!";

        $this->assertEquals($expPlayerWins, $cardHand->winner());

        //If bank gets to much points.
        $bankLooseDeck = ['[' . 'J' . "\u{2660}" . ']', '[' . 'K' . "\u{2660}" . ']', '[' . 'Q' . "\u{2660}" . ']', '[' . 'A' . "\u{2665}" . ']'];
        $cardHand->addToBankHand($bankLooseDeck);

        $this->assertEquals($expPlayerWins, $cardHand->winner());
    }
}
