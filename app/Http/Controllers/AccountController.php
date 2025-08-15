<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\SignupInvitation;
use App\Models\Position;
use App\Mail\SignupInvitation as SignupInvitationMail;
use Illuminate\Support\Facades\Mail;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = User::orderBy("updated_at", "desc")->paginate("7");
        return view('accounts.index', ["accounts" => $accounts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function createSignupInvite()
    {
        return view('accounts.createSignupInvite', [
            'positions' => Position::all(),
            'invites' => SignupInvitation::all(),
        ]);
    }

    public function sendSignupInvite(Request $request)
    {
        $signupInvite = new SignupInvitation();
        $signupInvite->invite_code = Str::random(32);
        $signupInvite->email = $request->email;
        $signupInvite->position()->associate(Position::find($request->position));
        $signupInvite->is_accepted = false;
        $signupInvite->expires_at = now()->hour(24)->toDateTimeString();
        $signupInvite->save();

        $url = url('http://' . config('custom.user_domain') . route('user.invitation', [
            'invite-code' => $signupInvite->invite_code
        ], false));

        Mail::to($request->email)->send(new SignupInvitationMail($url));

        return back()->with('sent', 1);
    }

    public function revokeSignupInvite(SignupInvitation $invite)
    {
        $invite->delete();

        return back()->with('deleted', 1);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $account)
    {
        return view('accounts.show', [
            'account' => $account
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
