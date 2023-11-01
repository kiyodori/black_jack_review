<?php

namespace BlackJack;

trait AutoContinue
{
    // 引き続けるかどうか
    public function selectContinue(string $playerName): void
    {
        if ($this->playerScore >= 17) {
            // プロパティを上書き
            $this->continue = false;
            echo "{$playerName}はここで引くのをやめました。" . PHP_EOL;
        }
    }
}
