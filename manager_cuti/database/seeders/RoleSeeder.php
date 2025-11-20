<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Division;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin if not exists
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('Password'),
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Admin Address',
                'join_date' => now(),
                'leave_quota' => 12,
                'active_status' => true,
            ]);
        }

        // Create HRD if not exists
        if (!User::where('email', 'hrd@example.com')->exists()) {
            User::create([
                'name' => 'HRD User',
                'email' => 'hrd@example.com',
                'password' => Hash::make('Password'),
                'role' => 'hrd',
                'phone' => '081234567891',
                'address' => 'HRD Address',
                'join_date' => now(),
                'leave_quota' => 12,
                'active_status' => true,
            ]);
        }

        // Create Divisions if not exist
        if (!Division::where('name', 'IT')->exists()) {
            $itDivision = Division::create([
                'name' => 'IT',
                'description' => 'Information Technology Division',
            ]);
        } else {
            $itDivision = Division::where('name', 'IT')->first();
        }

        if (!Division::where('name', 'Finance')->exists()) {
            $financeDivision = Division::create([
                'name' => 'Finance',
                'description' => 'Finance Division',
            ]);
        } else {
            $financeDivision = Division::where('name', 'Finance')->first();
        }

        // Create Division Leader for IT if not exists
        if (!User::where('email', 'lead.it@example.com')->exists()) {
            $itLeader = User::create([
                'name' => 'IT Division Leader',
                'email' => 'lead.it@example.com',
                'password' => Hash::make('Password'),
                'role' => 'division_leader',
                'division_id' => $itDivision->id,
                'phone' => '081234567892',
                'address' => 'IT Leader Address',
                'join_date' => now(),
                'leave_quota' => 12,
                'active_status' => true,
            ]);

            // Update the division with the leader
            $itDivision->update(['leader_id' => $itLeader->id]);
        }

        // Create Staff Members if not exist
        if (!User::where('email', 'staff.it@example.com')->exists()) {
            User::create([
                'name' => 'IT Staff',
                'email' => 'staff.it@example.com',
                'password' => Hash::make('Password'),
                'role' => 'user',
                'division_id' => $itDivision->id,
                'phone' => '081234567893',
                'address' => 'IT Staff Address',
                'join_date' => now(),
                'leave_quota' => 12,
                'active_status' => true,
            ]);
        }

        if (!User::where('email', 'staff.finance@example.com')->exists()) {
            User::create([
                'name' => 'Finance Staff',
                'email' => 'staff.finance@example.com',
                'password' => Hash::make('Password'),
                'role' => 'user',
                'division_id' => $financeDivision->id,
                'phone' => '081234567894',
                'address' => 'Finance Staff Address',
                'join_date' => now(),
                'leave_quota' => 12,
                'active_status' => true,
            ]);
        }
    }
}