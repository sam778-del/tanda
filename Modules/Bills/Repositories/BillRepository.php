<?php


namespace Modules\Bills\Repositories;


use Modules\Bills\Models\Bill;

class BillRepository
{
    public $request;

    public function __construct()
    {
        $this->request = request();
    }


}
