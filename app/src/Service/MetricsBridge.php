<?php

declare(strict_types=1);

namespace Payflow\Service;

final class MetricsBridge
{
    public function __construct(
        private readonly ?\Redis $redis = null
    ) {
    }

    public function redisPingMs(): ?float
    {
        if (!$this->redis instanceof \Redis) {
            return null;
        }

        try {
            $started = microtime(true);
            $this->redis->ping();
            return round((microtime(true) - $started) * 1000, 3);
        } catch (\Throwable) {
            return null;
        }
    }
}
