<?php

namespace Shuffler;

class Card implements \JsonSerializable {

    /**
     * @var string The suit for the card
     */
    private $suit;

    /**
     * @var string The 'number' of the card.  A bit of an omission, A, J, Q, K can be included.
     */
    private $number;
    
    /**
     * @var int The ranking of the card.
     */
    private $rank;
    

    /**
     * Creates a new cards of suit $suit with number $number.
     * @param string $suit
     * @param string $number
     * @throws InvalidArgumentException if $suit is not a string.
     * @throws InvalidArgumentException if $number is not a string or an int.
     *
     * @todo More comprehensive checks to make sure each suit as number is valid.
     */
    public function __construct($suit, $number, $rank) {
        if (!is_string($suit)) {
            throw new InvalidArgumentException(
                    'First parameter to Card::__construct() must be a string.'
            );
        }

        if (!is_string($number) && !filter_var($number, FILTER_VALIDATE_INT)) {
            throw new InvalidArgumentException(
                    'Second parameter to Card::__construct() must be a string or an int.'
            );
        }
        
        if (!is_int($rank) && !filter_var($rank, FILTER_VALIDATE_INT)) {
            throw new InvalidArgumentException(
                    'Third parameter to Card::__construct() must be an int.'
            );
        }
        
        
        $this->suit = $suit;
        $this->number = $number;
        $this->rank = $rank;
    }

    /**
     * @return string The suit for the card;
     */
    public function suit() {
        return $this->suit;
    }

    /**
     * @return string The number for the card;
     */
    public function number() {
        return $this->number;
    }
    
    /**
     * @return int The rank for the card;
     */
    public function rank() {
        return $this->rank;
    }

    /**
     * Returns a string depicting the card. Although it's json_encoded, don't
     * rely on that fact.  
     *
     * @return string The Card as a string.
     */
    public function __toString() {
        return json_encode($this->jsonSerialize());
    }

    /**
     * Returns the data that should be encoded into JSON.
     * @return array Return data which should be serialized by json_encode().
     */
    public function jsonSerialize() {
        return get_object_vars($this);
    }

}
