<?php

namespace Modules\Employees\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeAdvance extends Model{
    use \Modules\BriskCore\Traits\ModelTrait;

    protected $table = 'em_employees_advances';

    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];

    public function employee(){
        return $this->belongsTo(\Modules\Employees\Entities\Employee::class);
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
    public function created_by_user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class, 'created_by');
    }

    public function _employee_id(){
        return [
            'title' => 'اسم الموظف  ',
            'input' => 'select',
            'name' => 'employee_id',
            'classes' => ['select2'],
            'required' => true,
            'data' => [
                'options_source' => 'employees',
            ],
            'operations' => [
                'show' => ['text' => 'employee.full_name', 'id' => 'employee_id'],
            ]
        ];
    }

    public function _amount(){
        return [
            'title' => 'قيمة السلفة',
            'input' => 'input',
            'name' => 'amount',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'amount']
            ]
        ];
    }
    
}
