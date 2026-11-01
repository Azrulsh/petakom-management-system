
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Manage Registration') | PETAKOM</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap"
          rel="stylesheet">

    <!-- Existing PETAKOM CSS -->
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/nav.css" rel="stylesheet">
    <link href="/css/body.css" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            margin: 0;
        }

        .registration-content {
            flex: 1;
            min-width: 0;
            padding: 25px;
        }

        .wrapper {
            display: flex;
            min-height: calc(100vh - 100px);
        }

        .registration-content .card {
            border-radius: 10px;
        }

        .registration-content .form-control,
        .registration-content .form-select {
            min-height: 42px;
        }

        .registration-content .table th {
            white-space: nowrap;
        }

        .registration-content .table td {
            vertical-align: middle;
        }

        @media (max-width: 768px) {
            .wrapper {
                display: block;
            }

            .registration-content {
                padding: 15px;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="bodies">

    <!-- Header -->
    <header class="title">

        <img class="logo"
             src="/images/petakom-logo.png"
             alt="PETAKOM Logo">

        PETAKOM MANAGEMENT SYSTEM

        <img class="profile"
             src="/images/profile.png"
             alt="Profile">

        <span class="person">
            {{ auth()->user()->name ?? 'Users' }}
        </span>

    </header>

    <!-- Navigation and Content -->
    <div class="wrapper">

        <!-- Sidebar -->
        <div class="sidebar">

            <ul>

                <li>
                    <a href="/">Dashboard</a>
                </li>

                <br>

                
<li class="has-submenu">

    <a href="#">
        Registration
    </a>

    <ul class="submenu">

        <li>
            <a href="{{ route('manage-registration.students.index') }}"
               class="{{ request()->routeIs('manage-registration.students.*') ? 'active' : '' }}">
                Student Registration
            </a>
        </li>

        <li>
            <a href="{{ route('manage-registration.lecturers.index') }}"
               class="{{ request()->routeIs('manage-registration.lecturers.*') ? 'active' : '' }}">
                Lecturer Registration
            </a>
        </li>

        <li>
            <a href="{{ route('manage-registration.committees.index') }}"
               class="{{ request()->routeIs('manage-registration.committees.*') ? 'active' : '' }}">
                Committee Registration
            </a>
        </li>

    </ul>

</li>


                <br>

                <li>
                    <a href="/calendar">Calendar</a>
                </li>

                <br>

                <li>
                    <a href="#">Proposal</a>
                </li>

                <br>

                <li>
                    <a href="#">Activity</a>
                </li>

                <br>

                <li>
                    <a href="#">Committee Election</a>
                </li>

                <br>

                <li>
                    <a href="#">Bulletin</a>
                </li>

            </ul>

        </div>

        <!-- Main Content -->
        <main class="registration-content">

            @yield('content')

        </main>

    </div>

    <!-- Footer -->
    <footer class="footers">
        © 2026 PETAKOM Management System, Malaysia
    </footer>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    @stack('scripts')

</body>
</html>
