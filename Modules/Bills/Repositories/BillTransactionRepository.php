<?php


namespace Modules\Bills\Repositories;


use Modules\Bills\Models\BillTransaction;

class BillTransactionRepository
{
    public function storeServiceTransaction(array $data) : ?BillTransaction
    {
        return BillTransaction::create($data);
    }

}
