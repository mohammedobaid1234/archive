<?php

namespace Modules\Employees\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeTeam extends Model{
    // use SoftDeletes;
    protected $table = 'em_employee_team';

    protected $fillable = [];
    
    public function team(){
        return $this->belongsTo(\Modules\Employees\Entities\Team::class);
    }
    public function Employee(){
        return $this->belongsTo(\Modules\Employees\Entities\Employee::class);
    }
}
