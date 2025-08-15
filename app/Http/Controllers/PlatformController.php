<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Platform;

class PlatformController extends Controller
{
    public function index()
    {
        $platforms = Platform::orderBy("updated_at", "desc")->paginate("7");
        return view('platforms.index', ["platforms" => $platforms]);
    }

    public function create()
    {
        return view('platforms.create');
    }

    public function store(Request $request)
    {
        $platform = new Platform();
        $platform->name = $request->name;
        $platform->description = $request->description;
        $platform->start_date = $request->start_date;
        $platform->end_date = $request->end_date;
        $platform->progress = $request->progress;
        $platform->user()->associate(auth()->user());
        $platform->save();

        return redirect()->route('platforms.index');
    }

    public function show(string $id)
    {
        return view('platforms.show', ['platform' => Platform::find($id)]);
    }

    public function edit(string $id)
    {
        return view('platforms.edit', ['platform' => Platform::find($id)]);
    }

    public function update(Request $request, string $id)
    {

        $platform = Platform::find($id);
        $platform->name = $request->name;
        $platform->description = $request->description;
        $platform->start_date = $request->start_date;
        $platform->end_date = $request->end_date;
        $platform->progress = $request->progress;
        $platform->save();

        return redirect()->route('platforms.show', ['platform' => $platform->id]);
    }

    public function destroy(string $id)
    {
        //
    }
}
