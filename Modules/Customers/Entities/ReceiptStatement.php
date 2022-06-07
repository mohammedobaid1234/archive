<?php

namespace Modules\Customers\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ReceiptStatement extends Model implements HasMedia{
    use SoftDeletes;
    use InteractsWithMedia;
    use \Modules\BriskCore\Traits\ModelTrait;
    protected $table = 'cm_receipt_statements';
    protected $appends = ['receipt_statement_image_url'];

    public function created_by_user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class, 'created_by');
    }
    public function employee(){
        return $this->belongsTo(\Modules\Employees\Entities\Employee::class, 'employee_id');
    }
    public function customer(){
        return $this->belongsTo(\Modules\Customers\Entities\Customer::class, 'customer_id');
    }
    public function bank(){
        return $this->belongsTo(\Modules\Core\Entities\Bank::class, 'bank_id');
    }
    public function currency(){
        return $this->belongsTo(\Modules\Core\Entities\Currency::class, 'currency_id');
    }
    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media  $media = null): void{
        $this->addMediaConversion('thumb')
              ->width(400)
              ->height(400);
    }
    public function getReceiptStatementImageUrlAttribute(){
        $image = $this->getMedia('receipt_statement')->first();

        if($image){
            return url('/') . '/storage/app/public/' . $image->id . '/' . $image->file_name;
        }

        return asset('/public/themes/Falcon/v2.8.0/assets/img/team/avatar.png');
    }
    public function scopeWhereFullNameLike($query, $name){
        $name = str_replace("أ", "ا", $name);
        $name = str_replace("إ", "ا", $name);
        $name = str_replace("ة", "ه", $name);
        $name = str_replace("ى", "ي", $name);

        return $query->where(\DB::raw('REPLACE(REPLACE(REPLACE(REPLACE(full_name, "أ", "ا"), "إ", "ا"), "ة", "ه"), "ى", "ي")'), 'like', ('%' . trim($name) . '%'));
    }  
    public function scopeWhereTransactionDate($query, $transaction_date){
        return $query->where(function($query) use ($transaction_date){
            if(str_contains(trim($transaction_date), ' - ')){
                $transaction_date = explode(' - ', $transaction_date);
                $transaction_date_from = $transaction_date[0];
                $transaction_date_from = $transaction_date[1];

                $query->whereDate('transaction_date', '>=', date('Y-m-d', strtotime(trim($transaction_date[0]))));
                $query->whereDate('transaction_date', '<=', date('Y-m-d', strtotime(trim($transaction_date[1]))));
            }else{
                $query->whereDate('transaction_date', date('Y-m-d', strtotime(trim($transaction_date))));
            }
        });
    }
    public function scopeWhereCheckDueDate($query, $check_due_date){
        return $query->where(function($query) use ($check_due_date){
            if(str_contains(trim($check_due_date), ' - ')){
                $check_due_date = explode(' - ', $check_due_date);
                $check_due_date_from = $check_due_date[0];
                $check_due_date_from = $check_due_date[1];

                $query->whereDate('check_due_date', '>=', date('Y-m-d', strtotime(trim($check_due_date[0]))));
                $query->whereDate('check_due_date', '<=', date('Y-m-d', strtotime(trim($check_due_date[1]))));
            }else{
                $query->whereDate('check_due_date', date('Y-m-d', strtotime(trim($check_due_date))));
            }
        });
    }
    public function scopeWhereNextDueDate($query, $next_due_date){
        return $query->where(function($query) use ($next_due_date){
            if(str_contains(trim($next_due_date), ' - ')){
                $next_due_date = explode(' - ', $next_due_date);
                $next_due_date_from = $next_due_date[0];
                $next_due_date_from = $next_due_date[1];

                $query->whereDate('next_due_date', '>=', date('Y-m-d', strtotime(trim($next_due_date[0]))));
                $query->whereDate('next_due_date', '<=', date('Y-m-d', strtotime(trim($next_due_date[1]))));
            }else{
                $query->whereDate('next_due_date', date('Y-m-d', strtotime(trim($next_due_date))));
            }
        });
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
