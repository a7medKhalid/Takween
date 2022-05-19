<?php

namespace App\Http\Controllers;

use App\Models\Table;

class TableController extends Controller
{
    function create($name, $level, $db_id){

        Table::create([
            'name' => $name,
            'level' => $level,
            'db_id' => $db_id
        ]);

    }

    function update($table_id, $data){

        $table = Table::find($table_id);

        $table->data = $data;

        $table->save();
    }
}
