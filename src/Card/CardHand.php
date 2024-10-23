<?php

namespace App\Card;

use App\Card\DeckOfCards;

class CardHand
{
    public $cardHand;
    public $cardDeck;
    public $bankHand;

    public function __construct()
    {
        $this->cardHand = [];
        $this->cardDeck = [];
        $this->bankHand = [];
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

    public function addBankHand($deckOfCards)
    {
        if(!$deckOfCards || 1 > count($deckOfCards)) {
            return $this;
        }
        while ($this->countBankPoints() < 17) {
            $cardsToAdd = array_shift($deckOfCards);

            $this->bankHand = array_merge($this->bankHand, [$cardsToAdd]);

            // $this->cardDeck = array_slice($deckOfCards, 1);
        }
        $this->cardDeck = $deckOfCards;
    }

    public function addToHand($cards)
    {
        $allCards = count($cards);

        for ($i = 0; $i < $allCards; $i++) {
            // $card = $deckOfCards[$i];
            // unset($deckOfCards[$i]);
            // $this->cardHand[$i] = $card;
            $this->cardHand[] = $cards[$i];
        }
    }

    public function addToBankHand($cards)
    {
        $allCards = count($cards);

        for ($i = 0; $i < $allCards; $i++) {
            $this->bankHand[] = $cards[$i];
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

    public function getBankHand()
    {
        return $this->bankHand;
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

    public function apiBankHand()
    {
        $apiBankHand = [];
        foreach($this->bankHand as $card) {
            $apiBankHand[] = $card;
        }
        return $apiBankHand;
    }

    public function countPlayerPoints()
    {
        $points = 0;
        foreach($this->cardHand as $point) {
            if ($point[1] == "1" && $point[2] == "0") {
                $points += 10;
            } elseif ($point[1] == "J") {
                $points += 11;
            } elseif ($point[1] == "Q") {
                $points += 12;
            } elseif ($point[1] == "K") {
                $points += 13;
            } elseif ($point[1] == "A") {
                if ($points + 14 < 21) {
                    $points += 14;
                } else {
                    $points += 1;
                }
            } else {
                $points += intval($point[1]);
            }
        }
        return $points;
    }

    public function countBankPoints()
    {
        $points = 0;
        foreach($this->bankHand as $point) {
            if ($point[1] == "1" && $point[2] == "0") {
                $points += 10;
            } elseif ($point[1] == "J") {
                $points += 11;
            } elseif ($point[1] == "Q") {
                $points += 12;
            } elseif ($point[1] == "K") {
                $points += 13;
            } elseif ($point[1] == "A") {
                if ($points + 14 < 21) {
                    $points += 14;
                } else {
                    $points += 1;
                }
            } else {
                $points += intval($point[1]);
            }
        }
        return $points;
    }

    public function winner()
    {
        $result = "";

        if ($this->countPlayerPoints() && $this->countBankPoints() < 22) {
            if ($this->countPlayerPoints() > $this->countBankPoints()) {
                $result = "Spelaren vann!";
            } else {
                $result = "Banken vann!";
            }
        } else {
            $result = "Spelaren vann!";
        }

        return $result;
    }
}
