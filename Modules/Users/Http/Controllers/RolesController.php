<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class RolesController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "إدارة أدوار النظام";
    private $model = \Spatie\Permission\Models\Role::class;

    public function manage(Request $request){
        // $this->can('users_module_roles_manage', 'view');

        $data['activePage'] = ['users' => 'roles'];
        $data['breadcrumb'] = [
            ['title' => "إدارة المستخدمين"],
            ['title' => $this->title]
        ];

        return view("users::roles", $data);
    }

    public function datatable(Request $request){
        $this->can('users_module_roles_manage');

        $eloquent = $this->model::with([]);

        $columns = [
            ['title' => 'الاسم (en)', 'column' => 'name'],
            ['title' => 'الاسم (ع)', 'column' => 'label'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters = [], $columns));
    }

    public function create(){
        return [
            'inputs' => [
                [
                    'title' => 'الاسم (en)',
                    'input' => 'input',
                    'name' => 'name',
                    'required' => true,
                    'operations' => [
                        'show' => ['text' => 'name']
                    ]
                ],
                [
                    'title' => 'الاسم (ع)',
                    'input' => 'input',
                    'name' => 'label',
                    'required' => true,
                    'operations' => [
                        'show' => ['text' => 'label']
                    ]
                ]
            ]
        ];
    }

    public function store(Request $request){
        $this->can('users_module_roles_store');

        $request->validate([
            'name' => 'required|string',
            'label' => 'required|string'
        ]);

        \DB::beginTransaction();
        try{
            $role = new $this->model;
            $role->name = str_replace(" ", "_", trim($request->name));
            $role->label = trim($request->label);
            $role->save();

            \DB::commit();
            return response()->json(['message' => 'ok']);
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'fail'], 403);
    }

    public function show(Request $request, $id){
        return $this->model::whereId($id)->first();
    }

    public function update(Request $request, $id){
        $this->can('users_module_roles_update');

        \DB::beginTransaction();
        try{
            $role = $this->model::whereId($id)->first();
            $role->name = str_replace(" ", "_", trim($request->name));
            $role->label = trim($request->label);
            $role->save();

            \DB::commit();
            return response()->json(['message' => 'ok']);
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'fail'], 403);
    }

    public function employees_roles(Request $request){
        $response = $this->model::select('*');

        if(in_array('scopeActive', get_class_methods($this->model))){
            $response->active();
        }

        $response->orderBy('created_at', 'DESC');

        if($request->has('search') && trim($request->search)){
            if(is_numeric(trim($request->search))){
                $response->where('id', trim($request->search));
            }else{
                $response->where((trim($request->where_like_column) === "" ? 'name' : trim($request->where_like_column)), 'like', ('%' . trim($request->search) . '%'));
            }
        }

        if($request->has('page')){
            return $response->paginate(20);
        }

        return ["data" => $response->get()];
    }
}
