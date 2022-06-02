<?php

namespace Modules\Employees\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    public function create(){
        return [
            "title" => "إعادة تعيين كلمة المرور",
            "inputs" => [
                ['title' => 'كلمة المرور القديمة', 'input' => 'input', 'type' => 'password', 'name' => 'old_password', 'required' => true],
                ['title' => 'كلمة المرور الجديدة', 'input' => 'input', 'type' => 'password', 'name' => 'new_password', 'required' => true],
                ['title' => 'تأكيد كلمة المرور الجديدة', 'input' => 'input', 'type' => 'password', 'name' => 'confirm_password', 'required' => true],
            ]
        ];
    }

    public function store(Request $request){
        if(trim($request->old_password) == ""){
            return response(['message' => "يرجى التحقق من ادخال كلمة المرور القديمة."], 403);
        }

        if(trim($request->new_password) == ""){
            return response(['message' => "يرجى التحقق من ادخال كلمة المرور الجديدة."], 403);
        }

        if(trim($request->confirm_password) == ""){
            return response(['message' => "يرجى التحقق من تأكيد كلمة المرور."], 403);
        }

        if(trim($request->new_password) !== trim($request->confirm_password)){
            return response(['message' => "كلمة المرور غير متطابقة."], 403);
        }

        $result_success =  Hash::check($request->old_password, \Auth::user()->password);
        if(!$result_success){
            return response(['message' => "كلمة المرور القديمة خطأ."], 403);
        }

        \Auth::user()->password = Hash::make($request->new_password);
        \Auth::user()->save();

        return response(['message' => "تم تعديل كلمة المرور بنجاح."]);
    }
}
