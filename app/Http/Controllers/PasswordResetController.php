<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Requests\StorePasswordResetRequest;
use App\Http\Requests\UpdatePasswordResetRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordReset;

class PasswordResetController extends Controller
{
    private static string $siteContext;

    public function __construct()
    {
        $domain = request()->getHost();
        self::$siteContext = $domain === config('app.user_domain') ? 'user' : 'admin';
    }

    public function createPasswordReset()
    {
        return view('users.request-password-reset', [
            'backRoute' => self::$siteContext === 'user' ? route('user.login') :
                route('admin.login'),
            'formAction' => route('profile.password-reset.store')
        ]);
    }

    public function storePasswordReset(StorePasswordResetRequest $request)
    {
	$status = 'Now check the email inbox for update password page link.';
        $user = User::firstWhere('email', $request->email);
        if (!$user) {
            $route = self::$siteContext === 'user' ? 'user.login' : 
                'admin.login';
            return redirect()->route($route)->with('status', $status);
        }
        $token = Str::random(64);
        DB::table('password_reset_tokens')->upsert(
            [
                'email' => $request->email,
                'token' => Hash::make($token)
            ],
            ['email'],
            ['token']
        );
        $url = route('profile.password-reset.edit', [
            'email' => $request->email,
            'token' => $token
        ]);
        $duration = config('auth.passwords.users.expire') . ' minutes';
        Mail::to($request->email)->send(new PasswordReset($url, $user, 
            $duration)); 
        $route = self::$siteContext === 'user' ? 'user.login' : 
            'admin.login';
        return redirect()->route($route)->with('status', $status);
    }

    public function endPasswordReset(Request $request)
    {
        return view('users.password-reset-successful', [
            'backRoute' => self::$siteContext === 'user' ? route('user.login') :
                route('admin.login'),
            'signinRoute' => url('/'),
        ]);
    }

    public function editPasswordReset(Request $request)
    {
        return view('users.reset-password', [
            'backRoute' => self::$siteContext === 'user' ? route('user.login') :
                route('admin.login'),
            'formAction' => route('profile.password-reset.update'),
            'email' => $request->email,
            'token' => $request->token
        ]);
    }

    public function updatePasswordReset(UpdatePasswordResetRequest $request)
    {
        $user = User::firstWhere('email', $request->email);
        $user->password = Hash::make($request->password);
        $user->save();
        DB::table('password_reset_tokens')->where('email', $request->email)
            ->delete();
        return redirect()->route('profile.password-reset.end');
    }
}
