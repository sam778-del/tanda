<?php


namespace Modules\Bills\Processors;


use Modules\Bills\Processors\Airvend\Airvend;
use Modules\Bills\Repositories\BillRepository;
use Modules\Bills\Traits\BillsTrait;

class ProcessorResolver
{
    use BillsTrait;

    public function initiate()
    {
        $billsProcessor = $this->getProcessor();
        switch ($billsProcessor) {
            case "airvend":
                return new Airvend(new BillRepository());
            default:
                return "unknown processor";
        }
    }

}
