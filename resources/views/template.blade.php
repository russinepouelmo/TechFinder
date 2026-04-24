<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>TechFinder</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: #7f1d1d;    /* Rouge bordeaux */
            --accent-color: #ec4899;     /* Rose */
            --accent-hover: #db2777;
            --bg-body: #fff1f2;          /* Rose très clair */
            --text-main: #3f0d17;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: var(--bg-body);
            color: var(--text-main);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            margin: 0;
        }

        main {
            flex: 1 0 auto;
        }

        /* Barre de navigation personnalisée */
        .navbar-custom {
            background-color: var(--primary-color) !important;
            border-bottom: 3px solid var(--accent-color);
            padding: 0.8rem 0;
        }

        .navbar-brand {
            color: var(--accent-color) !important;
            letter-spacing: 1px;
            font-weight: 800 !important;
            font-size: 1.4rem;
        }

        .navbar-brand span {
            color: #ffffff;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.7) !important;
            font-weight: 500;
            transition: all 0.25s ease;
            margin: 0 5px;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--accent-color) !important;
        }

        /* Bouton Connexion */
        .btn-connexion {
            color: var(--accent-color);
            border: 2px solid var(--accent-color);
            font-weight: 600;
            border-radius: 8px;
            padding: 0.4rem 1.2rem;
            transition: all 0.3s ease;
        }

        .btn-connexion:hover {
            background-color: var(--accent-color);
            color: white !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        /* Footer */
        footer {
            background-color: #ffffff;
            border-top: 1px solid #fecdd3;
            padding: 1.5rem 0;
        }

        .footer-brand {
            color: var(--primary-color);
            font-weight: 700;
        }

        .badge-3il {
            background-color: #ffe4e6;
            color: #9f1239;
            border: 1px solid #fecdd3;
            font-weight: 500;
            padding: 0.4em 0.8em;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">TECH<span>FINDER</span></a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('Web/competences*') ? 'active' : '' }}" href="{{ route('web.competences.index') }}">Compétences</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('Web/utilisateurs*') ? 'active' : '' }}" href="{{ route('web.utilisateurs.index') }}">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Intervention</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">User Compétence</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="#" class="btn btn-connexion btn-sm">Connexion</a>
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('main')
    </main>

    <footer>
        <div class="container text-center">
            <span class="text-muted">
                © 2026 <span class="footer-brand">TechFinder</span> |
                <span class="badge badge-3il">Partenaire 3iL</span>
            </span>
        </div>
    </footer>

</body>
</html>
