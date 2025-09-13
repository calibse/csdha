<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\SignupInvitation;
use App\Http\Requests\SigninRequest;

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
        $user = User::firstWhere('username', $request->username);
        if (!$user || !$user->isAdmin()) {
            return view('message', [
                'message' => 'We only allow log-in to administrator account '
                    . 'here.'
            ]);
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

    public function signinWith(Request $request, string $provider)
    {
        if (auth()->check()) {
            return redirect()->intended('home.html');
        }
        $inviteCode = $request->invite_code;
        $request->session()->flash('inviteCode', $inviteCode);
        switch ($provider) {
        case 'google':
            return Socialite::driver('google')->with([
                'access_type' => 'offline',
            ])->redirect();
        case 'facebook':
            return Socialite::driver('facebook')->redirect();
        }
    }

    public function authWith(Request $request, string $provider)
    {
        $inviteCode = session('inviteCode') ?? $request->invite_code;
        $signupInvite = $inviteCode ? SignupInvitation::firstWhere(
            'invite_code', $inviteCode) : null;
        $user = self::getSocialUser($provider);
        if (!$user && !$signupInvite) {
            return view('message', [
                'message' => 'Sorry, we only allow sign-in for registered '
                    . 'users or from a sign-up invitation link right now.'
            ]);
        }
        if (!$user) {
            self::storeSocialUser($provider, $signupInvite);
        }
        Auth::login($user, $remember = true);
        $request->session()->regenerate();
        return redirect()->intended('home.html');
    }

    public function adminAuthWith(Request $request, string $provider)
    {
        $user = self::getSocialUser($provider);
        if (!$user || !$user->isAdmin()) {
            return view('message', [
                'message' => 'We only allow log-in to administrator '
                    . 'account here.'
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
            'googleRoute' => route('auth.redirect', ['provider' => 'google'])
        ]);
    }

    public function logout(Request $request) {
		Auth::logout();
		$request->session()->invalidate();
		$request->session()->regenerateToken();
		return redirect('/');
    }

    private static function getSocialUser(string $provider): ?User
    {
        switch ($provider) {
        case 'google':
            $googleUser = Socialite::driver('google')->user();
            $user = $googleUser ? User::firstWhere('google_id',
                $googleUser->id) : null;
            return $user;
        case 'facebook':
        }
    }

    private static function storeSocialUser(string $provider, ?SignupInvitation
            $signupInvite = null): void
    {
        switch ($provider) {
        case 'google':
            $user = new User();
            $user->google_id = $googleUser->id;
            $user->google_token = $googleUser->token;
            $user->google_refresh_token = $googleUser->refreshToken;
            $user->google_expires_at = now()->second($googleUser->expiresIn)
                ->toDateTimeString();
            $user->first_name = $googleUser->getRaw()['given_name'];
            $user->last_name = $googleUser->getRaw()['family_name'];
            if ($inviteCode) {
                $user->position()->associate($signupInvite->position);
            }
            $user->save();
            break;
        case 'facebook':
            break;
        }
        if ($signupInvite) {
            $signupInvite->is_accepted = true;
            $signupInvite->save();
        }
    }
}
