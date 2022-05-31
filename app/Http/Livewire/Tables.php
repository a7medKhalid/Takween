<?php

namespace App\Http\Livewire;

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

        $newTable = Table::create([
            'name' => $this->tableName
        ]);

        $this->database->tables()->save($newTable);

        $this->tables->push($newTable);

        $this->tableName = '';

        $idColumn = Column::create([
            'name' => 'id',
            'type' => 'id'
        ]);

        $newTable->columns()->save($idColumn);


    }

    public function deleteTable($table){

        $tableModel = $this->database->tables->where('id', $table['id'])->first();

        $tableModel->delete();

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
            $permission = $user->permissions->where('database_id', $id)->first();
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
