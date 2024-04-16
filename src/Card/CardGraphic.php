<?php

namespace App\Card;
use App\Card\Card;

class CardGraphic extends Card {

    public function __construct($value=null, $color=null) {
        parent::__construct($value, $color);
        $deckGraphic = [];
        if($value && $color) {
            $this->representation = '[' . $value . $this->getSymbol($color) . ']';
        }
    }

    public function getAsString() {
        return $this->representation;
    }

    public function getSymbol($color)
    {
        if($color) {
            if ($color === 'Spades') {
                return "\u{2660}";
            } elseif ($color === 'Hearts') {
                return "\u{2665}";
            } elseif ($color === 'Diamonds') {
                return "\u{2666}";
            } else {
                return "\u{2663}";
            }
        }
        return;
    }

    public function deckToString($deck) {
        // $this->deckGraphic = [];
        foreach ($deck->getDeckOfCards() as $card) {
            $card = new CardGraphic($card->getValue(), $card->getColor());
            $this->deckGraphic[] = $card->getAsString();
        }
        return $this;
    }

    public function addArray($array) {
        // $this->deckGraphic = [];
        foreach ($array as $card) {
            $this->deckGraphic[] = $card;
        }
        return $this;
    }

    public function shuffleCardDeck() {
        return shuffle($this->deckGraphic);
    }
}