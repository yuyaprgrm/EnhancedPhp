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

namespace yuyaprgrm\enhancedphp\iter\internal;

use Closure;

/**
 * @internal
 */
final class Filter{

    public function __construct(
        private Closure $callback
    ){
    }

    /**
     * @throws ValueFilteredException
     */
    public function execute(mixed $v) : mixed{
        if(!(($this->callback)($v))){
            throw new ValueFilteredException();
        }
        return $v;
    }
}
