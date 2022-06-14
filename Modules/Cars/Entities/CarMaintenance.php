<?php

namespace Modules\Cars\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CarMaintenance extends Model implements HasMedia{
    protected $table = 'cr_cars_maintenances';
     use \Modules\BriskCore\Traits\ModelTrait;
    use InteractsWithMedia;

    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];

    protected $appends = ['maintenances_image_url'];

    public function car(){
        return $this->belongsTo(\Modules\Cars\Entities\Car::class);
    }
   
    public function getMaintenancesImageUrlAttribute(){
        $image = $this->getMedia('maintenance_car')->last();

        if($image){
            return url('/') . '/storage/app/public/' . $image->id . '/conversions/' . $image->file_name;
        }

        return asset('/public/themes/Falcon/v2.8.0/assets/img/team/avatar.png');
    }
    public function scopeWhereCreatedAt($query, $created_at){
        return $query->where(function($query) use ($created_at){
            if(str_contains(trim($created_at), ' - ')){
                $created_at = explode(' - ', $created_at);
                $created_at_from = $created_at[0];
                $created_at_from = $created_at[1];
    
                $query->whereDate('created_at', '>=', date('Y-m-d', strtotime(trim($created_at[0]))));
                $query->whereDate('created_at', '<=', date('Y-m-d', strtotime(trim($created_at[1]))));
            }else{
                $query->whereDate('created_at', date('Y-m-d', strtotime(trim($created_at))));
            }
    });
    }

}