<?php

namespace App\Actions\TableJSONData;

class AddRow
{
    public function execute($table, $newRow)
    {
        $columns = $table->columns;

        $rows = json_decode($table->data, true);

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
