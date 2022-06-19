<?php

namespace Modules\Expenses\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OtherPapersController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "أوراق أخرى ";
    private $model = \Modules\Expenses\Entities\OtherPaper::class;


    public function manage(){
        \Auth::user()->authorize('expenses_module_other_papers_manage');

        $data['activePage'] = ['other_papers' => true];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('expenses::other_papers', $data);
    }
    public function datatable(Request $request){
        \Auth::user()->authorize('expenses_module_other_papers_manage');

        $eloquent = $this->model::with([]);

        if((int) $request->filters_status){
            if(trim($request->label) !== ""){
                $eloquent->whereLabelLike($request->label);
            }
            if(trim($request->note) !== ""){
                $eloquent->WhereNoteLike($request->note);
            }
            if (trim($request->created_at) !== "") {
                $eloquent->whereCreatedAt($request->created_at);
            }
            if (trim($request->started_at) !== "") {
                $eloquent->whereWhereStartedAt($request->started_at);
            }
            if (trim($request->ended_at) !== "") {
                $eloquent->WhereEndedAt($request->ended_at);
            }
        }

        $filters = [
            ['title' => 'الاسم', 'type' => 'input', 'name' => 'label'],
            ['title' => 'التفاصيل', 'type' => 'input', 'name' => 'note'],
            ['title' => 'تاريخ البداية', 'type' => 'input', 'name' => 'started_at', 'date_range' => true],
            ['title' => 'تاريخ النهاية', 'type' => 'input', 'name' => 'ended_at', 'date_range' => true],
        ];

        $columns = [
            ['title' => 'الاسم', 'column' => 'label'],
            ['title' => ' صورة المستند ', 'column' => 'image', 'formatter' => 'image'],
            ['title' => 'تفاصيل أخرى', 'column' => 'note' ,'formatter' => 'notes'],
            ['title' => 'تاريخ البداية', 'column' => 'started_at'],
            ['title' => 'تاريخ النهاية', 'column' => 'ended_at'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }

    public function store(Request $request){
        \Auth::user()->authorize('expenses_module_other_papers_store');

        $request->validate([
            'label' => 'required',
            'note' => 'required',
            'image' => 'required',
        ]);

        \DB::beginTransaction();
        try{
            $other_paper = new $this->model;
            $other_paper->label = trim($request->label);
            $other_paper->note = trim($request->note);
            $other_paper->started_at = $request->started_at;
            $other_paper->ended_at = $request->ended_at;
            $other_paper->save();
            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "other_paper_image";

                $other_paper->addMediaFromRequest('image[0]')
                        ->usingFileName($media_new_name)
                        ->usingName($request->file('image')[0]->getClientOriginalName())
                        ->toMediaCollection($collection);
            }
            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
    public function show($id){
        return $this->model::with([])->whereId($id)->first();
    }
    public function update(Request $request, $id){
        \Auth::user()->authorize('expenses_module_other_papers_update');

        $request->validate([
            'label' => 'required',
            'note' => 'required',
        ]);

        \DB::beginTransaction();
        try{
            $other_paper =  $this->model::whereId($id)->first();
            $other_paper->label = trim($request->label);
            $other_paper->note = trim($request->note);
            $other_paper->started_at = $request->started_at;
            $other_paper->ended_at = $request->ended_at;
            $other_paper->save();
            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "other_paper_image";

                $other_paper->addMediaFromRequest('image[0]')
                        ->usingFileName($media_new_name)
                        ->usingName($request->file('image')[0]->getClientOriginalName())
                        ->toMediaCollection($collection);
            }
            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }

}

