<?php

namespace Modules\Cars\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
   
}
