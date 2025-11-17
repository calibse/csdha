<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use App\Models\User;
use App\Models\Permission;
use App\Models\ResourceType;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdatePositionRequest;

class PositionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:viewAny,' . Position::class, only: ['index']),
            new Middleware('can:view,position', only: ['show']),
            new Middleware('can:create,' . Position::class, 
                only: ['create', 'store']),
            new Middleware('can:update,position', 
            	only: ['edit', 'update']),
            new Middleware('can:delete,position', 
            	only: ['confirmDestroy', 'destroy']),
        ];
    }

	public function index() 
	{		
		return view('positions.index', [
			'positions' => Position::orderBy('position_order', 'asc')->get()
		]);
	}
	
	public function show(Request $request, Position $position)
	{
		return view('positions.show', [
			'permissions' => Permission::all(),
			'position' => $position,
			'officer' => $position->user,
			'resources' => ResourceType::all(),
			'users' => User::doesntHave('position')->get(),
		]);
	}
	
	public function create()
	{
		return view('positions.create', [
			'users' => User::doesntHave('position')->get(),
            'permissions' => Permission::all(),
			'resources' => ResourceType::all(),
        ]);
	}
	
	public function store(StorePositionRequest $request)
	{
		$position = new Position();
		$position->name = $request->position_name;
		$position->position_order = $request->position_order;
		$position->save();
        if ($request->officer >= "1") {
            $user = User::findByPublic($request->officer);
            $user->position()->associate($position)->save();
        }
        $permissions = array_values(array_filter($request->permissions ?? [], 
        	function($value) use ($request, $position) {
        		return $request->user()->can('addPerm', 
        			Permission::find($value));
        	}));
		$position->permissions()->sync($permissions);
		$position->save();
		
		return redirect()->route('positions.index');
	}
	
	public function edit() 
	{
		return view('positions.edit', [
			'users' => User::doesntHave('position')->get(),
			'positions' => Position::all()
		]);
	}
	
	public function update(UpdatePositionRequest $request, 
            Position $position)
	{
            if ($request->user()->can('rename', $position)) {
                $position->name = $request->position_name;
            }
            if ($request->user()->can('removeOfficer', $position) && 
                $request->officer === "0") {
                $position->user?->position()->dissociate()->save();
            } else if ($request->officer >= "1") {
                $user = User::findByPublic($request->officer);
                if (!$user->is($position->user)) {
                    $position->user?->position()->dissociate()->save();
                }
                $user->position()->associate($position)->save();
            }
            $permissions = array_values(array_filter($request->permissions ?? 
                [], function($value) use ($request, $position) {
                    return $request->user()->can('changePerm', [$position, 
                        Permission::find($value)]);	
            }));
            $defaultPerm = array_values(array_filter($position->permissions
                ->pluck('id')->toArray() ?? [], function($value) 
                use ($request, $position) {
                    return $request->user()->cannot('changePerm', [$position, 
                        Permission::find($value)]);	
            }));
            $permissions = array_values(array_unique(array_merge($permissions, 
                $defaultPerm)));
            $position->permissions()->sync($permissions);
            $position->position_order = $request->position_order;
            $position->save();
            return redirect()->route('positions.index');
        }
	
	public function confirmDestroy(Request $request, Position $position)
	{
		return view('positions.delete', ['position' => $position]);	
	}

	public function destroy(Request $request, Position $position)
	{
		$position->user?->position()->dissociate()->save();
		$position->permissions()->sync([]);
		$position->delete();
		return redirect()->route('positions.index');
	}
	
	private static function stake($str) 
	{
	    return strtolower(preg_replace('/\s+/', '_', $str));	
	}
}
