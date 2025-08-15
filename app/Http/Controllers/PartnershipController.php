<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partnership;

class PartnershipController extends Controller
{
    public function index()
    {
        $partnerships = Partnership::orderBy("updated_at", "desc")->paginate("7");
        return view('partnerships.index', ["partnerships" => $partnerships]);
    }

    public function create()
    {
        return view('partnerships.create');
    }

    public function store(Request $request)
    {
        $partnership = new Partnership();
        $partnership->organization_name = $request->org_name;
        $partnership->purpose = $request->purpose;
        $partnership->benefits = $request->benefits;
        $partnership->action = $request->action;
        $partnership->links = $request->links;
        $partnership->accomplished_by = $request->accomplished_by;
        $partnership->officer = $request->officer;
        $partnership->user()->associate(auth()->user());
        $partnership->save();

        return redirect()->route('partnerships.index');
    }

    public function show(string $id)
    {
        return view('partnerships.show', ['partnership' => Partnership::find($id)]);
    }

    public function edit(string $id)
    {
        return view('partnerships.edit', ['partnership' => Partnership::find($id)]);
    }

    public function update(Request $request, string $id)
    {
        $partnership = Partnership::find($id);
        $partnership->organization_name = $request->org_name;
        $partnership->purpose = $request->purpose;
        $partnership->benefits = $request->benefits;
        $partnership->action = $request->action;
        $partnership->links = $request->links;
        $partnership->accomplished_by = $request->accomplished_by;
        $partnership->officer = $request->officer;
        $partnership->user()->associate(auth()->user());
        $partnership->save();
        
        return redirect()->route('partnerships.show', ['partnership' => $partnership->id]);
    }

    public function destroy(string $id)
    {
        //
    }
}
