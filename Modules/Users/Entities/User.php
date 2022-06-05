<?php

namespace Modules\Users\Entities;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {
    use  HasRoles;

    protected $table = 'users';

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['email_verified_at' => 'datetime', 'created_at' => 'datetime:Y-m-d H:i:s a'];

    protected $appends = ['personal_image_url','name'];
    
    public function userable(){
        return $this->morphTo();
    }
    

    public function authorize($permission, $type = "json"){
        if(\Auth::user()->can(trim($permission))){
            return true;
        }

        $type = strtolower(trim($type));

        if($type == "view"){
            response()->view('errors.401', [], 401)->send();
            die();
        }

        if($type == "json"){
            response()->json(['message' => "لا تملك صلاحية الوصول إلى هذا الإجراء."], 401)->send();
            die();
        }

        if($type == "boolean"){
            return false;
        }

        return false;
    }
    
    public function getNameAttribute(){
        return $this->userable->first_name . " " . $this->userable->last_name;
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


    public function scopeWhereEmailLike($query, $email){
        return $query->where('email', 'like', ('%' . trim($email) . '%'));
    }
    public function getPersonalImageUrlAttribute(){
        return asset('/public/themes/Falcon/v2.8.0/assets/img/team/avatar.png');
    }
   

}
