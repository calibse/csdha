<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\Image;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateEmailRequest;
use App\Http\Requests\StorePasswordResetRequest;
use App\Http\Requests\UpdatePasswordResetRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerify;
use App\Mail\PasswordReset;
use Laravel\Socialite\Facades\Socialite;
use App\Models\GoogleAccount;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProfileController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth.account-setting:updateEmail,' . User::class, 
                only: ['editEmail', 'updateEmail', 'resendEmailVerify']),
            new Middleware('auth.account-setting:updatePassword,' . 
                User::class, only: [
                    'editPassword', 'updatePassword'
                ]),
        ];
    }

    public function index()
    {
        return view('profile.index');
    }

    public function edit(Request $request)
    {
        $backRoute = route('user.home');
        $passwordRoute = route('profile.password.edit');
        $emailRoute = route('profile.email.edit');
        $formAction = route('profile.update');
        $googleRoute = auth()->user()->google 
            ? route('profile.connect.remove', [
                'provider' => 'google'
            ])
            : route('profile.connect.redirect', [
                'provider' => 'google'
            ]);
        $email = auth()->user()->email;
        $hasEmail = auth()->user()->email ? true : false;
        $emailVerified = auth()->user()->email_verified_at ? true : false;
        $hasPassword = auth()->user()->password ? true : false;
        return view('profile.edit', [
            'user' => auth()->user(),
            'backRoute' => $backRoute,
            'passwordRoute' => $passwordRoute,
            'emailRoute' => $emailRoute,
            'formAction' => $formAction,
            'hasPassword' => $hasPassword,
            'googleRoute' => $googleRoute,
            'email' => $email,
            'emailVerified' => $emailVerified,
            'hasEmail' => $hasEmail,
            'editEmailAction' => route('profile.email.update'),
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = auth()->user();
        $user->username = $request->username;
        if ($request->remove_avatar && $user->avatar_filepath) {
            Storage::delete($user->avatar_filepath);
            $user->avatar_filepath = null;
        }
        elseif ($request->has('avatar')) {
            $imageFile = 'user/avatar/' . Str::random(8) . '.jpg';
            $image = new Image($request->file('avatar'));
            Storage::put($imageFile, (string) $image->scaleDown(300));
            if ($user->avatar_filepath) {
                Storage::delete($user->avatar_filepath);
            }
            $user->avatar_filepath = $imageFile;
        }
        $user->save();
        return redirect()->back()->with('status', 'Account updated.');
    }

    public function editEmail()
    {
        if (!is_null(auth()->user()->password)) {
            $view = 'profile.edit-email';
        } elseif (auth()->user()->google && is_null(auth()->user()->password)) {
            $view = 'profile.edit-email-without-password';
        }
        $backRoute = route('profile.edit');
        return view($view, [
            'backRoute' => $backRoute,
            'user' => auth()->user(),
            'formAction' => route('profile.email.update'),
            'resendRoute' => route('profile.email.send-verify')
        ]);
    }

    public function updateEmail(UpdateEmailRequest $request)
    {
        $user = auth()->user();
        $status = 'Email updated.';
        if ($user->email !== $request->email || !$request->email) {
            $user->email_verified_at = null;
        }
        $user->email = $request->email;
        $user->save();
        if ($user->email && !$user->email_verified_at) {
            self::sendEmailVerify();
            return redirect()->back()->with('status', $status);
        }
        return redirect()->back();
    }

    public function resendEmailVerify()
    {
        $user = auth()->user();
        if ($user->email && $user->email_verified_at) {
            return view('message', [
                'message' => 'Your email is already verified.'
            ]);
        } elseif (!$user->email) {
            return view('message', [
                'message' => 'You do not have email on your profile.'
            ]);
        }
        self::sendEmailVerify();
        return redirect()->back()->with('status', 'Verification email sent. ' .
            'Please check your inbox.');
    }

    public function verifyEmail(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }
        $user = User::where('public_id', $request->id)->where('email',
            $request->email)->first();
        if (!$user) {
            abort(401);
        }
        if ($user->email_verified_at) {
            $message = 'Your email is already verified.';
        } else {
            $user->email_verified_at = now();
            $message = 'Your email has been verified successfully.';
        }
        $user->save();
        return view('message', [
            'message' => $message
        ]);
    }

    public function editPassword()
    {
        $backRoute = route('profile.edit');
        $hasPassword = auth()->user()->password ? true : false;
        return view('profile.edit-password', [
            'backRoute' => $backRoute,
            'hasPassword' => $hasPassword,
            'formAction' => route('profile.password.update')
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = auth()->user();
        $changed = false;
        if ($request->password && Hash::make($request->password) !== 
            Hash::make($user->password)) {
            $changed = true;
        } else $changed = true;
        $user->password = Hash::make($request->password);
        $user->save();
        if ($changed) {
            return redirect()->back()->with('status', 'Password updated.');
        }
        return redirect()->back();
    }

    public function showAvatar()
    {
        $user = auth()->user();
        if (!$user->avatar_filepath) abort(404);
        return response()->file(Storage::path($user->avatar_filepath));
    }

    public function connectSocial(string $provider)
    {
        switch ($provider) {
        case 'google':
            config(['services.google.redirect' => route(
                    'profile.connect.callback', [
                'provider' => $provider
            ])]);
            return Socialite::driver($provider)->with([
                'access_type' => 'offline',
                'prompt' => 'consent'
            ])->redirect();
            break;
        }
    }

    public function updateSocial(string $provider)
    {
        config(['services.google.redirect' => route(
                'profile.connect.callback', [
            'provider' => $provider
        ])]);
        $socialUser = Socialite::driver($provider)->user();
        $user = $socialUser ? self::findSocialUser($provider,
            $socialUser->id) : null;
        if ($user) {
            return redirect()->route('profile.edit')->withErrors([
                'connect_account' => "The {$provider} account is already taken."
            ]);
        }
        self::storeOrUpdateSocial($provider, $socialUser);
        return redirect()->route('profile.edit')->with('status',
            'Account updated.');
    }

    public function deleteSocial(string $provider)
    {
        if (!auth()->user()->email_verified_at) {
            return redirect()->route('profile.edit')->with('status',
                'Add email first.');
        }
        $user = auth()->user();
        switch ($provider) {
        case 'google':
            $user->google->delete();
            break;
        }
        return redirect()->route('profile.edit')->with('status',
            'Account updated.');
    }

    public function createPasswordReset()
    {
        return view('users.request-password-reset', [
            'backRoute' => route('user.login'),
            'formAction' => route('profile.password-reset.store')
        ]);
    }

    public function storePasswordReset(StorePasswordResetRequest $request)
    {
	$status = 'Now check the email inbox for update password page link.';
        $user = User::firstWhere('email', $request->email);
        if (!$user) {
            return redirect()->route('user.login')->with('status', $status);
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
        return redirect()->route('user.login')->with('status', $status);
    }

    public function endPasswordReset(Request $request)
    {
        return view('users.password-reset-successful', [
            'backRoute' => route('user.login'),
            'signinRoute' => route('user.login')
        ]);
    }

    public function editPasswordReset(Request $request)
    {
        return view('users.reset-password', [
            'backRoute' => route('user.login'),
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

    private static function findSocialUser(string $provider, string $id)
    {
        switch ($provider) {
        case 'google':
            $user = GoogleAccount::firstWhere('google_id', $id)?->user;
            break;
        }
        return $user;
    }

    private static function storeOrUpdateSocial(string $provider, $socialUser)
    {
        $user = auth()->user();
        switch ($provider) {
        case 'google':
            if (!$user->google) {
                $google = new GoogleAccount;
                $google->user()->associate($user);
            } else {
                $google = $user->google;
            }
            $google->google_id = $socialUser->id;
            $google->token = $socialUser->token;
            $google->refresh_token = $socialUser->refreshToken;
            $google->expires_at = now()->second($socialUser->expiresIn)
                ->toDateTimeString();
            $google->save();
            break;
        }
    }

    private static function sendEmailVerify()
    {
        $user = auth()->user();
        $url = URL::temporarySignedRoute('verify-email', now(config('timezone'))
            ->addHours(24), [
            'id' => $user->public_id,
            'email' => $user->email
        ]);
        Mail::to($user->email)->send(new EmailVerify($url));
    }
}
