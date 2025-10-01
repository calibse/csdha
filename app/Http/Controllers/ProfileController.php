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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerify;

class ProfileController extends Controller
{
    private string $siteContext;
    public function __construct()
    {
        if (request()->getHost() === config('app.user_domain')) {
            $this->siteContext = 'user';
        } elseif (request()->getHost() === config('app.admin_domain')) {
            $this->siteContext = 'admin';
        }
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
        return view('profile.edit', [
            'backRoute' => $backRoute,
            'passwordRoute' => $passwordRoute,
            'emailRoute' => $emailRoute,
            'formAction' => $formAction
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
        elseif ($request->avatar) {
	        $imageFile = 'user/avatar/' . Str::random(8) . '.jpg';
	        $image = new Image($request->file('avatar')->get());
	        Storage::put($imageFile, (string) $image->scaleDown(300));
        	if ($user->avatar_filepath) {
        		Storage::delete($user->avatar_filepath);
        	}
        	$user->avatar_filepath = $imageFile;
        }
        $user->save();
        return redirect()->back()->with('status', 'Profile updated.');
	}

    public function editEmail()
    {
        $backRoute = route('profile.edit');
        return view('profile.edit-email', [
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

    public function verifyEmail(Request $request)
    {
        if (!$request->hasValidSignature()) {
            return 'Hello';
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
        return view('profile.edit-password', [
            'backRoute' => $backRoute,
            'formAction' => route('profile.password.update')
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = auth()->user();
        $changed = false;
        if (Hash::make($request->password) !== Hash::make($user->password)) {
            $changed = true;
        }
        $user->password = Hash::make($request->password);
        $user->save();
        if ($changed) {
            return redirect()->back()->with('status', 'Password updated.');
        }
        return redirect()->back();
    }

	public function showAvatar() {
		$user = auth()->user();
		return $user->avatar_filepath ? response()->file(Storage::path(
			$user->avatar_filepath)) : null;
	}
}
