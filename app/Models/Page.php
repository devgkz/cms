<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'parent_id',
        'slug',
        'title',
        'introtext',
        'content',
        'tag',
        'template',
        'layout',
        'in_menu',
        'is_pin',
        'section_menu',
        'order_childs_by',
        'sort_order',
        'status',
    ];

    public static function getLayouts() : array
    {
        return [
            'normal' => 'Обычный',
        ];
    }
    
    public static function getTemplates() : array
    {
        return [
            'static' => 'Статическая страница',
            'list' => 'Список страниц',
            'list_intro' => 'Список страниц c интро',
            'gallery_list' => 'Список галерей',
            'gallery' => 'Галерея',
            'bypass'=>'By pass',
            'home'=>'Главная страница',
        ];
    }
    
    public static function getSectionMenuTypes() : array
    {
        return [
            '0'=>'Нет',
            '1'=>'Собственное',
            '2'=>'Унаследованное'
        ];
    }
    
    /**
     * Get the parent for the page.
     */
    public function parent()
    {
        return $this->belongsTo('App\Models\Page', 'parent_id');
    }
    /**
     * Get the childs for the page.
     */
    public function childs()
    {
        return $this->hasMany('App\Models\Page', 'parent_id');
    }
    
    /**
     * Get the media for the page.
     */
    public function media()
    {
        return $this->hasMany('App\Models\Media', 'page_id');
    }
}
