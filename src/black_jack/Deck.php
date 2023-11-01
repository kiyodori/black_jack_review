<?php

namespace BlackJack;

require_once('Card.php');

class Deck
{
    // カードの束を保存する
    private array $cardStock;
    // 数字・アルファベット部分
    public const CARD_NUM = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

    // デッキ呼び出し時に、カードを生成してシャッフルしておく
    public function __construct()
    {
        $cardStock = [];
        // 52枚のカードの束を生成
        foreach (['クラブ', 'ハート', 'スペード', 'ダイヤ'] as $mark) {
            foreach ($this::CARD_NUM as $cardNum) {
                // [[new Card('クラブ', 'A')], [new Card('クラブ', '2')],...]
                $cardStock[] = new Card($mark, $cardNum);
            }
        }

        // カードをシャッフルしてストックに格納
        shuffle($cardStock);
        $this->cardStock = $cardStock;
    }

    // $cardStock からカード（インスタンス）を１枚引いて返す
    public function drawCard(): object
    {
        // $cardStock 配列の先頭を切り出して返す
        return array_shift($this->cardStock);
    }
}
