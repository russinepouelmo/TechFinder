<?php

namespace App\Http\Controllers;

use App\Models\User_Competence;
use Illuminate\Http\Request;

class UserCompetenceController extends Controller
{
    /**
     * Display a listing of all user competences
     */
    public function index()
    {
        $userCompetences = User_Competence::all();
        return response()->json($userCompetences, 200);
    }

    /**
     * Store a newly assigned competence to user
     */
    public function store(Request $request)
    {
        $validate = request()->validate([
            'code_user' => 'required|string|exists:utilisateurs,code_user',
            'code_comp' => 'required|integer|exists:competences,code_comp',
        ]);

        // Check if competence is already assigned to user
        $exists = User_Competence::where('code_user', $validate['code_user'])
            ->where('code_comp', $validate['code_comp'])
            ->first();

        if ($exists) {
            return response()->json(['message' => 'Competence already assigned to user'], 409);
        }

        try {
            $userCompetence = User_Competence::create($validate);
            return response()->json($userCompetence, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display user competences by user ID
     */
    public function showByUser($code_user)
    {
        $userCompetences = User_Competence::where('code_user', $code_user)->get();

        if ($userCompetences->isEmpty()) {
            return response()->json(['message' => 'No competences found for this user'], 404);
        }

        return response()->json($userCompetences, 200);
    }

    /**
     * Remove a competence from user (delete)
     */
    public function destroy(Request $request)
    {
        $validate = request()->validate([
            'code_user' => 'required|string',
            'code_comp' => 'required|integer',
        ]);

        $deleted = User_Competence::where('code_user', $validate['code_user'])
            ->where('code_comp', $validate['code_comp'])
            ->delete();

        if (!$deleted) {
            return response()->json(['message' => 'User competence not found'], 404);
        }

        return response()->json(null, 204);
    }
}
