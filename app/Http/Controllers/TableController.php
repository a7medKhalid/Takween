<?php

namespace App\Http\Controllers;

use App\Actions\TableJSONData\AddRow;
use App\Actions\TableJSONData\DeleteRow;
use App\Models\Column;
use App\Models\Table;

class TableController extends Controller
{

    public function getTableById($user, $tableId){

        $table = Table::find($tableId);

        if($table?->database->user_id != $user->id){

            $permission = $user->permissions->where('data_base_id', $table->data_base_id)->where('isValid', true)->first();

            if (!$permission){
                return null;
            }
        }

        return $table;
    }

    public function getTableByName($user, $tableName){

        $table = Table::where('name', $tableName)->first();

        if($table?->database->user_id != $user->id){
            $permission = $user->permissions->where('data_base_id', $table->data_base_id)->where('isValid', true)->first();

            if (!$permission){
                return null;
            }
        }

        return $table;
    }


    public function create($user, $database, $tableName){


        //check if database belongs to user
        if ($database->user_id != $user->id) {
            return false;
        }

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

    public function delete($user, $tableId){


        $tableModel = Table::find($tableId);

        //check if database belongs to user
        if($tableModel->database->user_id != $user->id){
            return false;
        }


        $tableModel->delete();
    }



}
