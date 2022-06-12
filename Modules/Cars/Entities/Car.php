<?php

namespace Modules\Cars\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model{
    use \Modules\BriskCore\Traits\ModelTrait;

    protected $table = 'cr_cars';

    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];
    protected $with = ['team'];
    public function employee(){
       return $this->belongsTo(\Modules\Employees\Entities\Employee::class,'driver_id');
    }
    public function team(){
       return $this->belongsTo(\Modules\Employees\Entities\Team::class);
    }
    public function papers(){
       return $this->hasMany(\Modules\Cars\Entities\CarPaper::class);
    }
    
    
    
}
