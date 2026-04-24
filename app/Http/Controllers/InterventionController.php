<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class InterventionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $interventions = Intervention::all();
        return response()->json($interventions, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = request()->validate([
            'date_int' => 'required|date',
            'note_int' => 'nullable|integer|min:0|max:5',
            'commentaire_int' => 'nullable|string',
            'code_user_client' => 'required|string|exists:utilisateurs,code_user',
            'code_user_techn' => 'required|string|exists:utilisateurs,code_user',
            'code_comp' => 'required|integer|exists:competences,code_comp',
        ]);

        try {
            $intervention = Intervention::create($validate);
            return response()->json($intervention, 201);
        } catch (QueryException $e) {
            $sqlState = $e->errorInfo[0] ?? null;
            $message = $e->getMessage();
            if ($sqlState === '23000') {
                return response()->json(['message' => 'Foreign key constraint violation: ' . $message], 400);
            }
            return response()->json(['message' => $message], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Intervention $intervention)
    {
        return response()->json($intervention, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Intervention $intervention)
    {
        $validate = request()->validate([
            'date_int' => 'required|date',
            'note_int' => 'nullable|integer|min:0|max:5',
            'commentaire_int' => 'nullable|string',
            'code_user_client' => 'required|string|exists:utilisateurs,code_user',
            'code_user_techn' => 'required|string|exists:utilisateurs,code_user',
            'code_comp' => 'required|integer|exists:competences,code_comp',
        ]);

        try {
            $intervention->update($validate);
            return response()->json($intervention, 200);
        } catch (QueryException $e) {
            $sqlState = $e->errorInfo[0] ?? null;
            $message = $e->getMessage();
            if ($sqlState === '23000') {
                return response()->json(['message' => 'Foreign key constraint violation: ' . $message], 400);
            }
            return response()->json(['message' => $message], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Intervention $intervention)
    {
        try {
            $intervention->delete();
            return response()->json(null, 204);
        } catch (QueryException $e) {
            $sqlState = $e->errorInfo[0] ?? null;
            $message = $e->getMessage();
            if ($sqlState === '23000') {
                return response()->json(['message' => 'Foreign key constraint violation: ' . $message], 400);
            }
            return response()->json(['message' => $message], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
