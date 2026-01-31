<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::paginate(10);
        return view('pustakawan.announcements.index', [
            'announcements' => $announcements,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'creator_id' => Auth::id(),
            'status' => 'published',
            'published_at' => now(),
        ]);

        return redirect()->route('librarian.announcements')
            ->with('success', 'Pengumuman berhasil dibuat.');
    }
}
