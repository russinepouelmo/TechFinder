@extends('template')

@section('main')
<main class="flex-grow-1 container mt-4">
    <style>
        .users-page .title-main { color: #9f1239; font-weight: 800; }
        .users-page .card-header-theme { background: linear-gradient(120deg, #9f1239, #ec4899); color: #fff; }
        .users-page .btn-theme { background-color: #e11d48; color: #fff; border: 1px solid #e11d48; }
        .users-page .btn-theme:hover { background-color: #be123c; border-color: #be123c; color: #fff; }
        .users-page .table thead { background-color: #9f1239; color: #fff; }
        .users-page .action-buttons { display: inline-flex; align-items: center; justify-content: center; gap: 8px; flex-wrap: nowrap; }
        .users-page .modal-header-theme { background: linear-gradient(120deg, #9f1239, #f43f5e); color: #fff; }
        .users-page .top-actions { display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap; }
        .users-page .search-form { display: flex; align-items: center; gap: 8px; }
        .users-page .search-icon-btn { display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 8px; border: 1px solid #111827; background-color: #fff; color: #111827; }
    </style>

    <div class="users-page">
        <div class="top-actions mb-4">
            <h1 class="mb-0 title-main">Liste des Utilisateurs</h1>
            <form action="{{ route('web.utilisateurs.index') }}" method="GET" class="search-form">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Rechercher un utilisateur">
                <button type="submit" class="search-icon-btn" aria-label="Lancer la recherche">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.398 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.114-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/></svg>
                </button>
            </form>
        </div>

        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 2000;">
            @if (session('success'))
                <div class="toast align-items-center text-bg-success border-0 js-auto-toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="4000">
                    <div class="d-flex"><div class="toast-body">{{ session('success') }}</div></div>
                </div>
            @endif
            @if ($errors->any())
                <div class="toast align-items-center text-bg-danger border-0 js-auto-toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="4000">
                    <div class="d-flex">
                        <div class="toast-body">
                            <strong>Veuillez corriger les erreurs :</strong>
                            <ul class="mb-0 ps-3 mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header card-header-theme fw-semibold">Ajouter un utilisateur</div>
            <div class="card-body">
                <form action="{{ route('web.utilisateurs.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3"><label class="form-label">Nom</label><input type="text" name="nom_user" class="form-control" value="{{ old('nom_user') }}" required></div>
                        <div class="col-md-3"><label class="form-label">Prenom</label><input type="text" name="prenom_user" class="form-control" value="{{ old('prenom_user') }}" required></div>
                        <div class="col-md-3"><label class="form-label">Login</label><input type="text" name="login_user" class="form-control" value="{{ old('login_user') }}" required></div>
                        <div class="col-md-3"><label class="form-label">Mot de passe</label><input type="password" name="password_user" class="form-control" required></div>
                        <div class="col-md-3"><label class="form-label">Telephone</label><input type="text" name="tel_user" class="form-control" value="{{ old('tel_user') }}" required></div>
                        <div class="col-md-2"><label class="form-label">Sexe</label><select name="sexe_user" class="form-select" required><option value="M">M</option><option value="F">F</option></select></div>
                        <div class="col-md-3"><label class="form-label">Role</label><select name="role_user" class="form-select" required><option value="client">Client</option><option value="technicien">Technicien</option><option value="admin">Administrateur</option></select></div>
                        <div class="col-md-2"><label class="form-label">Etat</label><select name="etat_user" class="form-select" required><option value="actif">Actif</option><option value="inactif">Inactif</option><option value="suspendu">Suspendu</option></select></div>
                        <div class="col-md-2 d-flex align-items-end"><button type="submit" class="btn btn-theme w-100">Ajouter</button></div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Matricule</th><th>Nom</th><th>Prenom</th><th>Login</th><th>Role</th><th>Etat</th><th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($utilisateurs_list as $user)
                            <tr>
                                <td>{{ $user->code_user }}</td>
                                <td>{{ $user->nom_user }}</td>
                                <td>{{ $user->prenom_user }}</td>
                                <td>{{ $user->login_user }}</td>
                                <td>{{ $user->role_user }}</td>
                                <td>{{ $user->etat_user }}</td>
                                <td class="text-center">
                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#editUserModal"
                                            data-code="{{ $user->code_user }}" data-nom="{{ $user->nom_user }}" data-prenom="{{ $user->prenom_user }}"
                                            data-login="{{ $user->login_user }}" data-tel="{{ $user->tel_user }}" data-sexe="{{ $user->sexe_user }}"
                                            data-role="{{ $user->role_user }}" data-etat="{{ $user->etat_user }}">Modifier</button>
                                        <form action="{{ route('web.utilisateurs.destroy', $user->code_user) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">Aucun utilisateur enregistre.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">Affichage de {{ $utilisateurs_list->firstItem() ?? 0 }} a {{ $utilisateurs_list->lastItem() ?? 0 }} sur {{ $utilisateurs_list->total() }} utilisateur(s).</small>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $utilisateurs_list->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</main>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-theme">
                <h5 class="modal-title">Modifier utilisateur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label">Nom</label><input type="text" id="edit_nom_user" name="nom_user" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label">Prenom</label><input type="text" id="edit_prenom_user" name="prenom_user" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label">Login</label><input type="text" id="edit_login_user" name="login_user" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label">Nouveau mot de passe (optionnel)</label><input type="password" name="password_user" class="form-control"></div>
                        <div class="col-md-3"><label class="form-label">Telephone</label><input type="text" id="edit_tel_user" name="tel_user" class="form-control" required></div>
                        <div class="col-md-2"><label class="form-label">Sexe</label><select id="edit_sexe_user" name="sexe_user" class="form-select" required><option value="M">M</option><option value="F">F</option></select></div>
                        <div class="col-md-3"><label class="form-label">Role</label><select id="edit_role_user" name="role_user" class="form-select" required><option value="client">Client</option><option value="technicien">Technicien</option><option value="admin">Administrateur</option></select></div>
                        <div class="col-md-3"><label class="form-label">Etat</label><select id="edit_etat_user" name="etat_user" class="form-select" required><option value="actif">Actif</option><option value="inactif">Inactif</option><option value="suspendu">Suspendu</option></select></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-theme">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('editUserModal').addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        document.getElementById('editUserForm').action = '/Web/utilisateurs/' + btn.getAttribute('data-code');
        document.getElementById('edit_nom_user').value = btn.getAttribute('data-nom');
        document.getElementById('edit_prenom_user').value = btn.getAttribute('data-prenom');
        document.getElementById('edit_login_user').value = btn.getAttribute('data-login');
        document.getElementById('edit_tel_user').value = btn.getAttribute('data-tel');
        document.getElementById('edit_sexe_user').value = btn.getAttribute('data-sexe');
        document.getElementById('edit_role_user').value = btn.getAttribute('data-role');
        document.getElementById('edit_etat_user').value = btn.getAttribute('data-etat');
    });

    const toasts = document.querySelectorAll('.js-auto-toast');
    if (window.bootstrap && window.bootstrap.Toast) {
        toasts.forEach(function (el) {
            const toast = new bootstrap.Toast(el, { autohide: true, delay: 4000 });
            toast.show();
        });
    } else {
        toasts.forEach(function (el) {
            el.style.display = 'block';
            el.classList.add('show');
            setTimeout(function () { el.classList.remove('show'); el.style.display = 'none'; }, 4000);
        });
    }
</script>
@endsection
