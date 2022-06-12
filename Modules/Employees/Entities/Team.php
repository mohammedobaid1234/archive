<?php

namespace Modules\Employees\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model{
    use SoftDeletes;
    use \Modules\BriskCore\Traits\ModelTrait;
    protected $table = 'em_teams';

    protected $fillable = [];
    
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];

    public function team_member(){
        return $this->hasMany(\Modules\Employees\Entities\Employee::class);
    }

}
