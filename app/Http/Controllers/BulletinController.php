<?php

namespace App\Http\Controllers;

use App\Models\Bulletin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BulletinController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Admin and Coordinator can view all bulletins
        $canManageAll = $user->isAdmin() || $user->isCoordinator();

        // Active Committee can view their own drafts
        $query = Bulletin::with('user');

        if (!$canManageAll) {
            $query->where(function ($q) use ($user) {
                $q->where('status', 'published');

                if ($user->isCommittee()) {
                    $q->orWhere(function ($ownQuery) use ($user) {
                        $ownQuery->where('user_id', $user->id)
                            ->where('type', 'petakom');
                    });
                }
            });
        }

        // Filter bulletin category
        if (in_array($request->query('type'), ['petakom', 'faculty'], true)) {
            $query->where('type', $request->query('type'));
        }

        // Search bulletin title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->query('search') . '%');
        }

        // Show latest bulletins first
        $bulletins = $query
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('bulletin.index', compact(
            'bulletins',
            'canManageAll'
        ));
    }

    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Only Admin, Coordinator and Active Committee
        $canCreate = $user->isAdmin()
            || $user->isCoordinator()
            || $user->isCommittee();

        abort_unless(
            $canCreate,
            403,
            'You are not authorized to create bulletins.'
        );

        return view('bulletin.create');
    }
    
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Only Admin, Coordinator and Active Committee
        $canCreate = $user->isAdmin()
            || $user->isCoordinator()
            || $user->isCommittee();

        abort_unless(
            $canCreate,
            403,
            'You are not authorized to create bulletins.'
        );

        // Committee can only create PETAKOM bulletins
        $allowedTypes = ($user->isAdmin() || $user->isCoordinator())
            ? ['petakom', 'faculty']
            : ['petakom'];

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'type' => ['required', Rule::in($allowedTypes)],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
        ]);

        // Store attachment in public storage
        $attachmentPath = null;

        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')
                ->store('bulletins', 'public');
        }

        // Create bulletin
        Bulletin::create([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'type' => $validated['type'],
            'attachment' => $attachmentPath,
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published'
                ? now()
                : null,
        ]);

        return redirect()
            ->route('bulletin.index')
            ->with('success', 'Bulletin created successfully.');
    }

    public function show(Bulletin $bulletin)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $canManageAll = $user->isAdmin()
            || $user->isCoordinator();

        $canViewOwn = $user->isCommittee()
            && $bulletin->user_id === $user->id
            && $bulletin->type === 'petakom';

        // Published bulletins can be viewed by everyone.
        // Drafts can only be viewed by authorized users.
        abort_unless(
            $bulletin->status === 'published'
            || $canManageAll
            || $canViewOwn,
            403,
            'You are not authorized to view this bulletin.'
        );

        $bulletin->load('user');

        return view('bulletin.show', compact('bulletin'));
    }
    
    public function edit(Bulletin $bulletin)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Admin can edit all bulletins
        $canEdit = $user->isAdmin()
            || (
                $user->isCoordinator()
                && $bulletin->user_id === $user->id
            )
            || (
                $user->isCommittee()
                && $bulletin->user_id === $user->id
                && $bulletin->type === 'petakom'
            );

        abort_unless(
            $canEdit,
            403,
            'You are not authorized to edit this bulletin.'
        );

        return view('bulletin.edit', compact('bulletin'));
    }
  
    public function update(Request $request, Bulletin $bulletin)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check edit permission
        $canEdit = $user->isAdmin()
            || (
                $user->isCoordinator()
                && $bulletin->user_id === $user->id
            )
            || (
                $user->isCommittee()
                && $bulletin->user_id === $user->id
                && $bulletin->type === 'petakom'
            );

        abort_unless(
            $canEdit,
            403,
            'You are not authorized to update this bulletin.'
        );

        // Allowed bulletin types based on role
        $allowedTypes = ($user->isAdmin() || $user->isCoordinator())
            ? ['petakom', 'faculty']
            : ['petakom'];

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'type' => ['required', Rule::in($allowedTypes)],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
        ]);

        $oldAttachment = $bulletin->attachment;
        $newAttachment = null;

        $oldAttachmentExists = $oldAttachment&& Storage::disk('public')->exists($oldAttachment);

        // Upload replacement attachment
        if ($request->hasFile('attachment')) {
            $newAttachment = $request->file('attachment')
                ->store('bulletins', 'public');
        }

        try {
            // Update bulletin information
            $bulletin->update([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'type' => $validated['type'],
                'status' => $validated['status'],
                'attachment' => $newAttachment ?? $oldAttachment,
                'published_at' => $validated['status'] === 'published'
                    ? ($bulletin->published_at ?? now())
                    : null,
            ]);
        } catch (\Throwable $e) {
            // Remove newly uploaded file if database update fails
            if ($newAttachment) {
                Storage::disk('public')->delete($newAttachment);
            }

            throw $e;
        }

        // Remove old file only if it exists and has been replaced
        if ($newAttachment && $oldAttachmentExists) {
            Storage::disk('public')->delete($oldAttachment);
        }

        return redirect()
            ->route('bulletin.show', $bulletin->id)
            ->with('success', 'Bulletin updated successfully.');
    }

    public function destroy(Bulletin $bulletin)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check delete permission
        $canDelete = $user->isAdmin()
            || (
                $user->isCoordinator()
                && $bulletin->user_id === $user->id
            )
            || (
                $user->isCommittee()
                && $bulletin->user_id === $user->id
                && $bulletin->type === 'petakom'
            );

        abort_unless(
            $canDelete,
            403,
            'You are not authorized to delete this bulletin.'
        );

        // Save attachment path before deleting bulletin
        $attachment = $bulletin->attachment;

        // Delete bulletin from database first
        $bulletin->delete();

        // Delete attachment if it still exists
        if (
            $attachment &&
            Storage::disk('public')->exists($attachment)
        ) {
            Storage::disk('public')->delete($attachment);
        }

        return redirect()
            ->route('bulletin.index')
            ->with('success', 'Bulletin deleted successfully.');
    }

    public function updateStatus(Request $request, Bulletin $bulletin)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Admin can manage all bulletins.
        // Coordinator and Active Committee can manage their own.
        $canManage = $user->isAdmin()
            || (
                $user->isCoordinator()
                && $bulletin->user_id === $user->id
            )
            || (
                $user->isCommittee()
                && $bulletin->user_id === $user->id
                && $bulletin->type === 'petakom'
            );

        abort_unless(
            $canManage,
            403,
            'You are not authorized to change this bulletin status.'
        );

        $validated = $request->validate([
            'status' => ['required', 'in:draft,published'],
        ]);

        // Prevent unnecessary updates
        if ($bulletin->status === $validated['status']) {
            return back()->with('success', 'Bulletin status is already up to date.');
        }

        $bulletin->update([
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published'
                ? now()
                : null,
        ]);

        return back()->with(
            'success',
            $validated['status'] === 'published'
                ? 'Bulletin published successfully.'
                : 'Bulletin unpublished successfully.'
        );
    }

}
