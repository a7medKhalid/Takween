<?php

namespace App\Http\Livewire;

use App\Models\DataBase;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Databases extends Component
{
    public $databases;


    public $databaseName ;


    function viewDatabases(){
        $user = Auth::user();

        $this->databases = $user->databases->fresh();

    }

    public function addDataBase(){

        $user = Auth::user();

        $newDatabase = Database::create([
            'name' => $this->databaseName
        ]);

        $user->databases()->save($newDatabase);

        $this->databases->push($newDatabase);

        $this->databaseName = '';


    }

    public function deleteDataBase($database){

        $user = Auth::user();

        $databaseModel = $user->databases->where('id', $database['id'])->first();

        $databaseModel->delete();

        $this->viewDatabases();

    }

    public function redirectToTables($id)
    {
        return redirect()->to('databases/'. $id);
    }

    public function mount(){
        $this->viewDatabases();
    }

    public function render()
    {

        return view('livewire.databases');
    }
}
