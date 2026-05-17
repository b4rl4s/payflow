<?php

declare(strict_types=1);

namespace Payflow\Http;

final class HostProfile
{
    public function __construct(
        public readonly string $hostname,
        public readonly string $kind,
    ) {
    }

    public static function fromServer(string $hostname): self
    {
        $normalized = strtolower($hostname);

        return new self($normalized, self::inferKind($normalized));
    }

    private static function inferKind(string $host): string
    {
        return match ($host) {
            'payflow.local', 'www.payflow.local' => 'marketing',
            'app.payflow.local' => 'app',
            'api.payflow.local' => 'api',
            'admin.payflow.local' => 'admin',
            'cdn.payflow.local' => 'cdn',
            'status.payflow.local' => 'status',
            'staging.payflow.local' => 'staging',
            'beta.payflow.local' => 'beta',
            'dev-api.payflow.local' => 'devapi',
            default => str_contains($host, 'payflow.local') ? 'marketing' : 'marketing',
        };
    }

    public function isStagingChannel(): bool
    {
        return in_array($this->kind, ['staging', 'beta', 'devapi'], true);
    }
}
