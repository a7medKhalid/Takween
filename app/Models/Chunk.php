<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chunk extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
