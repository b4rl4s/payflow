<?php

declare(strict_types=1);

namespace Payflow\Support;

final class Env
{
    public static function bool(string $name, bool $default = false): bool
    {
        $raw = strtolower((string) getenv($name));

        return match ($raw) {
            '1', 'true', 'yes', 'on' => true,
            '0', 'false', 'no', 'off', '' => false,
            default => $default,
        };
    }
}
