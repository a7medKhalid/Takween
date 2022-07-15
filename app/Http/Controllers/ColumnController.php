<?php

namespace App\Http\Controllers;

use App\Models\Column;
use Illuminate\Http\Request;

class ColumnController extends Controller
{

    public function getTableColumns($user, $table){

       //check if user owns table database
       if ($table?->database->user_id != $user->id){
           return null;
       }

       $columns = $table->columns->fresh();

       return $columns;
    }
    public function create($user, $table, $columnType, $columnName , $relationTable = null, $relationColumnName = null){

        //check if table database belongs to user
        if($table->database->user_id != $user->id){
            return false;
        }

        if($columnType === 'relation'){
            $newColumn = Column::create([
                'name' => $columnName,
                'type' => $columnType,
                'relationColumnName' => $relationColumnName,
                'relationTable' => $relationTable
            ]);
        }else{

            $newColumn = Column::create([
                'name' => $columnName,
                'type' => $columnType
            ]);
        }

        //add null values for previous data
        $table->fresh();

        $rows = json_decode($table->data, true);

        $nullColumn = [$newColumn->name => 'NULL'];

        if ($rows){

            $updatedRows = [];

            foreach ($rows as $row){
                $row = array_merge($row, $nullColumn);

                array_push($updatedRows, $row);
            }

            $table->data = json_encode($updatedRows,JSON_NUMERIC_CHECK);

            $table->save();
        }



        $table->columns()->save($newColumn);

        return $newColumn;
    }

    public function delete($user, $table, $column){

        //check if table database belongs to user
        if($table->database->user_id != $user->id){
            return false;
        }


        $columnModel = $table->columns->where('id', $column['id'])->first();

        $columnModel->delete();


        //delete column from rows
        $table->fresh();

        $rows = json_decode($table->data, true);

        if ($rows){

            $updatedRows = [];

            foreach ($rows as $row){
                unset($row[$columnModel->name]);

                array_push($updatedRows, $row);
            }

            $table->data = json_encode($updatedRows,JSON_NUMERIC_CHECK);

            $table->save();
        }

    }
}
