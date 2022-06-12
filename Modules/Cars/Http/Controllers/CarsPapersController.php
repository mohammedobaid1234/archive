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
            ['title' =>'نوع الورقة', 'column' => 'type'],
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
        
    }
}
