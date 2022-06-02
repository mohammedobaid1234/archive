<?php

namespace Modules\Customers\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Company extends Model implements  HasMedia {
    use SoftDeletes;
    use \Modules\BriskCore\Traits\ModelTrait;
    use InteractsWithMedia;

    protected $table = 'cm_companies';

    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];

    protected $appends = ['image_url'];

    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void{
        $this->addMediaConversion('thumb')
              ->width(600)
              ->height(600)
              ->nonQueued();
    }

    public function created_by_user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class, 'created_by');
    }

    public function customer(){
        return $this->belongsTo(\Modules\Customers\Entities\Customer::class, 'owner_id');
    }

    public function province(){
        return $this->belongsTo(\Modules\Core\Entities\CountryProvince::class, 'province_id');
    }

    public function addresses(){
        return $this->hasMany(\Modules\Customers\Entities\CompanyAddress::class, 'company_id');
    }

    public function image(){
        return $this->morphOne(Media::class,'model');
    }

    public function getImageUrlAttribute(){
        if(!$this->image){
            return asset('/public/themes/Falcon/v2.8.0/assets/img/team/avatar.png');
        }

        return url('/') . '/storage/app/public/' . $this->image->id . '/conversions/' . substr($this->image->file_name, 0, -4) . '-thumb.jpg';
    }
}
