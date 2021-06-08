<?php

namespace App\Imports;

use App\Students;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportStudents implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Students([
            //
        ]);
    }

    public function collection(Collection $collection)
    {
        // TODO: Implement collection() method.
    }
}
