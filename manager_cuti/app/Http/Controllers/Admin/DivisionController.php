<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DivisionController extends Controller
{
    /**
     * Display a listing of the divisions with filtering and sorting capabilities.
     */
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $sort = $request->query('sort', 'created_newest');

        $query = Division::query();

        // Eager load leader and count members
        $query->with('leader')->withCount('users');

        // Apply search filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhereHas('leader', function ($leaderQuery) use ($search) {
                      $leaderQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Apply sorting
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'created_newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'created_oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'members_most':
                $query->orderBy('users_count', 'desc');
                break;
            case 'members_least':
                $query->orderBy('users_count', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $divisions = $query->paginate(10)->withQueryString();

        return view('admin.divisions.index', compact('divisions', 'search', 'sort'));
    }

    /**
     * Show the form for creating a new division.
     */
    public function create()
    {
        $availableLeaders = User::where('role', 'division_leader')
            ->whereNotIn('id', Division::whereNotNull('leader_id')->pluck('leader_id'))
            ->get();
        return view('admin.divisions.create', compact('availableLeaders'));
    }

    /**
     * Store a newly created division in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name',
            'description' => 'nullable|string',
            'leader_id' => [
                'nullable',
                Rule::exists('users', 'id')->where('role', 'division_leader'),
                Rule::unique('divisions', 'leader_id')
            ],
        ], [
            'leader_id.exists' => 'The selected leader must have division leader role.',
            'leader_id.unique' => 'Selected leader is already assigned to another division.'
        ]);

        $division = Division::create([
            'name' => $request->name,
            'description' => $request->description,
            'leader_id' => $request->leader_id,
        ]);

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division created successfully');
    }

    /**
     * Display the specified division.
     */
    public function show(Division $division)
    {
        // Load leader relationship eagerly
        $division->load('leader');

        $members = $division->users()->orderBy('name')->paginate(10);
        $availableUsers = User::where('role', 'user')
            ->whereNull('division_id')
            ->get();

        return view('admin.divisions.show', compact('division', 'members', 'availableUsers'));
    }

    /**
     * Add a member to the division.
     */
    public function storeMember(Request $request, Division $division)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::findOrFail($request->user_id);

        // Check if user already has a division
        if ($user->division_id) {
            return redirect()->back()
                ->withErrors(['user_id' => 'User is already assigned to another division.']);
        }

        // Check if the user's role is 'user' (regular employee)
        if ($user->role !== 'user') {
            return redirect()->back()
                ->withErrors(['user_id' => 'Only users with role "user" can be added to division.']);
        }

        $user->update(['division_id' => $division->id]);

        return redirect()->back()
            ->with('success', 'Member added successfully to the division.');
    }

    /**
     * Remove a member from the division.
     */
    public function removeMember(Division $division, User $user)
    {
        // Only allow removing users from the division (not deleting the user account)
        $user->update(['division_id' => null]);

        return redirect()->back()
            ->with('success', 'Member removed from division successfully.');
    }

    /**
     * Show the form for editing the specified division.
     */
    public function edit(Division $division)
    {
        // Get all division leaders and filter them in PHP to include the current division's leader
        $allLeaders = User::where('role', 'division_leader')->get();

        // Filter leaders that are not assigned to other divisions, but include the current division's leader
        $availableLeaders = $allLeaders->filter(function ($leader) use ($division) {
            // Check if this leader is already assigned to a different division
            $assignedToOtherDivision = Division::where('leader_id', $leader->id)
                ->where('id', '!=', $division->id)
                ->exists();

            // Include the leader if they are not assigned to another division OR they're the current division's leader
            return !$assignedToOtherDivision || $leader->id == $division->leader_id;
        });

        return view('admin.divisions.edit', compact('division', 'availableLeaders'));
    }

    /**
     * Update the specified division in storage.
     */
    public function update(Request $request, Division $division)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name,' . $division->id,
            'description' => 'nullable|string',
            'leader_id' => [
                'nullable',
                Rule::exists('users', 'id')->where('role', 'division_leader'),
                Rule::unique('divisions', 'leader_id')->ignore($division->id)
            ],
        ], [
            'leader_id.exists' => 'The selected leader must have division leader role.',
            'leader_id.unique' => 'Selected leader is already assigned to another division.'
        ]);

        $division->update([
            'name' => $request->name,
            'description' => $request->description,
            'leader_id' => $request->leader_id,
        ]);

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division updated successfully');
    }

    /**
     * Remove the specified division from storage.
     */
    public function destroy(Division $division)
    {
        \DB::transaction(function () use ($division) {
            // Step 1: Detach all members from this division by setting their division_id to NULL
            User::where('division_id', $division->id)->update(['division_id' => null]);

            // Step 2: Now delete the division
            $division->delete();
        });

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division deleted successfully and members have been reset');
    }
}