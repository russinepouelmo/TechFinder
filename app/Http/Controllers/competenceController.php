<?php

namespace App\Http\Controllers;

use App\Models\competence;
use Illuminate\Http\Request;

class competenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $competences = competence::all();
        return response()->json($competences, 200);
    }

    /**
     * Store a newly created resource in storage.
     */

public function store(Request $request)
{
    $validated = $request->validate([
        'label_comp' => 'required|string|max:255',
        'description_comp' => 'nullable|string',
    ]);

    try {
        $competence = competence::create($validated);
        return response()->json($competence, 201);
    } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}
    /**
     * Display the specified resource.
     */
    public function show(competence $competence)
    {
        return response()->json($competence, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, competence $competence)
    {
        $validate = request()->validate([
            'label_comp' => 'required|string|max:255',
            'description_comp' => 'nullable|string',
        ]);

        try {
            $competence->update($validate);
            return response()->json($competence, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Search competences by keyword
     */
    public function search($keyword)
    {
        $competences = competence::where('label_comp', 'like', '%' . $keyword . '%')
            ->orWhere('description_comp', 'like', '%' . $keyword . '%')
            ->get();

        return response()->json($competences, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(competence $competence)
    {
        try {
            $competence->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
