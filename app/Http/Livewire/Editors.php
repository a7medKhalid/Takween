<?php

namespace App\Http\Livewire;

use App\Http\Controllers\DataBaseController;
use App\Http\Controllers\PermissionController;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Editors extends Component
{
    public $database;

    public $editors;

    public $editorEmail;

    function viewEditors(){

        $this->database->fresh();

        $permissionController = new PermissionController();

        $this->editors = $permissionController->getDatabasePermissions(Auth::user(), $this->database);
    }

    public function addEditor(){

        $permissionController = new PermissionController();

        $permissionController->create(Auth::user(), $this->database, $this->editorEmail);

        $this->viewEditors();

        $this->editorEmail = '';

    }

    public function deactivateEditor($editor){

        $permissionModel = $this->database->permissions->find($editor['id']);

        $permissionController = new PermissionController();
        $permissionController->update(Auth::user(), $permissionModel, false);

        $this->viewEditors();
    }

    public function activateEditor($editor){

        $permissionModel = $this->database->permissions->find($editor['id']);


        $permissionController = new PermissionController();
        $permissionController->update(Auth::user(), $permissionModel, true);

        $this->viewEditors();
    }



    public function mount($id){

        $user = Auth::user();

        $databaseController = new DataBaseController();
        $this->database = $databaseController->getDatabaseById($user, $id);


        $this->viewEditors();
    }


    public function render()
    {

        return view('livewire.editors');
    }
}
