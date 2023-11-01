<?php
require_once('card.php');

class Deck
{
    public function __construct(private array $deck = []){

    }

    public function getDeck(): array
    {
        return $this->deck;
    }
    //デッキを初期化する
    public function initDeck(): array
    {
        $card = new Card;
        $this->deck = $card->createNewDeck();
        shuffle($this->deck);
        return $this->deck;
    }

    public function takeCard():void
    {
        $this->deck = array_slice($this->deck,1);
    }

}
// $testdeck = new Deck;
// print_r ($testdeck->initDeck());
