<?php

namespace Modules\Customers\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Draft extends Model implements HasMedia{
    use SoftDeletes;
    use \Modules\BriskCore\Traits\ModelTrait;
    use InteractsWithMedia;

    protected $table = 'cm_drafts';

    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];
    protected $appends = ['draft_image_url'];
    
    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media  $media = null): void{
        $this->addMediaConversion('thumb')
              ->width(400)
              ->height(400);
    }
    public function getDraftImageUrlAttribute(){
        $image = $this->getMedia('draft_image')->first();

        if($image){
            return url('/') . '/storage/app/public/' . $image->id . '/' . $image->file_name;
        }

        return asset('/public/themes/Falcon/v2.8.0/assets/img/team/avatar.png');
    }
    public function customer(){
        return $this->belongsTo(\Modules\Customers\Entities\Customer::class, 'customer_id');
    }
    public function customer_payment_date(){
        return $this->morphOne(\Modules\Customers\Entities\CustomerPaymentsDate::class, 'payment');
    }
    public function employee(){
        return $this->belongsTo(\Modules\Employees\Entities\Employee::class);
    }

    public function created_by_user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class, 'created_by');
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
    public function scopeWhereDueDate($query, $due_date){
        return $query->where(function($query) use ($due_date){
            if(str_contains(trim($due_date), ' - ')){
                $due_date = explode(' - ', $due_date);
                $due_date_from = $due_date[0];
                $due_date_from = $due_date[1];

                $query->whereDate('due_date', '>=', date('Y-m-d', strtotime(trim($due_date[0]))));
                $query->whereDate('due_date', '<=', date('Y-m-d', strtotime(trim($due_date[1]))));
            }else{
                $query->whereDate('due_date', date('Y-m-d', strtotime(trim($due_date))));
            }
        });
    }
    public function currency(){
        return $this->belongsTo(\Modules\Core\Entities\Currency::class, 'currency_id');
    }
}
