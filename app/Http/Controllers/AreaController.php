<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        return Area::all();
    }

    public function show($id)
    {
        return Area::findOrFail($id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'area_name' => 'required|string|max:100|unique:areas',
        ]);

        return Area::create($validated);
    }

    public function update(Request $request, $id)
    {
        $area = Area::findOrFail($id);
        $validated = $request->validate([
            'area_name' => 'required|string|max:100|unique:areas,area_name,' . $id,
        ]);

        $area->update($validated);
        return $area;
    }

    public function destroy($id)
    {
        $area = Area::findOrFail($id);
        $area->delete();
        return response()->noContent();
    }
}
