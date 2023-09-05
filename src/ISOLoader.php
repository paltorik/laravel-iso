<?php

namespace Language\Iso;

use Illuminate\Support\Facades\File;
use Language\Iso\Contract\ISOLoaderInterface;

class ISOLoader implements ISOLoaderInterface
{

    public function readFromFile(string $filePath): array
    {
        $data = File::get(resource_path($filePath));
        return json_decode($data, true);
    }

}
