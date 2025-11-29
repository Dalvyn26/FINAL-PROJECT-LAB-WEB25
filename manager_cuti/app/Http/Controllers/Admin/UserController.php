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
    public function index(Request $request)
    {
        $query = User::query();

        $query->with('division');
        $search = $request->query('search');
        $role = $request->query('role');
        $divisionId = $request->query('division_id');
        $status = $request->query('status');
        $tenure = $request->query('tenure');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
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

        $sort = $request->query('sort', 'name');
        $direction = $request->query('direction', 'asc');
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

        $divisions = Division::all();

        return view('admin.users.index', compact('users', 'search', 'role', 'divisionId', 'status', 'tenure', 'sort', 'direction', 'divisions'));
    }

    public function create()
    {
        $hrdExists = User::where('role', 'hrd')->exists();
        return view('admin.users.create', compact('hrdExists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => [
                'required',
                'in:hrd,division_leader,user',
                function ($attribute, $value, $fail) {
                    if ($value === 'admin') {
                        $fail('Admin role cannot be created.');
                    }
                    if ($value === 'hrd' && User::where('role', 'hrd')->exists()) {
                        $fail('HRD role already exists. Only one HRD is allowed.');
                    }
                },
            ],
            'leave_quota' => 'required|integer|min:0|max:365',
        ]);

        DB::transaction(function () use ($request) {
            User::create([
                'username' => $request->username,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'division_id' => null,
                'leave_quota' => $request->leave_quota,
                'active_status' => true,
                'join_date' => now(),
            ]);
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    public function show(User $user)
    {
        $user->load(['division', 'division.leader']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $divisions = Division::all();
        $hrdExists = User::where('role', 'hrd')->where('id', '!=', $user->id)->exists();
        return view('admin.users.edit', compact('user', 'divisions', 'hrdExists'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => [
                'required',
                'in:hrd,division_leader,user',
                function ($attribute, $value, $fail) use ($user) {
                    if ($value === 'admin') {
                        $fail('Admin role cannot be assigned.');
                    }
                    if ($value === 'hrd' && User::where('role', 'hrd')->where('id', '!=', $user->id)->exists()) {
                        $fail('HRD role already exists. Only one HRD is allowed.');
                    }
                },
            ],
            'division_id' => 'nullable|exists:divisions,id',
            'leave_quota' => 'required|integer|min:0|max:365',
            'join_date' => 'nullable|date',
        ]);

        DB::transaction(function () use ($request, $user) {
            $data = [
                'username' => $request->username,
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'division_id' => $request->division_id,
                'join_date' => $request->join_date ?? $user->join_date,
                'leave_quota' => $request->leave_quota,
                'active_status' => $request->active_status ?? $user->active_status,
            ];

            $user->update($data);
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }

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