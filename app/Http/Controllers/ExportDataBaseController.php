<?php

namespace App\Http\Controllers;

use App\Models\DataBase;
use App\Models\Table;
use Illuminate\Http\Request;

class ExportDataBaseController extends Controller
{


    public function jsonExport($database_id){

        //get database
        $database = DataBase::find($database_id);

        $tables = $database->tables;

        $jsonExport = [];

        foreach ($tables as $table){

            //CP:it is added as string
            $parentRows = json_decode($table->data);


            $tableName = $table->name;

            array_push($jsonExport , [
                'tableName' => $tableName,
                'rows' => $parentRows
            ]);

        }


        return json_encode($jsonExport);


    }


    function sqliteExport(){

    }

    //transfer to update table form

//    function validateData($data){
//
//        //validate date by table columns
//
//    }


    public function web(Request $request) {

//        $exportType = $request['exportType'];
//
//        $db_id = $request['db_id'];
//
//        if ($exportType === 'json') {
//            $data = $this->jsonExport($db_id);
//        }
//
        $data = $this->jsonExport(1);

//        $data = 'test';

        return inertia('Export', compact('data'));
    }



}
