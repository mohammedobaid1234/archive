<?php

namespace Modules\Cars\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CarsMaintenancesController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;
    
    private $title = "ملفات صيانة السيارات";
    private $model = \Modules\Cars\Entities\CarMaintenance::class;
    
    public function manage(){
        
        \Auth::user()->authorize('cars_module_cars_maintenance_manage', 'view');
    
        $data['activePage'] = ['cars_maintenance' => true];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];
    
        return view('cars::cars_maintenance', $data);
    }
    public function datatable(Request $request){
        \Auth::user()->authorize('cars_module_cars_maintenance_manage');
    
        $eloquent = $this->model::with(['car','employee']);
    
        if((int) $request->filters_status){
            if (trim($request->car_id) !== "") {
                $eloquent->whereHas('car', function($query) use ($request){
                    $query->where('id', trim($request->car_id));
                });
            }
            if (trim($request->employee_id) !== "") {
                $eloquent->whereHas('employee', function($query) use ($request){
                    $query->where('id', trim($request->employee_id));
                });
            }
            // if (trim($request->team_id) !== "") {
            //     $eloquent->whereHas('car.team', function($query) use ($request){
            //         $query->where('id', trim($request->team_id));
            //     });
            // }
            if (trim($request->created_at) !== "") {
                $eloquent->whereCreatedAt($request->created_at);
            }
        }
    
        $filters = [
            ['title' => 'اسم السيارة ',  'type' => 'select', 'name' => 'car_id', 'data' => ['options_source' => 'cars', 'has_empty' => true]],
            // ['title' => 'اسم الفريق', 'type' => 'input', 'type' => 'select', 'name' => 'team_id', 'data' => ['options_source' => 'teams', 'has_empty' => true]],
            ['title' => 'اسم الموظف القائم بالصيانة', 'type' => 'input', 'type' => 'select', 'name' => 'employee_id', 'data' => ['options_source' => 'employees', 'has_empty' => true]],
            ['title' =>  '  تاريخ الإنشاء', 'type' => 'input', 'name' => 'created_at', 'date_range' => true],
        ];
    
        $columns = [
            ['title' => 'اسم السيارة ', 'column' => 'car.type'],
            ['title' => 'رقم اللوحة', 'column' => 'car.plate_number'],
            ['title' => 'رقم رخصة السيارة', 'column' => 'car.driving_license_number'],
            ['title' =>' محتوى الصيانة', 'column' => 'details','formatter' => 'details'],
            ['title' =>'صورة المستند', 'column' => 'image',  'formatter' => 'image'],
            ['title' => 'تاريخ الصيانة', 'column' => 'maintenance_date'],
            ['title' => 'اسم الموظف المسؤول ', 'column' => 'employee.full_name'],
            ['title' => 'تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];
    
        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
    public function create(){
        return [
            "title" => "اضافة تفاصيل صيانة جديد",
            "inputs" => [
                ['title' => 'نوع السيارة ', 'input' => 'select', 'name' => 'car_id', 'required' => true,'classes' => ['select2'],'data' => ['options_source' => 'cars', 'placeholder' => 'اسم السيارة ...'], 'operations' => ['show' => ['text' => 'car.type', 'id' => 'car.id']]],
                ['title' => 'اسم  الموظف ', 'input' => 'select', 'name' => 'employee_id', 'required' => true,'classes' => ['select2'],'data' => ['options_source' => 'employees', 'placeholder' => 'اسم الموظف المسؤول ...'], 'operations' => ['show' => ['text' => 'employee.full_name', 'id' => 'employee.id']]],
                ['title' => 'رقم اللوحة ', 'input' => 'input', 'name' => 'plate_number', 'required' => true, 'disabled'=>'disabled','operations' => ['show' => ['text' => 'car.plate_number']]],
                ['title' => 'تاريخ عمل الصيانة ', 'input' => 'input', 'name' => 'maintenance_date', 'required' => true, 'date' => true,'operations' => ['show' => ['text' => 'maintenance_date']]],
                ['title' => 'تفاصيل الصيانة ', 'input' => 'textarea', 'name' => 'details','required' => true, 'operations' => ['show' => ['text' => 'details']]],
                ['title' => 'صورة المستند', 'input' => 'input','type' => 'file', 'name' => 'image'],

                ['title' => 'رقم اللوحة ', 'input' => 'input', 'name' => 'plate_number', 'required' => true, 'disabled'=>'disabled','operations' => ['show' => ['text' => 'car.plate_number']]],

                
            ]
        ]; 
    }
    public function store(Request $request){
        \Auth::user()->authorize('cars_module_cars_papers_store');
        $request->validate([
            'car_id' => 'required',
            'details' => 'required',
            'employee_id' => 'required',
            'maintenance_date' => 'required',

        ]);
        $plate_number = \Modules\Cars\Entities\Car::
        where('id', $request->car_id)
        ->first();

        if(!$plate_number){
            return response()->json(['message' => "رقم السيارة غير موجود ."], 403);
        }
        \DB::beginTransaction();
        try {
            $paper =new \Modules\Cars\Entities\CarMaintenance;
            $paper->car_id = $request->car_id;            
            $paper->employee_id = $request->employee_id;            
            $paper->maintenance_date = $request->maintenance_date;            
            $paper->details = $request->details;            
            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "maintenance_car";

                $paper->addMediaFromRequest('image[0]')
                        ->usingFileName($media_new_name)
                        ->usingName($request->file('image')[0]->getClientOriginalName())
                        ->toMediaCollection($collection);
            }
            $paper->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
    
    public function show($id){
        return  $this->model::with(['car','employee'])->whereId($id)->first();
    }

    public function update(Request $request, $id){
        \Auth::user()->authorize('cars_module_cars_maintenance_update');
        $request->validate([
            'car_id' => 'required',
            'details' => 'required',
            'employee_id' => 'required',
            'maintenance_date' => 'required',
        ]);
        $plate_number = \Modules\Cars\Entities\Car::
        where('id', $request->car_id)
        ->first();

        if(!$plate_number){
            return response()->json(['message' => "رقم السيارة غير موجود ."], 403);
        }
        \DB::beginTransaction();
        try {
            $paper = \Modules\Cars\Entities\CarMaintenance::whereId($id)->first();
            $paper->car_id = $request->car_id;  
            $paper->employee_id = $request->employee_id;            
            $paper->maintenance_date = $request->maintenance_date;            

            $paper->details = $request->details;            
            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "maintenance_car";

                $paper->addMediaFromRequest('image[0]')
                        ->usingFileName($media_new_name)
                        ->usingName($request->file('image')[0]->getClientOriginalName())
                        ->toMediaCollection($collection);
            }
            $paper->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
   
}
