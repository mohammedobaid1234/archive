<?php 

namespace Modules\BriskCore\Traits\Resource;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

trait UpdateTrait {

    public function update(Request $request, $id){
        \DB::beginTransaction();
        try{
            $this->model::whereId($id)->update($this->extractRequestData($request->all(), $this->model));

            \DB::commit();
            return response()->json(['message' => 'ok']);
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'fail'], 403);
    }

    public static function extractRequestData($requestData, $model){
        $data = [];
        
        foreach((new $model)->getBriskFillable() as $field => $attributes){
            if(is_numeric($field)){
                $field = $attributes;
            }

            $existed = false;
            foreach($requestData as $key => $value){
                if($key == $field){
                    $existed = true;
                }
            }

            if(!$existed){
                continue;
            }

            if(is_array($attributes)){
                if(array_search('nullable', $attributes) > -1 && strlen(trim($requestData[$field])) == 0){
                    $data[$field] = NULL;
                    continue;
                }
            }

            $data[$field] = trim($requestData[$field]);
        }

        return $data;
    }
}