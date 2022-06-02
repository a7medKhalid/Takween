<?php

namespace App\Http\Controllers;

use App\Models\DataBase;
use App\Models\Table;
use Illuminate\Http\Request;

class ExportDataBaseController extends Controller
{

    public function sqliteExport($database_id){

        //get database
        $database = DataBase::find($database_id);

        $dbName = $database->name . time() . ".db";

        $sqlite = new JSON2SQL($dbName , '');

        $sqlite->debugMode(false);


        $tables = $database->tables;


        foreach ($tables as $table){


            if ($table->data){
                
                //get table schema

                $columns = $table->columns;

                $schema = [];

                foreach ($columns as $column){

                    $type = '';
                    if (in_array($column->type, array('text', 'tel', 'email', 'url' ,'date')) ){
                        $type = (object) [
                            $column->name => 'text',
                        ];
                        array_push($schema,$type);
                    }elseif (in_array($column->type, array('number', 'checkbox','relation' ))){
                        $type = (object) [
                            $column->name => 'integer',
                        ];
                        array_push($schema,$type);
                    }



                }

                $schema = json_encode($schema);

                $sqlite->createTable($schema, $table->name);


                $rows = [];

                foreach (json_decode($table->data) as $row){

                    unset($row->id);

                    array_push($rows, $row);

                }

                $sqlite->selectTable($table->name);
                $sqlite->add(json_encode($rows, JSON_NUMERIC_CHECK));
            }




        }

        return $dbName;



    }


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


        return json_encode($jsonExport,JSON_NUMERIC_CHECK);


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
