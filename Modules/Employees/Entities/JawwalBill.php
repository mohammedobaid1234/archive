<?php

namespace Modules\Employees\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JawwalBill extends Model{
    use \Modules\BriskCore\Traits\ModelTrait;

    protected $table = 'em_jawwal_bills';

    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];

    public function employee(){
        return $this->belongsTo(\Modules\Employees\Entities\Employee::class);
    }

    public function _employee_id(){
        return [
            'title' => 'اسم الموظف حامل الفاتورة',
            'input' => 'select',
            'name' => 'employee_id',
            'classes' => ['select2'],
            'required' => true,
            'data' => [
                'options_source' => 'employees',
                'placeholder' => 'اسم  الموظف حامل الفاتورة...'
            ],
            'operations' => [
                'show' => ['text' => 'employee.full_name', 'id' => 'employee_id'],
            ]
        ];
    }

    public function _mobile_no(){
        return [
            'title' => 'رقم الفاتورة',
            'input' => 'input',
            'name' => 'mobile_no',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'mobile_no']
            ]
        ];
    }
    public function _value(){
        return [
            'title' => 'قيمة الفاتورة',
            'input' => 'input',
            'name' => 'value',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'value']
            ]
        ];
    }
}
