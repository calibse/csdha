<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fund;
use App\Models\Event;

class FundController extends Controller
{
    public function index()
    {
        $funds = Fund::orderBy("updated_at", "desc")->paginate("7");
        return view('funds.index', [
            'funds' => $funds,
            'events' => Event::all()
]);
    }

    public function create()
    {
        return view('funds.create', ['events' => Event::all()]);
    }

    public function store(Request $request)
    {
        $fund = new Fund();
        $fund->event()->associate(Event::find($request->event_id));
        $fund->collected = $request->collected;
        $fund->spent = $request->spent;
        $fund->remaining = $fund->collected - $fund->spent;
        $fund->save();

        return redirect()->route('funds.index');
    }

    public function show(string $id)
    {
        return view('funds.show', ['fund' => Fund::find($id)]);
    }

    public function edit(string $id)
    {
        return view('funds.edit', ['fund' => Fund::find($id)]);
    }

    public function update(Request $request, string $id)
    {
        $fund = Fund::find($id);
        $fund->collected = $request->collected;
        $fund->spent = $request->spent;
        $fund->remaining = $fund->collected - $fund->spent;
        $fund->save();

        return redirect()->route('funds.show', ['fund' => $fund->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
