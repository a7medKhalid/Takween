<?php

namespace App\Http\Livewire;

use App\Actions\ExportDatabase\JSONExport;
use App\Actions\ExportDatabase\SQLiteExport;
use App\Http\Controllers\DataBaseController;
use App\Models\DataBase;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

use Illuminate\Support\Facades\Response;



class Databases extends Component
{
    public $databases;
    public $invitedDatabases = [];


    public $hideExportModal = true;
    public $hideDeleteModal = true;


    public $databaseName ;

    public $exportType;
    public $databaseId;

    public function showExportModal($databaseId){
        $this->hideExportModal = false;

        $this->databaseId = $databaseId;
    }
    public function hideExportModal(){
    $this->hideExportModal = true;
    }

    public function showDeleteModal($databaseId){
        $this->hideDeleteModal = false;

        $this->databaseId = $databaseId;
    }

    public function hideDeleteModal(){
        $this->hideDeleteModal = true;
    }

    public function exportDatabase(SQLiteExport $sqliteExport, JSONExport $jsonExport){

        $user = Auth::user();

        if ($this->exportType === 'json'){
            $data = $jsonExport->execute($user, $this->databaseId);

            $jsonFile = time() . '_file.json';

            return response()->streamDownload(function () use ($data) {
                echo $data;
            }, $jsonFile);

        }elseif ($this->exportType === 'sqlite'){
            $dbName = $sqliteExport->execute($user, $this->databaseId);

            return response()->download(public_path($dbName))->deleteFileAfterSend(true);
        }


    }


    function viewDatabases(){
        $user = Auth::user();

       $databaseController = new DataBaseController();

        $this->databases = $databaseController->getOwnedDatabases($user);

    }

    function viewInvitedDatabases(){
        $user = Auth::user();

        $databaseController = new DataBaseController();

        $this->invitedDatabases = $databaseController->getInvitedDatabases($user);

    }

    public function addDataBase(){


        $user = Auth::user();

       if ($user->cannot('create', DataBase::class)){
            $this->addError('database', 'Upgrade your subscription to create more databases');
            return ;
        }

        $databaseController = new DataBaseController();
        $newDatabase = $databaseController->create($user, $this->databaseName);

        $this->databases->push($newDatabase);

        $this->databaseName = '';


    }

    public function deleteDataBase(){

        $user = Auth::user();

        $databaseController = new DataBaseController();

        $databaseController->delete($user, $this->databaseId);

        $this->viewDatabases();

    }

    public function redirectToTables($id)
    {
        return redirect()->to('databases/'. $id);
    }

    public function redirectToEditors($id)
    {
        return redirect()->to('databases/'. $id . '/editors');
    }

    public function mount(){

        $this->hideModal = true;
        $this->viewDatabases();
        $this->viewInvitedDatabases();

    }

    public function render()
    {

        return view('livewire.databases');
    }
}
