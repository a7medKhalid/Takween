<?php

namespace App\Http\Livewire;

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

        if($this->columnType === 'relation'){

            if(!$this->isCustomRelationName){
                $this->customRelationName = $this->columnName . '_id';
            }

            $newColumn = Column::create([
                'name' => $this->customRelationName,
                'type' => $this->columnType,
                'relationColumnName' => $this->relationColumnName,
                'relationTable' => $this->columnName
            ]);
        }else{

            $newColumn = Column::create([
                'name' => $this->columnName,
                'type' => $this->columnType
            ]);
        }

        //add null values for pervious data
        $this->table->fresh();

        $rows = json_decode($this->table->data, true);

        $nullColumn = [$newColumn->name => 'NULL'];

        if ($rows){

            $updatedRows = [];

            foreach ($rows as $row){
                $row = array_merge($row, $nullColumn);

                array_push($updatedRows, $row);
            }

            $this->table->data = json_encode($updatedRows,JSON_NUMERIC_CHECK);

            $this->table->save();
        }



        $this->table->columns()->save($newColumn);

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

        $columnModel = $this->table->columns->where('id', $column['id'])->first();

        $columnModel->delete();

        $this->viewColumns();

        //delete column from rows
        $this->table->fresh();

        $rows = json_decode($this->table->data, true);

        if ($rows){

            $updatedRows = [];

            foreach ($rows as $row){
                unset($row[$columnModel->name]);

                array_push($updatedRows, $row);
            }
            
            $this->table->data = json_encode($updatedRows,JSON_NUMERIC_CHECK);

            $this->table->save();
        }

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
