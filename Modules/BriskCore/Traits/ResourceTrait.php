<?php 

namespace Modules\BriskCore\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

trait ResourceTrait {
    use \Modules\BriskCore\Traits\Resource\IndexTrait;
    use \Modules\BriskCore\Traits\Resource\CreateTrait;
    use \Modules\BriskCore\Traits\Resource\UpdateTrait;
    use \Modules\BriskCore\Traits\Resource\DestroyTrait;
    use \Modules\BriskCore\Traits\Resource\DatatableInitializerTrait;

}