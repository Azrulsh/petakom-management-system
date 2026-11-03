<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Registration\Student;
use App\Models\Registration\Committee;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    /**
     * Display all committee members.
     */
    public function index(Request $request)
    {
        $members = Committee::with(['student.user'])
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

        return view(
            'manage-election.committees.index',
            compact('members')
        );
    }

    /**
     * Show committee registration form.
     */
    public function create()
    {
        // Only students with existing user accounts.
        $students = Student::whereNotNull('user_id')
            ->orderBy('full_name')
            ->get();

        return view(
            'manage-election.committees.create',
            compact('students')
        );
    }

    /**
     * Register an existing student as committee.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'position' => 'required|string|max:100',
            'academic_session' => 'required|string|max:30',
            'appointed_at' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        // Ensure student has an existing User account.
        $studentExists = Student::whereKey($validated['student_id'])
            ->whereNotNull('user_id')
            ->exists();

        if (!$studentExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_id' =>
                    'This student does not have an associated user account.'
                ]);
        }

        // Prevent duplicate registration in the same session.
        $exists = Committee::where(
            'student_id',
            $validated['student_id']
        )
        ->where(
            'academic_session',
            $validated['academic_session']
        )
        ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_id' =>
                    'This student is already registered for this academic session.'
                ]);
        }

        // Create Committee record only.
        // No new User account is created.
        Committee::create($validated);

        return redirect()
            ->route('manage-election.committees.index')
            ->with(
                'success',
                'Committee member registered successfully.'
            );
    }

    /**
     * Display committee details.
     */
    public function show(Committee $committee)
    {
        $committee->load('student.user');

        return view(
            'manage-election.committees.show',
            compact('committee')
        );
    }

    /**
     * Show committee edit form.
     */
    public function edit(Committee $committee)
    {
        $students = Student::whereNotNull('user_id')
            ->orderBy('full_name')
            ->get();

        $committee->load('student.user');

        return view(
            'manage-election.committees.edit',
            compact('committee', 'students')
        );
    }

    /**
     * Update committee information.
     */
    public function update(Request $request, Committee $committee)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'position' => 'required|string|max:100',
            'academic_session' => 'required|string|max:30',
            'appointed_at' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        // Ensure selected student has a User account.
        $studentExists = Student::whereKey($validated['student_id'])
            ->whereNotNull('user_id')
            ->exists();

        if (!$studentExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_id' =>
                    'This student does not have an associated user account.'
                ]);
        }

        // Prevent duplicate committee registration.
        $exists = Committee::where(
            'student_id',
            $validated['student_id']
        )
        ->where(
            'academic_session',
            $validated['academic_session']
        )
        ->where('id', '!=', $committee->id)
        ->exists();

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
            ->route('manage-election.committees.index')
            ->with(
                'success',
                'Committee member updated successfully.'
            );
    }

    /**
     * Delete committee membership only.
     */
    public function destroy(Committee $committee)
    {
        // Do not delete Student or User account.
        $committee->delete();

        return redirect()
            ->route('manage-election.committees.index')
            ->with(
                'success',
                'Committee membership deleted successfully.'
            );
    }
}
