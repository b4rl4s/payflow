<?php

declare(strict_types=1);

namespace Payflow\Support;

/**
 * Absolute URLs for PayFlow hosts. Defaults to http for local Docker; set PUBLIC_SCHEME=https
 * behind a TLS terminator, or rely on X-Forwarded-Proto when PUBLIC_SCHEME is unset.
 */
final class AppUrl
{
    public static function scheme(): string
    {
        $env = strtolower((string) getenv('PUBLIC_SCHEME'));
        if ($env === 'https' || $env === 'http') {
            return $env;
        }

        if (PHP_SAPI !== 'cli') {
            $xf = strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''));
            if ($xf === 'https' || $xf === 'http') {
                return $xf;
            }

            $https = strtolower((string) ($_SERVER['HTTPS'] ?? ''));
            if ($https === 'on' || $https === '1') {
                return 'https';
            }
        }

        return 'http';
    }

    public static function origin(string $hostname): string
    {
        return self::scheme().'://'.$hostname;
    }

    public static function site(string $hostname, string $path = '/'): string
    {
        if ($path === '' || $path === '/') {

            return self::origin($hostname).'/';
        }

        if ($path[0] !== '/') {
            $path = '/'.$path;
        }

        return self::origin($hostname).$path;
    }

    /** connect-src helpers: ws + wss for realtime host */
    public static function realtimeSocketSources(): string
    {
        return 'ws://realtime.payflow.local wss://realtime.payflow.local';
    }
}
