<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "إدارة صلاحيات النظام";
    private $model = \Spatie\Permission\Models\Permission::class;

    public function index(Request $request){
        $this->can('users_module_permissions_manage');

        // return \Spatie\Permission\Models\Permission::all();
        // $role = \Spatie\Permission\Models\Role::where("name", "system_administration")->first();
        // $role->givePermissionTo('users_module_users_manage');
        // $role->givePermissionTo('users_module_roles_manage');

        if($request->type == 'user'){
            $target = \Modules\Users\Entities\User::whereId($request->id)->first();
        }

        if($request->type == 'role'){
            $target = \Spatie\Permission\Models\Role::whereId($request->id)->first();
        }

        return [
            'all_permissions' => \Modules\Users\Entities\PermissionGroup::whereNull('parent_id')->with(['permissions', 'allChildrenGroups.permissions'])->orderByOrderNo()->get(),
            'target_permissions' => (isset($target) ? $target->getAllPermissions() : [])    
        ];
    }
    
    public function manage(Request $request){
        $this->can('users_module_permissions_manage', 'view');

        $data['activePage'] = ['users' => 'permissions'];
        $data['breadcrumb'] = [
            ['title' => "إدارة المستخدمين"],
            ['title' => $this->title]
        ];

        if($request->type == "user"){
            $data['breadcrumb'][] = ['title' => "المستخدمين", 'url' => "employees/manage"];
            $data['breadcrumb'][] = ['title' => \Modules\Users\Entities\User::whereId($request->id)->first()->name];
        }

        if($request->type == "role"){
            $data['breadcrumb'][] = ['title' => "الأدوار", 'url' => "users/roles/manage"];
            $data['breadcrumb'][] = ['title' => \Spatie\Permission\Models\Role::whereId($request->id)->first()->label];
        }

        $data['permissions']['type'] = $request->type;
        $data['permissions']['id'] = $request->id;

        // $data['permissions']['groups'] = \Modules\Users\Entities\PermissionGroup::with(['permissions'])->get();
        // $data['permissions']['notGrouped'] = $this->model::whereNull('group_id')->get();

        return view("users::permissions", $data);
    }

    public function update(Request $request, $id){
        $this->can('users_module_role_permissions_update');

        $request->validate([
            'name' => 'required',
            'type' => 'required',
            'value' => 'required'
        ]);

        if($request->type !== 'user' && $request->type !== 'role'){
            return response()->json(['message' => 'fail'], 403);
        }
        if($request->type == 'user'){
            $target = \Modules\Users\Entities\User::whereId($id)->first();
        }

        if($request->type == 'role'){
            $target = \Spatie\Permission\Models\Role::whereId($id)->first();
        }
        if($request->value == 'true'){
            $target->givePermissionTo($request->name);
        }else{
            $target->revokePermissionTo($request->name);
        }

        return response()->json(['message' => 'ok']);
    }
}