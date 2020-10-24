<?php

namespace App\Imports;

use App\Models\Job;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Row;

class JobImport implements OnEachRow , WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function startRow(): int
    {
        return 2;
    }

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row      = $row->toArray();
        
        $job = Job::firstOrCreate([
            'name' => $row[11],
        ]);

        if (! $job->wasRecentlyCreated) {
            $job->update([
                'name' => $row[11],
            ]);
        }
    
    }
}
