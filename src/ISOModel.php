<?php
namespace Language\Iso;
class ISOModel implements \JsonSerializable
{
    public string $code;
    public string $name;
    public string $flag;

    public function __construct(string $code){
        $this->code= $code;
    }

    public function toArray(): array
    {
        $arr = [];
        foreach (get_class_vars(get_class($this)) as $key => $value) {
            $arr[$key] = $this->{$key};
        }
        return $arr;
    }

    public function jsonSerialize(): array
    {
        return  $this->toArray();
    }
}
