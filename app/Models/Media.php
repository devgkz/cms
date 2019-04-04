<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'page_id',
        'type_id',
        'title',
        'filename',
        'fn_original',
        'content',
        'sort_order',
    ];

    protected static $types = [
        '0'=>'Фото',
        '1'=>'Код видео',
        '2'=>'HTML',
    ];

    public static function getTypes() : array
    {
        return static::$types;
    }

    /**
     * Get the page for the media.
     */
    public function page()
    {
        return $this->belongsTo('App\Models\Page', 'page_id');
    }
}
