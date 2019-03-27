<?php

namespace App\Models;

class UserRoleList
{
    /**
    * @var array
    */
    private static $list = [
        '' => 'Не определено',
        'admin'=>'Администратор',
        'user'=>'Пользователь',
    ];
    
    private static $listCss = [
        'admin'=>'danger',
        'user'=>'info',
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
