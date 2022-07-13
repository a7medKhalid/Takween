<?php

namespace App\Actions\TableJSONData;

class DeleteRow
{

    public function execute($table, $row){

        $rows = json_decode($table->data, true);

        $key = array_search($row, $rows);

        if ($key !== false) {
            unset($rows[$key]);
        }

        $table->data = json_encode($rows, JSON_NUMERIC_CHECK);


        $table->save();

    }

}
