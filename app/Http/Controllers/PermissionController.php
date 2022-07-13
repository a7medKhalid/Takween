<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function create($database, $editorEmail)
    {


        $editor = User::where('email', $editorEmail)->first();

        $permission = Permission::firstOrCreate([
            'data_base_id' => $database->id,
            'user_id' => $editor->id,
        ]);


        $editor->assignRole('editor');

        return $permission;
    }
}
