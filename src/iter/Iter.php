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
use Traversable;
use yuyaprgrm\enhancedphp\iter\internal\Filter;
use yuyaprgrm\enhancedphp\iter\internal\Map;

/**
 * Immutable iterator interface
 *
 * This interface is largely influenced by Rust iter trait.
 * 
 * @template TKey
 * @template TValue
 */
final class Iter{

    /**
     * @phpstan-param iterable<TKey, TValue> $elements
     */
    private function __construct(
        private iterable $elements
    ){
    }

    /**
     * Create new Iter
     *
     * @template UKey
     * @template UValue
     * @param iterable<UKey, UValue> $elements
     * @phpstan-return self<UKey, UValue>
     */
    public static function create(iterable $elements) : self{
        return new self($elements);
    }

    /**
     * Filter elements in the iteratir with callback.
     *
     * @phpstan-param Closure(TValue) : bool $callback
     * @return self<TKey, TValue>
     */
    public function filter(Closure $callback) : self{
        return new self(self::internalFilter($this->elements, $callback));
    }

    /**
     * @param iterable<TKey, TValue> $i
     * @phpstan-param Closure(TValue) : bool $callback
     * @return iterable<TKey, TValue>
     */
    private static function internalFilter(iterable $i, Closure $callback) : iterable{
        foreach($i as $k => $v){
            if($callback($v)){
                yield $k => $v;
            }
        }
    }

    /**
     * Map elements in the iterable to new elements with callback.
     *
     * @template UValue
     * @phpstan-param Closure(TValue) : UValue $callback
     * @return self<TKey, UValue>
     */
    public function map(Closure $callback) : self{
        return new self(self::internalMap($this->elements, $callback));
    }

    /**
     * @template UValue
     * @param iterable<TKey, TValue> $i
     * @phpstan-param Closure(TValue) : UValue $callback
     * @return iterable<TKey, UValue>
     */
    private static function internalMap(iterable $i, Closure $callback) : iterable{
        foreach($i as $k => $v){
            yield $k => $callback($v);
        }
    }

    /**
     * Skip elements until `$n` elements skipped or the end is reached.
     * 
     * @return self<TKey, TValue>
     */
    public function skip(int $n) : self{
        return new self(self::internalSkip($this->elements, $n));
    }

    /**
     * @param iterable<TKey, TValue> $i
     * @return iterable<TKey, TValue>
     */
    private static function internalSkip(iterable $i, int $n) : iterable{
        $j = 0;
        foreach($i as $k => $v){
            if($j < $n){
                $j++;
                continue;
            }
            yield $k => $v;
        }
    }

    /**
     * Skip elements until `$n` is false or the end is reached.
     * 
     * @phpstan-param Closure(TValue) : bool $callback
     * @return self<TKey, TValue>
     */
    public function skipWhile(Closure $callback) : self{
        return new self(self::internalSkipWhile($this->elements, $callback));
    }

    /**
     * @param iterable<TKey, TValue> $i
     * @return iterable<TKey, TValue>
     */
    private static function internalSkipWhile(iterable $i, Closure $callback) : iterable{
        $flagForAlreadyFalse = false;
        foreach($i as $k => $v){
            if(!$flagForAlreadyFalse && $callback($v)){
                $flagForAlreadyFalse = true;
            }
            if($flagForAlreadyFalse){
                yield $k => $v;
            }
        }
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
     * @return Traversable<TKey, TValue>)
     */
    public function native() : Traversable{
        foreach($this->elements as $k => $v){
            yield $k => $v;
        }
    }

}
