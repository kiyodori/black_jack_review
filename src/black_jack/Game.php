<?php

namespace BlackJack;

require_once('Player.php');
require_once('Dealer.php');
require_once('Deck.php');
require_once('Judge.php');

// ゲーム全体の進行を扱う
class Game
{
    //プレイヤー、ディーラー、デッキのインスタンスを受け取りゲームの準備
    public function __construct(public Deck $deck, public Judge $judge)
    {
    }

    // ゲームスタートし、オートプレイヤーを生成
    public function start(): array
    {
        // ゲーム開始の合図
        echo "ブラックジャックを開始します。" . PHP_EOL;
        echo "同時に対戦するプレイヤーの人数を 0 から 2 の間で入力してください。" . PHP_EOL;
        $autoPlayerNum = trim(fgets(STDIN));

        // オートプレイヤーたちをいれる変数を準備
        $autoPlayers = [];

        // 指定された人数のオートプレイヤーを生成
        for ($i = 1; $i <= $autoPlayerNum; $i++) {
            // オートプレイヤー生成
            $autoPlayer = new AutoPlayer();
            // オートプレイヤー番号を登録
            $autoPlayer->playerNum = $i;
            // プレイヤー名を "プレイヤー1" の様に番号を付けて上書き
            $autoPlayer->playerName = $autoPlayer->playerName . $autoPlayer->playerNum;
            // まとめる
            $autoPlayers[] = $autoPlayer;
        }

        // オートプレイヤーの配列を返す
        return $autoPlayers;
    }

    // 最初のカードが配られる
    public function prepare(UserPlayer $userPlayer, array $autoPlayers, Dealer $dealer)
    {
        // 参加メンバーをまとめる
        $players = array_merge([$userPlayer], $autoPlayers, [$dealer]);

        // 表示用の情報を入れる箱
        $allInfo = [];

        // 各メンバーがそれぞれカードを２枚ずつ引く
        foreach ($players as $player) {
            for ($i = 1; $i <= 2; $i++) {
                // カードインスタンスを引く
                $card = $player->drawCard($this->deck);
                // カードの情報を取得 [$mark,$numAl]
                $cardInfo = $card->getCardInfo();
                // ユーザーの手持ちカードに加える [[$mark,$numAl].[$mark,$numAl],...]
                $player->drawnCards[] = $cardInfo;
                // 表示用の情報をまとめる [[$user,$mark,$numAl],[user,$mark,$numAl]]
                $allInfo[] = array_merge([$player->playerName], $cardInfo);
            }
        }

        // 配列の最後の要素を削除し、ディーラーの２枚目の情報を削除
        array_pop($allInfo);

        // 各プレイヤーの２枚のカード、ディーラーの１枚目のカードの開示
        foreach ($allInfo as $info) {
            $this->showCard($info);
        }

        echo "ディーラーの引いた2枚目のカードはわかりません。" . PHP_EOL;
    }

    public function showCard(array $info): void
    {
        echo "{$info[0]}の引いたカードは{$info[1]}の{$info[2]}です。" . PHP_EOL;
    }


    // プレイヤーターン。スコアを確認し、続けるか決める
    public function playerTurn(UserPlayer $userPlayer, array $autoPlayers, Judge $judge): array
    {
        // プレイヤーをまとめる
        $players = array_merge([$userPlayer], $autoPlayers);

        // もう一枚引くプレイヤーの配列
        $continuePlayers = $players;
        echo "-------------------------------------------" . PHP_EOL;
        echo "[ 現在の得点 ]" . PHP_EOL;
        // プレイヤーそれぞれの得点を表示
        foreach ($continuePlayers as $continuePlayer) {

            // 現時点でのスコアを取得する
            $continuePlayer->playerScore = $continuePlayer->getStore($this->judge);

            if ($continuePlayer->playerScore >= 21) {
                // 終了させる
                $judge->resultUnder21($continuePlayer);
                return false;
            }

            echo "{$continuePlayer->playerName}の現在の得点は{$continuePlayer->playerScore}です。" . PHP_EOL;
        }

        // もう一枚引くプレイヤーがいれば
        while (count($continuePlayers)) {
            // echo "-------------------------------------------" . PHP_EOL;

            // 各プレイヤー毎に実行
            foreach ($continuePlayers as $continuePlayer) {
                // プレイヤーの１ターンを実行し、21 を超えていないかを返す
                $under21 = $this->onePlayerTurn($continuePlayer, $judge);

                // 21 以上になっていたらプレイヤーから削除
                if (!$under21) {
                    unset($players[$continuePlayer->playerNum]);
                    unset($continuePlayers[$continuePlayer->playerNum]);
                }
            }

            foreach ($continuePlayers as $continuePlayer) {
                // カードを引かない場合
                if (!$continuePlayer->continue) {
                    // 続けてカードを引くプレイヤーリストから削除
                    unset($continuePlayers[$continuePlayer->playerNum]);
                }
            }
            if (count($continuePlayers) > 1) {
                // プレイヤーそれぞれの得点を表示
                echo "[ 現在の得点 ]";
                foreach ($continuePlayers as $continuePlayer) {

                    // 現時点でのスコアを取得する
                    $continuePlayer->playerScore = $continuePlayer->getStore($this->judge);

                    if ($continuePlayer->playerScore >= 21) {
                        // 終了させる
                        $judge->resultUnder21($continuePlayer);
                        return false;
                    }

                    echo "{$continuePlayer->playerName}の現在の得点は{$continuePlayer->playerScore}です。" . PHP_EOL;
                }
            }
        }

        // 21 以上で負けていない、残りのプレイヤーを返す
        return $players;
    }


    public function onePlayerTurn(Player $player, Judge $judge)
    {
        echo "-------------------------------------------" . PHP_EOL;

        // もう一枚引くかの分岐
        $player->selectContinue($player->playerName);

        if ($player->continue) {
            // カードインスタンスを引く
            $card = $player->drawCard($this->deck);
            // カードの情報を取得 [$mark,$numAl]
            $cardInfo = $card->getCardInfo();
            // ユーザーの手持ちカードに加える [[$mark,$numAl].[$mark,$numAl],...]
            $player->drawnCards[] = $cardInfo;
            // 表示用にユーザー名も加える
            $info = array_merge([$player->playerName], $cardInfo);
            // 取得結果を表示する
            $this->showCard($info);

            // 現時点でのスコアを取得し表示
            $player->playerScore = $player->getStore($this->judge);
            echo "{$player->playerName}の現在の得点は{$player->playerScore}です。" . PHP_EOL;

            if ($player->playerScore >= 21) {
                // 終了させる
                $judge->resultUnder21($player);
                return false;
            }
        }

        return true;
    }


    public function dealerTurn(Dealer $dealer): void
    {
        echo "-------------------------------------------" . PHP_EOL;
        // ディーラーの２枚目のカードを開示
        echo "ディーラーの引いた2枚目のカードは{$dealer->drawnCards[1][0]}の{$dealer->drawnCards[1][1]}でした。" . PHP_EOL;

        // 現時点でのスコアを取得する
        $dealer->playerScore = $dealer->getStore($this->judge);

        // もう一枚引くかの分岐
        $dealer->selectContinue($dealer->playerName);

        while ($dealer->continue) {
            echo "ディーラーのの現在の得点は{$dealer->playerScore}です。" . PHP_EOL;

            // カードインスタンスを引く
            $card = $dealer->drawCard($this->deck);
            // カードの情報を取得 [$mark,$numAl]
            $cardInfo = $card->getCardInfo();

            // ユーザーの手持ちカードに加える [[$mark,$numAl].[$mark,$numAl],...]
            $dealer->drawnCards[] = $cardInfo;
            // 表示用にユーザー名も加える
            $info = array_merge([$dealer->playerName], $cardInfo);

            // 取得結果を表示する
            $this->showCard($info);

            // 現時点でのスコアを取得する
            $dealer->playerScore = $dealer->getStore($this->judge);

            // 続けるかどうか
            $dealer->selectContinue($dealer->playerName);
        }
    }

    // 結果表示
    public function showResult(array $under21Players, Dealer $dealer): void
    {
        // 勝者の判定結果を出力
        $this->judge->judgeWinner($under21Players, $dealer);

        echo "ブラックジャックを終了します。" . PHP_EOL;
    }
}
