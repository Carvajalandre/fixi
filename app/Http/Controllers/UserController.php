<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return User::with('role','area')->get();
    }

    public function show($id)
    {
        return User::with('role','area')->findOrFail($id);
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'area_id' => 'required|exists:areas,id',
        ]);

        $validated['role_id'] = 1; // id del rol "user"
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json($user, 201);
    }

    public function storeSupport(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'area_id' => 'required|exists:areas,id',
            'support_code' => 'required|string',
        ]);
        // Lista de códigos válidos (mejor guardarlos en .env)
        $validCodes = explode(',', env('SUPPORT_CODES', 'ABC123,XYZ789'));
        if (!in_array($validated['support_code'], $validCodes)) {
            return response()->json(['error' => 'Código de soporte inválido'], 403);
        }

        $validated['role_id'] = 2; // id del rol "support"
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'full_name' => 'sometimes|required|string|max:100',
            'email' => 'sometimes|required|string|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:6',
            'role_id' => 'sometimes|exists:roles,id',
            'area_id' => 'sometimes|exists:areas,id',
        ]);

        if(isset($validated['password'])){
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        return $user;
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->noContent();
    }
}
