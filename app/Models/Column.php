<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'relationColumnName'
    ];

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }

}
