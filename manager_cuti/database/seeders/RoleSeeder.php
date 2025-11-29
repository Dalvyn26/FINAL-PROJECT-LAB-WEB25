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
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'username' => 'admin',
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

        if (!User::where('email', 'hrd@example.com')->exists()) {
            User::create([
                'username' => 'hrd',
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

        if (!Division::where('name', 'IT')->exists()) {
            $itDivision = Division::create([
                'name' => 'IT',
                'description' => 'Information Technology Division',
            ]);
        } else {
            $itDivision = Division::where('name', 'IT')->first();
        }

        if (!User::where('email', 'lead.it@example.com')->exists()) {
            $itLeader = User::create([
                'username' => 'leadit',
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

            $itDivision->update(['leader_id' => $itLeader->id]);
        }

        if (!User::where('email', 'staff.it@example.com')->exists()) {
            User::create([
                'username' => 'staffit',
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
