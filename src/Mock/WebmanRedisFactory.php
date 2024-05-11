<?php

namespace Woox\WebmanCache\Mock;

use Illuminate\Contracts\Redis\Factory;
use support\Redis;

final class WebmanRedisFactory implements Factory
{
    /**
     * @inheritDoc
     */
    public function connection($name = null): \Illuminate\Redis\Connections\Connection
    {
        return Redis::connection($name);
    }
}
