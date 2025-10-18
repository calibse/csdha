<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        $role = Role::firstWhere('name', 'admin');
        if (session('errors')?->any()) {
            $selectedUsers = User::whereIn('public_id', 
                old(strtolower($role->name)) ?? [])->notAuthUser()->get();
            $users = User::whereNotIn('public_id', 
                old(strtolower($role->name)) ?? [])->notAuthUser()->get();
        } else {
            $selectedUsers = $role->users()->notAuthUser()->get();
            $users = User::whereDoesntHave('role', function ($query) 
                    use ($role) {
                $query->where('roles.id', $role->id);
            })->notAuthUser()->get();
        }
        return view('roles.index', [
            'formAction' => route('roles.update'),
            'role' => $role,
            'selectedUsers' => $selectedUsers,
            'users' => $users,
            'authUserHasRole' => (auth()->user()->role->id === $role->id) 
        ]);
    }

    public function update(UpdateRoleRequest $request)
    {
        $role = Role::firstWhere('name', 'admin');
        foreach (User::notAuthUser()->get() as $user) {
            if (in_array($user->public_id, 
                $request->input(strtolower($role->name)) ?? [])) {
                $user->role()->associate($role);
                $user->save();
            } elseif ($user->role?->id === $role->id) {
                $user->role()->dissociate();
                $user->setRememberToken(Str::random(60));
                $user->save();
                DB::table('sessions')->where('user_id', $user->id)->delete();
            }
        }
        return back()->with('status', 'Role updated.');




        /*
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
        */
    }
}
