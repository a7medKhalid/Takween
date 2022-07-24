<?php

namespace App\Actions\TableJSONData;

class DeleteRow
{

    public function execute($chunk, $row){


        $dataBase = $chunk->table->database;

        //decrease database rows count
        $dataBase->rowsCount -= 1;
        $dataBase->save();

        $rows = json_decode($chunk->data, true);

        $key = array_search($row, $rows);

        if ($key !== false) {
            unset($rows[$key]);
        }

        return $rows;

    }

}
