<?php

namespace App\Http\Livewire;

use App\Http\Controllers\ExportDataBaseController;
use App\Models\DataBase;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

use Illuminate\Support\Facades\Response;



class Databases extends Component
{
    public $databases;

    public $hideModal;


    public $databaseName ;

    public $exportType;
    public $databaseId;

    public function showModal($databaseId){
        $this->hideModal = false;

        $this->databaseId = $databaseId;
    }
    public function hideModal(){
    $this->hideModal = true;
    }

    public function exportDatabase(){

        $user = Auth::user();

        $databaseModel = $user->databases->where('id', $this->databaseId)->first();


        if($databaseModel){


            if ($this->exportType === 'json'){
                $data = (new \App\Http\Controllers\ExportDataBaseController)->jsonExport($databaseModel->id);

                $jsongFile = time() . '_file.json';

                return response()->streamDownload(function () use ($data) {
                    echo $data;
                }, $jsongFile);

            }elseif ($this->exportType === 'sqlite'){
                $dbName = (new \App\Http\Controllers\ExportDataBaseController)->sqliteExport($databaseModel->id);
                //download file with database name

//                dd(public_path($dbName));

                return response()->download(public_path($dbName));
            }
        }



    }


    function viewDatabases(){
        $user = Auth::user();

        $this->databases = $user->databases->fresh();

    }

    public function addDataBase(){

        $user = Auth::user();

        $newDatabase = Database::create([
            'name' => $this->databaseName
        ]);

        $user->databases()->save($newDatabase);

        $this->databases->push($newDatabase);

        $this->databaseName = '';


    }

    public function deleteDataBase($database){

        $user = Auth::user();

        $databaseModel = $user->databases->where('id', $database['id'])->first();

        $databaseModel->delete();

        $this->viewDatabases();

    }

    public function redirectToTables($id)
    {
        return redirect()->to('databases/'. $id);
    }

    public function mount(){

        $this->hideModal = true;
        $this->viewDatabases();
    }

    public function render()
    {

        return view('livewire.databases');
    }
}
