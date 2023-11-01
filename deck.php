<?php
namespace Blackjack;

class Deck
{
    public $cards = [];
    private $drawnCards = [];

    public function __construct()
    {
        $suits = ["ハート", "ダイヤ", "スペード", "クラブ"];
        $ranks = ["2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K", "A"];

        foreach ($suits as $suit) {
            foreach ($ranks as $rank) {
                $value = is_numeric($rank) ? (int)$rank : ($rank === "A" ? 11 : 10);
                $this->cards[] = new Card($suit, $rank, $value);
            }
        }
        $this->shuffle();
    }

    public function shuffle()
    {
        shuffle($this->cards);
    }

    public function drawCard()
    {
        $card = array_shift($this->cards);
        $this->drawnCards[] = $card;
        return array_shift($this->cards);
    }
}
