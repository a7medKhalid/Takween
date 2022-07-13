<?php

namespace App\Http\Controllers;

use App\Models\DataBase;
use Illuminate\Http\Request;

class DataBaseController extends Controller
{
    public function create($user, $databaseName){
        $newDatabase = Database::create([
            'name' => $databaseName
        ]);

        $user->databases()->save($newDatabase);

        return $newDatabase;

    }

    public function delete($user, $databaseId){
        $database = $user->databases->where('id', $databaseId)->first();

        $database->delete();

        return $database;
    }


}
