<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderBy("created_at", "desc")
            ->paginate("7");
        return view('announcements.index', [
            'announcements' => $announcements
        ]);
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        $announcement = new Announcement();
        $announcement->title = $request->title;
        $announcement->introduction = $request->introduction;
        $announcement->message = $request->message;
        $announcement->save();

        return redirect()->route('announcement.index');
    }

    public function show(string $id)
    {
        return view('announcements.show', [
            'announcement' => Announcement::find($id)
        ]);
    }

    public function edit(string $id)
    {
        return view('announcements.edit', [
            'announcement' => Announcement::find($id)
        ]);
    }

    public function update(Request $request, string $id)
    {
        $announcement = Announcement::find($id);
        $announcement->title = $request->title;
        $announcement->introduction = $request->introduction;
        $announcement->message = $request->message;
        $announcement->save();

        return redirect()->route('announcement.show', [
            'announcement' => $id
        ]);
    }

    public function destroy(string $id)
    {
        //
    }
}
