<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Models\Registration\Student;
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
            'student_number' => 'required|string|max:50|unique:students',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:students',
            'phone' => 'nullable|string|max:30',
            'programme' => 'required|string|max:255',
            'year_of_study' => 'nullable|integer|min:1|max:10',
            'status' => 'required|in:pending,active,inactive',
        ]);

        Student::create($validated);

        return redirect()
            ->route('manage-registration.students.index')
            ->with('success', 'Student registered successfully.');
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
                Rule::unique('students')->ignore($student->id),
            ],
            'full_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('students')->ignore($student->id),
            ],
            'phone' => 'nullable|string|max:30',
            'programme' => 'required|string|max:255',
            'year_of_study' => 'nullable|integer|min:1|max:10',
            'status' => 'required|in:pending,active,inactive',
        ]);

        $student->update($validated);

        return redirect()
            ->route('manage-registration.students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        if ($student->committee()->exists()) {
            return back()->with(
                'error',
                'Cannot delete student with committee membership records.'
            );
        }

        $student->delete();

        return redirect()
            ->route('manage-registration.students.index')
            ->with('success', 'Student deleted successfully.');
    }
}