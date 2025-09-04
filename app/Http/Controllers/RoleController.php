<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Http\Requests\UpdateRoleRequest;

class RoleController extends Controller
{
    public function index()
    {
        return view('roles.index', [
            'formAction' => route('roles.update'),
            'users' => User::all(),
            'roles' => Role::all(),
        ]);
    }

    public function update(UpdateRoleRequest $request)
    {
        $logoutUser = true;
        foreach ($request->roles as $roleId => $userIds) {
            $role = Role::find($roleId);
            foreach ($role->users as $user) {
                $user->role()->dissociate();
                $user->save();
            }
            foreach ($userIds as $userId) {
                if (auth()->user()->public_id == $userId) {
                    $logoutUser = false;
                }
                $user = User::findByPublic($userId);
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
        return back()->with('status', 'Role updated.');
    }
}
