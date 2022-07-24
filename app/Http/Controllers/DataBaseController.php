<?php

namespace App\Http\Controllers;

use App\Models\DataBase;
use Illuminate\Http\Request;

class DataBaseController extends Controller
{

    public function getDatabaseById($user, $id){

        $database = $user->databases->find($id);

        if (!$database){
           //check if user has permission to access database
              $permission = $user->permissions->where('data_base_id', $id)->where('isValid', true)->first();

              if ($permission){
                    $database = $permission->database;
              }
        }

        return $database;
    }

    public function getOwnedDatabases($user){

        $databases = $user->databases->fresh();;
        return $databases;
    }

    public function getInvitedDatabases($user){

        $invitedDatabases = [];

        $permissions = $user->permissions->fresh();

        foreach ($permissions as $permission){
            array_push($invitedDatabases, $permission->database);
        }

        return $invitedDatabases;
    }
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
