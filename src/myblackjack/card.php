<?php
class Card{
    private const CARD_SCORE = [
        'A' => 1,
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
        '6' => 6,
        '7' => 7,
        '8' => 8,
        '9' => 9,
        '10' => 10,
        'J' => 10,
        'Q' => 10,
        'K' => 10,
    ];
    private array $suits = [
        'ハート','ダイヤ','クラブ','スペード'
    ];

    public function createNewDeck (): array
    {
        $deck = [];
        foreach($this->suits as $suit){
            foreach(self::CARD_SCORE as $num => $score) {
                $deck[] = [
                    'suit' => $suit,
                    'num' => $num,
                    'score' => $score,
                ];
            }
        }
        return $deck;
    }

}
