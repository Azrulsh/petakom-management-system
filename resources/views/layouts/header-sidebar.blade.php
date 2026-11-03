<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Manage Registration') | PETAKOM</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/nav.css') }}" rel="stylesheet">
    <link href="{{ asset('css/body.css') }}" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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

                @if(auth()->user()->isAdmin())

                    <li class="has-submenu">
                        <a href="#">Registration</a>

                        <ul class="submenu">

                            <li>
                                <a href="{{ route('manage-registration.students.index') }}">
                                    Student Registration
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('manage-registration.lecturers.index') }}">
                                    Lecturer Registration
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('manage-registration.committees.index') }}">
                                    Committee Registration
                                </a>
                            </li>

                        </ul>
                    </li>

                @endif

                <li>
                    <a href="#">Activity</a>
                </li>

                <br>

                @auth
                    <li>
                        <a href="{{ route('calendar.index') }}">
                            Calendar
                        </a>
                    </li>
                @endauth

                <br>

                @if(
                    auth()->user()->isAdmin() ||
                    auth()->user()->isStudent() ||
                    auth()->user()->isLecturer() ||
                    auth()->user()->isCommittee() ||
                    auth()->user()->isCoordinator()
                )

                    <li class="has-submenu">
                                                
                        <a href="#">Proposal</a>

                        <ul class="submenu">

                            {{-- ACTIVITY PROPOSAL --}}
                            {{-- Admin and Student --}}

                            @if(auth()->check() &&
                                (auth()->user()->isAdmin() ||
                                auth()->user()->isStudent()))

                                <li>
                                    <a href="{{ route('manage-proposal.proposals.index') }}">
                                        <i class="fas fa-file-alt"></i>
                                        Activity Proposal
                                    </a>
                                </li>

                            @endif


                            {{-- ACTIVITY REPORTS --}}
                            {{-- Admin, Committee, Lecturer and Coordinator --}}

                            @if(auth()->check() &&
                                (auth()->user()->isAdmin() ||
                                auth()->user()->isActiveCommitteeMember() ||
                                auth()->user()->isLecturer() ||
                                auth()->user()->isCoordinator()))

                                <li>
                                    <a href="{{ route('manage-proposal.activity-reports.index') }}">
                                        <i class="fas fa-clipboard-list"></i>
                                        Activity Reports
                                    </a>
                                </li>

                            @endif


                            {{-- GENERATE REPORTS --}}

                            <li>
                                <a href="{{ route('manage-proposal.reports.index') }}">
                                    <i class="fas fa-chart-bar"></i>
                                    Generate Reports
                                </a>
                            </li>


                            {{-- APPROVAL MANAGEMENT --}}
                            {{-- Admin, Lecturer and Coordinator --}}

                            @if(auth()->check() &&
                                (auth()->user()->isAdmin() ||
                                auth()->user()->isLecturer() ||
                                auth()->user()->isCoordinator()))

                                <li>
                                    <a href="{{ route('proposal-approvals.index') }}">
                                        <i class="fas fa-clipboard-check"></i>
                                        Pending Approvals
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('proposal-approvals.history.index') }}">
                                        <i class="fas fa-history"></i>
                                        Approval History
                                    </a>
                                </li>

                            @endif

                        </ul>

                    </li>

                @endif

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
