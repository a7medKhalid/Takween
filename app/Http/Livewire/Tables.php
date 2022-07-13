<?php

namespace App\Http\Livewire;

use App\Http\Controllers\TableController;
use App\Models\Column;
use App\Models\DataBase;
use App\Models\Table;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Tables extends Component
{

    public $database;

    public $tables;

    public $tableName ;

    public $isOwned;


    function viewTables(){

        $this->tables = $this->database->tables->fresh();

    }

    public function addTable(){

        $tableController = new TableController();

        $newTable = $tableController->create($this->database, $this->tableName);

        $this->tables->push($newTable);
        $this->tableName = '';


    }

    public function deleteTable($table){

       $tableController = new TableController();

         $tableController->delete($this->database, $table);

        $this->viewTables();

    }

    public function redirectToTableBuild($id)
    {
        return redirect()->to('tables/'. $id . '/build');
    }

    public function redirectToTableFill($id)
    {
        return redirect()->to('tables/'. $id . '/fill');
    }

    public function mount($id){

        $user = Auth::user();

        $this->database = $user->databases->where('id', $id)->first();

        $this->isOwned = 1;

        if (!$this->database){
            $permission = $user->permissions->where('data_base_id', $id)->first();
            if ($permission){
                $this->database = DataBase::find($id);
                $this->isOwned = 0;
            }
        }

        $this->viewTables();
    }

    public function render()
    {
        return view('livewire.tables');
    }
}
