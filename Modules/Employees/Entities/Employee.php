<?php

namespace Modules\Employees\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Employee extends Model implements HasMedia{
    use HasFactory;
    use SoftDeletes;
    use \Modules\BriskCore\Traits\ModelTrait;
    use InteractsWithMedia;

    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];

    protected $appends = ['personal_image_url'];
    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media  $media = null): void{
        $this->addMediaConversion('thumb')
              ->width(400)
              ->height(400);
    }
    public function user(){
        return $this->morphOne(\Modules\Users\Entities\User::class, 'userable');
    }
    public function profile(){
        return $this->hasOne(\Modules\Employees\Entities\Profile::class);
    }
    public function created_by_user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class, 'created_by');
    }
    public function department(){
        return $this->belongsTo(\Modules\Employees\Entities\Department::class);
    }
    public function scopeWhereFullNameLike($query, $name){
        $name = str_replace("أ", "ا", $name);
        $name = str_replace("إ", "ا", $name);
        $name = str_replace("ة", "ه", $name);
        $name = str_replace("ى", "ي", $name);

        return $query->where(\DB::raw('REPLACE(REPLACE(REPLACE(REPLACE(full_name, "أ", "ا"), "إ", "ا"), "ة", "ه"), "ى", "ي")'), 'like', ('%' . trim($name) . '%'));
    }
    public function getPersonalImageUrlAttribute(){
        $image = $this->getMedia('personal-image')->first();

        if($image){
            return url('/') . '/storage/app/public/' . $image->id . '/conversions/' . substr($image->file_name, 0, -4) . '-thumb.jpg';
        }

        return asset('/public/themes/Falcon/v2.8.0/assets/img/team/avatar.png');
    }
    
    
    public function scopeWhereFirstNameLike($query, $name){
        $name = str_replace("أ", "ا", $name);
        $name = str_replace("إ", "ا", $name);
        $name = str_replace("ة", "ه", $name);
        $name = str_replace("ى", "ي", $name);

        return $query->where(\DB::raw('REPLACE(REPLACE(REPLACE(REPLACE(first_name, "أ", "ا"), "إ", "ا"), "ة", "ه"), "ى", "ي")'), 'like', (trim($name) . '%'));
    }

    public function scopeWhereFatherNameLike($query, $name){
        $name = str_replace("أ", "ا", $name);
        $name = str_replace("إ", "ا", $name);
        $name = str_replace("ة", "ه", $name);
        $name = str_replace("ى", "ي", $name);

        return $query->where(\DB::raw('REPLACE(REPLACE(REPLACE(REPLACE(father_name, "أ", "ا"), "إ", "ا"), "ة", "ه"), "ى", "ي")'), 'like', (trim($name) . '%'));
    }

    public function scopeWhereGrandfatherNameLike($query, $name){
        $name = str_replace("أ", "ا", $name);
        $name = str_replace("إ", "ا", $name);
        $name = str_replace("ة", "ه", $name);
        $name = str_replace("ى", "ي", $name);

        return $query->where(\DB::raw('REPLACE(REPLACE(REPLACE(REPLACE(grandfather_name, "أ", "ا"), "إ", "ا"), "ة", "ه"), "ى", "ي")'), 'like', (trim($name) . '%'));
    }

    public function scopeWhereLastNameLike($query, $name){
        $name = str_replace("أ", "ا", $name);
        $name = str_replace("إ", "ا", $name);
        $name = str_replace("ة", "ه", $name);
        $name = str_replace("ى", "ي", $name);

        return $query->where(\DB::raw('REPLACE(REPLACE(REPLACE(REPLACE(last_name, "أ", "ا"), "إ", "ا"), "ة", "ه"), "ى", "ي")'), 'like', ('%' . trim($name) . '%'));
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

    public function scopeWhereNationalId($query, $name){
        return $query->where(function($query) use ($name){
            $query->where('national_id' , 'like', '%' . trim($name) . '%');
        });
    }

    public function getGenderNameAttribute(){
        if($this->gender == "male"){
            return "ذكر";
        }
        if($this->gender == "female"){
            return "أنثى";
        }

        return "-";
    }
}
