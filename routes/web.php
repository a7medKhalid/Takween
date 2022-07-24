<?php

use App\Http\Livewire\Databases;
use App\Http\Livewire\Editors;
use App\Http\Livewire\LandingPage;
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

Route::get('/', LandingPage::class)->name('LandingPage');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {


    Route::get('/databases', Databases::class)->name('Databases');
    Route::get('/databases/{id}', Tables::class)->name('Tables');
    Route::get('/databases/{id}/editors', Editors::class)->name('Editors');


    Route::get('/tables/{id}/build', TableColumns::class)->name('Build Table');
    Route::get('/tables/{id}/fill', TableFill::class)->name('Fill Table');





});
