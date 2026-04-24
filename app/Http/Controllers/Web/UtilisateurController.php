<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UtilisateurController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $query = Utilisateur::query();

        if ($search !== '') {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('code_user', 'like', '%' . $search . '%')
                    ->orWhere('nom_user', 'like', '%' . $search . '%')
                    ->orWhere('prenom_user', 'like', '%' . $search . '%')
                    ->orWhere('login_user', 'like', '%' . $search . '%')
                    ->orWhere('role_user', 'like', '%' . $search . '%');
            });
        }

        $utilisateurs_list = $query->orderBy('nom_user')->paginate(10)->withQueryString();

        return view('utilisateur', compact('utilisateurs_list'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_user' => 'required|string|max:255',
            'prenom_user' => 'required|string|max:255',
            'login_user' => 'required|string|max:255|unique:utilisateurs,login_user',
            'password_user' => 'required|string|min:6|max:255',
            'tel_user' => 'required|string|max:15',
            'sexe_user' => 'required|in:M,F',
            'role_user' => 'required|in:admin,technicien,client',
            'etat_user' => 'required|in:actif,inactif,suspendu',
        ]);

        $validated['code_user'] = Utilisateur::generateUniqueMatricule();
        $validated['password_user'] = Hash::make($validated['password_user']);

        Utilisateur::create($validated);

        return redirect()->route('web.utilisateurs.index')
            ->with('success', 'Utilisateur cree avec succes.');
    }

    public function update(Request $request, string $code_user)
    {
        $utilisateur = Utilisateur::findOrFail($code_user);

        $validated = $request->validate([
            'nom_user' => 'required|string|max:255',
            'prenom_user' => 'required|string|max:255',
            'login_user' => 'required|string|max:255|unique:utilisateurs,login_user,' . $utilisateur->code_user . ',code_user',
            'password_user' => 'nullable|string|min:6|max:255',
            'tel_user' => 'required|string|max:15',
            'sexe_user' => 'required|in:M,F',
            'role_user' => 'required|in:admin,technicien,client',
            'etat_user' => 'required|in:actif,inactif,suspendu',
        ]);

        if (!empty($validated['password_user'])) {
            $validated['password_user'] = Hash::make($validated['password_user']);
        } else {
            unset($validated['password_user']);
        }

        $utilisateur->update($validated);

        return redirect()->route('web.utilisateurs.index')
            ->with('success', 'Utilisateur modifie avec succes.');
    }

    public function destroy(string $code_user)
    {
        Utilisateur::findOrFail($code_user)->delete();

        return redirect()->route('web.utilisateurs.index')
            ->with('success', 'Utilisateur supprime avec succes.');
    }
}
