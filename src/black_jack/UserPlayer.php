<?php

namespace BlackJack;

require_once('Player.php');
require_once('Deck.php');

class UserPlayer extends Player
{
    // 表示名を定義
    public string $playerName = "あなた";
    public int $playerNum = 0;

    // もう一枚引くか選択
    public function selectContinue(string $playerName): void
    {
        while (true) {
            echo "カードを引きますか？（Y/N）" . PHP_EOL;
            // 標準入力を受け取る
            $continue = trim(fgets(STDIN));
            if ($continue == "Y") {
                $this->continue = true;
                break;
            } elseif ($continue == "N") {
                $this->continue = false;
                break;
            } else {
                echo "Y か N を入力してください。" . PHP_EOL;
            }
        }
    }
}
