<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'role_id',
        'area_id'
    ];

    protected $hidden = [
        'password'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function requestedTickets()
    {
        return $this->hasMany(Ticket::class, 'requester_id');
    }

    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_support_id');
    }
}
