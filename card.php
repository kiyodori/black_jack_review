<?php
/**
 * @category Card_Games
 * @package  Blackjack
 * Represents a playing card.
 *
 * This class represents a playing card used in the game of Blackjack.
 */

namespace Blackjack;

class Card
{
    public $suit;
    public $rank;
    public $value;

    /**
     * Initializes a new card with suit, rank, and value.
     *
     * @param string $suit  The suit of the card.
     * @param string $rank  The rank of the card.
     * @param int    $value The value of the card.
     */
    public function __construct($suit, $rank, $value)
    {
        $this->suit = $suit;
        $this->rank = $rank;
        $this->value = $value;
    }
}
