<?php

namespace Modules\Employees\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model  {
    use HasFactory;
    use SoftDeletes;
    use \Modules\BriskCore\Traits\ModelTrait;
    protected $table = 'em_employee_profile';

    
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];

    public function employee(){
        return $this->belongsTo(\Modules\Employees\Entities\Employee::class);
    }

}
