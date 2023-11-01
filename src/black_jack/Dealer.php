<?php

namespace BlackJack;

require_once('Deck.php');
require_once('AutoContinue.php');

class Dealer extends Player
{
    // オートコンティニューを利用
    use AutoContinue;

    // 表示名を定義
    public string $playerName = "ディーラー";
}
