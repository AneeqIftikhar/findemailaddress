<?php

namespace App\Imports;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class EmailsImportArray implements ToCollection, WithLimit,  WithCustomCsvSettings
{
    public function collection(Collection $rows)
    {
        return $rows;
    }
    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'ISO-8859-1'
        ];
    }
    public function limit(): int
    {
        return 3;
    }
}