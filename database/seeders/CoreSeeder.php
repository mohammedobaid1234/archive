<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CoreSeeder extends Seeder{

    public function run(){

        $core_currencies = [
            ['id' => 'ILS', 'name' => 'شيكل', 'symbol' => 'ILS'],
            ['id' => 'JOD', 'name' => 'دينار أردني', 'symbol' => 'JOD'],
            ['id' => 'USD', 'name' => 'دولار أمريكي', 'symbol' => 'USD'],
        ];

        foreach($core_currencies as $row){
            $record = new \Modules\Core\Entities\Currency;
            $record->id = $row['id'];
            $record->name = $row['name'];
            $record->symbol = $row['symbol'];
            $record->save();
        }

        $core_countries = [
            [ 'name' => 'فلسطين', 'created_by' =>1],
          
        ];

        foreach($core_countries as $row){
            $record = new \Modules\Core\Entities\Country;
            $record->name = $row['name'];
            $record->created_by = $row['created_by'];
            $record->save();
        }

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
    }
}
