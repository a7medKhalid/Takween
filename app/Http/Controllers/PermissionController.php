<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{

    public function getDatabasePermissions($user, $database){

        //check if user owns database
        if($database->user_id != $user->id){
            return false;
        }

        //get all permissions for database
        $permissions = $database->permissions->fresh();
        return $permissions;
    }
    public function create($user, $database, $editorEmail)
    {
        //check if user owns the database
        if ($database->user_id != $user->id) {
            return false;
        }

        $editor = User::where('email', $editorEmail)->first();

        $permission = Permission::firstOrCreate([
            'data_base_id' => $database->id,
            'user_id' => $editor?->id,
        ]);

        return $permission;
    }

    public function update($user, $permission, $isValid){
        //check if user owns the database
        if ($permission->database->user_id != $user->id) {
            return false;
        }

        $permission->isValid = $isValid;
        $permission->save();

        return $permission;

    }
}
