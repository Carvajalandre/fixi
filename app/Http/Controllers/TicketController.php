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

        // User solo ve sus tickets
        if ($user->role_id === 1) {
            $query->where('requester_id', $user->id);
        }

        return $query->get();
    }

    public function show($id)
    {
        return Ticket::with('requester','assignedSupport','status')
            ->findOrFail($id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requester_id' => 'required|exists:users,id',
            'status_id' => 'required|exists:ticket_statuses,id',
        ]);

        return Ticket::create($validated);
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status_id' => 'sometimes|exists:ticket_statuses,id',
        ]);

        $ticket->update($validated);
        return $ticket;
    }

    // ✅ ASIGNARSE TICKET (SOPORTE)
    public function assign($id)
    {
        $user = Auth::user();

        if ($user->role_id !== 2) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $ticket = Ticket::findOrFail($id);

        $ticket->update([
            'assigned_support_id' => $user->id,
            'status_id' => 2 // in_progress
        ]);

        return $ticket->load('requester','assignedSupport','status');
    }

    // ✅ ELIMINAR TICKET (SOPORTE)
    public function destroy($id)
    {
        $user = Auth::user();

        if ($user->role_id !== 2) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return response()->noContent();
    }
}
