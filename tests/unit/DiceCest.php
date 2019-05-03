<?php

use App\Dice;

class DiceCest
{
    /** @var Dice */
    private $dice;

    public function _before(): void
    {
        $this->dice = new Dice();
    }

    /**
     * @param UnitTester $I
     *
     * @throws Exception
     */
    public function testRoll(UnitTester $I): void
    {
        $score = $this->dice->roll();

        $I->assertInternalType('int', $score);
        $I->assertGreaterThanOrEqual(1, $score);
        $I->assertLessThanOrEqual(6, $score);
    }
}
