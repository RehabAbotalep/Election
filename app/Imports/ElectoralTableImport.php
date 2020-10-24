<?php

namespace App\Imports;

use App\Models\ElectoralTable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Row;

class ElectoralTableImport implements OnEachRow , WithStartRow
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
        
        $table = ElectoralTable::firstOrCreate([
            'name' => $row[3],
        ]);

        if (! $table->wasRecentlyCreated) {
            $table->update([
                'name' => $row[3],
            ]);
        }
    
    }
}
