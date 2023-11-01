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
        $player_total_score = $player->getTotalScore();
        $dealer_total_score = $dealer->getTotalScore();
        if ($player_total_score > $dealer_total_score){
            $player->changeStatus('win');
        } elseif ($player_total_score < $dealer_total_score) {
            $player->changeStatus('lose');
        } elseif ($player_total_score === $dealer_total_score){
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
