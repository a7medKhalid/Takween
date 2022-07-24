<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataBase extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];


    public function tables()
    {
        return $this->hasMany(Table::class)->orderBy('name');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    //rows count accessor
    public function getRowsCountAttribute()
    {
        return $this->tables->sum('rowsCount');
    }
}
