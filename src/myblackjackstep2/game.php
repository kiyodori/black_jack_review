<?php
require_once('player.php');
require_once('dealer.php');
require_once('deck.php');

class Game
{
    public function __construct(
        private Deck $deck = new Deck(),
        private Player $player = new Player(),
        private Dealer $dealer = new Dealer(),
    )
    {
        $this->deck->initDeck();
        $this->player->initHand($this->deck);
        $this->dealer->initHand($this->deck);
    }

    public function getDeck()
    {
        return $this->deck;
    }

    public function getPlayer()
    {
        return $this->player;
    }

    public function getDealer()
    {
        return $this->dealer;
    }

    public function start()
    {
        $this->startMessage();
        while ($this->player->getStatus() === 'hit')
        {
            $inputYesOrNo = trim(fgets(STDIN));

            if ($inputYesOrNo === 'Y')
            {
                $this->player->drawCard($this->deck);
                $this->dealer->checkBurst($this->player);
                if ($this->player->getStatus() === 'burst'){
                    break;
                }
            $this->currentMessage();
            } elseif($inputYesOrNo === 'N') {
                $this->standMessage();
                $this->player->changeStatus('stand');
            } else {
                $this->errorMessage();
            }
        }
            if ($this->player->getStatus() === 'burst')
            {
                $this->playerBurstMesseage();
                exit;
            }

            $this->dealer->judgeWinner($this->player, $this->dealer);
            $this->resultMessage();
            exit;
    }

    private function startMessage()
    {
        echo 'ブラックジャックを開始します。' . PHP_EOL;
        foreach ($this->player->getHand() as $card)
        {
            echo 'あなたの引いたカードは' . $card['suit'] . 'の' . $card['num'] . 'です。' . PHP_EOL;
        }
        unset($card);

        $dealerFirstCard = $this->dealer->getHand()[0];
        echo 'ディーラーの引いたカードは' . $dealerFirstCard['suit'] . 'の' . $dealerFirstCard['num'] . 'です。' . PHP_EOL;
        echo 'ディーラーの引いた2枚目のカードは分かりません。' . PHP_EOL;
        echo 'あなたの現在の点数は' . $this->player->getTotalScore() . 'です。カードを引きますか？(Y/N)' . PHP_EOL;

    }

    private function errorMessage()
    {
        echo 'Y/Nで入力してください。カードを引きますか？(Y/N)' . PHP_EOL;
    }

    private function playerBurstMesseage(): void
    {
        $hand = $this->player->getHand();
        $cardDrawn = end($hand);
        echo 'あなたの引いたカードは' . $cardDrawn['suit'] . 'の' . $cardDrawn['num'] . 'です。' . PHP_EOL;
        echo 'あなたの現在の得点は' . $this->player->getTotalScore() . 'です。' . PHP_EOL;
        echo '合計値が21を超えたので、あなたの負けです。' . PHP_EOL;

    }

    private function dealerBurstMessage(): void
    {
        echo 'ディーラーの得点は' . $this->dealer->getTotalScore() . 'です。' . PHP_EOL;
        echo '合計値が21を超えたので、ディーラーはバーストしました。' . PHP_EOL;
        echo 'あなたの勝ちです。' . PHP_EOL;
        exit;
    }

    private function currentMessage(): void
    {
        $hand = $this->player->getHand();
        $cardDrawn = end($hand);
        echo 'あなたの引いたカードは' . $cardDrawn['suit'] . 'の' . $cardDrawn['num'] . 'です。' . PHP_EOL;
        echo 'あなたの現在の得点は' . $this->player->getTotalScore() . 'です。カードを引きますか？(Y/N)' . PHP_EOL;
    }

    private function standMessage(): void
    {
        $dealerHand = $this->dealer->getHand();
        $dealerSecondCard = end($dealerHand);
        echo 'ディーラーの引いた2枚目のカードは' . $dealerSecondCard['suit'] . 'の' . $dealerSecondCard['num'] . 'でした。' . PHP_EOL;
        echo 'ディーラーの得点は' . $this->dealer->getTotalScore() . 'です。' . PHP_EOL;
    }

    private function dealerDrawnCard(): void
    {
        $dealerHand = $this->dealer->getHand();
        $cardDrawnByDealer = array_slice($dealerHand, 2);
        foreach ($cardDrawnByDealer as $card)
        {
            echo 'ディーラーの引いたカードは' . $card['suit'] . 'の' . $card['num'] . 'です。' . PHP_EOL;

        }
    }

    private function resultMessage(): void
    {
        echo 'あなたの得点は' . $this->player->getTotalScore() . 'です。' . PHP_EOL;
        echo 'ディーラーの得点は' . $this->dealer->getTotalScore() . 'です。' . PHP_EOL;

        if($this->player->getStatus() === 'win')
        {
            echo 'あなたの勝ちです。' . PHP_EOL;
        } elseif ($this->player->getStatus() === 'lose') {
            echo 'あなたの負けです。' . PHP_EOL;
        } elseif ($this->player->getStatus() === 'draw') {
            echo '引き分けです。' . PHP_EOL;
        }
        echo 'ブラックジャックを終了します' . PHP_EOL;
    }


}
