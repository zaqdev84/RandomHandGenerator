<?php

namespace Shuffler;

class Deck implements \IteratorAggregate, \ArrayAccess, \JsonSerializable {

    private $deck;
    private $randomKeys;
    public $cardSet = ['A' => 13, '2' => 1, '3' => 2, '4' => 3, '5' => 4, '6' => 5, '7' => 6, '8' => 7, '9' => 8, '10' => 9, 'J' => 10, 'Q' => 11, 'K' => 12];

    /**
     * Creates a new, unshuffled deck of cards, where the suits are in the order
     * of d = diamonds, h = hearts, c = clubs, s = spades, and each suit is ordered A, 2 .. 10,
     * J, Q, K.
     *
     * @param array $deck [optional] The deck of cards to be used.
     * @throws InvalidArgumentException if the any of the elements in $deck are not type Card.
     */
    public function __construct(array $deck = null) {
        if (isset($deck) && count($deck) > 0) {
            foreach ($deck as $card) {
                if (!($card instanceof Card)) {
                    throw new InvalidArgumentException(
                            'The first parameter to Deck::__construct must be an array'
                            . ' containing only objects of type Card'
                    );
                }
            }
            $this->deck = $deck;
        } else {
            $this->deck = $this->createDeck();
        }
    }

    /**
     * Shuffle an array.  Uses PHP's shuffle if no function is provided. If a
     * function is provided, it must take an array of Cards as its only
     * parameter.
     * @param callable $function If $function isn't callable, shuffle will be used instead
     * @return mixed Returns the result of the shuffle function.
     */
    public function shuffle($function = null) {
        if (is_callable($function, false, $callable_name)) {
            return $callable_name($this->deck);
        } else {
            return shuffle($this->deck);
        }
    }

    /**
     * Returns an array of random number of cards from shuffled deck
     * @param int $numberOfCards
     * @return array
     */
    public function getRandomCards($numberOfCards) {
        for ($i = 0; $i < $numberOfCards; $i++) {
            $random[] = $this->deck[$i]->number() . $this->deck[$i]->suit();
            $this->randomKeys[] = $i;
        }
        return $random;
    }

    /**
     * Returns randomized cards with ranking
     * @return array
     */
    public function getRandomRankedCards() {
        foreach ($this->randomKeys as $key) {
            $random[$this->deck[$key]->number() . $this->deck[$key]->suit()] = $this->deck[$key]->rank();
        }
        asort($random);
        return $random;
    }
    
    /**
     * Extracts data from manual array input
     * @param array $cards
     * @return array
     */
    private function extractData(array $cards): array {
        $arr = [];
        foreach ($cards as $key => $card) {
            $data = $this->sanitizeData(str_split($card));
            if ($data) {
                $arr[$card] = $data;
            } else {
                die('Invalid Data');
            }
        }
        return $arr;
    }
    
    /**
     * Sanitizes data and return array with suite and ranking of input array
     * @param array $data
     * @return array
     */
    private function sanitizeData(array $data): array {
        if (count($data) > 3 || count($data) < 2 || intval($data[0] . $data[1]) > 13) {
            return false;
        } else if (count($data) == 2) {
            return array('rank' => $this->cardSet[$data[0]], 'suite' => $data[1]);
        } else {
            return array('rank' => $this->cardSet[$data[0] . $data[1]], 'suite' => $data[2]);
        }
    }
    /**
     * Extracts ranking of cards of given array
     * @param array $cards
     * @return array
     */
    private function extractRanking(array $cards): array {
        $arr = [];
        foreach ($cards as $card) {
            $arr[] = $card['rank'];
        }
        sort($arr);
        return $arr;
    }

    /**
     * Verifies if the set of Array provided is a straight or not.
     * @param array $cards
     * @return boolean
     */
    public function isStraight(array $cards) {
        $cards_data = $this->extractData($cards);
        $ranks = $this->extractRanking($cards_data);        
        if (in_array(13, $ranks)) {
            $testRank = $ranks[0] + 1;            
            for ($i = 1; $i < count($ranks) - 1; $i++) {
                if ($ranks[$i] != $testRank) {
                    return false;
                }
                $testRank++;
            }
            return ($testRank-- == $ranks[count($ranks) - 1]) ? true : ($ranks[0] == 1) ? true : false;
        } else {
            $testRank = $ranks[0] + 1;
            for ($i = 1; $i < count($ranks); $i++) {
                if ($ranks[$i] != $testRank) {
                    return false;
                }
                $testRank++;
            }
        }
        return true;
    }
    
    /**
     * Verifies if the set of Array provided is a flush or not.
     * @param array $cards
     * @return bool
     */
    public function isFlush(array $cards): bool {
        $cards_data = $this->extractData($cards);

        $arr = [];
        foreach ($cards_data as $card) {
            $arr[] = $card['suite'];
        }
        $suite_count = array_count_values($arr);
        return (count($suite_count) == 1) ? true : false;
    }
    
    
    /**
     * Verifies if the set of Array provided is a flush and a straight or not.
     * @param array $cards
     * @return bool
     */
    public function isStraightFlush(array $cards): bool {
        return ($this->isFlush($cards) && $this->isStraight($cards)) ? true : false;
    }

    /**
     * Used by IteratorAggregate to loop over the object.
     * @return ArrayIterator
     */
    public function getIterator() {
        return new \ArrayIterator($this->deck);
    }

    /**
     * @param string $suit The suite to create.
     * @return array The cards for the suit.
     */
    private function createSuit($suit) {
        $setOfCards = [];
        foreach ($this->cardSet as $card => $rank) {
            $setOfCards[] = new Card($suit, $card, $rank);
        }
        return $setOfCards;
    }

    /**
     * Returns unshuffled array of cards, where the suits are in the
     * order of d, h, c, s, and each suit is ordered:
     * A, 2 .. 10, J, Q, K.
     * @return array An array of type Card.
     */
    private function createDeck() {
        return array_merge(
                $this->createSuit('d'),
                $this->createSuit('h'),
                $this->createSuit('c'),
                $this->createSuit('s')
        );
    }

    /**
     * Resets the deck to an unshuffled order, and returns the deck.
     * @return \Deck
     */
    public function reset() {
        $this->deck = $this->createDeck();
        return $this;
    }

    /**
     * Returns the data that should be encoded into JSON. 
     *
     * @return mixed Return data which should be serialized by json_encode().
     */
    public function jsonSerialize() {
        $array = $this->deck;

        foreach ($array as &$card) {
            /**
             * @var Card $card
             */
            $card = $card->jsonSerialize();
        }

        return $array;
    }

    /**
     * Used by ArrayAccess.  Determine whether an offset(index) exists.
     * @param int $index The index to test for existence.
     * @return boolean Returns true of the offset exists.
     */
    public function offsetExists($index) {
        return array_key_exists($index, $this->deck);
    }

    /**
     * Used by ArrayAccess.  Returns an item from the index provided.
     * @param int $index The index to get..
     * @return boolean Returns the object at the location.
     * @throws OutOfBoundsException if you specify an index that does not exist.
     */
    public function offsetGet($index) {
        if (!$this->offsetExists($index)) {
            throw new OutOfBoundsException(
                    "The index '$index' does not exist."
            );
        }
        return $this->deck[$index];
    }

    /**
     * Used by ArrayAccess. Sets an index with the value, or adds a value if it
     * is null.
     * @param int|null $index The index to set, or null to add.
     * @param Card $value The card to set/add.
     * @return void
     * @throws InvalidArgumentException if the value provided is not a Card.
     * @throws InvalidArgumentException if the index provided is not an integer.
     * @throws OutOfBoundsException if the index provided does not exist.
     */
    public function offsetSet($index, $value) {
        if (!($value instanceof Card))
            throw new InvalidArgumentException('Decks only contain cards.');

        if ($index == null) {
            $this->deck[] = $value;
            return;
        }

        if (!is_numeric($index) || $index != (int) $index) {
            throw new InvalidArgumentException("Index '$index' must be an integer.");
        }

        if (!$this->offsetExists($index)) {
            throw new OutOfBoundsException("Index '$index' does not exist");
        }

        $this->deck[$index] = $value;
    }

    /**
     * Unsets the index location.
     * @param int $index
     * @return void
     * @throws InvalidArgumentException if the index provided does not exist.
     */
    public function offsetUnset($index) {
        if (!$this->offsetExists($index)) {
            throw new InvalidArgumentException("Index '$index' Does not exist.");
        }

        array_splice($this->deck, $index, 1);
    }

    /**
     * Returns a string depicting the card. Although it's json_encoded, don't
     * rely on that fact.  PHP 5.4 introduces the JsonSerializeable interface,
     * which should be used to json_encode an object.
     *
     * @return string The Card as a string.
     */
    public function __toString() {
        return json_encode($this->jsonSerialize());
    }

    /**
     * Used by interface Count.
     * @return int The size of the deck.
     */
    function count() {
        return count($this->deck);
    }

}
