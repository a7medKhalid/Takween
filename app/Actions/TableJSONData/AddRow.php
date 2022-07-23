<?php

namespace App\Actions\TableJSONData;

class AddRow
{
    public function execute($table, $chunk, $newRow)
    {

        $dataBase = $table->database;

        //increase database rows count
        $dataBase->rowsCount += 1;
        $dataBase->save();

        $columns = $table->columns;

        $rows = json_decode($chunk->data, true);

        $row = ['id' => $table->counter];

        if ($rows === null){
            $rows = [];
        }


//        {{--  to parse relation and number as integers  --}}


        $integerColumns = [];
        foreach ($columns as $column){
            if ($column->type !== 'id' and in_array($column->type, array('number', 'checkbox', 'relation'))) {
                array_push($integerColumns,$column->name);
            }
        }

        foreach ($integerColumns as $integerColumn) {
            $newRow[$integerColumn] = intval($newRow[$integerColumn]);
        }

        $row = array_merge($row, $newRow);

        array_push($rows, $row);

        return $rows;
    }

}
