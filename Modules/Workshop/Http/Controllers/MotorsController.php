<?php

namespace Modules\Workshop\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MotorsController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "ملفات مولدات الورشة";
    private $model = \Modules\Workshop\Entities\Motor::class;

    public function index(Request $request){
        $response = $this->model::with([]);
        if ((int) trim($request->all)) {
            return ["data" => $response->get()];
        }
        return $response->paginate(20);
    }
    public function manage(){
        \Auth::user()->authorize('workshops_module_motors_manage');

        $data['activePage'] = ['motors' => true];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('workshop::motors', $data);
    }

    public function datatable(Request $request){
        \Auth::user()->authorize('workshops_module_motors_manage');

        $eloquent = $this->model::with(['created_by_user']);

        if((int) $request->filters_status){
            if(trim($request->type_of_motor) !== ""){
                $eloquent->where('type_of_motor', 'LIKE', "%".trim($request->type_of_motor).'%');
            }
            if(trim($request->model_of_motor) !== ""){
                $eloquent->where('model_of_motor', 'LIKE', "%".trim($request->model_of_motor).'%');
            }
            if(trim($request->motor_number) !== ""){
                $eloquent->where('motor_number', 'LIKE', "%".trim($request->motor_number).'%');
            }
            if(trim($request->motor_capacity) !== ""){
                $eloquent->where('motor_capacity', 'LIKE', "%".trim($request->motor_capacity).'%');
            }
            if(trim($request->type_of_engine) !== ""){
                $eloquent->where('type_of_engine', 'LIKE', "%".trim($request->type_of_engine).'%');
            }
            if(trim($request->model_of_engine) !== ""){
                $eloquent->where('model_of_engine', 'LIKE', "%".trim($request->model_of_engine).'%');
            }
            if(trim($request->engine_number) !== ""){
                $eloquent->where('engine_number', 'LIKE', "%".trim($request->engine_number).'%');
            }
            if (trim($request->created_at) !== "") {
                $eloquent->whereCreatedAt($request->created_at);
            }
        }

        $filters = [
            ['title' => 'نوع المولد', 'type' => 'input', 'name' => 'type_of_motor'],
            ['title' => 'موديل المولد', 'type' => 'input', 'name' => 'model_of_motor'],
            ['title' => 'رقم المولد', 'type' => 'input', 'name' => 'motor_number'],
            ['title' => 'سعة المولد', 'type' => 'input', 'name' => 'motor_capacity'],
            ['title' => 'نوع المحرك', 'type' => 'input', 'name' => 'type_of_engine'],
            ['title' => 'موديل المحرك', 'type' => 'input', 'name' => 'model_of_engine'],
            ['title' => 'رقم المحرك', 'type' => 'input', 'name' => 'engine_number'],
            ['title' =>  ' تاريخ الإنشاء', 'type' => 'input', 'name' => 'created_at', 'date_range' => true],
        ];

        $columns = [
            ['title' => 'نوع المولد', 'column' => 'type_of_motor'],
            ['title' => 'موديل المولد', 'column' => 'model_of_motor'],
            ['title' => 'رقم المولد', 'column' => 'motor_number'],
            ['title' => 'سعة المولد', 'column' => 'motor_capacity'],
            ['title' => 'نوع المحرك', 'column' => 'type_of_engine'],
            ['title' => 'موديل المحرك', 'column' => 'model_of_engine'],
            ['title' => 'رقم المحرك', 'column' => 'engine_number'],
            ['title' => 'بواسطة', 'column' => 'created_by_user.name'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }

    public function create(){
        return [
            "title" => "اضافة مولد جديد",
            "inputs" => [
                [
                    ['title' => 'نوع المولد ', 'input' => 'input', 'name' => 'type_of_motor', 'required' => true,'operations' => ['show' => ['text' => 'type_of_motor']]],
                    ['title' => 'موديل المولد ', 'input' => 'input', 'name' => 'model_of_motor', 'required' => true,'operations' => ['show' => ['text' => 'model_of_motor']]],
                ],
                [
                    ['title' => 'رقم المولد ', 'input' => 'input', 'name' => 'motor_number', 'required' => true,'operations' => ['show' => ['text' => 'motor_number']]],
                    ['title' => 'قدرة المولد ', 'input' => 'input', 'name' => 'motor_capacity', 'required' => true,'operations' => ['show' => ['text' => 'motor_capacity']]],

                ],
                [
                    ['title' => 'نوع المحرك ', 'input' => 'input', 'name' => 'type_of_engine', 'required' => true,'operations' => ['show' => ['text' => 'type_of_engine']]],
                    ['title' => 'موديل المحرك ', 'input' => 'input', 'name' => 'model_of_engine', 'required' => true,'operations' => ['show' => ['text' => 'model_of_engine']]],
                    ['title' => 'رقم المحرك ', 'input' => 'input', 'name' => 'engine_number', 'required' => true,'operations' => ['show' => ['text' => 'engine_number']]],
                ],
            ]
        ];
    }
    public function store(Request $request){
        
        \Auth::user()->authorize('workshops_module_motors_store');

        $request->validate([
            'type_of_motor' => 'required',
            'model_of_motor' => 'required',
            'motor_number' => 'required',
            'motor_capacity' => 'required',
            'type_of_engine' => 'required',
            'model_of_engine' => 'required',
            'engine_number' => 'required',
        ]);
         $motor = \Modules\Workshop\Entities\Motor::where('motor_number', $request->motor_number)->first();

        if($motor){
            return response()->json(['message' => "رقم المولد موجود مسبقا."], 403);
        }
 
        \DB::beginTransaction();
        try {
            $motor = new \Modules\Workshop\Entities\Motor;
            $motor->type_of_motor = $request->type_of_motor;
            $motor->model_of_motor = $request->model_of_motor;
            $motor->motor_number = $request->motor_number;
            $motor->motor_capacity = $request->motor_capacity;
            $motor->type_of_engine = $request->type_of_engine;
            $motor->model_of_engine = $request->model_of_engine;
            $motor->engine_number = $request->engine_number;
            $motor->created_by = \Auth::user()->id;
            $motor->save();

            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "motor_image";

                $motor->addMediaFromRequest('image[0]')
                        ->usingFileName($media_new_name)
                        ->usingName($request->file('image')[0]->getClientOriginalName())
                        ->toMediaCollection($collection);
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
    
    public function show($id){
        return $this->model::with(['created_by_user'])->whereId($id)->first();
    }

    public function update(Request $request, $id){
        
        \Auth::user()->authorize('workshops_module_motors_update');

        $request->validate([
            'type_of_motor' => 'required',
            'model_of_motor' => 'required',
            'motor_number' => 'required',
            'motor_capacity' => 'required',
            'type_of_engine' => 'required',
            'model_of_engine' => 'required',
            'engine_number' => 'required',
        ]);
         $motor = \Modules\Workshop\Entities\Motor::where('id' , '<>', $id)->where('motor_number', $request->motor_number)->first();

        if($motor){
            return response()->json(['message' => "رقم المولد موجود مسبقا."], 403);
        }
 
        \DB::beginTransaction();
        try {
            $motor =  \Modules\Workshop\Entities\Motor::whereId($id)->first();
            $motor->type_of_motor = $request->type_of_motor;
            $motor->model_of_motor = $request->model_of_motor;
            $motor->motor_number = $request->motor_number;
            $motor->motor_capacity = $request->motor_capacity;
            $motor->type_of_engine = $request->type_of_engine;
            $motor->model_of_engine = $request->model_of_engine;
            $motor->engine_number = $request->engine_number;
            $motor->created_by = \Auth::user()->id;
            $motor->save();

            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "motor_image";

                $motor->addMediaFromRequest('image[0]')
                        ->usingFileName($media_new_name)
                        ->usingName($request->file('image')[0]->getClientOriginalName())
                        ->toMediaCollection($collection);
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
}
