<?php

namespace Modules\Employees\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TeamsController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "ملفات الفرق";
    private $model = \Modules\Employees\Entities\EmployeeTeam::class;

    public function index(){
        $teams = \Modules\Employees\Entities\Team::get();
       return response()->json(['data' => $teams]);
    }
    public function manage(){
        \Auth::user()->authorize('employees_module_teams_manage', 'view');

        $data['activePage'] = ['employees' => 'teams'];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('employees::teams', $data);
    }

    public function datatable(Request $request){
        \Auth::user()->authorize('employees_module_teams_manage');
        
        $eloquent = $this->model::with(['employee', 'team']);
        if((int) $request->filters_status){
            if (trim($request->employee_id) !== '') {
                $eloquent->whereHas('employee', function($query) use ($request){
                    $query->where('id', "LIKE" , trim($request->employee_id));
                });
            }
            if (trim($request->mobile_no) !== '') {
                $eloquent->whereHas('employees', function($query) use ($request){
                    $query->where('mobile_no', "LIKE" , "%". trim($request->mobile_no) . "%");
                });
            }
        }

        $filters = [
            ['title' => 'رقم جوال الموظف', 'type' => 'input', 'name' => 'employment_id'],
            ['title' => 'اسم الموظف', 'type' => 'select', 'name' => 'employee_id', 'data' => ['options_source' => 'employees', 'has_empty' => true]],
        ];

        $columns = [
            ['title' => 'الرقم الوظيفي', 'column' => 'employee.employment_id'],
            ['title' => 'الاسم', 'column' => 'employee.full_name'],
            ['title' => 'رقم جوال الموظف', 'column' => 'employee.mobile_no'],
            ['title' => 'اسم الفريق التابع له', 'column' => 'team.name'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }

    public function create(){
        return [
            "title" => "اضافة فريق جديد",
            "inputs" => [
                 ['title' => 'اسم الفريق ', 'input' => 'select', 'name' => 'team_id', 'required' => true,'classes' => ['select2'], 'data' => ['options_source' => 'teams'],'operations' => ['show' => ['text' => 'team.name', 'id' => 'team.id']]],
              
                [
                    ['title' => 'اسم الموظف ', 'input' => 'select', 'name' => 'employee_id[]', 'required' => true, 'classes' => ['select2'], 'data' => ['options_source' => 'employees'],'operations' => ['show' => ['text' => 'employee.full_name', 'id' => 'employee.id']]],
                    ['title' => 'نوع الموظف في الفريق ',"rowIndex" => 1, 'input' => 'select', 'name' => 'type[]', 'required' => true, 'classes' => ['select2'], 'data' => ['options_source' => 'type_in_team', ],'operations' => ['show' => ['text' => 'type']]],
                ],
                [
                    [ 'input' => 'select', 'name' => 'employee_id[]',  'classes' => ['select2'], 'data' => ['options_source' => 'employees', ],'operations' => ['show' => ['text' => 'employee.full_name', 'id' => 'employee.id']]],
                    ["rowIndex" => 1, 'input' => 'select', 'name' => 'type[]',  'classes' => ['select2'], 'data' => ['options_source' => 'type_in_team'],'operations' => ['show' => ['text' => 'type']]],
                ],
                [
                    [ 'input' => 'select', 'name' => 'employee_id[]',  'classes' => ['select2'], 'data' => ['options_source' => 'employees', ],'operations' => ['show' => ['text' => 'employee.full_name', 'id' => 'employee.id']]],
                    ["rowIndex" => 1, 'input' => 'select', 'name' => 'type[]',  'classes' => ['select2'], 'data' => ['options_source' => 'type_in_team'],'operations' => ['show' => ['text' => 'type']]],
                ],
           
            ]
        ];
    }
    public function store(Request $request){
        \Auth::user()->authorize('employees_module_teams_store');

        $request->validate([
            'employee_id' => 'required',
            'team_id' => 'required',
            'type' => 'required',
        ]);
        
        \DB::beginTransaction();
        try {
            $employee_id = $request->employee_id[0];
            $type = $request->type[0];
            $employees = explode(',',$employee_id);
            $types = explode(',',$type);
            if(count($employees) == count($types)){
                for ($i=0; $i <count($employees) ; $i++) { 
                    $team =  new \Modules\Employees\Entities\EmployeeTeam;
                    $team->team_id   = trim($request->team_id); 
                    $team->employee_id   = trim($employees[$i]); 
                    $team->type  = trim($types[$i]); 
                    $team->save();
                }
            }else{
                return response()->json(['message' => 'Thats Error'], 403);

            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }

    public function show($id){
        return $this->model::with(['employee','team'])->whereId($id)->first();
    }

}
