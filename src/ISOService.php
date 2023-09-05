<?php

namespace Language\Iso;

;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Contracts\Cache\Repository as Cache;
use Language\Iso\Contract\ISOConfigInterface;
use Language\Iso\Contract\ISOLoaderInterface;

class ISOService
{
    /**
     * @var ISOModel[]
     */
    static ?array $_dictionary = null;
    /**
     * @var ISOModel[]
     */
    static ?array $_dictionary_available = null;

    public function __construct(
        private readonly ISOConfigInterface $config,
        private readonly ISOLoaderInterface $ISOLoader,
        private readonly Cache $cache
    )
    {
    }

    public function findOrDefault(string $code): ?ISOModel
    {
        return $this->find($code) ?? $this->getAllLanguages()[$this->config->getDefaultCode()];
    }

    public function find(string $code): ?ISOModel
    {
        return $this->getAllLanguages()[$code] ?? null;
    }

    public function setLocalByAvailableLanguage(ISOModel|string $code): void
    {
        if ($code instanceof ISOModel) $code = $code->code;
        App::setLocale($this->isAvailableLanguage($code) ? $code : $this->config->getDefaultCode());
    }

    public function setLocalByRequest(Request $request): void
    {
        $this->setLocalByAvailableLanguage($request->header('Accept-Language') ?? $this->config->getDefaultCode());
    }

    public function isDefault(ISOModel|string $code): bool
    {
        return $this->config->getDefaultCode() === $this->normalizeParam($code);
    }

    public function isISOCode(ISOModel|string $code): bool
    {
        return isset($this->getAllLanguages()[$this->normalizeParam($code)]);
    }

    public function isAvailableLanguage(ISOModel|string $code): bool
    {
        return isset($this->getAvailableLanguages()[$this->normalizeParam($code)]);
    }

    private function prepareAvailableLanguage(): array
    {
        $availableLanguages = [];
        foreach ($this->config->getAvailableLanguages() as $code) {
            if ($lang = $this->find($code)) {
                $availableLanguages[$code] = $lang;
            }
        }
        return $availableLanguages;
    }

    public function getAvailableLanguages(): ?array
    {
        return static::$_dictionary_available ?? static::$_dictionary_available = $this->prepareAvailableLanguage();
    }

    private function scan(): ?array
    {
        return static::$_dictionary ?? static::$_dictionary = $this->prepareFormat($this->readFromFile());
    }

    private function prepareFormat(array $data): ?array
    {
        foreach ($data as $key => $datum) {
            static::$_dictionary[$key] = ISOModelFactory::make($key, $datum);
        }
        return static::$_dictionary;
    }


    public function getAllLanguages(): array
    {
        if ($this->config->isCached()) {
            return $this->getCached();
        }
        return $this->scan();
    }

    private function getCached(): array
    {
        return $this->cache->rememberForever($this->config->getCacheKey(), function () {
            return $this->scan();
        });
    }

    private function readFromFile(): array
    {
        return  $this->ISOLoader->readFromFile($this->config->getFilePath());
    }

    private function normalizeParam(string|ISOModel $code): string
    {
        return ($code instanceof ISOModel) ? $code->code : $code;
    }

}
