<?php

namespace App\Card;

use App\Card\DeckOfCards;

class CardHand
{
    public $cardHand;
    public $cardDeck;

    public function __construct()
    {
        $this->cardHand = [];
        $this->cardDeck = [];
    }

    public function addCardHand($numberOfCards, $deckOfCards)
    {
        if(!$deckOfCards || $numberOfCards > count($deckOfCards)) {
            return $this;
        }
        $cardsToAdd = array_slice($deckOfCards, 0, $numberOfCards);
        $this->cardHand = array_merge($this->cardHand, $cardsToAdd);

        $this->cardDeck = array_slice($deckOfCards, $numberOfCards);
    }

    public function addToHand($cards)
    {
        for ($i = 0; $i < count($cards); $i++) {
            // $card = $deckOfCards[$i];
            // unset($deckOfCards[$i]);
            // $this->cardHand[$i] = $card;
            $this->cardHand[] = $cards[$i];
        }
    }

    public function getCardHand()
    {
        return $this->cardHand;
    }

    public function getCardDeck()
    {
        return $this->cardDeck;
    }

    public function apiCardHand()
    {
        $apiCardHand = [];
        foreach($this->cardHand as $card) {
            $apiCardHand[] = $card;
        }
        return $apiCardHand;
    }

    public function apiCardDeck()
    {
        $apiCardDeck = [];
        foreach($this->cardDeck as $card) {
            $apiCardDeck[] = $card;
        }
        return $apiCardDeck;
    }
}
