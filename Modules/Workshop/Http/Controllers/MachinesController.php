<?php

namespace Modules\Workshop\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MachinesController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "ملفات  الورشة";
    private $model = \Modules\Workshop\Entities\machine::class;

    public function index(Request $request){
        $response = $this->model::with([]);
        if ((int) trim($request->all)) {
            return ["data" => $response->get()];
        }
        return $response->paginate(20);
    }
    public function manage(){
        \Auth::user()->authorize('workshops_module_machines_manage');

        $data['activePage'] = ['machines' => true];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('workshop::machines', $data);
    }

    public function datatable(Request $request){
        \Auth::user()->authorize('workshops_module_machines_manage');

        $eloquent = $this->model::with(['created_by_user']);

        if((int) $request->filters_status){
            if(trim($request->type_of_machine) !== ""){
                $eloquent->where('type_of_machine', 'LIKE', "%".trim($request->type_of_machine).'%');
            }
            if(trim($request->model_of_machine) !== ""){
                $eloquent->where('model_of_machine', 'LIKE', "%".trim($request->model_of_machine).'%');
            }
            if(trim($request->machine_number) !== ""){
                $eloquent->where('machine_number', 'LIKE', "%".trim($request->machine_number).'%');
            }
            if (trim($request->created_at) !== "") {
                $eloquent->whereCreatedAt($request->created_at);
            }
        }

        $filters = [
            ['title' => 'نوع المكينة', 'type' => 'input', 'name' => 'type_of_machine'],
            ['title' => 'موديل المكينة', 'type' => 'input', 'name' => 'model_of_machine'],
            ['title' => 'رقم المكينة', 'type' => 'input', 'name' => 'machine_number'],
            ['title' =>  ' تاريخ الإنشاء', 'type' => 'input', 'name' => 'created_at', 'date_range' => true],
        ];

        $columns = [
            ['title' => 'نوع المكينة', 'column' => 'type_of_machine'],
            ['title' => 'موديل المكينة', 'column' => 'model_of_machine'],
            ['title' => 'رقم المكينة', 'column' => 'machine_number'],
            ['title' => 'بواسطة', 'column' => 'created_by_user.name'],
            ['title' => 'تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }

    public function create(){
        return [
            "title" => "اضافة مولد جديد",
            "inputs" => [
               
                ['title' => 'نوع المكينة ', 'input' => 'input', 'name' => 'type_of_machine', 'required' => true,'operations' => ['show' => ['text' => 'type_of_machine']]],
                ['title' => 'موديل المكينة ', 'input' => 'input', 'name' => 'model_of_machine', 'required' => true,'operations' => ['show' => ['text' => 'model_of_machine']]],
                ['title' => 'رقم المكينة ', 'input' => 'input', 'name' => 'machine_number', 'required' => true,'operations' => ['show' => ['text' => 'machine_number']]],
            ]
        ];
    }
    public function store(Request $request){
        
        \Auth::user()->authorize('workshops_module_machines_store');

        $request->validate([
            'type_of_machine' => 'required',
            'model_of_machine' => 'required',
            'machine_number' => 'required',
        ]);
         $machine = \Modules\Workshop\Entities\Machine::where('machine_number', $request->machine_number)->first();

        if($machine){
            return response()->json(['message' => "رقم المكينة موجود مسبقا."], 403);
        }
 
        \DB::beginTransaction();
        try {
            $machine = new \Modules\Workshop\Entities\Machine();
            $machine->type_of_machine = $request->type_of_machine;
            $machine->model_of_machine = $request->model_of_machine;
            $machine->machine_number = $request->machine_number;
            $machine->created_by = \Auth::user()->id;
            $machine->save();

            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "machine_image";

                $machine->addMediaFromRequest('image[0]')
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
        
        \Auth::user()->authorize('workshops_module_machines_update');

        $request->validate([
            'type_of_machine' => 'required',
            'model_of_machine' => 'required',
            'machine_number' => 'required',
        ]);
         $machine = \Modules\Workshop\Entities\machine::where('id' , '<>', $id)->where('machine_number', $request->machine_number)->first();

        if($machine){
            return response()->json(['message' => "رقم المكينة موجود مسبقا."], 403);
        }
 
        \DB::beginTransaction();
        try {
            $machine =  \Modules\Workshop\Entities\machine::whereId($id)->first();
            $machine->type_of_machine = $request->type_of_machine;
            $machine->model_of_machine = $request->model_of_machine;
            $machine->machine_number = $request->machine_number;
            $machine->created_by = \Auth::user()->id;
            $machine->save();

            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "machine_image";

                $machine->addMediaFromRequest('image[0]')
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
