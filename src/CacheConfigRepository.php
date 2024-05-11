<?php

namespace Woox\WebmanCache;

use Illuminate\Config\Repository;
use support\Container;

final class CacheConfigRepository extends Repository
{
    public function __construct()
    {
        parent::__construct([
            'cache' => config('plugin.woox.webman-cache.cache', []),
            'rate_limiter' => config('plugin.woox.webman-cache.rate_limiter', [])
        ]);
    }

    public static function instance(): self
    {
        return Container::get(self::class);
    }
}
