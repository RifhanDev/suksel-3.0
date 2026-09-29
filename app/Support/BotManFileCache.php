<?php

namespace App\Support;

use BotMan\BotMan\Interfaces\CacheInterface;
use Illuminate\Contracts\Cache\Repository;

/**
 * BotMan multi-step conversations must survive separate HTTP requests.
 * When CACHE_DRIVER=array (common in local .env), Laravel's default cache
 * is wiped after each request — aduan/FAQ flows then fall through to fallback.
 */
class BotManFileCache implements CacheInterface
{
    private Repository $store;

    public function __construct()
    {
        $this->store = cache()->store('file');
    }

    public function has($key): bool
    {
        return $this->store->has($key);
    }

    public function get($key, $default = null)
    {
        return $this->store->get($key, $default);
    }

    public function pull($key, $default = null)
    {
        return $this->store->pull($key, $default);
    }

    public function put($key, $value, $minutes): void
    {
        if (! $minutes instanceof \DateTimeInterface) {
            $minutes = (int) $minutes * 60;
        }

        $this->store->put($key, $value, $minutes);
    }
}
