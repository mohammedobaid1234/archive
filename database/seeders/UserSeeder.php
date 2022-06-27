<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder{

    public function run(){
        /**
         *
         */
        $roles = [
            ['name' => 'system_administration', 'label' => 'إدارة النظام', 'guard_name' => 'web'],
            ['name' => 'employee', 'label' => 'موظف', 'guard_name' => 'web'],
        ];

        foreach($roles as $row){
            $record = new \Spatie\Permission\Models\Role;
            $record->name = $row['name'];
            $record->label = $row['label'];
            $record->guard_name  = $row['guard_name'];
            $record->save();
        }

        /**
         *
        */
        $users = [
            ['first_name' => 'محمد', 'father_name' => 'أحمد', 'grandfather_name' => 'محمد', 'last_name' => 'عبيد', 'birthdate' => '2000-12-13', 'employment_id' => 2000, 'email' => 'mhmd.obaid.18@gmail.com', 'mobile_no' => "0594034429", 'roles' => ["system_administration"]],
        ];

        foreach($users as $row){
            $user = new \Modules\Users\Entities\User;
            $user->userable_id = 0;
            $user->userable_type = "Modules\Employees\Entities\Employee";
            $user->email = $row['email'];
            $user->password = \Illuminate\Support\Facades\Hash::make("12345678");
            $user->save();
            $user->assignRole($row['roles']);
            
            $employee = new \Modules\Employees\Entities\Employee;
            $employee->employment_id = $row['employment_id'];
            $employee->first_name = $row['first_name'];
            $employee->father_name = $row['father_name'];
            $employee->grandfather_name = $row['grandfather_name'];
            $employee->last_name = $row['last_name'];
            $record->full_name = $row['first_name'] . " " .$row['father_name'] . " " .$row['grandfather_name']. " " . $row['last_name'];
            
            $employee->gender =  "male";
            $employee->mobile_no = $row['mobile_no'];
            $employee->created_by = 1;
            $employee->save();

            $user->userable_id = $employee->id;
            $user->save();

            $employee_profile = new \Modules\Employees\Entities\Profile;
            $employee_profile->employee_id  = $employee->id;
            $employee_profile->national_id  = '406995613';
            $employee_profile->fingerprint_number  = '57';
            $employee_profile->address  = 'غزة النصر';
            $employee_profile->save();

        }
    }
}
