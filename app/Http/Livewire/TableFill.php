<?php

namespace App\Http\Livewire;

use App\Actions\TableJSONData\AddRow;
use App\Actions\TableJSONData\DeleteRow;
use App\Http\Controllers\ChunkController;
use App\Http\Controllers\TableController;
use App\Models\DataBase;
use App\Models\Table;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TableFill extends Component
{
    public $table;



    public $parents;
    public $databaseId;

    public $rows;
    public $columns;

    public $createdRow=[];

    function viewRows(){

        $this->table->fresh();

        $this->rows = json_decode($this->table->data, true);

        if(!$this->rows){
            $this->rows = [];
        }

        $this->columns = $this->table->columns->fresh();

    }


    public function addRow(){

        $chunkController = new ChunkController();
        $chunkController->updateDataAddRow(Auth::user(), $this->table, $this->createdRow);

        $this->viewRows();

    }

    public function deleteRow($row){

       $tableController = new TableController();
       $tableController->updateDataDeleteRow(Auth::user(), $this->table, $row);

        $this->viewRows();

    }

    public function getParentRows($column){

        $relationName = $column->relationTable;

        $tableController = new TableController();
        $table = $tableController->getTableByName(Auth::user(), $relationName);


        $data = json_decode($table->data, true);

        $this->parents = $data;

//        $this->parents =  $database->tables->map(function ($tables) {
//            return collect($tables->toArray())
//                ->only( 'name')
//                ->all();
//        });

    }

    function populateWithDefaultValues(){

        foreach($this->columns as $column){
            if($column->type === "relation"){
                $this->getParentRows($column);

                if($this->parents){
                    $this->createdRow['' . $column->name] = $this->parents[0]['id'];
                }
                else{
                    $this->createdRow['' . $column->name] = '';
                }

            }
            elseif ($column->type === "checkbox"){
                $this->createdRow['' . $column->name] = 0;
            }
        }

//        dd($this->createdRow);

    }


    public function mount($id){

        $tableController = new TableController();
        $this->table = $tableController->getTableById(Auth::user(), $id);

        $database = $this->table?->database;

        $this->databaseId = $database?->id;

        $this->viewRows();

        $this->populateWithDefaultValues();


    }
    public function render()
    {
        return view('livewire.table-fill');
    }
}
