<?php

namespace Modules\Customers\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NoteController extends Controller{
    
    public function index(Request $request, $customer_id){

        $customer = \Modules\Customers\Entities\Customer::whereId($customer_id)->first();

        if(!$customer){
            return response(['message' => 'لم يتم العثور على ملف العميل.'], 403);
        }

        $notes = \Modules\Customers\Entities\Note::where('customer_id', $customer_id)->with(['comments.created_by_user', 'created_by_user', 'comments.comments.created_by_user'])->orderBy('created_at', 'DESC')->paginate(20);

        return response()->json($notes);
    }

    public function store(Request $request, $customer_id){
        
        if(trim($request->content) == ''){
            return response(['message' => 'يرجى التحقق من ادخال الملاحظة.'], 403);
        }

        $customer = \Modules\Customers\Entities\Customer::whereId($customer_id)->first();

        if(!$customer){
            return response(['message' => 'لم يتم العثور على ملف العميل.'], 403);
        }

        \DB::beginTransaction();
        try {
           

            $note = new \Modules\Customers\Entities\Note;
            $note->content = trim($request->content);
            $note->customer_id = $customer->id;
            $note->parent_id = $request->parent_id ? $request->parent_id : Null;
            $note->created_by = \Auth::user()->id;
            $note->save();
            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "contact-image";

                $note->addMediaFromRequest('image[0]')
                        ->usingFileName($media_new_name)
                        ->usingName($request->file('image')[0]->getClientOriginalName())
                        ->toMediaCollection($collection);
            }
            \DB::commit();
            return response()->json(['message' => 'ok']);
        } catch (\Exception $e) {
            \DB::rollback();
            return response(['message' => $e->getMessage()], 403);
        }
    }
}
