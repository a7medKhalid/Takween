<?php

use App\Http\Livewire\Databases;
use App\Http\Livewire\Editors;
use App\Http\Livewire\TableColumns;
use App\Http\Livewire\TableFill;
use App\Http\Livewire\Tables;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/databases', Databases::class);
    Route::get('/databases/{id}', Tables::class);
    Route::get('/databases/{id}/editors', Editors::class);


    Route::get('/tables/{id}/build', TableColumns::class);
    Route::get('/tables/{id}/fill', TableFill::class);





});
