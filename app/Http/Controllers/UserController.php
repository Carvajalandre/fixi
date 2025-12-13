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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'area_id' => 'required|exists:areas,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        return User::create($validated);
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
