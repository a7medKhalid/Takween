<?php

namespace App\Http\Controllers;

use App\Models\Column;
use App\Models\Table;

class TableController extends Controller
{

    public function create($database, $tableName){

        $newTable = Table::create([
            'name' => $tableName
        ]);

        $database->tables()->save($newTable);

        $idColumn = Column::create([
            'name' => 'id',
            'type' => 'id'
        ]);

        $newTable->columns()->save($idColumn);

        return $newTable;

    }

    public function delete($database, $table){

        $tableModel = $database->tables->where('id', $table['id'])->first();

        $tableModel->delete();
    }
}
