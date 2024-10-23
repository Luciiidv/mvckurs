<?php

namespace App\Card;

use App\Card\Card;

class CardGraphic extends Card
{
    protected $representation;
    public $deckGraphic;

    /**
     * Constructs CardGraphic class.
     */
    public function __construct($value = null, $color = null)
    {
        parent::__construct($value, $color);
        $this->deckGraphic = [];
        if($value && $color) {
            $this->representation = '[' . $value . $this->getSymbol($color) . ']';
        }
    }

    /**
     * Gets card as a string "[ value color]"
     */
    public function getAsString()
    {
        return $this->representation;
    }

    /**
     * Returns unicode char of the specific color.
     */
    public function getSymbol($color)
    {
        if ($color === 'Spades') {
            return "\u{2660}";
        }

        if ($color === 'Hearts') {
            return "\u{2665}";
        }

        if ($color === 'Diamonds') {
            return "\u{2666}";
        }

        return "\u{2663}";
    }

    /**
     * Returns whole deck in string format.
     */
    public function deckToString($deck)
    {
        // $this->deckGraphic = [];
        foreach ($deck->getDeckOfCards() as $card) {
            $card = new CardGraphic($card->getValue(), $card->getColor());
            $this->deckGraphic[] = $card->getAsString();
        }
        return $this;
    }

    /**
     * Adds an array to deckGraphic.
     */
    public function addArray($array)
    {
        // $this->deckGraphic = [];
        foreach ($array as $card) {
            $this->deckGraphic[] = $card;
        }
        return $this;
    }

    /**
     * Shuffle deck
     */
    public function shuffleCardDeck()
    {
        return shuffle($this->deckGraphic);
    }

}
