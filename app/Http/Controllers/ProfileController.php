<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\Image;

class ProfileController extends Controller
{
	public function index()
	{
		return view('profile.index');
	}

	public function edit()
	{
		return view('profile.edit');
	}

	public function update(Request $request)
	{
		$user = auth()->user();
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->suffix_name = $request->suffix_name;
        $user->email = $request->email;

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
        return redirect()->route('user.home');
	}

	public function showAvatar() {
		$user = auth()->user();
		return $user->avatar_filepath ? response()->file(Storage::path(
			$user->avatar_filepath)) : null;
	}
}
