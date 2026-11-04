<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Models\Registration\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                      ->orWhere('student_number', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('manage-registration.students.index', compact('students'));
    }

    public function create()
    {
        return view('manage-registration.students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_number' => 'required|string|max:50|unique:students,student_number',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:students,email|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'programme' => 'required|string|max:255',
            'year_of_study' => 'nullable|integer|min:1|max:10',
            'status' => 'required|in:pending,active,inactive',
        ]);

        // Create User and Student together
        $student = DB::transaction(function () use ($validated) {

            // 1. Create User account
            $user = new User();

            $user->name = $validated['full_name'];
            $user->email = $validated['email'];
            $user->password = Hash::make(Str::random(40));
            $user->role = 'student';
            $user->status = $validated['status'] === 'inactive'
            ? 'inactive'
            : 'active';

            $user->save();

            // 2. Create Student record
            $studentData = $validated;
            $studentData['user_id'] = $user->id;

            return Student::create($studentData);
        });

        // 3. Send password setup link
        $status = Password::sendResetLink([
            'email' => $student->email,
        ]);

        return redirect()
            ->route('manage-registration.students.index')
            ->with(
                $status === Password::RESET_LINK_SENT ? 'success' : 'warning',
                $status === Password::RESET_LINK_SENT
                    ? 'Student registered successfully. Password setup link sent.'
                    : 'Student registered, but password setup email was not sent: ' . __($status)
            );
    }

    public function show(Student $student)
    {
        return view('manage-registration.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        return view('manage-registration.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'student_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('students', 'student_number')
                    ->ignore($student->id),
            ],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('students', 'email')
                    ->ignore($student->id),
                Rule::unique('users', 'email')
                    ->ignore($student->user_id),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'programme' => ['required', 'string', 'max:255'],
            'year_of_study' => ['nullable', 'integer', 'min:1', 'max:10'],
            'status' => ['required', 'in:pending,active,inactive'],
        ]);

        DB::transaction(function () use ($student, $validated) {

            // Update Student information
            $student->update($validated);

            // Update associated User account
            $user = $student->user;

            if ($user) {
                $user->update([
                    'name' => $validated['full_name'],
                    'email' => $validated['email'],
                    'status' => $validated['status'] === 'inactive'
                        ? 'inactive'
                        : 'active',
                ]);
            }
        });

        return redirect()
            ->route('manage-registration.students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        // Prevent deletion if related records exist.
        if (
            $student->committee()->exists() ||
            $student->candidates()->exists()
        ) {
            return back()->with(
                'error',
                'Cannot delete student because committee or election records exist.'
            );
        }

        DB::transaction(function () use ($student) {

            // Keep linked User account
            $user = $student->user;

            // Delete Student registration
            $student->delete();

            // Disable User login
            if ($user) {
                $user->status = 'inactive';
                $user->save();
            }
        });

        return redirect()
            ->route('manage-registration.students.index')
            ->with('success', 'Student deleted and user account deactivated successfully.');
    }

}