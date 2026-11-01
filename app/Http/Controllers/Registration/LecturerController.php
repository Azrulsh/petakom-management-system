<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Models\Registration\Lecturer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LecturerController extends Controller
{
    public function index(Request $request)
    {
        $lecturers = Lecturer::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                      ->orWhere('staff_number', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('manage-registration.lecturers.index', compact('lecturers'));
    }

    public function create()
    {
        return view('manage-registration.lecturers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_number' => 'required|string|max:50|unique:lecturers',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:lecturers',
            'phone' => 'nullable|string|max:30',
            'department' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        Lecturer::create($validated);

        return redirect()
            ->route('manage-registration.lecturers.index')
            ->with('success', 'Lecturer registered successfully.');
    }

    public function show(Lecturer $lecturer)
    {
        return view('manage-registration.lecturers.show', compact('lecturer'));
    }

    public function edit(Lecturer $lecturer)
    {
        return view('manage-registration.lecturers.edit', compact('lecturer'));
    }

    public function update(Request $request, Lecturer $lecturer)
    {
        $validated = $request->validate([
            'staff_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('lecturers')->ignore($lecturer->id),
            ],
            'full_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('lecturers')->ignore($lecturer->id),
            ],
            'phone' => 'nullable|string|max:30',
            'department' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $lecturer->update($validated);

        return redirect()
            ->route('manage-registration.lecturers.index')
            ->with('success', 'Lecturer updated successfully.');
    }

    public function destroy(Lecturer $lecturer)
    {
        $lecturer->delete();

        return redirect()
            ->route('manage-registration.lecturers.index')
            ->with('success', 'Lecturer deleted successfully.');
    }
}