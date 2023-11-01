<?php

namespace BlackJack;

require_once('Player.php');
require_once('Deck.php');

class AutoPlayer extends Player
{
    // オートコンティニューを利用
    use AutoContinue;

    // 表示名を定義
    public string $playerName = "プレイヤー";

    public int $playerNum = 1;
}
