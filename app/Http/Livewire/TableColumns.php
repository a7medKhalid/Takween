<?php

namespace App\Http\Livewire;

use App\Models\Column;
use App\Models\Table;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TableColumns extends Component
{

    public $table;

    public $columns;

    public $columnName ;
    public $columnType ;



    function viewColumns(){

        $this->columns = $this->table->columns->where('table_id', $this->table['id'])->fresh();

    }

    public function addColumn(){

        $newColumn = Column::create([
            'name' => $this->columnName,
            'type' => $this->columnType
        ]);

        $this->table->columns()->save($newColumn);

        $this->columns->push($newColumn);

        $this->columnName = '';
        $this->columnType = '';
    }

    public function deleteColumn($column){

        $columnModel = $this->table->columns->where('id', $column['id'])->first();

        $columnModel->delete();

        $this->viewColumns();

    }

    public function mount($id){

        $table = Table::find($id);

        $user = Auth::user();

        $database = $user->databases->where('id', $table->data_base_id)->first();

        if($database){
            $this->table = $table;
        }

        $this->viewColumns();
    }

    public function render()
    {
        return view('livewire.table-columns');
    }
}
