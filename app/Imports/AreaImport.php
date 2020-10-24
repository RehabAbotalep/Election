<?php

namespace App\Imports;

use App\Models\Area;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Row;

class AreaImport implements OnEachRow , WithStartRow
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
        
        $area = Area::firstOrCreate([
            'name' => $row[12],
        ]);

        if (! $area->wasRecentlyCreated) {
            $area->update([
                'name' => $row[12],
            ]);
        }
    
    }
}
