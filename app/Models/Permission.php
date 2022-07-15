<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getNameAttribute()
    {
        return $this->user->name;
    }

    public function getEmailAttribute()
    {
        return $this->user->email;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function database()
    {
        return $this->belongsTo(DataBase::class, 'data_base_id');
    }
}
