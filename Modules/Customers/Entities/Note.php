<?php

namespace Modules\Customers\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Note extends Model {
    use SoftDeletes;
    use \Modules\BriskCore\Traits\ModelTrait;
    use InteractsWithMedia;

    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media  $media = null): void{
        $this->addMediaConversion('thumb')
              ->width(800)
              ->height(800);
    }

    protected $table = 'cm_notes';

    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];

    public function created_by_user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class, 'created_by');
    }

    public function parent(){
        return $this->belongsTo(\Modules\Customers\Entities\Note::class, 'parent_id');
    }

    public function comments(){
        return $this->hasMany(\Modules\Customers\Entities\Note::class, 'parent_id')->orderBy('created_at', 'DESC');
    }

    public function customer(){
        return $this->belongsTo(\Modules\Customers\Entities\Customer::class, 'customer_id');
    }
}
