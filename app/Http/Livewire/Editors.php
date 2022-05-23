<?php

namespace App\Http\Livewire;

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



        $editor = User::where('email', $this->editorEmail)->first();


        $permission = Permission::create();

        $this->database->permissions()->save($permission);

        $editor->permissions()->save($permission);

        $this->viewEditors();

        $this->editorEmail = '';

//        dd($this->editors);

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
