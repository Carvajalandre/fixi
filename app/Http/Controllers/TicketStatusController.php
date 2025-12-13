<?php

namespace App\Http\Controllers;

use App\Models\TicketStatus;
use Illuminate\Http\Request;

class TicketStatusController extends Controller
{
    public function index()
    {
        return TicketStatus::all();
    }

    public function show($id)
    {
        return TicketStatus::findOrFail($id);
    }
}
