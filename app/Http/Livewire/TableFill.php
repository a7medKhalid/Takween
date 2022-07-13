<?php

namespace App\Http\Livewire;

use App\Actions\TableJSONData\AddRow;
use App\Actions\TableJSONData\DeleteRow;
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


    public function addRow(AddRow $addRow){

        $addRow->execute($this->table, $this->createdRow);

        $this->viewRows();

    }

    public function deleteRow(DeleteRow $deleteRow, $row){

        $deleteRow->execute($this->table, $row);

        $this->viewRows();

    }

    public function getParentRows($column){

        $relationName = $column->relationTable;


        $table = Table::where('data_base_id', $this->databaseId)->where('name', $relationName )->first();


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


        $table = Table::find($id);

        $user = Auth::user();

        $database = $user->databases->where('id', $table->data_base_id)->first();

        if($database){
            $this->table = $table;
            $this->databaseId = $database->id;
        }else{
            $permission = $user->permissions->where('data_base_id', $table->data_base_id)->first();
            if ($permission){
                $this->table = $table;
                $this->databaseId = $table->data_base_id;
            }
        }

        $this->viewRows();

        $this->populateWithDefaultValues();


    }
    public function render()
    {
        return view('livewire.table-fill');
    }
}
