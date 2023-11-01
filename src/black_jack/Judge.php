<?php

namespace BlackJack;

require_once('Deck.php');

class Judge
{
    // カードのランク表 ['A' => 1, '2' => 2, ... 'Q' => 10, 'K' => 10 ]
    public array $cardRanks;
    private const SCORE_OF_A = 1;
    private const ANOTHER_SCORE_OF_A = 11;

    public function __construct()
    {
        $cardRanks = [];

        // A から K まで回す
        $rank = 1;
        foreach (Deck::CARD_NUM as $cardNum) {
            // 1 から始めてランクを割り当てていく
            $cardRanks[$cardNum] = $rank;
            if ($rank < 10) {
                $rank++;
            }
        }

        // プロパティに代入
        $this->cardRanks = $cardRanks;
    }

    // スコアを算出して返す
    public function calculateScore(array $drawnCards, Player $player): int
    {
        // カード情報の数字（アルファベット）をキーとするカードランクを取得
        // $drawnCards [["ハート","A"],["ハート","8"],...]
        $ranks = array_map(fn ($drawnCard) => $this->cardRanks[$drawnCard[1]], $drawnCards);

        // ランクを合計してスコアに格納
        $userScore = array_sum($ranks);

        // 手札にランク 1 の A がある場合
        if (in_array(1, $ranks)) {
            // もう一つのスコアとして、1 ではなく 11 を加算
            $anotherScore = $userScore - self::SCORE_OF_A + self::ANOTHER_SCORE_OF_A;

            // 大きい 11 の方の得点が 21 を超えないなら
            if ($anotherScore <= 21) {
                // そのスコアの方を返す
                $userScore = $anotherScore;
            }
        }

        // ユーザーの得点として保存
        $player->playerScore = $userScore;

        // 得点を返す
        return $player->playerScore;
    }


    public function resultUnder21(Player $player): void
    {
        echo "{$player->playerName}の得点が21を超えました。" . PHP_EOL;
        echo "{$player->playerName}とディーラーの勝負は、ディーラーの勝ちです。" . PHP_EOL;
    }


    // 勝敗判定
    public function judgeWinner(array $players, Dealer $dealer): void
    {
        // 21 を超えなかったプレイヤーそれぞれに処理
        foreach ($players as $player) {
            echo "-------------------------------------------" . PHP_EOL;
            echo "{$player->playerName}の現在の得点は{$player->playerScore}です。" . PHP_EOL;
            echo "ディーラーの得点は{$dealer->playerScore}です。" . PHP_EOL;

            if ($dealer->playerScore > 21) {
                echo "{$dealer->playerName}の得点が21を超えました。" . PHP_EOL;
                echo "{$player->playerName}とディーラーの勝負は、{$player->playerName}の勝ちです。" . PHP_EOL;
            } else {
                // それぞれの得点の、21との差を求める
                $playerDifference = 21 - $player->playerScore;
                $dealerDifference = 21 - $dealer->playerScore;

                // 差が小さい方が勝ち
                if ($playerDifference < $dealerDifference) {
                    echo "{$player->playerName}と{$dealer->playerName}の勝負は、{$player->playerName}の勝ちです。" . PHP_EOL;
                } elseif ($playerDifference > $dealerDifference) {
                    echo "{$player->playerName}と{$dealer->playerName}の勝負は、{$dealer->playerName}の勝ちです。" . PHP_EOL;
                } elseif ($playerDifference === $dealerDifference) {
                    echo "{$player->playerName}と{$dealer->playerName}の勝負は、引き分けです。" . PHP_EOL;
                }
            }
        }
    }
}
