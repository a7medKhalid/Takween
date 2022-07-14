<?php

namespace App\Http\Livewire;

use App\Http\Controllers\ColumnController;
use App\Http\Controllers\TableController;
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

    public $tableName;

    public $customRelationName;
    public $isCustomRelationName;
    public $relationColumnName ;


    function viewColumns(){

        $columnsController = new ColumnController();
        $this->columns = $columnsController->getTableColumns(Auth::user(), $this->table);

    }

    public function addColumn(){

        $columnController = new ColumnController();

        $relationTable = $this->tableName;

        $columnName = $this->columnName;

        if ($this->columnType === 'relation') {
            if ($this->isCustomRelationName) {
                $columnName = $this->customRelationName;
            } else {
                $columnName = $this->tableName . '_id';
            }
        }


        $newColumn = $columnController->create(Auth::user(), $this->table, $this->columnType, $columnName, $relationTable, $this->relationColumnName);



        $this->columns->push($newColumn);

        $this->columnName = '';
        $this->columnType = '';
    }

    public function updateRelationColumnList(){

        $tableController = new TableController();

        $table = $tableController->getTableByname(Auth::user(), $this->tableName);

        if($table){
            $this->relationColumns = $table->columns;
        }else{
            $this->relationColumns = [];
        }

    }

    public function deleteColumn($column){

            $columnController = new ColumnController();

            $columnController->delete(Auth::user(), $this->table, $column);

            $this->viewColumns();

    }

    public function mount($id){

        $tableController = new TableController();
        $this->table = $tableController->getTableById(Auth::user(), $id);

        $database = $this->table?->database;

        $this->databaseId = $database?->id;

        $this->viewColumns();

        $this->tables =  $database?->tables->map(function ($tables) {
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
