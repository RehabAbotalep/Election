<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\AreaImport;
use App\Imports\ElectoralTableImport;
use App\Imports\ElectrosImport;
use App\Imports\JobImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function import(Request $request) 
    {
        /*$splitName = explode(' ', ' احمد سعد عايض مقعد الصواغ العازمى ');
        return $splitName[5];*/
        Excel::import(new ElectrosImport,request()->file('file'));
           
        return 'Done';

       /* Excel::import(new ElectoralTableImport,request()->file('file'));
           
        return 'Done';*/


        /*Excel::import(new JobImport,request()->file('file'));
           
        return 'Done';*/

        /*Excel::import(new AreaImport,request()->file('file'));
           
        return 'Done';*/
    }
}
