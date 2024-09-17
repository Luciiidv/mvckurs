<?php

namespace App\Card;

class Card
{
    protected $value;
    protected $color;

    /**
    * Constructs for Card class, value defualt = null, color default = null.
    */
    public function __construct(string $value = null, string $color = null)
    {
        // $colors = ['Spades', 'Hearts', 'Diamonds', 'Clubs'];
        // $this->value = rand(1, 13);
        // $this->color = $colors[array_rand($colors)];
        $this->value =  $value;
        $this->color = $color;
    }

    /**
     * Gets value of card.
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Gets color of card.
     */
    public function getColor()
    {
        return $this->color;
    }
}
