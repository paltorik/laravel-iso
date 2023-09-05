<?php

namespace Language\Iso\Contract;
interface ISOLoaderInterface
{
    public function readFromFile(string $filePath): array;
}
