<?php

namespace App;

use Exception;

class Dice implements DiceInterface
{
    /**
     * @return int
     *
     * @throws Exception
     */
    public function roll(): int
    {
        return random_int(1, 6);
    }
}
