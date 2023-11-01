<?php
require_once('deck.php');

class Player{

    private const CARDS_IN_HAND_NUM = 2;

    public function __construct(
        private array $hand = [],
        private int $total_score = 0,
        private string $status = 'hit'
    )
    {}

    public function getHand(): array
    {
        return $this->hand;
    }

    public function getTotalScore(): int
    {
        return $this->total_score;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    private function calcTotalScore(): void
    {
        $this->total_score = 0;
        foreach($this->hand as $card){
            $this->total_score += $card['score'];
        }
    }

    public function drawCard(Deck $deck):void
    {
        $cardDrawn = array_slice($deck->getDeck(), 0, 1);
        $deck->takeCard();
        $this->hand = array_merge($this->hand, $cardDrawn);
        $this->calcTotalScore();
    }

    public function initHand(Deck $deck): void
    {
        for ($i = 1; $i <= self::CARDS_IN_HAND_NUM; $i++)
        {
            $this->drawCard($deck);
        }
        $this->calcTotalScore();
    }

    public function changeStatus(string $status): void
    {
        $this->status = $status;
    }

}
