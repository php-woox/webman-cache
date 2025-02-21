<?php

namespace Woox\WebmanCache\Middleware;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Redis\Connections\Connection as Redis;
use Illuminate\Redis\Limiters\DurationLimiter;

/**
 * 参考：https://github.com/laravel/framework/blob/10.x/src/Illuminate/Routing/Middleware/ThrottleRequestsWithRedis.php
 */
class ThrottleRequestsWithRedis extends ThrottleRequests
{
    /**
     * @var Redis
     */
    protected $redis;

    /**
     * @var array
     */
    public array $decaysAt = [];

    /**
     * @var array
     */
    public array $remaining = [];

    public function __construct(array $config = [])
    {
        $this->config['redis_connection_name'] = null;
        parent::__construct($config);

        $this->redis = \support\Redis::instance()->connection($this->config['redis_connection_name']);
    }

    /**
     * @inheritDoc
     */
    protected function tooManyAttempts(Limit $limit): bool
    {
        $limiter = new DurationLimiter(
            $this->redis, $limit->key, $limit->maxAttempts, $limit->decayMinutes * 60
        );

        $key = $limit->key;
        return tap(!$limiter->acquire(), function () use ($key, $limiter) {
            [$this->decaysAt[$key], $this->remaining[$key]] = [
                $limiter->decaysAt, $limiter->remaining,
            ];
        });
    }

    /**
     * @inheritDoc
     */
    protected function calculateRemainingAttempts(string $key, int $maxAttempts, ?int $retryAfter = null): int
    {
        return is_null($retryAfter) ? $this->remaining[$key] : 0;
    }

    /**
     * @inheritDoc
     */
    protected function getTimeUntilNextRetry(string $key): int
    {
        return $this->decaysAt[$key] - $this->currentTime();
    }
}
