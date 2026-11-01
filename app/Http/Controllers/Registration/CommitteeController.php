<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Models\Registration\Student;
use App\Models\Registration\Committee;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    public function index(Request $request)
    {
        $members = Committee::with('student')
            ->when($request->search, function ($query, $search) {
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                      ->orWhere('student_number', 'like', "%{$search}%");
                });
            })
            ->when($request->session, function ($query, $session) {
                $query->where('academic_session', $session);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('manage-registration.committees.index', compact('members'));
    }

    public function create()
    {
        $students = Student::orderBy('full_name')->get();

        return view(
            'manage-registration.committees.create',
            compact('students')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'position' => 'required|string|max:100',
            'academic_session' => 'required|string|max:30',
            'appointed_at' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        $exists = Committee::where(
            'student_id',
            $validated['student_id']
        )->where(
            'academic_session',
            $validated['academic_session']
        )->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_id' =>
                    'This student is already registered for this academic session.'
                ]);
        }

        Committee::create($validated);

        return redirect()
            ->route('manage-registration.committees.index')
            ->with('success', 'Committee member registered successfully.');
    }

    public function show(Committee $committee)
    {
        $committee->load('student');

        return view(
            'manage-registration.committees.show',
            compact('committee')
        );
    }

    public function edit(Committee $committee)
    {
        $students = Student::orderBy('full_name')->get();

        return view(
            'manage-registration.committees.edit',
            compact('committee', 'students')
        );
    }

    public function update(Request $request, Committee $committee)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'position' => 'required|string|max:100',
            'academic_session' => 'required|string|max:30',
            'appointed_at' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        $exists = Committee::where(
            'student_id',
            $validated['student_id']
        )->where(
            'academic_session',
            $validated['academic_session']
        )->where(
            'id',
            '!=',
            $committee->id
        )->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_id' =>
                    'This student already has a committee registration for this session.'
                ]);
        }

        $committee->update($validated);

        return redirect()
            ->route('manage-registration.committees.index')
            ->with('success', 'Committee member updated successfully.');
    }

    public function destroy(Committee $committee)
    {
        $committee->delete();

        return redirect()
            ->route('manage-registration.committees.index')
            ->with('success', 'Committee member deleted successfully.');
    }
}