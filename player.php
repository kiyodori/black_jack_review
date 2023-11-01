<?php
namespace Blackjack;

class Player
{
    public $hand = [];
    public $score = 0;

    public function drawCard($card)
    {
        // Aのカードの場合
        if ($card->rank === 'A') {
          // カードを引いた後の合計が11以下であれば、Aは11として扱う
            if ($this->score + 11 <= 21) {
                $card->value = 11;
            } else {
                $card->value = 1;
            }
        }

        $this->hand[] = $card;
        $this->score += $card->value;

        // もしAを含む手札が合計で21を超える場合、Aを1点として再評価する
        if ($this->score > 21) {
            foreach ($this->hand as $card) {
                if ($card->rank === 'A' && $card->value === 11) {
                    $card->value = 1;
                    $this->score -= 10;
                    if ($this->score <= 21) {
                        break;
                    }
                }
            }
        }
    }

    public function showHand()
    {
        foreach ($this->hand as $card) {
            echo "あなたの引いたカードは{$card->suit}の{$card->rank}です。\n";
        }
        echo "あなたの現在の得点は{$this->score}です。\n";
    }
    public function firstHand()
    {
        foreach ($this->hand as $card) {
            echo "あなたの引いたカードは{$card->suit}の{$card->rank}です。\n";
        }
    }
}
