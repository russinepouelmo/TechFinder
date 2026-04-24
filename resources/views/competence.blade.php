@extends('template')

@section('main')
<main class="flex-grow-1 container mt-4">
    <style>
        .competence-page .title-main {
            color: #9f1239;
            font-weight: 800;
        }

        .competence-page .card-header-theme {
            background: linear-gradient(120deg, #9f1239, #ec4899);
            color: #fff;
        }

        .competence-page .btn-theme {
            background-color: #e11d48;
            color: #fff;
            border: 1px solid #e11d48;
        }

        .competence-page .btn-theme:hover {
            background-color: #be123c;
            border-color: #be123c;
            color: #fff;
        }

        .competence-page .table thead {
            background-color: #9f1239;
            color: #fff;
        }

        .competence-page .action-buttons {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-wrap: nowrap;
        }

        .competence-page .modal-header-theme {
            background: linear-gradient(120deg, #9f1239, #f43f5e);
            color: #fff;
        }

        .competence-page .modal-soft-info {
            background-color: #fff1f2;
            border: 1px solid #fecdd3;
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 12px;
            color: #9f1239;
            font-size: 0.92rem;
        }

        .competence-page .pagination .page-link {
            color: #9f1239;
        }

        .competence-page .pagination .active > .page-link {
            background-color: #e11d48;
            border-color: #e11d48;
        }

        .competence-page .top-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .competence-page .search-form {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .competence-page .search-icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 1px solid #111827;
            background-color: #ffffff;
            color: #111827;
        }

        .competence-page .search-icon-btn:hover {
            background-color: #f3f4f6;
            color: #000;
        }
    </style>

<div class="competence-page">
    <div class="top-actions mb-4">
        <h1 class="mb-0 title-main">Liste des Compétences</h1>
        <form action="{{ route('web.competences.index') }}" method="GET" class="search-form">
            <input type="text"
                   name="q"
                   value="{{ request('q') }}"
                   class="form-control"
                   placeholder="Rechercher une compétence"
                   aria-label="Rechercher une compétence">
            <button type="submit" class="search-icon-btn" aria-label="Lancer la recherche">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.398 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.114-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                </svg>
            </button>
        </form>
    </div>

    {{-- Notifications en toast --}}
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 2000;">
        @if (session('success'))
            <div class="toast align-items-center text-bg-success border-0 js-auto-toast"
                 role="alert"
                 aria-live="assertive"
                 aria-atomic="true"
                 data-bs-autohide="true"
                 data-bs-delay="4000">
                <div class="d-flex">
                    <div class="toast-body">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="toast align-items-center text-bg-danger border-0 js-auto-toast"
                 role="alert"
                 aria-live="assertive"
                 aria-atomic="true"
                 data-bs-autohide="true"
                 data-bs-delay="4000">
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

    {{-- Formulaire d'ajout --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header card-header-theme fw-semibold">
            Ajouter une compétence
        </div>
        <div class="card-body">
            <form id="add-competence-form" action="{{ route('web.competences.store') }}" method="POST">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="label_comp" class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('label_comp') is-invalid @enderror"
                               id="label_comp"
                               name="label_comp"
                               value="{{ old('label_comp') }}"
                               placeholder="Ex : PHP, Docker…"
                               required>
                        @error('label_comp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="description_comp" class="form-label">Description</label>
                        <input type="text"
                               class="form-control @error('description_comp') is-invalid @enderror"
                               id="description_comp"
                               name="description_comp"
                               value="{{ old('description_comp') }}"
                               placeholder="Description courte (optionnel)">
                        @error('description_comp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-theme w-100">Ajouter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tableau des compétences --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:60px">#</th>
                        <th>Nom de la compétence</th>
                        <th>Description</th>
                        <th style="width:180px" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($competences_list as $competence)
                        <tr>
                            <td>{{ $competence->code_comp }}</td>
                            <td>{{ $competence->label_comp }}</td>
                            <td>{{ $competence->description_comp ?? '—' }}</td>
                            <td class="text-center">
                                <div class="action-buttons">
                                    {{-- Bouton Modifier --}}
                                    <button type="button"
                                            class="btn btn-outline-secondary btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal"
                                            data-code="{{ $competence->code_comp }}"
                                            data-label="{{ $competence->label_comp }}"
                                            data-description="{{ $competence->description_comp }}">
                                        Modifier
                                    </button>

                                    {{-- Bouton Supprimer --}}
                                    <form action="{{ route('web.competences.destroy', $competence->code_comp) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer « {{ $competence->label_comp }} » ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Aucune compétence enregistrée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <small class="text-muted">
            Affichage de {{ $competences_list->firstItem() ?? 0 }} a {{ $competences_list->lastItem() ?? 0 }} sur {{ $competences_list->total() }} competence(s).
        </small>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $competences_list->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>

</main>

{{-- Modal de modification --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-theme">
                <h5 class="modal-title" id="editModalLabel">Modifier la compétence</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="modal-soft-info">
                        Mettez a jour les informations de la competence puis enregistrez.
                    </div>
                    <div class="mb-3">
                        <label for="edit_label_comp" class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control"
                               id="edit_label_comp"
                               name="label_comp"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description_comp" class="form-label fw-semibold">Description</label>
                        <textarea class="form-control"
                                  id="edit_description_comp"
                                  name="description_comp"
                                  rows="4"
                                  placeholder="Donnez plus de details sur cette competence"></textarea>
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
</div>

{{-- Peuplement du modal via JS --}}
<script>
    document.getElementById('editModal').addEventListener('show.bs.modal', function (event) {
        const btn         = event.relatedTarget;
        const code        = btn.getAttribute('data-code');
        const label       = btn.getAttribute('data-label');
        const description = btn.getAttribute('data-description') || '';

        document.getElementById('editForm').action        = '/Web/competences/' + code;
        document.getElementById('edit_label_comp').value       = label;
        document.getElementById('edit_description_comp').value = description;
    });

    const toasts = document.querySelectorAll('.js-auto-toast');

    if (window.bootstrap && window.bootstrap.Toast) {
        toasts.forEach(function (el) {
            const toast = new bootstrap.Toast(el, {
                autohide: true,
                delay: Number(el.getAttribute('data-bs-delay')) || 4000
            });
            toast.show();
        });
    } else {
        // Fallback: show and hide toasts even if Bootstrap JS fails to load.
        toasts.forEach(function (el) {
            const delay = Number(el.getAttribute('data-bs-delay')) || 4000;
            el.style.display = 'block';
            el.classList.add('show');
            setTimeout(function () {
                el.classList.remove('show');
                el.style.display = 'none';
            }, delay);
        });
    }
</script>
@endsection
