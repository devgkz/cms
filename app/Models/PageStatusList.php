<?php

namespace App\Models;

class PageStatusList
{
    /**
    * @var array
    */
    private static $list = [
        0=>'Черновик',
        1=>'Опубликовано'
    ];
    
    private static $listCss = [
        0=>'info',
        1=>'success',
    ];
    
    public static function all(): array
    {
        return static::$list;
    }
    
    public static function get($id): string
    {
        return isset(static::$list[$id]) ? static::$list[$id] : '';
    }
    
    public static function getCss($id): string
    {
        return isset(static::$listCss[$id]) ? static::$listCss[$id] : '';
    }
}
