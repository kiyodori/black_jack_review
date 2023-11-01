<?php

require_once('player.php');

class Dealer extends Player
{
    public function checkBurst(Player $player): bool
    {
        if ($player->getTotalScore() > 21)
        {
            $player->changeStatus('burst');
            return true;
        }
        return false;
    }

    public function judgeWinner(Player $player, Dealer $dealer)
    {
        $playerScoreTotal = $player->getTotalScore();
        $dealerScoreTotal = $dealer->getTotalScore();
        if ($playerScoreTotal > $dealerScoreTotal){
            $player->changeStatus('win');
        } elseif ($playerScoreTotal < $dealerScoreTotal) {
            $player->changeStatus('lose');
        } elseif ($playerScoreTotal === $dealerScoreTotal){
            $player->changeStatus('draw');
        }
    }

    public function allPlayerStand(Deck $deck)
    {
        while ($this->getTotalScore() < 17)
        {
            $this->drawCard($deck);
        }
    }
}
