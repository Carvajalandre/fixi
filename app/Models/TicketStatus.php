<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_name'
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'status_id');
    }
}
