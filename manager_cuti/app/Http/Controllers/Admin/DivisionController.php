<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use App\Rules\DivisionLeaderRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DivisionController extends Controller
{
    /**
     * Display a listing of the divisions.
     */
    public function index()
    {
        $divisions = Division::with('leader')->paginate(10);
        return view('admin.divisions.index', compact('divisions'));
    }

    /**
     * Show the form for creating a new division.
     */
    public function create()
    {
        $leaders = User::where('role', 'division_leader')->get();
        return view('admin.divisions.create', compact('leaders'));
    }

    /**
     * Store a newly created division in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name',
            'description' => 'nullable|string',
            'leader_id' => ['nullable', 'exists:users,id', new DivisionLeaderRule]
        ]);

        DB::transaction(function () use ($request) {
            // Check if the selected leader is already assigned to another division
            if ($request->leader_id) {
                $existingDivision = Division::where('leader_id', $request->leader_id)->first();
                if ($existingDivision) {
                    throw new \Exception('Selected leader is already assigned to another division.');
                }
            }

            Division::create([
                'name' => $request->name,
                'description' => $request->description,
                'leader_id' => $request->leader_id
            ]);
        });

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division created successfully');
    }

    /**
     * Show the form for editing the specified division.
     */
    public function edit(Division $division)
    {
        $leaders = User::where('role', 'division_leader')->get();
        return view('admin.divisions.edit', compact('division', 'leaders'));
    }

    /**
     * Update the specified division in storage.
     */
    public function update(Request $request, Division $division)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name,' . $division->id,
            'description' => 'nullable|string',
            'leader_id' => ['nullable', 'exists:users,id', new DivisionLeaderRule]
        ]);

        DB::transaction(function () use ($request, $division) {
            // Check if the selected leader is already assigned to another division (excluding current division)
            if ($request->leader_id) {
                $existingDivision = Division::where('leader_id', $request->leader_id)
                    ->where('id', '!=', $division->id)
                    ->first();
                if ($existingDivision) {
                    throw new \Exception('Selected leader is already assigned to another division.');
                }
            }

            $division->update([
                'name' => $request->name,
                'description' => $request->description,
                'leader_id' => $request->leader_id
            ]);
        });

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division updated successfully');
    }

    /**
     * Remove the specified division from storage.
     */
    public function destroy(Division $division)
    {
        // Prevent deletion if the division has associated users
        if ($division->users()->count() > 0) {
            return redirect()->route('admin.divisions.index')
                ->with('error', 'Cannot delete division with associated users');
        }

        $division->delete();

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division deleted successfully');
    }
}