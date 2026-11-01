<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Models\Registration\Lecturer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class LecturerController extends Controller
{
    /**
     * Display all lecturers.
     */
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

        return view(
            'manage-registration.lecturers.index',
            compact('lecturers')
        );
    }

    /**
     * Show registration form.
     */
    public function create()
    {
        return view('manage-registration.lecturers.create');
    }

    /**
     * Register lecturer and create user account.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_number' => [
                'required',
                'string',
                'max:50',
                'unique:lecturers,staff_number',
            ],

            'full_name' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:lecturers,email',
                'unique:users,email',
            ],

            'phone' => 'nullable|string|max:30',

            'department' => 'required|string|max:255',

            'status' => 'required|in:active,inactive',
        ]);

        // Create User and Lecturer together.
        $lecturer = DB::transaction(function () use ($validated) {

            // 1. Create User account.
            $user = new User();

            $user->name = $validated['full_name'];
            $user->email = $validated['email'];
            $user->password = Hash::make(Str::random(40));
            $user->role = 'lecturer';

            $user->save();

            // 2. Create Lecturer record.
            $lecturerData = $validated;
            $lecturerData['user_id'] = $user->id;

            return Lecturer::create($lecturerData);
        });

        // 3. Send password setup link.
        try {

            $status = Password::sendResetLink([
                'email' => $lecturer->email,
            ]);

        } catch (\Throwable $exception) {

            report($exception);

            return redirect()
                ->route('manage-registration.lecturers.index')
                ->with(
                    'warning',
                    'Lecturer registered successfully, but password setup email could not be sent.'
                );
        }

        if ($status === Password::RESET_LINK_SENT) {

            return redirect()
                ->route('manage-registration.lecturers.index')
                ->with(
                    'success',
                    'Lecturer registered successfully. Password setup link has been sent.'
                );
        }

        return redirect()
            ->route('manage-registration.lecturers.index')
            ->with(
                'warning',
                'Lecturer registered, but password setup email was not sent: ' . __($status)
            );
    }

    /**
     * Display lecturer details.
     */
    public function show(Lecturer $lecturer)
    {
        return view(
            'manage-registration.lecturers.show',
            compact('lecturer')
        );
    }

    /**
     * Show edit form.
     */
    public function edit(Lecturer $lecturer)
    {
        return view(
            'manage-registration.lecturers.edit',
            compact('lecturer')
        );
    }

    /**
     * Update lecturer and linked user.
     */
    public function update(Request $request, Lecturer $lecturer)
    {
        $emailRules = [
            'required',
            'email',
            'max:255',
            Rule::unique('lecturers', 'email')
                ->ignore($lecturer->id),
        ];

        if ($lecturer->user_id) {

            $emailRules[] = Rule::unique('users', 'email')
                ->ignore($lecturer->user_id);

        } else {

            $emailRules[] = Rule::unique('users', 'email');
        }

        $validated = $request->validate([
            'staff_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('lecturers', 'staff_number')
                    ->ignore($lecturer->id),
            ],

            'full_name' => 'required|string|max:255',

            'email' => $emailRules,

            'phone' => 'nullable|string|max:30',

            'department' => 'required|string|max:255',

            'status' => 'required|in:active,inactive',
        ]);

        DB::transaction(function () use ($lecturer, $validated) {

            // Update Lecturer.
            $lecturer->update($validated);

            // Update linked User.
            $user = $lecturer->user;

            if ($user) {
                $user->name = $validated['full_name'];
                $user->email = $validated['email'];

                $user->save();
            }
        });

        return redirect()
            ->route('manage-registration.lecturers.index')
            ->with(
                'success',
                'Lecturer updated successfully.'
            );
    }

    /**
     * Delete lecturer and associated user.
     */
    public function destroy(Lecturer $lecturer)
    {
        DB::transaction(function () use ($lecturer) {

            $user = $lecturer->user;

            if ($user) {

                // Foreign key cascade deletes Lecturer.
                $user->delete();

            } else {

                $lecturer->delete();
            }
        });

        return redirect()
            ->route('manage-registration.lecturers.index')
            ->with(
                'success',
                'Lecturer and associated user account deleted successfully.'
            );
    }
}
