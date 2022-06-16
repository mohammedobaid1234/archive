<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CoreSeeder extends Seeder{

    public function run(){

        // $core_currencies = [
        //     ['id' => 'ILS', 'name' => 'شيكل', 'symbol' => 'ILS'],
        //     ['id' => 'JOD', 'name' => 'دينار أردني', 'symbol' => 'JOD'],
        //     ['id' => 'USD', 'name' => 'دولار أمريكي', 'symbol' => 'USD'],
        // ];

        // foreach($core_currencies as $row){
        //     $record = new \Modules\Core\Entities\Currency;
        //     $record->id = $row['id'];
        //     $record->name = $row['name'];
        //     $record->symbol = $row['symbol'];
        //     $record->save();
        // }

        // $core_countries = [
        //     [ 'name' => 'فلسطين', 'created_by' =>1],
          
        // ];

        // foreach($core_countries as $row){
        //     $record = new \Modules\Core\Entities\Country;
        //     $record->name = $row['name'];
        //     $record->created_by = $row['created_by'];
        //     $record->save();
        // }

        $core_country_provinces = [
            [ 'country_id' => 1,'name' => 'غزة', 'full_name' => 'غزة', 'created_by' =>1],
            [ 'country_id' => 1,'name' => 'رفح', 'full_name' => 'رفح', 'created_by' =>1],
            [ 'country_id' => 1,'name' => 'الشمال', 'full_name' => 'الشمال', 'created_by' =>1],
            [ 'country_id' => 1,'name' => 'خانيونس', 'full_name' => 'خانيونس', 'created_by' =>1],
            [ 'country_id' => 1,'name' => 'نصيرات', 'full_name' => 'نصيرات', 'created_by' =>1],
            [ 'country_id' => 1,'name' => 'دير البلح', 'full_name' => 'دير البلح', 'created_by' =>1],
        ];

        foreach($core_country_provinces as $row){
            $record = new \Modules\Core\Entities\CountryProvince();
            $record->country_id = $row['country_id'];
            $record->name = $row['name'];
            $record->full_name = $row['full_name'];
            $record->created_by = $row['created_by'];
            $record->save();
        }

        $cm_categories_of_contracts = [
            ['name' => ' عقد إتفاق بيع مولد'],
            ['name' => 'عقد صيانة مولد كهربائي'],
            ['name' => ' عقد إتفاق شراء مولد'],
            ['name' => 'فواتير بيع'],
            ['name' => 'فواتير شراء'],
        ];
        foreach($cm_categories_of_contracts as $row){
            $record = new \Modules\Customers\Entities\CategoriesOfContracts;
            $record->name = $row['name'];
            $record->save();
        }
        // $core_banks = [
        //     ['name' => 'بنك القدس', 'address' => ' غزة الرمال'],
        //     ['name' => 'البنك الإسلامي الفلسطيني', 'address' => ' غزة الرمال'],
        //     ['name' => 'بنك الإنتاج', 'address' => ' غزة النصر'],
        //     ['name' => 'البنك الوطني الإسلامي', 'address' => ' غزة النصر'],
        //     ['name' => 'بنك فلسطين', 'address' => ' غزة الرمال'],
        //     ['name' => 'بنك الأردن ', 'address' => ' غزة النصر '],
        // ];
        // foreach($core_banks as $row){
        //     $record = new \Modules\Core\Entities\Bank;
        //     $record->name = $row['name'];
        //     $record->address = $row['address'];
        //     $record->save();
        // }
        $em_departments = [
            ['label' =>'الورشة', 'name' =>'Workshop'],
            ['label' => 'معرض', 'name' =>'gallery'],
            ['label' => 'صيانة', 'name' =>'maintenance'],
            ['label' => 'صيانة-جنوب', 'name' =>'south_maintenance'],
            ['label' => 'صيانة-مسائي', 'name' =>'evening_maintenance'],
            ['label' => 'حارس -ليلي ', 'name' =>'night_watchman'],
        ];
        foreach($em_departments as $row){
            $record = new \Modules\Employees\Entities\Department;
            $record->name = $row['name'];
            $record->label = $row['label'];
            $record->save();
        }
    }
}
