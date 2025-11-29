<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DivisionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $sort = $request->query('sort', 'created_newest');

        $query = Division::query();

        $query->with('leader')->withCount('users');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhereHas('leader', function ($leaderQuery) use ($search) {
                      $leaderQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

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

    public function create()
    {
        $availableLeaders = User::where('role', 'division_leader')
            ->whereNotIn('id', Division::whereNotNull('leader_id')->pluck('leader_id'))
            ->get();
        return view('admin.divisions.create', compact('availableLeaders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name',
            'description' => 'nullable|string',
            'leader_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'division_leader'),
                Rule::unique('divisions', 'leader_id')
            ],
        ], [
            'leader_id.required' => 'Ketua divisi wajib dipilih.',
            'leader_id.exists' => 'Ketua divisi yang dipilih harus memiliki role division leader.',
            'leader_id.unique' => 'Ketua divisi yang dipilih sudah menjadi ketua divisi lain.'
        ]);

        $division = Division::create([
            'name' => $request->name,
            'description' => $request->description,
            'leader_id' => $request->leader_id,
        ]);

        User::where('id', $request->leader_id)->update([
            'division_id' => $division->id
        ]);

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division created successfully');
    }

    public function show(Division $division)
    {
        $division->load('leader');

        $members = $division->users()->orderBy('name')->paginate(10);
        $availableUsers = User::where('role', 'user')
            ->whereNull('division_id')
            ->get();

        return view('admin.divisions.show', compact('division', 'members', 'availableUsers'));
    }

    public function storeMember(Request $request, Division $division)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::findOrFail($request->user_id);

        if ($user->division_id) {
            return redirect()->back()
                ->withErrors(['user_id' => 'User is already assigned to another division.']);
        }

        if ($user->role !== 'user') {
            return redirect()->back()
                ->withErrors(['user_id' => 'Only users with role "user" can be added to division.']);
        }

        $user->update(['division_id' => $division->id]);

        return redirect()->back()
            ->with('success', 'Member added successfully to the division.');
    }

    public function removeMember(Division $division, User $user)
    {
        if ($division->leader_id == $user->id) {
            return redirect()->back()
                ->withErrors(['error' => 'Cannot remove division leader from the division. Please change the leader first in the edit division page.']);
        }

        $user->update(['division_id' => null]);

        return redirect()->back()
            ->with('success', 'Member removed from division successfully.');
    }

    public function edit(Division $division)
    {
        $allLeaders = User::where('role', 'division_leader')->get();

        $availableLeaders = $allLeaders->filter(function ($leader) use ($division) {
            $assignedToOtherDivision = Division::where('leader_id', $leader->id)
                ->where('id', '!=', $division->id)
                ->exists();

            return !$assignedToOtherDivision || $leader->id == $division->leader_id;
        });

        return view('admin.divisions.edit', compact('division', 'availableLeaders'));
    }

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

        $oldLeaderId = $division->leader_id;
        
        $division->update([
            'name' => $request->name,
            'description' => $request->description,
            'leader_id' => $request->leader_id,
        ]);

        if ($oldLeaderId && $oldLeaderId != $request->leader_id) {
            User::where('id', $oldLeaderId)->update(['division_id' => null]);
        }
        
        if ($request->leader_id) {
            User::where('id', $request->leader_id)->update([
                'division_id' => $division->id
            ]);
        }

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division updated successfully');
    }

    public function destroy(Division $division)
    {
        \DB::transaction(function () use ($division) {
            User::where('division_id', $division->id)->update(['division_id' => null]);
            
            if ($division->leader_id) {
                User::where('id', $division->leader_id)->update(['division_id' => null]);
            }

            $division->delete();
        });

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division deleted successfully and members have been reset');
    }
}