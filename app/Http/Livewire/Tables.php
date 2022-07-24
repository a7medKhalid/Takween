<?php

namespace App\Http\Livewire;

use App\Http\Controllers\DataBaseController;
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

    public $hideDeleteModal = true;
    public $databaseId;

    public function showDeleteModal($databaseId){
        $this->hideDeleteModal = false;

        $this->databaseId = $databaseId;
    }

    public function hideDeleteModal(){
        $this->hideDeleteModal = true;
    }


    function viewTables(){

        $this->tables = $this->database->tables()->select('id', 'name')->get();

    }

    public function addTable(){

        $tableController = new TableController();

        $newTable = $tableController->create(Auth::user(), $this->database, $this->tableName);

        $this->tables->push($newTable);
        $this->tableName = '';


    }

    public function deleteTable(){

       $tableController = new TableController();

         $tableController->delete(Auth::user(),$this->databaseId);

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

        $databaseController = new DatabaseController();

        $user = Auth::user();

        $this->database = $databaseController->getDatabaseById($user, $id);

        //check if database belongs to user
        if($this->database?->user_id === $user->id){
            $this->isOwned = 1;
        }else{
            $this->isOwned = 0;
        }

        $this->viewTables();
    }

    public function render()
    {
        return view('livewire.tables');
    }
}
