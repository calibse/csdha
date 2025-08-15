<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meeting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MeetingController extends Controller
{
    public function index()
    {
        $meetings = Meeting::orderBy("updated_at", "desc")->paginate("7");
        return view('meetings.index', ['meetings' => $meetings]);
    }

    public function create()
    {
        return view('meetings.create');
    }

    public function store(Request $request)
    {
        $meeting = new Meeting;
        $meeting->title = $request->title;
        $meeting->date = $request->date;
        $meeting->venue = $request->venue;
        $meeting->agenda = $request->agenda;
        $meeting->participants = $request->participants;
        $meeting->minutes_file = $request->minutes
            ?->storeAs('meetings/minutes_files',
                       'minutes_file_' . Str::random(16) . '.pdf');
        $meeting->user()->associate(auth()->user());
        $meeting->save();

        return redirect()->route('meetings.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('meetings.show', ['meeting' => Meeting::find($id)]);
    }

    public function edit(string $id)
    {
        return view('meetings.edit', ['meeting' => Meeting::find($id)]);
    }

    public function update(Request $request, string $id)
    {
        $meeting = Meeting::find($id);
        $meeting->title = $request->title;
        $meeting->date = $request->date;
        $meeting->venue = $request->venue;
        $meeting->agenda = $request->agenda;
        $meeting->participants = $request->participants;
        $meeting->user()->associate(auth()->user());
        if ($request->minutes) {
            $filename = $meeting->minutes_file ?
                basename($meeting->minutes_file) :
                'minutes_file_' . Str::random(16) . '.pdf';
            $meeting->minutes_file = $request->minutes
                ?->storeAs('meetings/minutes_files', $filename);
        }
        $meeting->save();

        return redirect()->route('meetings.index');
    }

    public function showMinutesFile(string $id, string $fileName)
    {
        $meeting = Meeting::find($id);
        $file = $meeting->minutes_file;

        return response()->file(Storage::path($file));
    }

    public function showMinutes(string $id)
    {
        $meeting = Meeting::find($id);

        return view('meetings.show_minutes', ['meeting' => $meeting]);
    }

    public function destroy(string $id)
    {
        //
    }
}
