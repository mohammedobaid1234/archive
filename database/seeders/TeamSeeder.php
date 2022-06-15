<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class TeamSeeder extends Seeder{

    public function run(){
        /**
         *
         */
        $team = [
            ['name' => 'فريق صيانة 1'],
            ['name' => 'فريق صيانة 2'],
        ];

        foreach($team as $row){
            $record = new \Modules\Employees\Entities\Team;
            $record->name = $row['name'];
            $record->save();
        }
        $employee = [
            ['employment_id'=> '2002','first_name' => 'احمد','father_name'=> 'سعيد','grandfather_name'=> 'مسعود','last_name'=>'يونس','gender'=>'male','mobile_no'=>'0594034423','created_by'=>1],
            ['employment_id'=> '2003','first_name' => 'محمد','father_name'=> 'خليل','grandfather_name'=> 'سعد','last_name'=>'وادي','gender'=>'male','mobile_no'=>'0594034422','created_by'=>1],
            ['employment_id'=> '2004','first_name' => 'محمود','father_name'=> 'سمير','grandfather_name'=> 'عمر','last_name'=>'زقوت','gender'=>'male','mobile_no'=>'0594034424','created_by'=>1],
            ['employment_id'=> '2005','first_name' => 'مهدي','father_name'=> 'احمد','grandfather_name'=> 'محمد','last_name'=>'حرز','gender'=>'male','mobile_no'=>'0594034425','created_by'=>1],
            ['employment_id'=> '2006','first_name' => 'سعيد','father_name'=> 'اسامة','grandfather_name'=> 'عبدالله','last_name'=>'مرتجى','gender'=>'male','mobile_no'=>'0594034426','created_by'=>1],
            ['employment_id'=> '2007','first_name' => 'محمود','father_name'=> 'عمرو','grandfather_name'=> 'عمر','last_name'=>'صيام','gender'=>'male','mobile_no'=>'0594034427','created_by'=>1],
        ];
        $count = 1;
        foreach($employee as $row){
            
            $record = new \Modules\Employees\Entities\Employee;
            $record->employment_id = $row['employment_id'];
            $record->first_name = $row['first_name'];
            $record->father_name = $row['father_name'];
            $record->grandfather_name = $row['grandfather_name'];
            $record->last_name = $row['last_name'];
            $record->gender = $row['gender'];
            $record->mobile_no = $row['mobile_no'];
            $record->created_by = $row['created_by'];
            $record->save();

            $user = new \Modules\Users\Entities\User;
            $user->userable_id = $record->id;
            $user->userable_type = "Modules\Employees\Entities\Employee";
            $user->email = $record->mobile_no;
            $user->password = \Illuminate\Support\Facades\Hash::make("12345678");
            $user->assignRole('employee');
            $user->save();

            $employee_profile = new \Modules\Employees\Entities\Profile;
            $employee_profile->employee_id  = $record->id;
            $employee_profile->national_id  = '406995613';
            $employee_profile->fingerprint_number  = '57';
            $employee_profile->address  = 'غزة النصر';
            $employee_profile->save();
        }

        $team_member = [
            ['employee_id' => 2,'team_id'=>1,'type'=>'مسؤول'],
            ['employee_id' => 3,'team_id'=>1,'type'=>'عضو'],
            ['employee_id' => 4,'team_id'=>1,'type'=>'عضو'],
            ['employee_id' => 5,'team_id'=>2,'type'=>'مسؤول'],
            ['employee_id' => 6,'team_id'=>2,'type'=>'عضو'],
            ['employee_id' => 7,'team_id'=>2,'type'=>'عضو'],
        ];
        foreach($team_member as $row){
            $record = new \Modules\Employees\Entities\EmployeeTeam();
            $record->employee_id = $row['employee_id'];
            $record->team_id = $row['team_id'];
            $record->type = $row['type'];
            $record->save();
        }
        
    }
}
