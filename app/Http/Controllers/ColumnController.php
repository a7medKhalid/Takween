<?php

namespace App\Http\Controllers;

use App\Models\Column;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    public function create($table, $columnType, $columnName , $relationColumnName = null ){
        if($columnType === 'relation'){


            $newColumn = Column::create([
                'name' => $relationColumnName,
                'type' => $columnType,
                'relationColumnName' => $relationColumnName,
                'relationTable' => $columnName
            ]);
        }else{

            $newColumn = Column::create([
                'name' => $columnName,
                'type' => $columnType
            ]);
        }

        //add null values for pervious data
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

    public function delete($table, $column){

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
