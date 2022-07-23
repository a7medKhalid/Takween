<?php

namespace App\Http\Controllers;

use App\Actions\TableJSONData\AddRow;
use App\Actions\TableJSONData\DeleteRow;
use App\Models\Chunk;
use Illuminate\Http\Request;

class ChunkController extends Controller
{

    public function getChunkByRowId($user , $table , $rowId){
        // algorithm to determine the id in which hundred
        $order = ceil($rowId / 100);

        $chunk = $table->chunks->where('order', $order)->first();

        //check if user can view chunk
        if($table?->database->user_id != $user->id){

            $permission = $user->permissions->where('data_base_id', $table->data_base_id)->where('isValid', true)->first();

            if (!$permission){
                return null;
            }
        }

        return $chunk;

    }

    public function getChunkByOrder($user, $table, $order){

        $chunk = Chunk::where('table_id', $table->id)->where('order', $order)->first();

        //check if user can view chunk
        if($table?->database->user_id != $user->id){

            $permission = $user->permissions->where('data_base_id', $table->data_base_id)->where('isValid', true)->first();

            if (!$permission){
                return null;
            }
        }

        return $chunk;
    }

    public function create($user, $table){
        //check if user owns table database
        if($table->database->user_id != $user->id){
            $permission = $user->permissions->where('data_base_id', $table->data_base_id)->where('isValid', true)->first();

            if (!$permission){
                return null;
            }
        }


        $newChunk = Chunk::firstOrCreate([
            'order' => $table->chunks->count() + 1,
            'table_id' => $table->id,
        ]);

//        $table->chunks()->save($newChunk);

        return $newChunk;
    }

    public function updateDataAddRow($user, $table, $newRow){

        $addRow = new AddRow();

        //check if user owns table database
        if($table->database->user_id != $user->id){
            $permission = $user->permissions->where('data_base_id', $table->data_base_id)->where('isValid', true)->first();

            if (!$permission){
                return null;
            }
        }

        $chunk = $this->getChunkByRowId($user, $table, $table->counter);

        if(!$chunk){
            //create chunk
            $chunk = $this->create($user,$table);
        }


        $rows = $addRow->execute($table, $chunk, $newRow);

        $chunk->data = json_encode($rows,JSON_NUMERIC_CHECK);

        $chunk->save();

        $table->counter += 1;

        $table->save();

    }

    public function updateDataDeleteRow($user, $table, $row){

        $deleteRow = new DeleteRow();

        //check if user owns table database
        if($table->database->user_id != $user->id){
            $permission = $user->permissions->where('data_base_id', $table->data_base_id)->where('isValid', true)->first();

            if (!$permission){
                return null;
            }
        }

        $chunk = $this->getChunkByRowId($user, $table, $row['id']);

        if(!$chunk){
            //create chunk
            return false;
        }

        $rows = $deleteRow->execute($chunk ,$row);

        $chunk->data = json_encode($rows, JSON_NUMERIC_CHECK);

        $chunk->save();
    }
}
