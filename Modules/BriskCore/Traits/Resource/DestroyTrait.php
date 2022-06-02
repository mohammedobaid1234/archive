<?php 

namespace Modules\BriskCore\Traits\Resource;

use Illuminate\Http\Request;

trait DestroyTrait {

    public function destroy(Request $request) {
        $id = "";
        
        foreach($request->route()->parameters as $parameter){
            $id = $parameter;
        }

        if($this->model::whereId($id)->delete()){
            return response()->json(['message' => 'تم الحذف بنجاح']);
        }

        return response()->json(['message' => 'fail'], 403);
    }
}