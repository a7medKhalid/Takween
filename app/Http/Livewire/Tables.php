<?php

namespace App\Http\Livewire;

use App\Models\Table;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Tables extends Component
{

    public $database;

    public $tables;

    public $tableName ;


    function viewTables(){
        $user = Auth::user();

        $databaseModel = $user->databases->where('id', $this->database['id'])->first();

        $this->tables = $databaseModel->tables->fresh();

    }

    public function addTable(){

        $user = Auth::user();

        $newTable = Table::create([
            'name' => $this->tableName
        ]);

        $this->database->tables()->save($newTable);

        $this->tables->push($newTable);

        $this->tableName = '';


    }

    public function deleteTable($table){

        $tableModel = $this->database->tables->where('id', $table['id'])->first();

        $tableModel->delete();

        $this->viewTables();

    }

    public function mount($id){

        $user = Auth::user();

        $this->database = $user->databases->where('id', $id)->first();

        $this->viewTables();
    }

    public function render()
    {
        return view('livewire.tables');
    }
}