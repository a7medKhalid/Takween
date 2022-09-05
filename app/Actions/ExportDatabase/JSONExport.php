<?php

namespace App\Actions\ExportDatabase;

use App\Http\Controllers\DataBaseController;
use App\Models\DataBase;

class JSONExport
{
    public function execute($user, $database_id){

        //get database
        $databaseController = new DataBaseController();
        $database = $databaseController->getDatabaseById($user, $database_id);

        $tables = $database->tables;

        $jsonExport = [];

        foreach ($tables as $table){

            //CP:it is added as string
            $parentRows = [];

            foreach ($table->chunks as $chunk){
                array_push($parentRows, json_decode($chunk->data));
            }

            $tableName = $table->name;

            array_push($jsonExport , [
                'tableName' => $tableName,
                'rows' => $parentRows
            ]);

        }

        //increment export count
        $user->exportsCount++;
        $user->save();


        return json_encode($jsonExport,JSON_NUMERIC_CHECK);


    }

}
