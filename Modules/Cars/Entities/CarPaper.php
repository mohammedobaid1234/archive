<?php

namespace Modules\Cars\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CarPaper extends Model  implements HasMedia {
    protected $table = 'cr_cars_papers';
     use \Modules\BriskCore\Traits\ModelTrait;
    use InteractsWithMedia;

    protected $appends = ['insurance_image_url','driving_license_image_url','driver_license_image_url','stated_date_for_driver_license'];

    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];

    public function car(){
        return $this->belongsTo(\Modules\Cars\Entities\Car::class);
    }
   
    public function getInsuranceImageUrlAttribute(){
        $image = $this->getMedia('insurance')->first();

        if($image){
            return url('/') . '/storage/app/public/' . $image->id . '/conversions/' . $image->file_name;
        }

        return asset('/public/themes/Falcon/v2.8.0/assets/img/team/avatar.png');
    }
    public function getDrivingLicenseImageUrlAttribute(){
        $image = $this->getMedia('driving_license')->first();

        if($image){
            return url('/') . '/storage/app/public/' . $image->id . '/conversions/' . $image->file_name;
        }

        return asset('/public/themes/Falcon/v2.8.0/assets/img/team/avatar.png');
    }
    public function getDriverLicenseImageUrlAttribute(){
        $image = $this->getMedia('driver_license')->first();

        if($image){
            return url('/') . '/storage/app/public/' . $image->id . '/conversions/' . $image->file_name;
        }

        return asset('/public/themes/Falcon/v2.8.0/assets/img/team/avatar.png');
    }
   public function getStatedDateForDriverLicenseAttribute(){
           $model = $this->where('type', 'رخصة_سائق')->first();
           return $model->stated_at;
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
   public function scopeWhereStaredAt($query, $stated_at){
    return $query->where(function($query) use ($stated_at){
        if(str_contains(trim($stated_at), ' - ')){
            $stated_at = explode(' - ', $stated_at);
            $stated_at_from = $stated_at[0];
            $stated_at_from = $stated_at[1];

            $query->whereDate('stated_at', '>=', date('Y-m-d', strtotime(trim($stated_at[0]))));
            $query->whereDate('stated_at', '<=', date('Y-m-d', strtotime(trim($stated_at[1]))));
        }else{
            $query->whereDate('stated_at', date('Y-m-d', strtotime(trim($stated_at))));
        }
     });
    }
   public function scopeWhereEndedAt($query, $ended_at){
    return $query->where(function($query) use ($ended_at){
        if(str_contains(trim($ended_at), ' - ')){
            $ended_at = explode(' - ', $ended_at);
            $ended_at_from = $ended_at[0];
            $ended_at_from = $ended_at[1];

            $query->whereDate('ended_at', '>=', date('Y-m-d', strtotime(trim($ended_at[0]))));
            $query->whereDate('ended_at', '<=', date('Y-m-d', strtotime(trim($ended_at[1]))));
        }else{
            $query->whereDate('ended_at', date('Y-m-d', strtotime(trim($ended_at))));
        }
     });
    }
   
}
