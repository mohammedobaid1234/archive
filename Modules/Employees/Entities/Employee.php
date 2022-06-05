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
    public function _employment_id(){
        return [
            'title' => 'الرقم الوظيفي',
            'input' => 'input',
            'name' => 'employment_id',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'employment_id']
            ]
        ];
    }
    public function _birthdate(){
        return [
            'title' => 'تاريخ الميلاد',
            'input' => 'input',
            'date' => 'true',
            'name' => 'birthdate',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'birthdate']
            ],
        ];
    }
    public function _started_work(){
        return [
            'title' => 'تاريخ بدء العمل',
            'input' => 'input',
            'date' => 'true',
            'name' => 'started_work',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'profile.started_work']
            ],
        ];
    }
    public function _first_name(){
        return [
            'title' => 'الإسم الأول',
            'input' => 'input',
            'name' => 'first_name',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'first_name']
            ]
        ];
    }
    public function _father_name(){
        return [
            'title' => 'الإسم الثاني',
            'input' => 'input',
            'name' => 'father_name',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'father_name']
            ]
        ];
    }
    public function _grandfather_name(){
        return [
            'title' => 'إسم الجد',
            'input' => 'input',
            'name' => 'grandfather_name',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'grandfather_name']
            ]
        ];
    }
    public function _last_name(){
        return [
            'title' => 'إسم العائلة',
            'input' => 'input',
            'name' => 'last_name',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'last_name']
            ]
        ];
    }
   
    public function _gender(){
        return [
            'title' => 'الجنس',
            'input' => 'select',
            'name' => 'gender',
            'required' => true,
            'classes' => ['select2'],
            'data' => [
                'options_source' => 'gender'
            ],
            'operations' => [
                'show' => ['text' => 'gender']
            ]
        ];
    }

    public function _roles(){
        return [
            'title' => 'الأدوار',
            'input' => 'select',
            'name' => 'roles',
            'classes' => ['select2'],
            'required' => true,
            'multiple' => true,
            'data' => [
                'options_source' => 'employees_roles'
            ],
            'operations' => [
                'show' => ['text' => 'user.roles.label', 'id' => 'user.roles.name']
            ]
        ];
    }
    public function _mobile_no(){
        return [
            'title' => 'رقم الجوال',
            'input' => 'input',
            'name' => 'mobile_no',
            'maxlength' => 10,
            'operations' => [
                'show' => ['text' => 'mobile_no']
            ]
        ];
    }
    public function _image(){
        return [
            'title' => 'الصورة الشخصية',
            'input' => 'input',
            'type' => 'file',
            'name' => 'image',
            'operations' => [
                'show' => ['text' => 'image'],
                'update' => ['text' => 'image'],
            ]
        ];
    }
    public function _password(){
        return [
            'title' => 'كلمة المرور',
            'input' => 'input',
            'name' => 'password',
            'required' => true,
            'operations' => [
                'show' => ['active' => false],
                'update' => ['active' => false]
            ]
        ];
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
