<?php

namespace BlackJack;

require_once('Game.php');
require_once('UserPlayer.php');
require_once('AutoPlayer.php');
require_once('Dealer.php');
require_once('Deck.php');
require_once('Judge.php');


// ディーラーを生成する
$dealer = new Dealer();
// デッキを生成するY
$deck = new Deck();
// 判定係を生成する
$judge = new Judge();
// プレイヤーを生成する
$userPlayer = new UserPlayer();

// 上記インスタンスを渡してゲームを開始する
$game = new Game($deck, $judge);

// ゲームスタートし、オートプレイヤーを生成
$autoPlayers = $game->start();

// 最初のカードが配られる
$game->prepare($userPlayer, $autoPlayers, $dealer);

// プレイヤーのターンを実行し、21 を超えていないプレイヤーを取得
$under21Players = $game->playerTurn($userPlayer, $autoPlayers, $judge);

// 得点が 21 を超えていないプレイヤーがいれば
if ($under21Players) {
    // ディーラーのターン
    $game->dealerTurn($dealer);
}

// 結果発表
$game->showResult($under21Players, $dealer);
