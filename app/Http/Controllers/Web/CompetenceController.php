<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Competence;
use Illuminate\Http\Request;

class CompetenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = trim((string) request('q', ''));

        $query = Competence::query();

        if ($search !== '') {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('label_comp', 'like', '%' . $search . '%')
                    ->orWhere('description_comp', 'like', '%' . $search . '%');
            });
        }

        $competences_list = $query->orderBy('label_comp')->paginate(10)->withQueryString();
        return view('competence', compact('competences_list'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label_comp'       => 'required|string|max:255',
            'description_comp' => 'nullable|string',
        ]);

        Competence::create($validated);

        return redirect()->route('web.competences.index')
                         ->with('success', 'Compétence ajoutée avec succès.');
    }

    public function update(Request $request, string $code_comp)
    {
        $competence = Competence::findOrFail($code_comp);

        $validated = $request->validate([
            'label_comp'       => 'required|string|max:255',
            'description_comp' => 'nullable|string',
        ]);

        $competence->update($validated);

        return redirect()->route('web.competences.index')
                         ->with('success', 'Compétence modifiée avec succès.');
    }

    public function destroy(string $code_comp)
    {
        Competence::findOrFail($code_comp)->delete();

        return redirect()->route('web.competences.index')
                         ->with('success', 'Compétence supprimée avec succès.');
    }
}
