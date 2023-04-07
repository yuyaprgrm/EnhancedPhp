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

namespace yuyaprgrm\enhancedphp\iter;

use Closure;
use Iterator;
use yuyaprgrm\enhancedphp\iter\internal\Filter;
use yuyaprgrm\enhancedphp\iter\internal\Map;
use function array_map;
use function array_reverse;
use function count;

/**
 * Immutable iterator interface
 *
 * This interface is largely influenced by Rust iter trait.
 *
 * @template TValue
 */
final class Iter{

    /**
     * @phpstan-param list<TValue> $elements
     */
    private function __construct(
        private array $elements
    ){
    }

    /**
     * Create new Iter
     *
     * @template UValue
     * @param list<UValue> $elements
     * @phpstan-return self<UValue>
     */
    public static function create(array $elements) : self{
        return new self($elements);
    }

    /**
     * Filter elements in the iteratir with callback.
     *
     * @phpstan-param Closure(TValue) : bool $callback
     * @return self<TValue>
     */
    public function filter(Closure $callback) : self{
        return new self(self::internalFilter($this->elements, $callback));
    }

    /**
     * @param list<TValue> $c
     * @phpstan-param Closure(TValue) : bool $callback
     * @return list<TValue>
     */
    private static function internalFilter(array $c, Closure $callback) : array{
        $d = [];
        foreach($c as $v){
            if($callback($v)){
                $d[] = $v;
            }
        }
        return $d;
    }

    /**
     * Map elements in the iterable to new elements with callback.
     *
     * @template UValue
     * @phpstan-param Closure(TValue) : UValue $callback
     * @return self<UValue>
     */
    public function map(Closure $callback) : self{
        return new self(self::internalMap($this->elements, $callback));
    }

    /**
     * @template UValue
     * @param list<TValue> $c
     * @phpstan-param Closure(TValue) : UValue $callback
     * @return list<UValue>
     */
    private static function internalMap(array $c, Closure $callback) : array{
        return array_map($callback, $c);
    }

    /**
     * Skip elements until `$n` elements skipped or the end is reached.
     *
     * @return self<TValue>
     */
    public function skip(int $n) : self{
        return new self(self::internalSkip($this->elements, $n));
    }

    /**
     * @param list<TValue> $c
     * @return list<TValue>
     */
    private static function internalSkip(array $c, int $n) : array{
        $d = [];
        for($i = $n, $s = count($c); $i < $s; $i++){
            $d[] = $c[$i];
        }
        return $d;
    }

    /**
     * Skip elements while callback is true, or the end is reached.
     *
     * @phpstan-param Closure(TValue) : bool $callback
     * @return self<TValue>
     */
    public function skipWhile(Closure $callback) : self{
        return new self(self::internalSkipWhile($this->elements, $callback));
    }

    /**
     * @param list<TValue> $c
     * @phpstan-param Closure(TValue) : bool $callback
     * @return list<TValue>
     */
    private static function internalSkipWhile(array $c, Closure $callback) : array{
        $d = [];
        for($i = 0, $s = count($c); $i < $s; $i++){
            if(!$callback($c[$i])){
                $d[] = $c[$i];
                break;
            }
        }

        for($i = $i + 1; $i < $s; $i++){
            $d[] = $c[$i];
        }
        return $d;
    }

    /**
     * Reverse iterator
     *
     * @return self<TValue>
     */
    public function rev() : self{
        return new self(self::internalRev($this->elements));
    }

    /**
     * @param list<TValue> $i
     * @return list<TValue>
     */
    private static function internalRev(array $i) : array{
        return array_reverse($i);
    }

    /**
     * Test if every elements in the itarator matches a condition given by callback.
     *
     * @phpstan-param Closure(TValue) : bool $callback
     */
    public function all(Closure $callback) : bool{
        foreach($this->elements as $v){
            if(!$callback($v)){
                return false;
            }
        }

        return true;
    }

    /**
     * Test if  elements in the itarator matches a condition given by callback.
     *
     * @phpstan-param Closure(TValue) : bool $callback
     */
    public function any(Closure $callback) : bool{
        foreach($this->elements as $v){
            if($callback($v)){
                return true;
            }
        }

        return false;
    }

    /**
     * Transform an iterable into native iterator.
     *
     * @return list<TValue>
     */
    public function native() : array{
        return $this->elements;
    }

}
