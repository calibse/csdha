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
        $signupInvite = $inviteCode ? SignupInvitation::firstWhere(
            'invite_code', $inviteCode) : null;
        return view('users.create', [
            'backRoute' => route('user.invitation', [
                'invite_code' => $inviteCode
            ]),
            'email' => $signupInvite->email,
            'formAction' => route('users.store', [
                'invite_code' => $inviteCode
            ])
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $inviteCode = $request->invite_code;
        $signupInvite = $inviteCode ? SignupInvitation::firstWhere(
            'invite_code', $inviteCode) : null;
        $user = new User();
        $user->first_name = $request->input('first_name');
        $user->middle_name = $request->input('middle_name');
        $user->last_name = $request->input('last_name');
		$user->suffix_name = $request->input('suffix_name');
        $user->email = $request->input('email');
        $user->username = $request->input('username');
        $user->password = Hash::make($request->input('password'));
        if ($signupInvite?->position) {
            $user->position()->associate($signupInvite->position);
        }
		$user->save();
        $signupInvite->is_accepted = true;
        $signupInvite->save();
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
