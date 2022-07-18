<?php

namespace Modules\Expenses\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class OtherPaper extends Model implements HasMedia{
    use SoftDeletes;
    use \Modules\BriskCore\Traits\ModelTrait;
    use InteractsWithMedia;

    protected $table = 'ex_other_papers';

    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];
    protected $appends = ['other_paper_image_url'];
    
    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media  $media = null): void{
        $this->addMediaConversion('thumb')
              ->width(400)
              ->height(400);
    }
    public function getOtherPaperImageUrlAttribute(){
        $image = $this->getMedia('other_paper_image')->first();

        if($image){
            return url('/') . '/storage/app/public/' . $image->id . '/' . $image->file_name;
        }

        return asset('/public/themes/Falcon/v2.8.0/assets/img/team/avatar.png');
    }
    public function created_by_user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class, 'created_by');
    }
    public function scopeWhereCreatedAt($query, $created_at){
        return $query->where(function($query) use ($created_at){
            if(str_contains(trim($created_at), ' - ')){
                $created_at = explode(' - ', $created_at);
                $created_at_from = $created_at[0];
                $created_at_from = $created_at[1];

                $query->whereDate('created_at', '>=', date('Y-m-d', strtotime(trim($created_at[0]))));
                $query->whereDate('created_at', '<=', date('Y-m-d', strtotime(trim($created_at[1]))));
            }else{
                $query->whereDate('created_at', date('Y-m-d', strtotime(trim($created_at))));
            }
        });
    }
    public function scopeWhereEndedAt($query, $ended_at){
        return $query->where(function($query) use ($ended_at){
            if(str_contains(trim($ended_at), ' - ')){
                $ended_at = explode(' - ', $ended_at);
                $ended_at_from = $ended_at[0];
                $ended_at_from = $ended_at[1];

                $query->whereDate('ended_at', '>=', date('Y-m-d', strtotime(trim($ended_at[0]))));
                $query->whereDate('ended_at', '<=', date('Y-m-d', strtotime(trim($ended_at[1]))));
            }else{
                $query->whereDate('ended_at', date('Y-m-d', strtotime(trim($ended_at))));
            }
        });
    }
    public function scopeWhereLabelLike($query, $name){
        $name = str_replace("أ", "ا", $name);
        $name = str_replace("إ", "ا", $name);
        $name = str_replace("ة", "ه", $name);
        $name = str_replace("ى", "ي", $name);

        return $query->where(\DB::raw('REPLACE(REPLACE(REPLACE(REPLACE(label, "أ", "ا"), "إ", "ا"), "ة", "ه"), "ى", "ي")'), 'like', ('%' . trim($name) . '%'));
    }
    public function scopeWhereNoteLike($query, $name){
        $name = str_replace("أ", "ا", $name);
        $name = str_replace("إ", "ا", $name);
        $name = str_replace("ة", "ه", $name);
        $name = str_replace("ى", "ي", $name);

        return $query->where(\DB::raw('REPLACE(REPLACE(REPLACE(REPLACE(note, "أ", "ا"), "إ", "ا"), "ة", "ه"), "ى", "ي")'), 'like', ('%' . trim($name) . '%'));
    }
    public function scopeWhereStartedAt($query, $started_at){
        return $query->where(function($query) use ($started_at){
            if(str_contains(trim($started_at), ' - ')){
                $started_at = explode(' - ', $started_at);
                $started_at_from = $started_at[0];
                $started_at_from = $started_at[1];

                $query->whereDate('started_at', '>=', date('Y-m-d', strtotime(trim($started_at[0]))));
                $query->whereDate('started_at', '<=', date('Y-m-d', strtotime(trim($started_at[1]))));
            }else{
                $query->whereDate('started_at', date('Y-m-d', strtotime(trim($started_at))));
            }
        });
    }
    public function _label(){
        return [
            'title' => ' الاسم ',
            'input' => 'input',
            'name' => 'label',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'label']
            ]
        ];
    }
    public function _name(){
        return [
            'title' => 'تفاصيل أخرى',
            'input' => 'textarea',
            'name' => 'note',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'note']
            ]
        ];
    }
    public function _image(){
        return [
            'title' => 'صورة ',
            'input' => 'input',
            'type' => 'file',
            'name' => 'image',
            'operations' => [
                'show' => ['text' => 'image'],
                'update' => ['text' => 'image'],
            ]
        ];
    }
    public function _statedAt(){
         return [
            'title' => 
            'تاريخ بداية المستند', 
            'input' => 'input', 
            'name' => 'started_at', 
            'classes' => ['numeric'], 
            'date' => true,
            'operations' => ['show' => ['text' => 'started_at']]
        ];
    }
    public function _endedAt(){
         return [
            'title' => 
            'تاريخ نهاية المستند', 
            'input' => 'input', 
            'name' => 'ended_at', 
            'classes' => ['numeric'], 
            'date' => true,
            'operations' => ['show' => ['text' => 'ended_at']]
        ];
    }

}
