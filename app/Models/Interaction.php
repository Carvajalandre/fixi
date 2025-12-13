<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'type',
        'content',
    ];

    // Relación con Ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}