<?php
require_once('deck.php');

class Player{

    private const CARDS_IN_HAND_NUM = 2;

    public function __construct(
        private array $hand = [],
        private int $total_score = 0,
        private string $status = 'hit',
        private int $total_ace = 0
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

    public function getTotalAce(): int
    {
        return $this->total_ace;
    }

    private function calcTotalAce(): void
    {
        for ($i = 0; $i < $this->getTotalAce(); $i++)
        {
            if ($this->total_score > 21)
            {
                $this->total_score -= 10;
            }
        }
    }

    private function calcTotalScore(): void
    {
        $this->total_score = 0;
        $this->total_ace = 0;

        foreach($this->hand as $card){
            if($card['num'] === 'A')
            {
                ++$this->total_ace;
            }
            $this->total_score += $card['score'];
        }
        unset($card);
        $this->calcTotalAce();
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
