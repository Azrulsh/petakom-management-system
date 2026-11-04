<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PETAKOM Management System</title>

    <!-- Google Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link href="{{ asset('css/homepage.css') }}" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    
</head>

<body>
    <!-- =====================================
         HEADER
    ===================================== -->

    <header class="home-header">

        <img src="/images/petakom-logo.png"
             alt="PETAKOM Logo">

        <div class="header-text">

            <h1>PETAKOM MANAGEMENT SYSTEM</h1>

            <p>FACULTY OF COMPUTING</p>

        </div>

        <!-- LOGIN / DASHBOARD -->

        @if (Route::has('login'))

            <div class="auth-actions">

                @auth

                    <form method="POST"
                    action="{{ route('logout') }}"
                    class="logout-form">

                    @csrf

                    <button type="submit" class="logout-button">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>

                    </form>

                @else

                    <a href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt"></i>
                        Login
                    </a>

                @endauth

            </div>

        @endif

    </header>

    <!-- =====================================
         MAIN CONTENT
    ===================================== -->

    <main class="home-main">

        <!-- =====================================
             WELCOME SECTION
        ===================================== -->

        <section class="welcome-section">

            <div class="welcome-label">
                PETAKOM DIGITAL PLATFORM
            </div>

            @auth

                <h2>Welcome, {{ auth()->user()->name }}</h2>

                <p>
                    Welcome to PETAKOM Management System.
                    Access your management modules below.
                </p>

            @else

                <h2>Welcome to PETAKOM</h2>

                <p>
                    Your central platform for managing PETAKOM activities,
                    registration, events and organisational information.
                </p>

            @endauth

        </section>

        <!-- =====================================
             ROLE-BASED MAIN MENU
        ===================================== -->

        @auth

            @php

                $user = auth()->user();

                $isAdmin = $user->isAdmin();
                $isStudent = $user->isStudent();
                $isLecturer = $user->isLecturer();
                $isCommittee = $user->isCommittee();
                $isCoordinator = $user->isCoordinator();

                // Admin can access every module.
                $canSeeRegistration = $isAdmin;

                $canSeeActivity = $isAdmin || $isCommittee || $isStudent;

                $canSeeCalendar = true;

                $canSeeProposal =
                    $isAdmin ||
                    $isStudent ||
                    $isLecturer ||
                    $isCommittee ||
                    $isCoordinator;

               $canSeeElection = auth()->check();

                // Bulletin is available to all PETAKOM users.
                $canSeeBulletin =
                    $isAdmin ||
                    $isStudent ||
                    $isLecturer ||
                    $isCommittee ||
                    $isCoordinator;

            @endphp

            <div class="home-container">

                <!-- =================================
                     1. REGISTRATION
                     ADMIN ONLY
                ================================== -->

                @if($canSeeRegistration)

                    <details class="home-card registration-card">

                        <summary>

                            <div class="home-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>

                            <h3>Manage Registration</h3>

                            <p>
                                Manage student, lecturer and committee registrations.
                            </p>

                            <span class="registration-hint">
                                Choose registration type ▼
                            </span>

                        </summary>


                        <div class="registration-options">

                            <a href="{{ route('registration.students.index') }}">
                                <i class="fas fa-user-graduate"></i>
                                Student Registration
                            </a>

                            <a href="{{ route('registration.lecturers.index') }}">
                                <i class="fas fa-chalkboard-teacher"></i>
                                Lecturer Registration
                            </a>

                        </div>

                    </details>

                @endif

                <!-- =================================
                     2. ACTIVITY
                     ADMIN & COMMITTEE
                ================================== -->

                @if($canSeeActivity)

                    <a href="{{ route('activity.index') }}"
                    class="home-card">

                        <div class="home-icon">
                            <i class="fas fa-tasks"></i>
                        </div>

                        <h3>Manage Activity</h3>

                        <p>
                            Organise and manage PETAKOM activities.
                        </p>

                    </a>

                @endif


                <!-- =================================
                     3. CALENDAR
                     ALL AUTHENTICATED USERS
                ================================== -->

                @if($canSeeCalendar)

                    <a href="{{ route('calendar.index') }}"
                       class="home-card">

                        <div class="home-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>

                        <h3>Manage Calendar</h3>

                        <p>
                            View and manage activities throughout the semester.
                        </p>

                    </a>

                @endif

                <!-- =================================
                     4. PROPOSAL
                     ALL PETAKOM ROLES
                ================================== -->

                @if($canSeeProposal)

                    
                    <a href="{{ auth()->user()->isLecturer() || auth()->user()->isCoordinator()
                        ? route('proposal-approvals.index')
                        : route('manage-proposal.proposals.index') }}"
                    class="home-card">

                        <div class="home-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>

                        <h3>Manage Proposal</h3>

                        <p>
                            Manage PETAKOM activity proposals and documentation.
                        </p>

                    </a>

                @endif

                <!-- =================================
                     5. COMMITTEE ELECTION
                     ADMIN & COMMITTEE
                ================================== -->
            
                @if($canSeeElection)

                    <details class="home-card registration-card">

                        <summary>

                            <div class="home-icon">
                                <i class="fas fa-users-cog"></i>
                            </div>

                            <h3>
                                {{ $isAdmin || auth()->user()->isCoordinator()
                                    ? 'Manage Committee Election'
                                    : 'Committee Election'
                                }}
                            </h3>

                            <p>
                                Manage PETAKOM committee elections
                                and committee registrations.
                            </p>

                            <span class="registration-hint">
                                Choose management type ▼
                            </span>

                        </summary>

                        <div class="registration-options">

                            {{-- Committee Registration - Admin Only --}}
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('election.committees.index') }}">
                                    <i class="fas fa-users"></i>
                                    Committee Registration
                                </a>
                            @endif

                            {{-- Committee Election --}}
                            <a href="{{ route('election.index') }}">
                                <i class="fas fa-vote-yea"></i>
                                Committee Election
                            </a>

                        </div>

                    </details>

                @endif

                <!-- =================================
                     6. BULLETIN
                     ALL PETAKOM ROLES
                ================================== -->

                @if($canSeeBulletin)

                    <a href="{{ route('bulletin.index') }}"
                    class="home-card">

                        <div class="home-icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>

                        <h3>Manage Bulletin</h3>

                        <p>
                            View and manage PETAKOM and Faculty
                            bulletins and announcements.
                        </p>

                    </a>

                @endif

            </div>

        @else

            <!-- =================================
                 GUEST ACCESS
            ================================== -->

            <div class="guest-message">

                <i class="fas fa-lock"></i>

                <h3>Access PETAKOM Management System</h3>

                <p>
                    Please log in to access registration, activities,
                    calendar, proposals, committee elections and bulletin.
                </p>

            </div>

        @endauth

    </main>

    <!-- =====================================
         FOOTER
    ===================================== -->

    <footer class="home-footer">

        © 2026 PETAKOM Management System, Malaysia.
        All Rights Reserved.

    </footer>

</body>

</html>
