<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class BlackJackCardHandTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateBlackJackCardHandClass()
    {
        $BlackJackCardHand = new BlackJackCardHand();

        //Test to setHands method.
        $BlackJackCardHand->setHands([['cards' => ['[' . '2' . "\u{2660}" . ']']]]);
        $exp = [['cards' => ['[' . '2' . "\u{2660}" . ']']]];
        $this->assertEquals($exp, $BlackJackCardHand->getHands());

        //Test to setBankHand method.
        $BlackJackCardHand->setBankHand(['[' . 'A' . "\u{2660}" . ']']);
        $expBank = ['[' . 'A' . "\u{2660}" . ']'];
        $this->assertEquals($expBank, $BlackJackCardHand->getBankHand());

        //test to setTotalChips method.
        $BlackJackCardHand->setTotalChips(10);
        $this->assertEquals(10, $BlackJackCardHand->getTotalChips());

        // Test getMessage method.
        $BlackJackCardHand->message = "Test message";
        $this->assertEquals("Test message", $BlackJackCardHand->getMessage());

        //Test instance of class.
        $this->assertInstanceOf("\App\Card\BlackJackCardHand", $BlackJackCardHand);
    }

    // Creating a cardhand and testing to add cards with different methods and deck methods.
    public function testCreateBlackjackGame()
    {
        $BlackJackCardHand = new BlackJackCardHand();

        //Adding hands and chips
        $hands = 2;
        $chips = 10;

        //Test to start new game.
        $deck = ['[' . '2' . "\u{2660}" . ']'];

        for ($i = 1; $i <= 15; $i++) {
            $deck[] = '[' . '7' . "\u{2660}" . ']';
        };

        // Adding deck to the game.
        $BlackJackCardHand->addCardDeck($deck);

        // Testing getCardDeck method.
        $this->assertEquals($deck, $BlackJackCardHand->getCardDeck());

        // Starting game with 1 hand and 10 chips.
        $BlackJackCardHand->startGame($hands, $chips);

        $player_deck_exp = ['[' . '2' . "\u{2660}" . ']', '[' . '7' . "\u{2660}" . ']'];
        $bankHand_exp = ['[' . '7' . "\u{2660}" . ']'];

        // Testing hands and chips.
        $this->assertEquals($player_deck_exp, $BlackJackCardHand->getHands()[0]['cards']);
        $this->assertEquals($bankHand_exp, $BlackJackCardHand->getBankHand());
        $this->assertEquals($chips, $BlackJackCardHand->getTotalChips());

        // Testing doulbe method.
        $BlackJackCardHand->double(0);

        // Should be 20 chips after double.
        $this->assertEquals($chips * 2, $BlackJackCardHand->getTotalChips());

        // Testing split method.
        $BlackJackCardHand->split(1);
        $player_deck_exp_after_split = ['[' . '7' . "\u{2660}" . ']'];
        $this->assertEquals($player_deck_exp_after_split, $BlackJackCardHand->getHands()[1]['cards']);
        // Should be 3 hands after split.
        $this->assertEquals(3, count($BlackJackCardHand->getHands()));

        // Testing playTurn method.
        $BlackJackCardHand->playTurn(1);
        $player_deck_exp_after_play = ['[' . '7' . "\u{2660}" . ']', '[' . '7' . "\u{2660}" . ']'];
        $this->assertEquals($player_deck_exp_after_play, $BlackJackCardHand->getHands()[1]['cards']);

        // Testing activePlayers method.
        $exp_active_players = 3;
        $this->assertEquals($exp_active_players, $BlackJackCardHand->activePlayers());

        // Testing bankTurn method.
        $BlackJackCardHand->bankTurn();
        $bankHand_exp_after_bank_turn = ['[' . '7' . "\u{2660}" . ']', '[' . '7' . "\u{2660}" . ']', '[' . '7' . "\u{2660}" . ']'];
        $this->assertEquals($bankHand_exp_after_bank_turn, $BlackJackCardHand->getBankHand());
        // Bank won against all hands should be 0 chips after bank turn.
        $this->assertEquals(0, round($BlackJackCardHand->getTotalChips()));
        // Testing message.
        $this->assertEquals('Spelrundan har avslutats. Banken har 21 poäng. och du har ' . $BlackJackCardHand->getTotalChips() . ' markörer kvar.', $BlackJackCardHand->message);

        //Testing endGame.
        $this->assertTrue($BlackJackCardHand->endGame);

    }
}
