<?php

namespace App\Http\Livewire;

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


        $row = ['id' => $this->table->counter];


//        {{--  to parse relation and number as integers  --}}

//        dd($this->createdRow);

        $integerColumns = [];
        foreach ($this->columns as $column){
            if ($column->type !== 'id' and in_array($column->type, array('number', 'checkbox', 'relation'))) {
                array_push($integerColumns,$column->name);
            }
        }

        foreach ($integerColumns as $integerColumn) {
            $this->createdRow[$integerColumn] = intval($this->createdRow[$integerColumn]);
        }

        $row = array_merge($row, $this->createdRow);

        array_push($this->rows, $row);


        $this->table->data = json_encode($this->rows,JSON_NUMERIC_CHECK);

        $this->table->counter += 1;

        $this->table->save();

    }

    public function deleteRow($row){

        $key = array_search($row, $this->rows);
        if ($key !== false) {
            unset($this->rows[$key]);
        }

        $this->table->data = json_encode($this->rows);

        $this->table->save();

        $this->viewRows();

    }

    public function getParentRows($relationName){

        $tableName = strtok($relationName, '_');

        $table = Table::where('data_base_id', $this->databaseId)->where('name', $tableName )->first();

        $data = json_decode($table->data, true);

        $this->parents = $data;

//        $this->parents =  $database->tables->map(function ($tables) {
//            return collect($tables->toArray())
//                ->only( 'name')
//                ->all();
//        });

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
                $this->databaseId = $id;
            }
        }

        $this->viewRows();

    }
    public function render()
    {
        return view('livewire.table-fill');
    }
}
