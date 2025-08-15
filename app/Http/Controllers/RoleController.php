<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        return view('roles.index', [
            'users' => User::all(),
            'roles' => Role::all(),
        ]);
    }

    public function update(Request $request)
    {
        $logoutUser = true;
        foreach ($request->roles as $roleId => $userIds) {
            $role = Role::find($roleId);
            foreach ($role->users as $user) {
                $user->role()->dissociate();
                $user->save();
            }
            foreach ($userIds as $userId) {
                if (auth()->user()->id == $userId) {
                    $logoutUser = false;
                }
                $user = User::find($userId);
                $user->role()->associate($role);
                $user->save();
            }
        }
        if ($logoutUser) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/');
        }
        return back()->with('saved', 1);
    }
}
