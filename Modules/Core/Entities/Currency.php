<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model{

    protected $table = 'core_currencies';
    
    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';
    
    protected $appends = ['index'];

    public function getIndexAttribute(){
        if($this->id == "ILS"){
            return 1;
        }
        if($this->id == "USD"){
            return 2;
        }
        if($this->id == "JOD"){
            return 3;
        }
        if($this->id == "EUR"){
            return 4;
        }
        if($this->id == "TRY"){
            return 5;
        }

        return "-";
    }
}