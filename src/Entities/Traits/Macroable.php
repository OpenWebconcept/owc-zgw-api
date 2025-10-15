<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Traits;

use Closure;
use BadMethodCallException;

trait Macroable
{
    /**
     * @var array<string, \Closure>
     */
    protected static array $macros = [];

    public static function macro(string $name, Closure $macro): void
    {
        static::$macros[$name] = $macro;
    }

    public static function hasMacro(string $name): bool
    {
        return isset(static::$macros[$name]);
    }

    public static function flushMacros(): void
    {
        static::$macros = [];
    }

    /**
     * @param array<mixed> $parameters
     */
    public static function __callStatic(string $method, array $parameters): mixed
    {
        if (! static::hasMacro($method)) {
            throw new BadMethodCallException(sprintf(
                'Method %s::%s does not exist',
                static::class,
                $method
            ));
        }

        $macro = static::$macros[$method];
        $macro = $macro->bindTo(null, static::class);

        return $macro(...$parameters);
    }

    /**
     * @param array<mixed> $parameters
     */
    public function __call(string $method, array $parameters): mixed
    {
        if (! static::hasMacro($method)) {
            throw new BadMethodCallException(sprintf(
                'Method %s::%s does not exist',
                static::class,
                $method
            ));
        }

        $macro = static::$macros[$method];

        if ($macro instanceof Closure) {
            $macro = $macro->bindTo($this, static::class);
        }

        return $macro(...$parameters);
    }
}
