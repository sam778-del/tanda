<?php


namespace Modules\Circle\Http\Services;


use Modules\Circle\Models\Company;

class CompanyService
{
    /**
     * @param array $attribute
     */
    public function create(array $attribute)
    {
        Company::create($attribute);
    }
}
