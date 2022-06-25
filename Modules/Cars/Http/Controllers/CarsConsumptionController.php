<?php

namespace Modules\Cars\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CarsConsumptionController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;
    
    private $title = "ملفات استهلاك السيارات";
    private $model = \Modules\Cars\Entities\carConsumption::class;
    
    public function manage(){
        
        \Auth::user()->authorize('cars_module_cars_consumption_manage', 'view');
    
        $data['activePage'] = ['cars_consumption' => true];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];
    
        return view('cars::cars_consumption', $data);
    }
    public function datatable(Request $request){
        \Auth::user()->authorize('cars_module_cars_maintenance_manage');
    
        $eloquent = $this->model::with(['car','driver']);
    
        if((int) $request->filters_status){
            if (trim($request->car_id) !== "") {
                $eloquent->whereHas('car', function($query) use ($request){
                    $query->where('id', trim($request->car_id));
                });
            }
            if (trim($request->driver_id) !== "") {
                $eloquent->whereHas('driver', function($query) use ($request){
                    $query->where('id', trim($request->driver_id));
                });
            }
            if (trim($request->created_at) !== "") {
                $eloquent->whereCreatedAt($request->created_at);
            }
            if (trim($request->packing_date) !== "") {
                $eloquent->WherePackingDate($request->packing_date);
            }
        }
    
        $filters = [
            ['title' => 'اسم السيارة ',  'type' => 'select', 'name' => 'car_id', 'data' => ['options_source' => 'cars', 'has_empty' => true]],
            ['title' => 'اسم الموظف', 'type' => 'input', 'type' => 'select', 'name' => 'driver_id', 'data' => ['options_source' => 'employees', 'has_empty' => true]],
            ['title' =>  '  تاريخ التعبئة', 'type' => 'input', 'name' => 'packing_date', 'date_range' => true],
            ['title' =>  '  تاريخ الإنشاء', 'type' => 'input', 'name' => 'created_at', 'date_range' => true],
        ];
    
        $columns = [
            ['title' => 'اسم السيارة ', 'column' => 'car.type'],
            ['title' => 'رقم اللوحة', 'column' => 'car.plate_number'],
            ['title' => 'رقم رخصة السيارة', 'column' => 'car.driving_license_number'],
            ['title' => 'الكمية (اللتر)', 'column' => 'quantity'],
            ['title' => 'القيمة (الشيكل)', 'column' => 'amount'],
            ['title' => 'تاريخ التعبئة', 'column' => 'packing_date'],
            ['title' =>' تفاصيل اخرى', 'column' => 'details','formatter' => 'notes'],
            ['title' =>'صورة المستند', 'column' => 'image',  'formatter' => 'image'],
            ['title' => 'اسم الموظف', 'column' => 'driver.full_name'],
            ['title' => 'تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];
    
        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
    public function show($id){
        return $this->model::with(['car','driver'])->first();

    }
    public function store(Request $request){
        \Auth::user()->authorize('cars_module_cars_consumption_store');
        $request->validate([
            'car_id' => 'required',
            'quantity' => 'required',
            'amount' => 'required',
            'note' => 'required',
            'packing_date' => 'required',
        ]);
        $plate_number = \Modules\Cars\Entities\Car::
        where('id', $request->car_id)
        ->first();

        if(!$plate_number){
            return response()->json(['message' => "رقم السيارة غير موجود ."], 403);
        }
        \DB::beginTransaction();
        try {
            $car = \Modules\Cars\Entities\Car::whereId($request->car_id)->first();
            $carConsumption =new \Modules\Cars\Entities\carConsumption();
            $carConsumption->car_id = $request->car_id;            
            $carConsumption->driver_id = $request->driver_id;            
            $carConsumption->quantity = $request->quantity;            
            $carConsumption->amount = $request->amount;            
            $carConsumption->packing_date = $request->packing_date;            
            $carConsumption->note = $request->note;            
            $carConsumption->created_by = \Auth::user()->id;            
            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "invoice-car-consumption-image";

                $carConsumption->addMediaFromRequest('image[0]')
                        ->usingFileName($media_new_name)
                        ->usingName($request->file('image')[0]->getClientOriginalName())
                        ->toMediaCollection($collection);
            }
            $carConsumption->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
    public function update(Request $request, $id){
        \Auth::user()->authorize('cars_module_cars_consumption_update');
        $request->validate([
            'car_id' => 'required',
            'quantity' => 'required',
            'amount' => 'required',
            'note' => 'required',
            'packing_date' => 'required',
        ]);
        $plate_number = \Modules\Cars\Entities\Car::
        where('id', $request->car_id)
        ->first();

        if(!$plate_number){
            return response()->json(['message' => "رقم السيارة غير موجود ."], 403);
        }
        \DB::beginTransaction();
        try {
            $car = \Modules\Cars\Entities\Car::whereId($request->car_id)->first();
            $carConsumption = \Modules\Cars\Entities\carConsumption::whereId($id)->first();
            $carConsumption->car_id = $request->car_id;            
            $carConsumption->driver_id = $request->driver_id;            
            $carConsumption->quantity = $request->quantity;            
            $carConsumption->amount = $request->amount;            
            $carConsumption->note = $request->note;            
            $carConsumption->packing_date = $request->packing_date;            
            $carConsumption->created_by = \Auth::user()->id;            
            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "invoice-car-consumption-image";

                $carConsumption->addMediaFromRequest('image[0]')
                        ->usingFileName($media_new_name)
                        ->usingName($request->file('image')[0]->getClientOriginalName())
                        ->toMediaCollection($collection);
            }
            $carConsumption->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
    
}
