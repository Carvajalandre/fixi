<?php

namespace App\Http\Controllers;

use App\Models\Interaction;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InteractionController extends Controller
{
    /**
     * Listar todas las interacciones de un ticket específico
     */
    public function index($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);

        // Trae las interacciones con el usuario que las creó
        $interactions = Interaction::with('user')
            ->where('ticket_id', $ticket->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($interactions);
    }

    /**
     * Crear una nueva interacción
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'content' => 'required|string',
            'type' => 'required|string|in:comment,update,note', // tipos de interacción
        ]);

        // Asignar el usuario autenticado
        $validated['user_id'] = Auth::id();

        $interaction = Interaction::create($validated);

        return response()->json($interaction, 201);
    }

    /**
     * Mostrar una interacción específica
     */
    public function show($id)
    {
        $interaction = Interaction::with('user', 'ticket')->findOrFail($id);
        return response()->json($interaction);
    }

    /**
     * Opcional: actualizar interacción
     */
    public function update(Request $request, $id)
    {
        $interaction = Interaction::findOrFail($id);

        $validated = $request->validate([
            'content' => 'sometimes|required|string',
            'type' => 'sometimes|required|string|in:comment,update,note',
        ]);

        $interaction->update($validated);

        return response()->json($interaction);
    }

    /**
     * Opcional: eliminar interacción
     */
    public function destroy($id)
    {
        $interaction = Interaction::findOrFail($id);
        $interaction->delete();

        return response()->noContent();
    }
}
