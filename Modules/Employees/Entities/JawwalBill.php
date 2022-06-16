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
    public function scopeWhereActivateDate($query, $activate_date){
        return $query->where(function($query) use ($activate_date){
            if(str_contains(trim($activate_date), ' - ')){
                $activate_date = explode(' - ', $activate_date);
                $activate_date_from = $activate_date[0];
                $activate_date_from = $activate_date[1];

                $query->whereDate('activate_date', '>=', date('Y-m-d', strtotime(trim($activate_date[0]))));
                $query->whereDate('activate_date', '<=', date('Y-m-d', strtotime(trim($activate_date[1]))));
            }else{
                $query->whereDate('activate_date', date('Y-m-d', strtotime(trim($activate_date))));
            }
        });
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
    public function _activate_date(){
        return [
            'title' => 'تاريخ تفعيل الفاتورة',
            'input' => 'input',
            'date' => 'true',
            'name' => 'activate_date',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'activate_date']
            ],
        ];
    }
}
