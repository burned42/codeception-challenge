<?php

namespace App;

interface DiceGameInterface
{
    /**
     * @param DiceInterface[] $dice
     */
    public function __construct(array $dice);

    public function score(): int;
}