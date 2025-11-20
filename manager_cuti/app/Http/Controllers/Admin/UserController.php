<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::with('division')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $divisions = Division::all();
        return view('admin.users.create', compact('divisions'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,hrd,division_leader,user',
            'division_id' => 'nullable|exists:divisions,id',
            'leave_quota' => 'required|integer|min:0|max:365',
        ]);

        DB::transaction(function () use ($request) {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'division_id' => $request->division_id,
                'phone' => $request->phone ?? null,
                'address' => $request->address ?? null,
                'join_date' => $request->join_date ?? null,
                'leave_quota' => $request->leave_quota,
                'active_status' => $request->active_status ?? true,
            ]);
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $divisions = Division::all();
        return view('admin.users.edit', compact('user', 'divisions'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,hrd,division_leader,user',
            'division_id' => 'nullable|exists:divisions,id',
            'leave_quota' => 'required|integer|min:0|max:365',
        ]);

        DB::transaction(function () use ($request, $user) {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'division_id' => $request->division_id,
                'phone' => $request->phone ?? null,
                'address' => $request->address ?? null,
                'join_date' => $request->join_date ?? null,
                'leave_quota' => $request->leave_quota,
                'active_status' => $request->active_status ?? true,
            ];

            if ($request->password) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete your own account');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }
}