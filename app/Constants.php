<?php

namespace App;

class Constants extends \App\Http\Controllers\Controller {

    const TICKET_STATUS_TYPE_NEW = 1;
    const TICKET_STATUS_TYPE_CLOSE = 2;
    const TICKET_STATUS_TYPE_RE_OPEN = 3;
    const TICKET_STATUS_TYPE_ARCHIVE = 4;

    public function index(){
        return (new \ReflectionClass($this))->getConstants();
    }
}