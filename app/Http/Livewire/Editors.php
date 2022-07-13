<?php

namespace App\Http\Livewire;

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

        $this->editors = $this->database->permissions->fresh();

    }

    public function addEditor(){
        $user = Auth::user();

        $permissionController = new PermissionController();

        $permissionController->create($this->database, $this->editorEmail);

        $this->viewEditors();

        $this->editorEmail = '';

    }

    public function deactivateEditor($editor){


        $editorModel = $this->database->permissions->find($editor['id']);

        $editorModel->isValid = false;

        $editorModel->save();

        $this->viewEditors();
    }

    public function activateEditor($editor){


        $editorModel = $this->database->permissions->find($editor['id']);

        $editorModel->isValid = true;

        $editorModel->save();

        $this->viewEditors();
    }



    public function mount($id){

        $user = Auth::user();

        $this->database = $user->databases->where('id', $id)->first();


        $this->viewEditors();
    }


    public function render()
    {

        return view('livewire.editors');
    }
}
