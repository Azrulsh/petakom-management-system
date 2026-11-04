# PETAKOM Management System

A web-based management system developed using Laravel to streamline administrative operations, student engagement, and committee management for PETAKOM (Persatuan Teknologi Komputer), Universiti Malaysia Pahang Al-Sultan Abdullah (UMPSA).

The system provides a centralized platform for managing activities, registrations, committee elections, appointments, and announcements with role-based access control.

## Features

### 1. Dashboard
- Provides a centralized interface for users to access system modules.
- Displays relevant information based on user roles.
- Offers navigation to administrative and student functionalities.

### 2. Activity Management
- Create, view, update, and delete activities through an interactive calendar.
- Integrated FullCalendar for calendar visualization.
- Supports all-day and date-based activities.
- Allows authorized users to manage organizational activities.
- Enables students to create and manage personal reminders.
- Restricts access to personal reminders based on ownership.

### 3. Committee Election Management
- Allows administrators and coordinators to create and manage committee election sessions.
- Configures election details, academic sessions, and nomination periods.
- Supports election statuses such as Draft, Open, and Closed.
- Enables students to register as candidates during active nomination periods.
- Allows authorized users to review, approve, or decline candidate applications.
- Prevents duplicate candidate registrations for the same election.
- Supports committee appointments from approved candidates after an election closes.

**Note:** The election module focuses on candidate nomination, approval, and appointment management. Online student voting is not implemented.

### 4. Registration Management
- Provides dedicated registration modules for students, lecturers, and committee members.
- Organizes registration records through role-specific interfaces.
- Supports committee registration through the approved candidate and appointment process.
- Restricts administrative registration management to authorized users.

### 5. Bulletin Management
- Provides a centralized module for PETAKOM announcements and information.
- Supports bulletin content and file attachments.
- Stores uploaded files using Laravel's filesystem.

### 6. Role-Based Access Control

The system provides three primary user roles:

| Role | Access |
|---|---|
| Admin | Full access to system management and administrative operations. |
| Coordinator | Delegated access to authorized management functions. |
| Student | Access to student functionalities, candidate registration, activities, and personal reminders. |

Role-based authorization ensures users can only access functions and records permitted by their assigned roles.

## Technology Stack

| Category | Technology |
|---|---|
| Backend | PHP, Laravel |
| Frontend | HTML, CSS, JavaScript, Blade |
| Database | MySQL |
| Calendar | FullCalendar.js |
| Dependency Management | Composer, npm |
| Version Control | Git |
| Development Environment | Laravel Development Server |

## Installation Guide

### Prerequisites

Ensure the following software is installed:

- PHP (compatible with the project's Laravel version)
- Composer
- Node.js and npm
- MySQL
- Git

### 1. Clone the Repository

```bash
git clone <repository-url>
cd <project-directory>
```

### 2. Install Dependencies

Install PHP dependencies:

```bash
composer install
```

Install JavaScript dependencies:

```bash
npm install
```

### 3. Configure Environment

Create an environment configuration file:

```bash
cp .env.example .env
```

Update the database settings inside `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=petakom_management
DB_USERNAME=root
DB_PASSWORD=
```

Create the corresponding MySQL database before running migrations.

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Database Migrations

```bash
php artisan migrate
```

If the project includes database seeders, run:

```bash
php artisan db:seed
```

### 6. Create Storage Symbolic Link

```bash
php artisan storage:link
```

This enables public access to uploaded files stored within Laravel's public storage disk.

### 7. Start the Application

Start the Laravel development server:

```bash
php artisan serve
```

In another terminal, start the frontend development server:

```bash
npm run dev
```

Access the application at:

```text
http://127.0.0.1:8000
```

## Committee Election Workflow

The committee election process follows these stages:

**1. Election Creation**

An Admin or Coordinator creates an election session, including its title, academic session, nomination period, and relevant details.

**2. Election Opening**

The election is opened to allow eligible students to register as candidates during the nomination period.

**3. Candidate Registration**

Students submit their candidate registrations. Duplicate registrations for the same election are prevented.

**4. Candidate Review**

An Admin or Coordinator reviews submitted applications and approves or declines each candidate.

**5. Election Closure**

The election is closed after the nomination and review process is completed.

**6. Committee Appointment**

Authorized users appoint approved candidates as official committee members. Appointment details are recorded separately from candidate application status.

## Security and Access Control

The system incorporates authorization and validation mechanisms, including:

- Role-based access restrictions for administrative functions.
- Ownership validation for personal student reminders.
- Nomination period validation for candidate registration.
- Prevention of duplicate candidate applications.
- Candidate approval before committee appointment.
- Server-side request validation for supported operations.

## Project Structure

The application follows Laravel's Model-View-Controller (MVC) architecture.

```text
app/
├── Http/
│   └── Controllers/
├── Models/

database/
├── migrations/
└── seeders/

resources/
├── css/
├── js/
└── views/
    ├── activity/
    ├── election/
    ├── registration/
    └── bulletin/

routes/
└── web.php

public/
storage/
```

*The directory structure above illustrates the main Laravel components and feature modules. Actual folder names may vary.*

## Future Enhancements

Potential enhancements include:

- Email notifications for candidate application decisions.
- Improved dashboard analytics and reporting.
- Additional activity participation tracking.
- Export functionality for administrative records.
- Enhanced bulletin categorization and search.

## Project Status

**Completed – Initial Release**

Core modules have been developed and functionally tested, including activity management, committee election and appointment management, registration, bulletin functionality, and role-based access control.

Further improvements may be introduced in future releases.

## Developer

**Muhammad Azrul Shafiq Bin Nadzri**

Bachelor of Computer Science (Software Engineering) with Honours

Universiti Malaysia Pahang Al-Sultan Abdullah (UMPSA)

## License

This project was developed for educational and portfolio purposes. No open-source license has been specified.
