<?php

/*
 *
 * EnhancedPhp by yuyaprgrm
 *
 * @author yuyaprgrm
 * @link https://github.com/yuyaprgrm/EnhancedPhp
 *
 *
 */

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use yuyaprgrm\enhancedphp\iter\Iter;

final class IterTest extends TestCase{
    public function testMap() : void{
        $case = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $expected = [1, 4, 9, 16, 25, 36, 49, 64, 81, 100];

        foreach(
            Iter::create($case)
                ->map(fn(int $v) : int => $v ** 2)
                ->native()
            as $k => $v
        ){
            $this->assertSame($expected[$k], $v);
        }
    }

    public function testFilter() : void{
        $case = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $expected = [0 => 1, 2 => 3, 4 => 5, 6 => 7, 8 => 9];

        $actual = Iter::create($case)
            ->filter(fn(int $v) : bool => ($v % 2 == 1))
            ->native();
        $actual = iterator_to_array($actual);
        $this->assertSame($expected, $actual);
    }

    public function testComplex() : void{
        $case = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $expected = [0 => 1, 2 => 9, 4 => 25, 6 => 49, 8 => 81];

        $actual = Iter::create($case)
            ->filter(fn(int $v) : bool => ($v % 2 == 1))
            ->map(fn(int $v) : int => $v ** 2)
            ->native();
        $actual = iterator_to_array($actual);
        $this->assertSame($expected, $actual);

        $case = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $expected = [0 => 2, 2 => 4, 4 => 6, 6 => 8, 8 => 10];

        $actual = Iter::create($case)
            ->filter(fn(int $v) : bool => ($v % 2 == 1))
            ->map(fn(int $v) : int => $v + 1)
            ->native();
        $actual = iterator_to_array($actual);
        $this->assertSame($expected, $actual);
    }

    public function testAll() : void{
        $case = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

        $this->assertTrue(Iter::create($case)->all(fn(int $v) => $v > 0));
        $this->assertFalse(Iter::create($case)->all(fn(int $v) => $v > 1));
    }
}
