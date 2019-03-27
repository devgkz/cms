<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = false;
    
    public static $types = [
        0 => 'Поле',
        1 => 'Многострочное поле',
        2 => 'Вкл./выкл.',
    ];
    
    protected $fillable = [
        'name',
        'title',
        'value',
        'options',
        'comment',
        'type',
        'required',
        'visible',
        'sort_order',
    ];
    
    
}
