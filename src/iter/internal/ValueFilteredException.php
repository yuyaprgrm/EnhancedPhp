<?php declare(strict_types=1);

namespace yuyaprgrm\enhancedphp\iter\internal;

use Exception;

/**
 * @internal
 * 
 * The purpose of this exception is to notify Iter internal proccess that value was flagged for remove. 
 */
final class ValueFilteredException extends Exception{
}