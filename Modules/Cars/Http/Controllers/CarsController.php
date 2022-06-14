<?php

namespace Modules\Cars\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CarsController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;
    
    private $title = "ملفات السيارات";
    private $model = \Modules\Cars\Entities\Car::class;
    
    public function manage(){
        
        \Auth::user()->authorize('cars_module_cars_manage', 'view');
    
        $data['activePage'] = ['cars' => true];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];
    
        return view('cars::cars', $data);
    }
    public function datatable(Request $request){
        \Auth::user()->authorize('employees_module_cars_manage');
    
        $eloquent = $this->model::with(['employee','team','papers']);
    
        if((int) $request->filters_status){
            if (trim($request->id) !== "") {
                $eloquent->where('id', trim($request->id));
            }
            if (trim($request->plate_number) !== '') {
                $eloquent->where('plate_number', 'LIKE', '%'. $request->plate_number . '%');
            }
            if (trim($request->driving_license_number) !== '') {
                $eloquent->where('driving_license_number', 'LIKE', '%'. $request->driving_license_number . '%');
            }
            if (trim($request->driver_license_number) !== '') {
                $eloquent->where('driver_license_number', 'LIKE', '%'. $request->driver_license_number . '%');
            }
            if (trim($request->insurance_number) !== '') {
                $eloquent->where('insurance_number', 'LIKE', '%'. $request->insurance_number . '%');
            }
            if (trim($request->insurance_number) !== '') {
                $eloquent->where('insurance_number', 'LIKE', '%'. $request->insurance_number . '%');
            }
            if (trim($request->team_id) !== '') {
                $eloquent->whereHas('team', function($query) use ($request){
                    $query->where('id',trim($request->team_id));
                });
            }
            if (trim($request->created_at) !== "") {
                $eloquent->whereCreatedAt($request->created_at);
            }
            
        }
    
        $filters = [
            ['title' => 'اسم السيارة ',  'type' => 'select', 'name' => 'id', 'data' => ['options_source' => 'cars', 'has_empty' => true]],
            ['title' => 'رقم اللوحة', 'type' => 'input', 'name' => 'plate_number'],
            ['title' => 'رقم رخصة السيارة', 'type' => 'input', 'name' => 'driving_license_number'],
            ['title' => 'رقم رخصة السائق', 'type' => 'input', 'name' => 'driver_license_number'],
            ['title' => 'رقم تأمين السيارة', 'type' => 'input', 'name' => 'insurance_number'],
            ['title' => 'اسم الفريق', 'type' => 'input', 'type' => 'select', 'name' => 'team_id', 'data' => ['options_source' => 'teams', 'has_empty' => true]],
            ['title' =>  '  تاريخ الإنشاء', 'type' => 'input', 'name' => 'created_at', 'date_range' => true],
        
        ];
    
        $columns = [
            ['title' => 'اسم السيارة ', 'column' => 'type'],
            ['title' => 'رقم اللوحة', 'column' => 'plate_number'],
            ['title' => 'رقم رخصة السيارة', 'column' => 'driving_license_number'],
            ['title' => 'رقم رخصة السائق', 'column' => 'driver_license_number'],
            ['title' => 'السائق', 'column' => 'employee.full_name'],
            ['title' => 'رقم تأمين السيارة', 'column' => 'insurance_number'],
            ['title' => 'اسم الفريق', 'column' => 'team.name'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];
    
        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }

    public function create(){
        return [
            "title" => "اضافة سيارة جديد",
            "inputs" => [
                ['title' => 'نوع السيارة ', 'input' => 'input', 'name' => 'type', 'required' => true, 'operations' => ['show' => ['text' => 'type']]],
                ['title' => 'رقم اللوحة ', 'input' => 'input', 'name' => 'plate_number', 'required' => true, 'operations' => ['show' => ['text' => 'plate_number']]],
                ['title' => 'اسم السائق ', 'input' => 'select', 'name' => 'driver_id', 'required' => true, 'classes' => ['select2'], 'data' => ['options_source' => 'employees', 'placeholder' => 'اسم  السائق ...'],'operations' => ['show' => ['text' => 'employee.full_name', 'id' => 'employee.id']]],
                [
                    ['title' => 'رقم رخصة السيارة ', 'input' => 'input', 'name' => 'driving_license_number', 'required' => true, 'operations' => ['show' => ['text' => 'driving_license_number']]],
                    ['title' => 'صورة رخصة السيارة ', 'input' => 'input','type' => 'file', 'name' => 'driving_license_image'],
                ],
                [
                    ['title' => 'تاريخ بداية رخصة السيارة ', 'input' => 'input', 'name' => 'driving_license_stated_at','classes' => ['numeric'], 'date' => true, 'required' => true, 'operations' => ['show' => ['text' => 'papers[0].stated_at']]],
                    ['title' => 'تاريخ نهاية رخصة السيارة', 'input' => 'input', 'name' => 'driving_license_ended_at', 'classes' => ['numeric'], 'date' => true,'required' => true,'operations' => ['show' => ['text' => 'papers[0].ended_at']]],

                ],
                [
                    ['title' => 'رقم رخصة السائق ', 'input' => 'input', 'name' => 'driver_license_number', 'required' => true, 'operations' => ['show' => ['text' => 'driver_license_number']]],
                    ['title' => 'صورة رخصة السائق ', 'input' => 'input','type' => 'file', 'name' => 'driver_license_image'],
                ],
                [
                    ['title' => 'تاريخ بداية رخصة السائق ', 'input' => 'input', 'name' => 'driver_license_stated_at','classes' => ['numeric'], 'date' => true, 'required' => true, 'operations' => ['show' => ['text' => 'papers[1].stated_at']]],
                    ['title' => 'تاريخ نهاية رخصة السائق', 'input' => 'input', 'name' => 'driver_license_ended_at', 'classes' => ['numeric'], 'date' => true,'required' => true,'operations' => ['show' => ['text' => 'papers[1].ended_at']]],

                ],
                [
                    ['title' => 'رقم تأمين السيارة ', 'input' => 'input', 'name' => 'insurance_number', 'required' => true, 'operations' => ['show' => ['text' => 'insurance_number']]],
                    ['title' => 'صورة تأمين السيارة ', 'input' => 'input','type' => 'file', 'name' => 'insurance_image'],
                ],
                [
                    ['title' => 'تاريخ بداية تأمين السيارة ', 'input' => 'input', 'name' => 'insurance_stated_at','classes' => ['numeric'], 'date' => true, 'required' => true, 'operations' => ['show' => ['text' => 'papers[2].stated_at']]],
                    ['title' => 'تاريخ نهاية تأمين السيارة', 'input' => 'input', 'name' => 'insurance_ended_at', 'classes' => ['numeric'], 'date' => true,'required' => true,'operations' => ['show' => ['text' => 'papers[2].ended_at']]],

                ],
                
            ]
        ];
    }
    public function store(Request $request){
        \Auth::user()->authorize('cars_module_cars_store');

        $request->validate([
            'type' => 'required',
            'plate_number' => 'required',
            'driver_id' => 'required',
            'driving_license_number' => 'required',
            'driving_license_stated_at' => 'required',
            'driving_license_ended_at' => 'required',
            'driving_license_image' => 'required',
            'driver_license_number' => 'required',
            'driver_license_stated_at' => 'required',
            'driver_license_ended_at' => 'required',
            'driver_license_image' => 'required',
            'insurance_number' => 'required',
            'insurance_stated_at' => 'required',
            'insurance_ended_at' => 'required',
            'insurance_image' => 'required',
        ]);
         $plate_number = \Modules\Cars\Entities\Car::where('plate_number', $request->plate_number)->first();

        if($plate_number){
            return response()->json(['message' => "رقم اللوحة موجود مسبقا."], 403);
        }
       
        $employee_id = \Modules\Employees\Entities\Employee::whereId($request->driver_id)->first();
        if(!$employee_id){
            return response()->json(['message' => "يرجى التحقق من السائق ."], 403);
        }
       
        if($request->driving_license_stated_at > $request->driving_license_ended_at){
            return response()->json(['message' => "التاريخ المدخل غير منطقي ."], 403);
        }
        if($request->driver_license_stated_at > $request->driver_license_ended_at){
            return response()->json(['message' => "التاريخ المدخل غير منطقي ."], 403);
        }
        if($request->insurance_stated_at > $request->insurance_ended_at){
            return response()->json(['message' => "التاريخ المدخل غير منطقي ."], 403);
        }
        $team = \Modules\Employees\Entities\EmployeeTeam::where('employee_id', $request->driver_id)->first();
        \DB::beginTransaction();
        try {
            $car = new \Modules\Cars\Entities\Car;
            $car->type = $request->type;
            $car->plate_number = $request->plate_number;
            $car->driver_id = $request->driver_id;
            $car->driving_license_number = $request->driving_license_number;
            $car->driver_license_number = $request->driver_license_number;
            $car->insurance_number = $request->insurance_number;
            $car->team_id = $team->team_id;
            $car->save();

            $driver_license =new \Modules\Cars\Entities\CarPaper;
            $driver_license->car_id = $car->id;            
            $driver_license->type = 'رخصة_سائق';            
            $driver_license->stated_at = $request->driver_license_stated_at;            
            $driver_license->ended_at = $request->driver_license_ended_at;            
            if($request->hasFile('driver_license_image') && $request->file('driver_license_image')[0]->isValid()){
                $extension = strtolower($request->file('driver_license_image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "driver_license";

                $driver_license->addMediaFromRequest('driver_license_image[0]')
                        ->usingFileName($media_new_name)
                        ->usingName($request->file('driver_license_image')[0]->getClientOriginalName())
                        ->toMediaCollection($collection);
            }
            $driver_license->save();
            
            $driving_license =new \Modules\Cars\Entities\CarPaper;
            $driving_license->car_id = $car->id;            
            $driving_license->type = 'رخصة_سيارة';            
            $driving_license->stated_at = $request->driving_license_stated_at;            
            $driving_license->ended_at = $request->driving_license_ended_at;            
            if($request->hasFile('driving_license_image') && $request->file('driving_license_image')[0]->isValid()){
                $extension = strtolower($request->file('driving_license_image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "driving_license";

                $driving_license->addMediaFromRequest('driving_license_image[0]')
                        ->usingFileName($media_new_name)
                        ->usingName($request->file('driving_license_image')[0]->getClientOriginalName())
                        ->toMediaCollection($collection);
            }
            $driving_license->save();

            $insurance =new \Modules\Cars\Entities\CarPaper;
            $insurance->car_id = $car->id;            
            $insurance->type = 'تأمين';            
            $insurance->stated_at = $request->insurance_stated_at;            
            $insurance->ended_at = $request->insurance_ended_at;            
            if($request->hasFile('insurance_image') && $request->file('insurance_image')[0]->isValid()){
                $extension = strtolower($request->file('insurance_image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "insurance";

                $insurance->addMediaFromRequest('insurance_image[0]')
                        ->usingFileName($media_new_name)
                        ->usingName($request->file('insurance_image')[0]->getClientOriginalName())
                        ->toMediaCollection($collection);
            }
            $insurance->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
    public function show($id){
       return  $this->model::with(['employee','team','papers'])->whereId($id)->first();

    }
    public function update(Request $request, $id){
        \Auth::user()->authorize('cars_module_cars_update');

        $request->validate([
            'type' => 'required',
            'plate_number' => 'required',
            'driver_id' => 'required',
            'driving_license_number' => 'required',
            'driving_license_stated_at' => 'required',
            'driving_license_ended_at' => 'required',
            'driver_license_number' => 'required',
            'driver_license_stated_at' => 'required',
            'driver_license_ended_at' => 'required',
            'insurance_number' => 'required',
            'insurance_stated_at' => 'required',
            'insurance_ended_at' => 'required',
        ]);
         $plate_number = \Modules\Cars\Entities\Car::where('id', '<>', $id)->where('plate_number', $request->plate_number)->first();

        if($plate_number){
            return response()->json(['message' => "رقم اللوحة موجود مسبقا."], 403);
        }
       
        $employee_id = \Modules\Employees\Entities\Employee::whereId($request->driver_id)->first();
        if(!$employee_id){
            return response()->json(['message' => "يرجى التحقق من السائق ."], 403);
        }
       
        if($request->driving_license_stated_at > $request->driving_license_ended_at){
            return response()->json(['message' => "التاريخ المدخل غير منطقي ."], 403);
        }
        if($request->driver_license_stated_at > $request->driver_license_ended_at){
            return response()->json(['message' => "التاريخ المدخل غير منطقي ."], 403);
        }
        if($request->insurance_stated_at > $request->insurance_ended_at){
            return response()->json(['message' => "التاريخ المدخل غير منطقي ."], 403);
        }
        $team = \Modules\Employees\Entities\EmployeeTeam::where('employee_id', $request->driver_id)->first();
        \DB::beginTransaction();
        try {
            $car =  \Modules\Cars\Entities\Car::whereId($id)->first();
            $car->type = $request->type;
            $car->plate_number = $request->plate_number;
            $car->driver_id = $request->driver_id;
            $car->driving_license_number = $request->driving_license_number;
            $car->driver_license_number = $request->driver_license_number;
            $car->insurance_number = $request->insurance_number;
            $car->team_id = $team->team_id;
            $car->save();

            $driver_license = \Modules\Cars\Entities\CarPaper::where('car_id',$id)->where('type','رخصة_سائق')->first();
            $driver_license->stated_at = $request->driver_license_stated_at;            
            $driver_license->ended_at = $request->driver_license_ended_at;            
            if($request->hasFile('driver_license_image') && $request->file('driver_license_image')[0]->isValid()){
                $extension = strtolower($request->file('driver_license_image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "driver_license";

                $driver_license->addMediaFromRequest('driver_license_image[0]')
                        ->usingFileName($media_new_name)
                        ->usingName($request->file('driver_license_image')[0]->getClientOriginalName())
                        ->toMediaCollection($collection);
            }
            $driver_license->save();
            
            $driving_license = \Modules\Cars\Entities\CarPaper::where('car_id',$id)->where('type','رخصة_سيارة')->first();
            $driving_license->car_id = $car->id;            
            $driving_license->stated_at = $request->driving_license_stated_at;            
            $driving_license->ended_at = $request->driving_license_ended_at;            
            if($request->hasFile('driving_license_image') && $request->file('driving_license_image')[0]->isValid()){
                $extension = strtolower($request->file('driving_license_image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "driving_license";

                $driving_license->addMediaFromRequest('driving_license_image[0]')
                        ->usingFileName($media_new_name)
                        ->usingName($request->file('driving_license_image')[0]->getClientOriginalName())
                        ->toMediaCollection($collection);
            }

            $driving_license->save();

            $insurance = \Modules\Cars\Entities\CarPaper::where('car_id',$id)->where('type','تأمين')->first();;
            $insurance->car_id = $car->id;            
            $insurance->stated_at = $request->insurance_stated_at;            
            $insurance->ended_at = $request->insurance_ended_at;            
            if($request->hasFile('insurance_image') && $request->file('insurance_image')[0]->isValid()){
                $extension = strtolower($request->file('insurance_image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "insurance";

                $insurance->addMediaFromRequest('insurance_image[0]')
                        ->usingFileName($media_new_name)
                        ->usingName($request->file('insurance_image')[0]->getClientOriginalName())
                        ->toMediaCollection($collection);
            }
            $insurance->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
}
