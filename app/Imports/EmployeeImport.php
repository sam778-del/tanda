<?php

namespace App\Imports;

use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Batch;

class EmployeeImport implements ToCollection, WithStartRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            if($row->filter()->isNotEmpty()){
                Batch::create([
                    'first_name' => $row[0],
                    'last_name' => $row[1],
                    'email' => $row[2],
                    'job_type' => $row[3],
                    'department' => $row[4],
                    'employment_type' => $row[5],
                    'phone_no' => $row[6],
                    'basic_pay' => $row[7],
                    'housing_allowance' => $row[8],
                    'transport_allowance' => $row[9],
                ]);
            }
        }
        return Batch::all();
    }

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
