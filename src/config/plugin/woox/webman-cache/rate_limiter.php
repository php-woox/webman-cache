<?php

use Illuminate\Cache\RateLimiting\Limit;
use Webman\Http\Request;
use Woox\WebmanCache\Facades\RateLimiter as RateLimiterFacade;

return [
    /**
     * RateLimiter 使用的默认驱动
     * 为 null 时同 cache 下的 default
     */
    'limiter' => null,

    /**
     * RateLimiter::for 的快速配置
     */
    'for' => [
        RateLimiterFacade::FOR_REQUEST => function (Request $request) {
            return Limit::perMinute(1000)
                ->by($request->getRealIp());
        }
    ],

    /**
     * ThrottleRequestsFactory 的配置
     */
    'throttle_requests' => [
        'use_redis' => false, // 是否使用 redis 模式，推荐
        'redis_connection_name' => null, // redis 模式下使用的 redis connection Name
        'limiter_for' => null, // 默认 RateLimiter 的 for 的 name
        'with_headers' => true, // 返回时是否携带 header 信息
    ]
];
