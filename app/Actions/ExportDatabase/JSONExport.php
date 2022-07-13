<?php

namespace App\Actions\ExportDatabase;

use App\Models\DataBase;

class JSONExport
{
    public function execute($database_id){

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

}
