<?php

namespace App\Http\Livewire;

use App\Http\Controllers\ColumnController;
use App\Models\Column;
use App\Models\Table;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TableColumns extends Component
{

    public $table;

    public $databaseId;
    public $relationColumns;

    public $tables;
    public $columns;

    public $columnName ;
    public $columnType ;

    public $customRelationName;
    public $isCustomRelationName;
    public $relationColumnName ;


    function viewColumns(){

        $this->columns = $this->table->columns->fresh();

    }

    public function addColumn(){

        $columnController = new ColumnController();

        if(!$this->isCustomRelationName){
            $this->relationColumnName = $this->columnName . '_id';
        }else{
            $this->relationColumnName = $this->customRelationName;
        }


        $newColumn = $columnController->create($this->table, $this->columnType, $this->columnName, $this->relationColumnName);



        $this->columns->push($newColumn);

        $this->columnName = '';
        $this->columnType = '';
    }

    public function updateRelationColumnList(){

        $table = Table::where('data_base_id', $this->databaseId)->where('name', $this->columnName )->first();

        if($table){
            $this->relationColumns = $table->columns;
        }else{
            $this->relationColumns = [];
        }

    }

    public function deleteColumn($column){

            $columnController = new ColumnController();

            $columnController->delete($this->table, $column);

            $this->viewColumns();

    }

    public function mount($id){

        $table = Table::find($id);

        $user = Auth::user();

        $database = $user->databases->where('id', $table->data_base_id)->first();

        if($database){
            $this->table = $table;
            $this->databaseId = $database->id;
        }

        $this->viewColumns();

        $this->tables =  $database->tables->map(function ($tables) {
            return collect($tables->toArray())
                ->only( 'name')
                ->all();
        });

        $this->isCustomRelationName = 0;

    }

    public function render()
    {
        return view('livewire.table-columns');
    }
}
