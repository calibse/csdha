<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Position;
use App\Models\SignupInvitation;
use App\Http\Requests\StoreUserRequest;

class UserController extends Controller
{
    public function index()
    {
        //
    }

    public function create(Request $request)
    {
        $inviteCode = $request->invite_code;
        $inviteCodeValid = false;
        $signupInvite = SignupInvitation::firstWhere('invite_code', 
            $inviteCode);
        if (!$signupInvite) {
            return view('message', [
                'message' => 'Your sign-up invitation link is invalid.'
            ]);
        }

        if ($signupInvite->is_accepted) {
            return view('message', [
                'message' => 'Your sign-up invitation has already been accepted.'
            ]);
        }

        if (now()->greaterThan($signupInvite->expires_at)) {
            return view('message', [
                'message' => 'Your sign-up invitation link has expired.'
            ]);
        }
        return view('users.create', [
            'backRoute' => route('user.invitation', [
                'invite_code' => $inviteCode 
            ]),
            'email' => $signupInvite->email,
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $user = new User();
        $user->first_name = $request->input('first_name');
        $user->middle_name = $request->input('middle_name');
        $user->last_name = $request->input('last_name');
		$user->suffix_name = $request->input('suffix_name');
        $user->email = $request->input('email');
        $user->username = $request->input('username');
        $user->password = Hash::make($request->input('password'));
		$user->save();
        Auth::login($user);
        return redirect()->route('user.home');
    }

    public function showAvatar(string $id) {
        $user = User::find($id);
        
        return response->file(Storage::path($user->avatar_filepath));
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
