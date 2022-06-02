<?php 

namespace Modules\BriskCore\Traits;

trait ModelActiveTrait {

    public function __construct(){
        parent::__construct();
        
        $this->appends[] = 'active_title';
        $this->appends[] = 'active';
    }

    public function scopeWhereActive($query, $active){
        if((int) trim($active)){
            return $query->active();
        }

        return $query->disable();
    }

    
}