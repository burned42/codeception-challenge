<?php

use App\Dice;
use App\DiceGame;
use Codeception\Example;
use Codeception\Stub\Expected;
use Codeception\Util\Stub;

class DiceGameCest
{
    /**
     * @param UnitTester $I
     *
     * @throws Exception
     */
    public function testConstructor(UnitTester $I): void
    {
        $dice = $this->generateDice(1, 2, 3, 4, 5, 6);

        $diceGame = new DiceGame($dice);

        $I->assertInstanceOf(DiceGame::class, $diceGame);
    }

    public function testExceptionOnInvalidConstructorParameter(UnitTester $I): void
    {
        $I->expectThrowable(
            new InvalidArgumentException('please provide 6 dice'),
            static function () {
                new DiceGame([]);
            }
        );
    }

    public function testExceptionIfNotAllConstructorParametersAreDice(UnitTester $I): void
    {
        $I->expectThrowable(
            new InvalidArgumentException('array elements should be of type '.Dice::class),
            static function () {
                new DiceGame([new Dice(), new Dice(), new Dice(), 1, 2, 3]);
            }
        );
    }

    /**
     * @param UnitTester $I
     *
     * @throws Exception
     */
    public function testEachDiceRolledOnce(UnitTester $I): void
    {
        $dice = $this->generateDice(1, 2, 3, 4, 5, 6);

        $diceGame = new DiceGame($dice);

        $diceGame->score();

        // The dice are generated with a constraint to be only rolled once to if we get here, everything is fine
        $I->assertTrue(true, 'Each die should only be rolled once');
    }

    /**
     * @param UnitTester $I
     * @param Example $example
     *
     * @throws Exception
     *
     * @dataProvider getTestData
     */
    public function testScoring(UnitTester $I, Example $example): void
    {
        $dice = $this->generateDice(...$example[1]);

        $diceGame = new DiceGame($dice);

        $I->assertEquals($example[0], $diceGame->score(), $example[2]);
    }

    /**
     * @param int[] $results
     *
     * @return array
     *
     * @throws Exception
     */
    protected function generateDice(int ...$results): array
    {
        $dice = [];
        foreach ($results as $result) {
            $dice[] = Stub::make(
                Dice::class,
                ['roll' => Expected::once($result)]
            );
        }

        return $dice;
    }

    protected function getTestData(): Generator
    {
        $msg = 'Test single ones';
        yield [100, [1, 2, 2, 3, 3, 4], $msg];
        yield [100, [2, 2, 1, 3, 3, 4], $msg];
        yield [100, [2, 2, 6, 3, 1, 4], $msg];
        yield [200, [1, 2, 6, 3, 1, 4], $msg];
        yield [200, [2, 2, 6, 3, 1, 1], $msg];
        yield [200, [1, 2, 6, 3, 4, 1], $msg];

        $msg = 'Test single fives';
        yield [50, [2, 3, 4, 5, 6, 6], $msg];
        yield [50, [2, 3, 4, 4, 6, 5], $msg];
        yield [50, [5, 3, 4, 4, 6, 6], $msg];
        yield [100, [5, 3, 4, 5, 6, 6], $msg];
        yield [100, [5, 3, 4, 4, 6, 5], $msg];

        $msg = 'Test single ones and fives';
        yield [150, [1, 2, 3, 4, 5, 4], $msg];
        yield [150, [1, 5, 2, 2, 3, 3], $msg];
        yield [150, [5, 2, 2, 3, 3, 1], $msg];
        yield [200, [5, 2, 5, 3, 3, 1], $msg];
        yield [250, [1, 2, 5, 3, 3, 1], $msg];
        yield [300, [1, 2, 5, 3, 5, 1], $msg];

        $msg = 'Test three or more of a kind';
        yield [1000, [1, 1, 1, 2, 3, 4], $msg];
        yield [200, [2, 2, 6, 2, 3, 4], $msg];
        yield [500, [5, 5, 3, 2, 5, 4], $msg];
        yield [600, [6, 3, 6, 2, 6, 4], $msg];
        yield [8000, [1, 1, 1, 1, 1, 1], $msg];
        yield [2400, [3, 3, 3, 3, 3, 3], $msg];

        $msg = 'Test three pairs';
        yield [800, [1, 1, 2, 2, 3, 3], $msg];
        yield [800, [4, 4, 5, 5, 6, 6], $msg];
        yield [800, [2, 2, 2, 2, 6, 6], $msg];

        $msg = 'Test straight';
        yield [1200, [1, 2, 3, 4, 5, 6], $msg];
        yield [1200, [4, 2, 3, 1, 6, 5], $msg];
    }
}
