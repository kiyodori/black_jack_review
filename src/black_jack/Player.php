<?php

namespace BlackJack;

abstract class Player
{
    // プレイヤーのカードを保持する [[$mark,$numAl].[$mark,$numAl],...]
    public array $drawnCards = [];
    // 表示名を定義
    public string $playerName = "";
    // プレイヤーの得点を保持
    public int $playerScore = 0;

    public bool $continue = true;

    // カード（インスタンス）を引く
    public function drawCard(Deck $deck): object
    {
        return $deck->drawCard();
    }

    // もう一枚引くか選択
    abstract public function selectContinue(string $playerName);

    // 自分の現時点でのスコアを取得する
    public function getStore(Judge $judge): int
    {
        // 現時点でのスコアを算出
        $this->playerScore = $judge->calculateScore($this->drawnCards, $this);

        return $this->playerScore;
    }
}
