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
     * Display a listing of the users with filtering and sorting capabilities.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Eager load division
        $query->with('division');

        // Apply filters
        $search = $request->query('search');
        $role = $request->query('role');
        $divisionId = $request->query('division_id');
        $status = $request->query('status');
        $tenure = $request->query('tenure');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role) {
            $query->where('role', $role);
        }

        if ($divisionId) {
            $query->where('division_id', $divisionId);
        }

        if ($status) {
            $query->where('active_status', $status === 'active' ? true : false);
        }

        if ($tenure) {
            $now = now();
            switch ($tenure) {
                case '<1':
                    $query->whereDate('join_date', '>', $now->copy()->subYear());
                    break;
                case '1-3':
                    $query->whereDate('join_date', '>=', $now->copy()->subYears(3))
                          ->whereDate('join_date', '<=', $now->copy()->subYear());
                    break;
                case '>3':
                    $query->whereDate('join_date', '<', $now->copy()->subYears(3));
                    break;
            }
        }

        // Apply sorting
        $sort = $request->query('sort', 'name');
        $direction = $request->query('direction', 'asc');

        // Validate and set proper direction based on sort field
        if ($sort === 'name_desc') {
            $direction = 'desc';
            $sort = 'name';
        } elseif ($sort === 'division_desc') {
            $direction = 'desc';
            $sort = 'division';
        } elseif ($sort === 'join_date_desc') {
            $direction = 'desc';
            $sort = 'join_date';
        } elseif (strpos($sort, '_desc') !== false) {
            $direction = 'desc';
            $sort = str_replace('_desc', '', $sort);
        }

        switch ($sort) {
            case 'name':
                $query->orderBy('name', $direction);
                break;
            case 'join_date':
                $query->orderBy('join_date', $direction);
                break;
            case 'division':
                $query->leftJoin('divisions', 'users.division_id', '=', 'divisions.id')
                      ->select('users.*')
                      ->orderByRaw('CASE WHEN divisions.name IS NULL THEN 1 ELSE 0 END, divisions.name ' . ($direction === 'asc' ? 'ASC' : 'DESC'));
                break;
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $users = $query->paginate(10)->withQueryString();

        // Get divisions for the filter dropdown
        $divisions = Division::all();

        return view('admin.users.index', compact('users', 'search', 'role', 'divisionId', 'status', 'tenure', 'sort', 'direction', 'divisions'));
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