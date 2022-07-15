<?php

namespace App\Actions\TableJSONData;

class DeleteRow
{

    public function execute($chunk, $row){

        $rows = json_decode($chunk->data, true);

        $key = array_search($row, $rows);

        if ($key !== false) {
            unset($rows[$key]);
        }

        return $rows;

    }

}
