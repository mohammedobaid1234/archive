<?php

namespace Modules\Products\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia {
    use SoftDeletes;
    use \Modules\BriskCore\Traits\ModelTrait;
    use \Modules\BriskCore\Traits\ModelActiveTrait;
    use InteractsWithMedia;

    protected $table = 'pm_products';
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];


    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void{
        $this->addMediaConversion('thumb')
              ->width(600)
              ->height(600)
              ->nonQueued();
    }

    public function category(){
        return $this->belongsTo(\Modules\Products\Entities\Category::class, 'category_id');
    }

    public function province(){
        return $this->belongsTo(\Modules\Core\Entities\CountryProvince::class, 'province_id');
    }
    public function currency(){
        return $this->belongsTo(\Modules\Core\Entities\Currency::class, 'currency_id');
    }


    public function scopeWhereFullNameLike($query, $name){
        $name = str_replace("أ", "ا", $name);
        $name = str_replace("إ", "ا", $name);
        $name = str_replace("ة", "ه", $name);
        $name = str_replace("ى", "ي", $name);

        return $query->where(\DB::raw('REPLACE(REPLACE(REPLACE(REPLACE(name, "أ", "ا"), "إ", "ا"), "ة", "ه"), "ى", "ي")'), 'like', ('%' . trim($name) . '%'));
    }

    public function _name(){
        return [
            'title' => 'اسم المنتج',
            'input' => 'input',
            'name' => 'name',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'name']
            ]
        ];
    }
    public function _price(){
        return [
            'title' => 'سعر المنتج',
            'input' => 'input',
            'name' => 'price',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'price']
            ]
        ];
    }
    public function _quantity(){
        return [
            'title' => 'الكمية ',
            'input' => 'input',
            'name' => 'quantity',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'quantity']
            ]
        ];
    }
    public function _currency_id(){
        return [
            'title' => 'نوع العملة',
            'input' => 'select',
            'name' => 'currency_id',
            'required' => true,
            'classes' => ['select2'],
            'data' => [
                'options_source' => 'currencies'
            ],
            'operations' => [
                'show' => ['text' => 'currency.name', 'id' => 'currency_id'],
                'update' => ['text' => 'currency.name', 'id' => 'currency_id']
            ]
        ];
    }
    public function _category_id(){
        return [
            'title' => 'االتصنيف ',
            'input' => 'select',
            'name' => 'category_id',
            'required' => true,
            'classes' => ['select2'],
            'data' => [
                'options_source' => 'categoriesOfProducts'
            ],
            'operations' => [
                'show' => ['text' => 'category.name', 'id' => 'category_id'],
                'update' => ['text' => 'category.name', 'id' => 'category_id']
            ]
        ];
    }
}
