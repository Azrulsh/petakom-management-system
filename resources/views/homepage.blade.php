<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PETAKOM Management System</title>

    <!-- Google Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700;800&display=swap"
          rel="stylesheet">

    <!-- Existing CSS -->
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/nav.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>

    
/* =====================================
   GLOBAL SETTINGS
===================================== */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Nunito', sans-serif;
    background: #F8F8F8;
    color: #333333;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* =====================================
   HEADER
===================================== */

.home-header {
    background: #8B0000;
    color: #FFFFFF;
    min-height: 105px;
    display: flex;
    align-items: center;
    padding: 18px 7%;
    gap: 20px;
}

.home-header img {
    width: 68px;
    height: 68px;
    object-fit: contain;
}

.header-text h1 {
    font-size: 24px;
    font-weight: 800;
    letter-spacing: 1.5px;
}

.header-text p {
    font-size: 12px;
    color: #F5DADA;
    letter-spacing: 2px;
    margin-top: 3px;
}

/* =====================================
   LOGIN BUTTON
===================================== */

.auth-actions {
    margin-left: auto;
}

.auth-actions a {
    display: inline-block;
    color: #FFFFFF;
    text-decoration: none;
    padding: 9px 20px;
    border: 1px solid rgba(255,255,255,.6);
    border-radius: 6px;
    font-size: 13px;
    font-weight: 700;
    transition: .3s;
}

.auth-actions a:hover {
    background: #FFFFFF;
    color: #8B0000;
}

/* =====================================
   MAIN CONTENT
===================================== */

.home-main {
    width: 100%;
    max-width: 1200px;
    margin: auto;
    padding: 65px 30px;
    flex: 1;
}

/* =====================================
   WELCOME SECTION
===================================== */

.welcome-section {
    text-align: center;
    margin-bottom: 45px;
}

.welcome-label {
    color: #C62828;
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 3px;
    margin-bottom: 12px;
}

.welcome-section h2 {
    font-size: 34px;
    font-weight: 800;
    color: #8B0000;
    margin-bottom: 12px;
}

.welcome-section p {
    color: #666666;
    font-size: 15px;
    line-height: 1.7;
}

/* =====================================
   MENU GRID
===================================== */

.home-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    align-items: stretch;
}

/* =====================================
   MENU CARDS
===================================== */

.home-card {
    position: relative;
    background: #FFFFFF;
    text-decoration: none;
    color: #8B0000;
    border-radius: 12px;

    padding: 32px 25px;

    width: 100%;
    height: 220px;
    min-height: 220px;

    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;

    border: 1px solid #F0D5D5;
    box-shadow: 0 4px 15px rgba(139, 0, 0, .04);

    transition: all .25s ease;
}

.home-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(139, 0, 0, .12);
    border-color: #C62828;
    color: #C62828;
    text-decoration: none;
    z-index: 2;
}

/* =====================================
   MENU ICON
===================================== */

.home-icon {
    width: 55px;
    height: 55px;

    background: #FDECEC;
    color: #C62828;

    border-radius: 12px;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 23px;
    margin-bottom: 20px;

    flex-shrink: 0;
    transition: .25s;
}

.home-card:hover .home-icon {
    background: #C62828;
    color: #FFFFFF;
}

/* =====================================
   MENU TEXT
===================================== */

.home-card h3 {
    font-size: 17px;
    font-weight: 800;
    margin-bottom: 8px;
    color: #8B0000;
}

.home-card p {
    font-size: 13px;
    color: #666666;
    line-height: 1.6;
    margin-bottom: 0;
}

/* =====================================
   REGISTRATION DROPDOWN
===================================== */

.registration-card {
    position: relative;
    display: block;

    height: 220px;
    min-height: 220px;

    padding: 0;
    overflow: visible;
}

/* Keep registration card above other cards */

.registration-card[open] {
    z-index: 20;
    transform: none;
}

/* Registration clickable area */

.registration-card summary {
    list-style: none;
    cursor: pointer;

    width: 100%;
    height: 100%;
    min-height: 220px;

    padding: 32px 25px;

    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;

    box-sizing: border-box;
}

.registration-card summary::-webkit-details-marker {
    display: none;
}

/* Dropdown indicator */

.registration-hint {
    display: inline-block;
    margin-top: 10px;

    color: #C62828;
    font-size: 12px;
    font-weight: 800;
}

/* Dropdown menu */

.registration-options {
    position: absolute;

    top: calc(100% + 8px);
    left: 0;

    width: 100%;

    padding: 12px;

    display: flex;
    flex-direction: column;
    gap: 10px;

    background: #FFFFFF;

    border: 1px solid #F0D5D5;
    border-radius: 10px;

    box-shadow: 0 8px 20px rgba(139, 0, 0, .12);

    z-index: 100;
}

/* Dropdown links */

.registration-options a {
    display: flex;
    align-items: center;
    gap: 12px;

    padding: 12px 15px;

    background: #FDECEC;
    color: #8B0000;

    text-decoration: none;
    border-radius: 7px;

    font-size: 13px;
    font-weight: 700;

    transition: .2s;
}

.registration-options a:hover {
    background: #C62828;
    color: #FFFFFF;
    text-decoration: none;
}

.registration-options a i {
    width: 20px;
    text-align: center;
}

/* =====================================
   FOOTER
===================================== */

.home-footer {
    background: #8B0000;
    color: #FFFFFF;

    text-align: center;
    padding: 20px;

    font-size: 13px;
}

/* =====================================
   TABLET RESPONSIVE
===================================== */

@media (max-width: 900px) {

    .home-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

}

/* =====================================
   MOBILE RESPONSIVE
===================================== */

@media (max-width: 600px) {

    .home-header {
        padding: 20px;
        gap: 12px;
        flex-wrap: wrap;
    }

    .home-header img {
        width: 50px;
        height: 50px;
    }

    .header-text h1 {
        font-size: 15px;
        letter-spacing: .5px;
    }

    .header-text p {
        font-size: 9px;
        letter-spacing: 1px;
    }

    .auth-actions {
        width: 100%;
        margin-left: 62px;
    }

    .home-main {
        padding: 40px 18px;
    }

    .welcome-section h2 {
        font-size: 26px;
    }

    .welcome-section p {
        font-size: 13px;
    }

    .home-container {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .home-card,
    .registration-card {
        height: 220px;
        min-height: 220px;
    }

    .registration-card summary {
        min-height: 220px;
    }

}

    </style>

</head>

<body>

    <!-- =========================
         HEADER
    ========================= -->

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

                    <a href="{{ url('/dashboard') }}">
                        <i class="fas fa-user-circle"></i>
                        Dashboard
                    </a>

                @else

                    <a href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt"></i>
                        Login
                    </a>

                @endauth

            </div>

        @endif

    </header>


    <!-- =========================
         MAIN CONTENT
    ========================= -->

    <main class="home-main">

        <!-- WELCOME SECTION -->

        <section class="welcome-section">

            <div class="welcome-label">
                PETAKOM DIGITAL PLATFORM
            </div>

            <h2>Welcome to PETAKOM</h2>

            <p>
                Your central platform for managing PETAKOM activities,
                registration, events and organisational information.
            </p>

        </section>


        <!-- =========================
             MAIN MENU
        ========================= -->

        <div class="home-container">
            
            <!-- REGISTRATION -->

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

                    <a href="{{ route('manage-registration.students.create') }}">
                        <i class="fas fa-user-graduate"></i>
                        Student Registration
                    </a>

                    <a href="{{ route('manage-registration.lecturers.create') }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        Lecturer Registration
                    </a>

                    <a href="{{ route('manage-registration.committees.create') }}">
                        <i class="fas fa-users"></i>
                        Committee Registration
                    </a>

                </div>

            </details>

            <!-- CALENDAR -->

            <a href="{{ url('/calendar') }}"
               class="home-card">

                <div class="home-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>

                <h3>Manage Calendar</h3>

                <p>
                    View and manage activities throughout the semester.
                </p>

            </a>

            <!-- PROPOSAL -->

            <a href="{{ url('Manage Proposal') }}"
               class="home-card">

                <div class="home-icon">
                    <i class="fas fa-file-alt"></i>
                </div>

                <h3>Manage Proposal</h3>

                <p>
                    Manage PETAKOM activity proposals and documentation.
                </p>

            </a>

            <!-- ACTIVITY -->

            <a href="{{ url('Manage Activity') }}"
               class="home-card">

                <div class="home-icon">
                    <i class="fas fa-tasks"></i>
                </div>

                <h3>Manage Activity</h3>

                <p>
                    Organise and manage PETAKOM activities.
                </p>

            </a>

            <!-- COMMITTEE ELECTION -->

            <a href="{{ url('Manage Committee Election/StudCandidateListPage') }}"
               class="home-card">

                <div class="home-icon">
                    <i class="fas fa-vote-yea"></i>
                </div>

                <h3>Manage Committee Election</h3>

                <p>
                    Manage candidate registration and committee elections.
                </p>

            </a>


            <!-- BULLETIN -->

            <a href="{{ url('Manage Committee Election/test') }}"
               class="home-card">

                <div class="home-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>

                <h3>Manage Bulletin</h3>

                <p>
                    Access PETAKOM announcements and latest information.
                </p>

            </a>

        </div>

    </main>


    <!-- =========================
         FOOTER
    ========================= -->

    <footer class="home-footer">

        © 2026 PETAKOM Management System, Malaysia.
        All Rights Reserved.

    </footer>

</body>

</html>
