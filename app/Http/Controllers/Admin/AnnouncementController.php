<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('creator')
            ->orderByDesc('published_at')
            ->paginate(15);

        return view('admin.announcements.index', ['announcements' => $announcements]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['published_at'] = now();
        $validated['status'] = 'published';

        Announcement::create($validated);

        return back()->with('success', 'Pengumuman berhasil dipublikasikan.');
    }
}
