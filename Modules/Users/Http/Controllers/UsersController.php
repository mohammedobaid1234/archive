<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;
    private $title = "مستخدمين النظام";
    private $model = \Modules\Users\Entities\User::class;
    
    public function manage(){
        $this->can('users_module_users_manage', 'view');
        $data['activePage'] = ['users' => 'users'];
        $data['breadcrumb'] = [
            ['title' => "إدارة النظام"],
            ['title' => $this->title]
        ];

        return view("users::users", $data);
    }

    public function datatable(Request $request){
        $this->can('users_module_users_manage');

        $eloquent = $this->model::with(['userable', 'roles']);
        if((int) $request->filters_status){
            if(trim($request->full_name) !== ""){
                $eloquent->whereHas('userable', function($query) use ($request){
                    $query->whereFullNameLike($request->full_name);
                });
            }
            if(trim($request->roles) !== ""){
                $eloquent->whereHas('roles', function($query) use ($request){
                    $query->whereIn('name', explode(',', str_replace(" ", "", $request->roles)));
                });
            }
           
            if(trim($request->created_at) !== ""){
                $eloquent->whereCreatedAt($request->created_at);
            }
           
        }
        $filters = [
            ['title' => 'الاسم', 'type' => 'input', 'name' => 'full_name'],
            ['title' => 'الأدوار', 'type' => 'select', 'name' => 'roles', 'multiple' => true, 'data' => ['options_source' => 'roles']],
            ['title' => 'تاريخ التسجيل', 'type' => 'input', 'name' => 'created_at', 'date_range' => true],
        ];

        $columns = [
            ['title' => 'الاسم', 'column' => 'userable.full_name'],
            ['title' => 'الأدوار', 'column' => 'roles.name', 'merge' => true, 'formatter' => 'roles'],
            ['title' => 'تاريخ التسجيل', 'column' => 'created_at']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
}
