<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'ticket', 'name', 'purpose', 'status', 'called_at', 'served_at'
    ];
}