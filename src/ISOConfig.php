<?php

namespace Language\Iso;

use Illuminate\Support\Facades\App;
use Language\Iso\Contract\ISOConfigInterface;

class ISOConfig implements ISOConfigInterface
{
    protected array $config;

    public function __construct(array $config = null)
    {
        $this->config = $config ?? config('iso-language');
    }

    public function getDefaultCode(): string
    {
        return $this->config['default_code'] ?? App::getLocale();
    }

    public function getAvailableLanguages(): array
    {
        if (!in_array($this->getDefaultCode(), $this->config['available'])) {
            $this->config['available'][] = $this->getDefaultCode();
        }
        return $this->config['available'];
    }

    public function getCacheKey(): string
    {
        return $this->config['cache_key'] ?? 'iso_data_cache';
    }

    public function isCached(): bool
    {
        return $this->config['cached'] ?? false;
    }

    public function getFilePath(): string
    {
        return $this->config['path'];
    }
}
