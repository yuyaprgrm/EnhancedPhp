<?php declare(strict_types=1);

namespace yuyaprgrm\enhancedphp\iter\internal;

use Closure;

/** 
 * @internal
 */
final class Map{
    
    public function __construct(
        private Closure $callback
    ){        
    }

    public function execute(mixed $v) : mixed{
        return ($this->callback)($v);
    }
}