<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\SignupInvitation;
use App\Http\Requests\SigninRequest;
use App\Models\Role;
use App\Models\GoogleAccount;

class LoginController extends Controller
{
    public function login(Request $request) {
        $inviteCode = $request->invite_code;
        return view('users.login', [
            'type' => 'user',
            'inviteCode' => $inviteCode,
            'homeRoute' => route('user.home'),
            'googleSigninRoute' => route('auth.redirect', [
                'provider' => 'google',
                'invite_code' => $inviteCode
            ]),
            'signinRoute' => route('user.auth'),
            'passwordResetRoute' => route('profile.password-reset.create'),
        ]);
    }

    public function auth(SigninRequest $request) {
        if (auth()->check()) {
            return redirect()->intended('home.html');
        }
        if (filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
            $credentials = [
                'email' => $request->username,
                'password' => $request->password
            ];
        } else {
            $credentials = [
                'username' => $request->username,
                'password' => $request->password
            ];
        }
        if (Auth::attempt($credentials, $remember = true)) {
            $request->session()->regenerate();
            return redirect()->intended('home.html');
        }
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function adminAuth(SigninRequest $request) {
        $adminId = Role::whereRaw('lower(name) = ?', ['admin'])->first();
        if (filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
            $credentials = [
                'email' => $request->username,
                'password' => $request->password,
                'role_id' => $adminId
            ];
        } else {
            $credentials = [
                'username' => $request->username,
                'password' => $request->password,
                'role_id' => $adminId
            ];
        }
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('home.html');
        }
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function adminLogin(Request $request)
    {
        return view('users.login', [
            'type' => 'admin',
            'homeRoute' => route('admin.home'),
            'googleSigninRoute' => route('auth.redirect', [
                'provider' => 'google'
            ]),
            'signinRoute' => route('admin.auth'),
        ]);
    }

    public function redirectSignup(Request $request, string $provider)
    {
        $inviteCode = $request->invite_code;
        $request->session()->flash('inviteCode', $inviteCode);
        switch ($provider) {
        case 'google':
            config(['services.google.redirect' => route('signup.callback', [
                'provider' => 'google'
            ])]);
            return Socialite::driver('google')->with([
                'access_type' => 'offline',
                'prompt' => 'consent'
            ])->redirect();
        }
    }

    public function redirectSignin(Request $request, string $provider)
    {
        /*
        if (auth()->check()) {
            return redirect()->intended('home.html');
        }
        $inviteCode = $request->invite_code;
        $request->session()->flash('inviteCode', $inviteCode);
        */
        switch ($provider) {
        case 'google':
            return Socialite::driver('google')->with([
                'access_type' => 'offline',
            ])->redirect();
        }
    }

    public function signupWith(Request $request, string $provider)
    {
        $inviteCode = session('inviteCode') ?? $request->invite_code;
        $signupInvite = $inviteCode ? SignupInvitation::firstWhere(
            'invite_code', $inviteCode) : null;
        config(['services.google.redirect' => route('signup.callback', [
            'provider' => 'google'
        ])]);
        $socialUser = Socialite::driver($provider)->user();
        $user = $socialUser ? self::findSocialUser($provider,
            $socialUser->id) : null;
        if ($user) {
            return redirect()->route('user.invitation', [
                'invite_code' => $signupInvite->invite_code
            ])->withErrors([
                'signin' => "The {$provider} account is already taken."
            ]);
        } else {
            $user = self::storeSocialUser($provider, $socialUser,
                $signupInvite);
        }
        Auth::login($user, $remember = true);
        $request->session()->regenerate();
        return redirect()->intended('home.html');
    }

    public function signinWith(Request $request, string $provider)
    {
	/*
        $inviteCode = session('inviteCode') ?? $request->invite_code;
        $signupInvite = $inviteCode ? SignupInvitation::firstWhere(
            'invite_code', $inviteCode) : null;
	*/
        $socialUser = Socialite::driver($provider)->user();
        $user = $socialUser ? self::findSocialUser($provider,
            $socialUser->id) : null;
        if (!$user) {
            return redirect()->route('user.login')->withErrors([
                'signin' => 'The provided credentials do not match our records.'
            ]);
        }
	/*
        if (!$user) {
            $user = self::storeSocialUser($provider, $socialUser,
                $signupInvite);
        }
	*/
        Auth::login($user, $remember = true);
        $request->session()->regenerate();
        return redirect()->intended('home.html');
    }

    public function adminAuthWith(Request $request, string $provider)
    {
        $socialUser = Socialite::driver($provider)->user();
        $user = $socialUser ? self::findSocialUser($provider,
            $socialUser->id) : null;
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('user.login')->withErrors([
                'signin' => 'The provided credentials do not match our records.'
            ]);
        }
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->intended('home.html');
    }

    public function showSignupInvitation(Request $request) {
        $inviteCode = $request->invite_code;
        if (!$inviteCode) abort(404);
        return view('users.show-signup-invitation', [
            'inviteCode' => $inviteCode,
            'emailRoute' => route('users.create'),
            'googleRoute' => route('signup.redirect', ['provider' => 'google'])
        ]);
    }

    public function logout(Request $request) {
		Auth::logout();
		$request->session()->invalidate();
		$request->session()->regenerateToken();
		return redirect('/');
    }

    private static function findSocialUser(string $provider, string $id)
    {
        switch ($provider) {
        case 'google':
            $user = GoogleAccount::firstWhere('google_id', $id)?->user;
            break;
        }
        return $user;
    }

    private static function storeSocialUser(string $provider, $socialUser,
            ?SignupInvitation $signupInvite = null): User
    {
        switch ($provider) {
        case 'google':
            $user = new User;
            $user->first_name = $socialUser->getRaw()['given_name'];
            $user->last_name = $socialUser->getRaw()['family_name'];
            if ($signupInvite?->position) {
                $user->position()->associate($signupInvite->position);
            }
            $user->save();
            $google = new GoogleAccount;
            $google->user()->associate($user);
            $google->google_id = $socialUser->id;
            $google->token = $socialUser->token;
            $google->refresh_token = $socialUser->refreshToken;
            $google->expires_at = now()->second($socialUser->expiresIn)
                ->toDateTimeString();
            $google->save();
            break;
        }
        if ($signupInvite) {
            /*
            $signupInvite->is_accepted = true;
            $signupInvite->save();
            */
            $signupInvite->delete();
        }
        return $user;
    }
}
