<?php

namespace App\Card;

use App\Card\DeckOfCards;
use App\Card\CardGraphic;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BlackJackCardHand
{
    public $cardHand;
    public $cardDeck;
    public $bankHand;
    public $totalChips;
    public $hands;

    public function __construct()
    {
        $this->cardHand = [];
        $this->cardDeck = [];
        $this->bankHand = [];
        $this->totalChips = 0;
        $this->hands = [];
        $this->message = null;
        $this->endGame = false;
    }

    public function addCardDeck($deckOfCards)
    {
        $this->cardDeck = $deckOfCards;
    }

    public function addCardHand($numberOfCards, $handIndex)
    {
        if(!$this->cardDeck || $numberOfCards > count($this->cardDeck)) {
            return $this;
        }
        $cardsToAdd = array_slice($this->cardDeck, 0, $numberOfCards);
        $this->hands[$handIndex]['cards'] = array_merge($this->hands[$handIndex]['cards'], $cardsToAdd);

        $this->cardDeck = array_slice($this->cardDeck, $numberOfCards);
    }

    public function addHands($numberOfHands)
    {
        for ($i = 0; $i < $numberOfHands; $i++) {
            $this->hands[] = [
            'cards' => [],
            'points' => 0,
            "lostGame" => false
        ];
        }
    }

    public function startBankHand($deckOfCards)
    {
        if(!$deckOfCards || 1 > count($deckOfCards)) {
            return $this;
        }

        $cardsToAdd = array_shift($deckOfCards);

        $this->bankHand = array_merge($this->bankHand, [$cardsToAdd]);
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

        public function getHands()
    {
        return $this->hands;
    }

    public function getCardDeck()
    {
        return $this->cardDeck;
    }

    public function getBankHand()
    {
        return $this->bankHand;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function countPlayerPoints()
    {
        foreach($this->hands as $i => $hand) {
            $points = 0;
            foreach($hand['cards'] as $point) {
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
            if ($points > 21) {
                $this->hands[$i]['lostGame'] = true;
            }
            $this->hands[$i]['points'] = $points;
        }
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

    public function setHands($hands)
    {
        $this->hands = $hands;
    }

    public function setBankHand($hand)
    {
        $this->bankHand = $hand;
    }

    public function setTotalChips($amount)
    {
        $this->totalChips = $amount;
    }

    public function addChips($amount)
    {
        return $this->totalChips = $this->totalChips + $amount;
    }

    public function withdrawChips($amount)
    {
        return $this->totalChips = $this->totalChips - $amount;
    }

    public function getTotalChips()
    {
        return $this->totalChips;
    }

    public function double($handIndex)
    {
        $playersActive = $this->activePlayers();
        $hand = $this->hands[$handIndex]['cards'];
        $sum  = $this->hands[$handIndex]['points'];

        if (count($hand) === 2 && $sum >= 9 && $sum <= 11) {

            // Dubbel insats
            $this->totalChips *= 2;

            // Dra exakt ett kort
            $this->addCardHand(1, $handIndex);

            // Räkna poäng igen
            $this->countPlayerPoints();

            if( $this->hands[$handIndex]['points'] > 21 ) {
                $this->withdrawChips(($this->getTotalChips()) / $playersActive); // Förlorare förlorar insatsen
                $this->hands[$handIndex]['lostGame'] = true;
            }

            return true;
        }
        // Skickar meddelande vid misslyckad dubbling
        $this->message = "Kan endast dubblas vid 9, 10 eller 11 poäng med två kort.";
    }

    public function split($handIndex)
    {
        $hand = $this->hands[$handIndex]['cards'];

        if (count($hand) === 2 && $hand[0][1] === $hand[1][1]) {

            // Dubbel insats
            $this->totalChips *= 2;

            // Första handen behåller första kortet
            $this->hands[$handIndex] = [
            'cards' => [$hand[0]],
            'points' => 0,
            "lostGame" => false
            ];

            // Skapa ny hand med andra kortet
            $newHand = [
            'cards' => [$hand[1]],
            'points' => 0,
            "lostGame" => false
            ];

            // Lägg in nya handen direkt efter nuvarande
            array_splice($this->hands, $handIndex + 1, 0, [$newHand]);

            // Räkna poäng igen
            $this->countPlayerPoints();

            return true;
        }
        // Skickar meddelande vid misslyckad split
        $this->message = "Kan endast splittas med två lika kort.";
    }

    public function startGame($hands, $initialChips)
    {
        $this->addHands((int)$hands);
        $this->addChips($initialChips);

        // Add two cards to each hand
        for ($i = 0; $i < (int)$hands; $i++) {
            $this->addCardHand(2, $i);
        }
        // Add one card to bank hand
        $this->startBankHand($this->cardDeck);

        // Count bank points
        $this->countBankPoints();

        // Add points to each hand
        $this->countPlayerPoints();

        // If hand has 21 points, set lostGame to true
        $chips = $this->getTotalChips();
        for ($i = 0; $i < (int)$hands; $i++) {
            if( $this->hands[$i]['points'] > 21 ) {
                $this->withdrawChips(($chips) / (int)$hands);
                $this->hands[$i]['lostGame'] = true;
            }
        }

    }

    public function playTurn($handIndex)
    {
        $playersActive = $this->activePlayers();
        $this->addCardHand(1, $handIndex);
        $this->countPlayerPoints();

        if ($this->hands[$handIndex]['points'] > 21) {
            $this->withdrawChips(($this->getTotalChips()) / $playersActive); // Förlorare förlorar insatsen
            $this->hands[$handIndex]['lostGame'] = true;
        }
    }

    public function bankTurn()
    {
        $playersActive = $this->activePlayers();
        $this->addBankHand($this->cardDeck);
        $chips = $this->getTotalChips();

        $chipsToAdd = (($chips) / $playersActive) * 2;
        $chipsToWithdraw = ($chips) / $playersActive;

        for ($i = 0; $i < count($this->hands); $i++) {
            if( $this->hands[$i]['lostGame'] ) {
                continue;
            }
            if ($this->hands[$i]['points'] > 21) {
                $this->withdrawChips($chipsToWithdraw); // Förlorare förlorar insatsen
                continue;
            }
            if ($this->countBankPoints() > 21 || $this->hands[$i]['points'] > $this->countBankPoints()) {
                $this->addChips($chipsToAdd); // Vinnare får dubbla insatsen
            }
            $this->withdrawChips($chipsToWithdraw); // Oavgjort, insatsen tillbaka
        }

        $bankPoints = $this->countBankPoints();
        $this->endGame = true;
        # Avsluta spelrundan med meddelande om bankens poäng och kvarvarande markörer
        $this->message =  'Spelrundan har avslutats. Banken har ' . $bankPoints . ' poäng. och du har ' . $this->getTotalChips() . ' markörer kvar.';
    }

    public function activePlayers()
    {
        $playersActive = 0;
        for($i = 0; $i < count($this->hands); $i++) {
            if (!$this->hands[$i]['lostGame']) {
                $playersActive += 1;
            }
        }
        return $playersActive;
    }
}
