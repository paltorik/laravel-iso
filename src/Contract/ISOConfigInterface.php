<?php

namespace Language\Iso\Contract;
interface ISOConfigInterface
{
    public function getDefaultCode(): string;
    public function getAvailableLanguages(): array;
    public function getCacheKey(): string;
    public function isCached(): bool;
    public function getFilePath(): string;
}
