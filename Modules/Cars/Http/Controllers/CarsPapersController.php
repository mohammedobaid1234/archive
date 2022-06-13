<?php

namespace Modules\Cars\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CarsPapersController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;
    
    private $title = "ملفات أوراق السيارات";
    private $model = \Modules\Cars\Entities\CarPaper::class;
    public function manage(){
        
        \Auth::user()->authorize('cars_module_cars_papers_manage', 'view');
    
        $data['activePage'] = ['cars_papers' => true];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];
    
        return view('cars::cars_papers', $data);
    }
    public function datatable(Request $request){
        \Auth::user()->authorize('cars_module_cars_papers_manage');
    
        $eloquent = $this->model::with(['car']);
    
        if((int) $request->filters_status){
           
        }
    
        $filters = [
            ['title' => 'اسم السيارة ', 'type' => 'input', 'name' => 'type'],
            ['title' => 'رقم اللوحة', 'type' => 'input', 'name' => 'plate_number'],
            ['title' => 'رقم رخصة السيارة', 'type' => 'input', 'name' => 'driving_license_number'],
            ['title' => 'رقم رخصة السائق', 'type' => 'input', 'name' => 'driver_license_number'],
            ['title' => 'رقم تأمين السيارة', 'type' => 'input', 'name' => 'insurance_number'],
            ['title' => 'اسم الفريق', 'type' => 'input', 'name' => 'team.name'],
        ];
    
        $columns = [
            ['title' => 'اسم السيارة ', 'column' => 'car.type'],
            ['title' => 'رقم اللوحة', 'column' => 'car.plate_number'],
            ['title' => 'رقم رخصة السيارة', 'column' => 'car.driving_license_number'],
            ['title' => 'رقم رخصة السائق', 'column' => 'car.driver_license_number'],
            ['title' => 'رقم تأمين السيارة', 'column' => 'car.insurance_number'],
            ['title' =>'نوع المستند', 'column' => 'type'],
            ['title' =>'صورة المستند', 'column' => 'image',  'formatter' => 'image'],
            ['title' => 'تاريخ بداية', 'column' => 'stated_at'],
            ['title' => 'تاريخ النهاية', 'column' => 'ended_at'],
            ['title' => 'اسم الفريق', 'column' => 'car.team.name'],
            ['title' => 'تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];
    
        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
    public function create(){
        return [
            "title" => "اضافة ورقة جديد",
            "inputs" => [
                ['title' => 'نوع السيارة ', 'input' => 'select', 'name' => 'car_id', 'required' => true,'classes' => ['select2'],'data' => ['options_source' => 'cars', 'placeholder' => 'اسم السيارة ...'], 'operations' => ['show' => ['text' => 'car.type', 'id' => 'car.id']]],
                ['title' => 'رقم اللوحة ', 'input' => 'input', 'name' => 'plate_number', 'required' => true, 'disabled'=>'disabled','operations' => ['show' => ['text' => 'car.plate_number']]],
                ['title' => 'نوع المستند ', 'input' => 'select', 'name' => 'type',"rowIndex" => 1, 'required' => true,'classes' => ['select2'],'data' => ['options_source' => 'type_in_papers', 'placeholder' => 'نوع المستند ...'], 'operations' => ['show' => ['text' => 'type', 'id' => 'id']]],
                [
                    ['title' => 'تاريخ بداية المسنمد ', 'input' => 'input', 'name' => 'stated_at','classes' => ['numeric'], 'date' => true, 'required' => true, 'operations' => ['show' => ['text' => 'stated_at']]],
                    ['title' => 'تاريخ نهاية المسنمد', 'input' => 'input', 'name' => 'ended_at', 'classes' => ['numeric'], 'date' => true,'required' => true,'operations' => ['show' => ['text' => 'ended_at']]],
                    ['title' => 'صورة المستند', 'input' => 'input','type' => 'file', 'name' => 'image'],
                ],
                
            ]
        ]; 
    }
    public function store(Request $request){
        \Auth::user()->authorize('cars_module_cars_papers_store');
        $request->validate([
            'car_id' => 'required',
            'type' => 'required',
            'stated_at' => 'required',
            'ended_at' => 'required',
        ]);
        $plate_number = \Modules\Cars\Entities\Car::
        where('id', $request->car_id)
        ->first();

        if(!$plate_number){
            return response()->json(['message' => "رقم السيارة غير موجود ."], 403);
        }
        \DB::beginTransaction();
        try {
            $paper =new \Modules\Cars\Entities\CarPaper;
            $paper->car_id = $request->car_id;            
            $paper->type = $request->type;            
            $paper->stated_at = $request->stated_at;            
            $paper->ended_at = $request->ended_at;            
            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                if($request->type == "تأمين"){
                    $collection = "paper";
                }else if($request->type == "رخصة_سيارة"){
                    $collection = "driving_license";
                }else{
                    $collection = "driver_license";
                }

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
       return  $this->model::with(['car'])->whereId($id)->first();
    }

    public function update(Request $request ,$id){
        \Auth::user()->authorize('cars_module_cars_papers_update');
        $request->validate([
            'car_id' => 'required',
            'type' => 'required',
            'stated_at' => 'required',
            'ended_at' => 'required',
        ]);

        $plate_number = \Modules\Cars\Entities\Car::
        where('id', $request->car_id)
        ->first();

        if(!$plate_number){
            return response()->json(['message' => "رقم السيارة غير موجود ."], 403);
        }
        \DB::beginTransaction();
        try {
            $paper = \Modules\Cars\Entities\CarPaper::whereId($id)->first();
            $paper->car_id = $request->car_id;            
            $paper->type = $request->type;            
            $paper->stated_at = $request->stated_at;            
            $paper->ended_at = $request->ended_at;            
            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                if($request->type == "تأمين"){
                    $collection = "insurance_image";
                }else if($request->type == "رخصة_سيارة"){
                    $collection = "driving_license";
                }else{
                    $collection = "driver_license";
                }

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
