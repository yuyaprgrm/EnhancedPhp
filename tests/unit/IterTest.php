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
use yuyaprgrm\enhancedphp\std\Iter;

final class IterTest extends TestCase{
    public function testMap() : void{
        $case = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $expected = [1, 4, 9, 16, 25, 36, 49, 64, 81, 100];
        $actual = Iter::create($case)
            ->map(fn(int $v) : int => $v ** 2)
            ->native();
        $this->assertSame($expected, $actual);
    }

    public function testFilter() : void{
        $case = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $expected = [1, 3, 5, 7, 9];

        $actual = Iter::create($case)
            ->filter(fn(int $v) : bool => ($v % 2 == 1))
            ->native();
        $this->assertSame($expected, $actual);
    }

    public function testComplex() : void{
        $case = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $expected = [1, 9, 25, 49, 81];

        $actual = Iter::create($case)
            ->filter(fn(int $v) : bool => ($v % 2 == 1))
            ->map(fn(int $v) : int => $v ** 2)
            ->native();
        $this->assertSame($expected, $actual);

        $case = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $expected = [2, 4, 6, 8, 10];

        $actual = Iter::create($case)
            ->filter(fn(int $v) : bool => ($v % 2 == 1))
            ->map(fn(int $v) : int => $v + 1)
            ->native();
        $this->assertSame($expected, $actual);
    }

    public function testSkip() : void{
        $case = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $expected = [6, 7, 8, 9, 10];

        $actual = Iter::create($case)
            ->skip(5)
            ->native();
        $this->assertSame($expected, $actual);
    }

    public function testSkipWhile() : void{
        $case = [1, 2, 3, 4, 5, -1, 7, 8, 9, 10];
        $expected = [-1, 7, 8, 9, 10];

        $actual = Iter::create($case)
            ->skipWhile(fn(int $v) : bool => $v > 0)
            ->native();
        $this->assertSame($expected, $actual);
    }

    public function restRev() : void{
        $case = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $expected = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

        $actual = Iter::create($case)
            ->rev()
            ->native();
        $this->assertSame($expected, $actual);
    }

    public function testAll() : void{
        $case = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

        $this->assertTrue(Iter::create($case)->all(fn(int $v) => $v > 0));
        $this->assertFalse(Iter::create($case)->all(fn(int $v) => $v > 1));
    }

    public function testAny() : void{
        $case = [1, 3, 5, 7, 9, 11, 13, 14, 15, 17];

        $this->assertTrue(Iter::create($case)->any(fn(int $v) => $v % 2 == 0));
        $this->assertFalse(Iter::create($case)->any(fn(int $v) => $v % 4 == 0));
    }
}
