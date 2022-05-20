<?php

namespace App\Http\Livewire;

use App\Models\Table;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TableFill extends Component
{
    public $table;

    public $rows;
    public $columns;

    public $createdRow=[];

    function viewRows(){

        $this->table->fresh();

        $this->rows = json_decode($this->table->data, true);

//        dd($this->rows);

        if(!$this->rows){
            $this->rows = [];
        }

        $this->columns = $this->table->columns->fresh();

    }


    public function addRow(){

        $row = ['id' => $this->table->counter];

        $row = array_merge($row, $this->createdRow);

        array_push($this->rows, $row);

        $this->table->data = json_encode($this->rows);

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

    public function saveRows(){

    }

    public function mount($id){

        $table = Table::find($id);

        $user = Auth::user();

        $database = $user->databases->where('id', $table->data_base_id)->first();

        if($database){
            $this->table = $table;
        }

        $this->viewRows();
    }
    public function render()
    {
        return view('livewire.table-fill');
    }
}
