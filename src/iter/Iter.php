<?php declare(strict_types=1);

namespace yuyaprgrm\enhancedphp\iter;

use Closure;
use Iterator;
use IteratorAggregate;
use Traversable;
use yuyaprgrm\enhancedphp\iter\internal\Map;
use yuyaprgrm\enhancedphp\iter\internal\Filter;
use yuyaprgrm\enhancedphp\iter\internal\ValueFilteredException;

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
     * @param iterable<TKey, mixed> $elements
     * @param list<Filter|Map> $processes
     */
    private function __construct(
        private iterable $elements,
        private array $processes
    ){
    }
    
    /**
     * Create new Iter
     * 
     * @template Key
     * @template Value
     * @param iterable<Key, Value> $elements
     * @return self<Key, Value>
     */
    public static function create(iterable $elements) : self{
        return new self($elements, []);
    }

    /**
     * Filter elements in the iteratir with callback.
     * 
     * @phpstan-param Closure(TValue) : bool $callback
     * @return self<TKey, TValue>
     */
    public function filter(Closure $callback) : self{
        return new self($this->elements, [...$this->processes, new Filter($callback)]);
    }

    /**
     * Map elements in the iterator to new elements with callback.
     * 
     * @template UValue
     * @phpstan-param Closure(TValue) : UValue $callback
     * @return self<TKey, UValue>
     */
    public function map(Closure $callback) : self{
        return new self($this->elements, [...$this->processes, new Map($callback)]);
    }

    /**
     * Test if every elements in the itarator matches a condition given by callback.
     * 
     * @phpstan-param Closure(TValue) : bool $callback
     */
    public function all(Closure $callback) : bool{
        foreach($this->elements as $v){
            try{    
                foreach($this->processes as $proc){
                    $v = $proc->execute($v);
                }
            }catch(ValueFilteredException){
                continue;
            }
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
            try{    
                foreach($this->processes as $proc){
                    $v = $proc->execute($v);
                }
            }catch(ValueFilteredException){
                continue;
            }
            if($callback($v)){
                return true;
            }
        }

        return false;
    }

    /**
     * Transform an iterator into native iterator.
     * 
     * @return iterable<TKey, TValue>
     */
    public function native() : iterable{
        foreach($this->elements as $k => $v){
            try{    
                foreach($this->processes as $proc){
                    $v = $proc->execute($v);
                }
            }catch(ValueFilteredException){
                continue;
            }

            /** @var TValue $v */
            yield $k => $v;
        }
    }

}