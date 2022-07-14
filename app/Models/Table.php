<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function columns()
    {
        return $this->hasMany(Column::class);
    }

    public function database()
    {
        return $this->belongsTo(Database::class , 'data_base_id');
    }
}
