<?php

namespace App\Card;

use App\Card\Card;
use App\Card\CardGraphic;

class DeckOfCards
{
    public $deckOfCards;

    public function __construct()
    {
        $this->deckOfCards = [
            new Card("A", "Spades"), new Card("2", "Spades"),
            new Card("3", "Spades"), new Card("4", "Spades"), new Card("5", "Spades"),
            new Card("6", "Spades"), new Card("7", "Spades"), new Card("8", "Spades"),
            new Card("9", "Spades"), new Card("10", "Spades"), new Card("J", "Spades"),
            new Card("Q", "Spades"), new Card("K", "Spades"),
            new Card("A", "Hearts"), new Card("2", "Hearts"),
            new Card("3", "Hearts"), new Card("4", "Hearts"), new Card("5", "Hearts"),
            new Card("6", "Hearts"), new Card("7", "Hearts"), new Card("8", "Hearts"),
            new Card("9", "Hearts"), new Card("10", "Hearts"), new Card("J", "Hearts"),
            new Card("Q", "Hearts"), new Card("K", "Hearts"),
            new Card("A", "Diamonds"), new Card("2", "Diamonds"),
            new Card("3", "Diamonds"), new Card("4", "Diamonds"), new Card("5", "Diamonds"),
            new Card("6", "Diamonds"), new Card("7", "Diamonds"), new Card("8", "Diamonds"),
            new Card("9", "Diamonds"), new Card("10", "Diamonds"), new Card("J", "Diamonds"),
            new Card("Q", "Diamonds"), new Card("K", "Diamonds"),
            new Card("A", "Clubs"), new Card("2", "Clubs"),
            new Card("3", "Clubs"), new Card("4", "Clubs"), new Card("5", "Clubs"),
            new Card("6", "Clubs"), new Card("7", "Clubs"), new Card("8", "Clubs"),
            new Card("9", "Clubs"), new Card("10", "Clubs"), new Card("J", "Clubs"),
            new Card("Q", "Clubs"), new Card("K", "Clubs")
        ];
    }

    public function getDeckOfCards()
    {
        return $this->deckOfCards;
    }

    public function shuffle()
    {
        shuffle($this->deckOfCards);
    }

    public function deckOfCardsJson()
    {
        $stringDeck = [];
        foreach ($this->getDeckOfCards() as $cards) {
            $stringDeck[] = [$cards->getValue() . " " . $cards->getColor()];
        }
        return $stringDeck;
    }
}
