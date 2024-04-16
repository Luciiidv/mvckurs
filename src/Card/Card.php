<?php

namespace App\Card;

class Card {

    protected $value;
    protected $color;


    public function __construct(string $value=null, string $color=null) {
        // $colors = ['Spades', 'Hearts', 'Diamonds', 'Clubs'];
        // $this->value = rand(1, 13);
        // $this->color = $colors[array_rand($colors)];
        $this->value =  $value;
        $this->color = $color;
    }

    public function getValue() {
        return $this->value;
    }

    public function getColor() {
        return $this->color;
    }
}
