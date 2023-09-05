<?php

namespace Language\Iso;


use Illuminate\Support\Collection;

final class ISOModelFactory
{
    public static function make(string $code, array $item = []): ISOModel
    {
        $class = new ISOModel($code);
        foreach ($item as $key => $value) {
            if (property_exists($class, $key)) $class->{$key} = $value;
        }
        return $class;
    }

    /**
     * @param array $attribute
     * @return Collection
     */
    public static function toCollection(array $attribute): Collection
    {
        return (new Collection($attribute))->map(fn($item,$key): ISOModel => ISOModelFactory::make($key,(array)$item));
    }

    /**
     * @param array $attribute
     * @return ISOModel[]
     */
    public static function toArray(array $attribute): array
    {
        return array_map(fn($item,$key): ISOModel => ISOModelFactory::make($key,(array)$item),$attribute);
    }

}
