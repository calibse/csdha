<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\SignupInvitation;

class LoginController extends Controller
{
    public function login(Request $request) {
        return view('users.login', [
            'inviteCode' => $request->query('invite-code'),
        ]);
    }

    public function auth(Request $request) {
        if (auth()->check()) {
            return redirect()->intended('home.html');
        }
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $remember = true)) {
            $request->session()->regenerate();
            return redirect()->intended('home.html');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function adminAuth(Request $request) {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        $user = User::firstWhere('username', $request->username);
        if (!$user || !$user->isAdmin()) {
            return "We only allow log-in to administrator account here.";
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
        return view('admin.login');
    }

    public function signinWith(Request $request, string $provider)
    {
        if (auth()->check()) {
            return redirect()->intended('home.html');
        }
        $inviteCode = $request->query('invite-code');
        $request->session()->flash('inviteCode', $inviteCode);
        switch ($provider) {
        case 'google':
            return Socialite::driver('google')
                ->with([
                    'access_type' => 'offline',
                ])
                ->redirect();
            break;
        case 'facebook':
            return Socialite::driver('facebook')
                ->redirect();
            break;
        }
    }

    public function authWith(Request $request, string $provider)
    {
        $inviteCode = session('inviteCode');
        if ($inviteCode) {
            $signupInvite = SignupInvitation::firstWhere('invite_code', 
                $inviteCode);
            if (!$signupInvite) {
                return "Your sign-up invitation link is invalid.";
            }

            if ($signupInvite->is_accepted) {
                return "Your sign-up invitation has already been accepted.";
            }

            if (now()->greaterThan($signupInvite->expires_at)) {
                return "Your sign-up invitation link has expired.";
            }
        }
        switch ($provider) {
        case 'google':
            $googleUser = Socialite::driver('google')->user();
            $user = User::firstWhere('google_id', $googleUser->id);
            break;
        case 'facebook':
            break;
        }
        if (!$user && !$inviteCode) {
            return "Sorry, we only allow sign-in for registered users or from a sign-up invitation link right now.";
        }
        switch ($provider) {
        case 'google':
            if (!$user) {
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
            }
            /*
            if (now()->greaterThan($user->google_expires_at)) {
                $googleUser = Socialite::driver('google')->refreshToken($user
                    ->google_refresh_token);
                $user->google_token = $googleUser->token;
                $user->google_expires_at = now()->second($googleUser->expiresIn)
                    ->toDateTimeString();
                $user->save();
            }
            */
            break;
        case 'facebook':
            break;
        }
        Auth::login($user, $remember = true);
        $request->session()->regenerate();
        if ($inviteCode) {
            $signupInvite->is_accepted = true;
            $signupInvite->save();
        }
        return redirect()->intended('home.html');
    }

    public function adminAuthWith(Request $request, string $provider)
    {
        switch ($provider) {
        case 'google':
            $googleUser = Socialite::driver('google')->user();
            $user = User::firstWhere('google_id', $googleUser->id);
            break;
        case 'facebook':
            break;
        }
        if (!$user || !$user->isAdmin()) {
            return "We only allow log-in to administrator account here.";
        }

        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->intended('home.html');
    }

    public function showSignupInvitation(Request $request) {
        $inviteCode = $request->query('invite-code');
        $signupInvite = SignupInvitation::firstWhere('invite_code', 
            $inviteCode);
        if (!$signupInvite) {
            return "Your sign-up invitation link is invalid.";
        }

        if ($signupInvite->is_accepted) {
            return "Your sign-up invitation has already been accepted.";
        }

        if (now()->greaterThan($signupInvite->expires_at)) {
            return "Your sign-up invitation link has expired.";
        }
        return view('users.show-signup-invitation', [
            'inviteCode' => $inviteCode
        ]);
    }

    public function logout(Request $request) {
		Auth::logout();
		$request->session()->invalidate();
		$request->session()->regenerateToken();
		return redirect('/');
    }
}
