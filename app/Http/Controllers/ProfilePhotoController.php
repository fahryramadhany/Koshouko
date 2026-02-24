<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoController extends Controller
{
    /**
     * Display profile photo form (edit).
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        
        return view('profile-photo.edit', compact('user'));
    }

    /**
     * Store/Upload profile photo.
     */
    public function store(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        $validated = $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Delete old photo if exists
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Store new photo
        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        
        $user->update(['profile_photo' => $path]);

        // Check if request is AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diperbarui',
                'photo_url' => Storage::url($path)
            ]);
        }

        return redirect()->route('profile.edit')->with('success', 'Foto profil berhasil diperbarui');
    }

    /**
     * Display profile photo.
     */
    public function show(User $user)
    {
        if (!$user->profile_photo) {
            return redirect('/images/default-avatar.png');
        }

        return Storage::disk('public')->download($user->profile_photo);
    }

    /**
     * Delete profile photo.
     */
    public function destroy(User $user)
    {
        $this->authorize('update', $user);
        
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $user->update(['profile_photo' => null]);

        return redirect()->route('profile.edit')->with('success', 'Foto profil berhasil dihapus');
    }
}
