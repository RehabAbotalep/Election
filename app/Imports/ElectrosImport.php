<?php

namespace App\Imports;

use App\Models\Area;
use App\Models\Elector;
use App\Models\ElectoralTable;
use App\Models\Job;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ElectrosImport implements ToModel , WithStartRow
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

    public function model(array $row)
    {
        $splitName = explode(' ', $row[9]);
        $birth_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['10']);

        return new Elector([
            'constituency_id' => 1,
            'area_id' => $this->getAreaId($row[12]),
            'table_id'   => $this->getTabelId($row[3]),
            'gender' => $row[4],
            'registeration_number'   => $row[6],
            'registeration_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['7']),
            'unified_number' => $row[8],
            'first_name'     => $splitName[1],
            'second_name'    => $splitName[2],
            'third_name'     => $splitName[3],
            'fourth_name'    => $splitName[4],
            'fifth_name'     => !empty($splitName[5]) ? $splitName[5] : '' ,
            'sixth_name'     => !empty($splitName[6]) ? $splitName[6] : '' ,
            'age'        => Carbon::parse($birth_date)->age,
            'birth_date' => $birth_date,
            'job_id'     => $this->getJobId($row[11]),
            'notes'      => $row[13],
        ]);
    }

    private function getDate($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

    private function getJobId($name)
    {
        return Job::where('name',$name)->value('id');
    }

    private function getAreaId($name)
    {
        return Area::where('name',$name)->value('id');
    }

    private function getTabelId($name)
    {
        return ElectoralTable::where('name',$name)->value('id');
    }
}
