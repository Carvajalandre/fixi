<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Ticket::with('requester','assignedSupport','status');

        if ($user->role_id === 1){
            $query->where('requester_id', $user->id);
        }

        return $query->get();
    }

    public function show($id)
    {
        return Ticket::with('requester','assignedSupport','status')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requester_id' => 'required|exists:users,id',
            'assigned_support_id' => 'nullable|exists:users,id',
            'status_id' => 'required|exists:ticket_statuses,id',
        ]);

        return Ticket::create($validated);
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'requester_id' => 'sometimes|required|exists:users,id',
            'assigned_support_id' => 'nullable|exists:users,id',
            'status_id' => 'sometimes|required|exists:ticket_statuses,id',
        ]);

        $ticket->update($validated);
        return $ticket;
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        return response()->noContent();
    }
}