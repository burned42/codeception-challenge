<?php

namespace App;

use Exception;
use InvalidArgumentException;

class DiceGame implements DiceGameInterface
{
    private $dice;

    /**
     * @param DiceInterface[] $dice
     */
    public function __construct(array $dice)
    {
        // make sure we indeed got 6 dice
        if (count($dice) !== 6) {
            throw new InvalidArgumentException('please provide 6 dice');
        }

        // and that those are really Dice objects
        foreach ($dice as $die) {
            if (!($die instanceof DiceInterface)) {
                throw new InvalidArgumentException('array elements should be of type '.DiceInterface::class);
            }
        }

        $this->dice = $dice;
    }

    /**
     * @throws Exception
     */
    public function score(): int
    {
        // roll the dice
        $scores = [];
        foreach ($this->dice as $die) {
            $scores[] = $die->roll();
        }

        $scores = array_count_values($scores);
        $scoresBackup = $scores;

        $result = 0;

        $threeOrMore = array_filter($scores, static function ($count) {
            return $count >= 3;
        }, ARRAY_FILTER_USE_BOTH);
        $rest = array_diff($scores, $threeOrMore);

        // calculate score for three or more of a kind
        $result += $this->getScoreForThreeOrMoreOfAKind($threeOrMore);

        // if there are any single ones of fives left, add the according score
        $result += $this->getScoreForSingleOnes($rest);
        $result += $this->getScoreForSingleFives($rest);

        // check for three pairs and if that would give a higher score than what's previously calculated
        if (800 > $result && $this->isThreePairs($scoresBackup)) {
            $result = 800;
        }

        // check for a straight and if that would give a higher score than what's previously calculated
        if (1200 > $result && $this->isStraight($scoresBackup)) {
            $result = 1200;
        }

        return $result;
    }

    private function isStraight(array $scores): bool
    {
        for ($i = 1; $i < 7; $i++) {
            if (!array_key_exists($i, $scores) || $scores[$i] !== 1) {
                return false;
            }
        }

        return true;
    }

    private function isThreePairs(array $scores): bool
    {
        $pairCount = 0;
        array_walk($scores, static function ($count) use (&$pairCount) {
            $pairCount += (int)floor($count / 2);
        });

        return $pairCount === 3;
    }

    private function getScoreForThreeOrMoreOfAKind(array $scores): int
    {
        $result = 0;
        foreach ($scores as $number => $count) {
            if ($count >= 3) {
                if ($number === 1) {
                    $score = 1000;
                } else {
                    $score = $number * 100;
                }

                $score *= 2 ** ($count - 3);

                $result += $score;
            }
        }

        return $result;
    }

    private function getScoreForSingleOnes(array $scores): int
    {
        if (array_key_exists(1, $scores)) {
            return $scores[1] * 100;
        }

        return 0;
    }

    private function getScoreForSingleFives(array $scores): int
    {
        if (array_key_exists(5, $scores)) {
            return $scores[5] * 50;
        }

        return 0;
    }
}
