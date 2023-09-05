<?php

namespace Language\Iso\Facades;


use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Language\Iso\ISOModel;

/**
 * @method static array getAvailableCodes()
 * @method static Collection|ISOModel[] getAllLanguagesCollection()
 * @method static ISOModel[] getAvailableLanguages()
 * @method static ISOModel[] getAllLanguages()
 * @method static void setLocalByAvailableLanguage(ISOModel|string $code)
 * @method static void setLocalByRequest(Request $request)
 * @method static string getDefault()
 * @method static ISOModel|null findOrDefault(string $code)
 * @method static ISOModel|null find(string $code)
 * @method static bool isISOCode(string|ISOModel $value)
 * @method static bool isDefault(string|ISOModel $value)
 * @method static bool isAvailableLanguage(string|ISOModel $value)
 */
class ISO extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ISO';
    }
}
