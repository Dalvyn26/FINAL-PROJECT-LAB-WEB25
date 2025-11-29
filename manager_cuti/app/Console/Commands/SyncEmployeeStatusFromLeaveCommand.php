<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SyncEmployeeStatusFromLeaveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:sync-employee-status {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync employee active_status based on their approved leave requests (for maintenance/sync)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');
        
        $this->info('Memulai sinkronisasi status karyawan berdasarkan cuti...');
        
        if ($isDryRun) {
            $this->warn('MODE DRY RUN: Tidak ada perubahan yang akan dilakukan');
        }

        $today = Carbon::today();
        
        $employees = User::where('role', '!=', 'admin')
            ->with(['leaveRequests' => function($query) {
                $query->whereIn('status', ['approved_by_leader', 'approved'])
                      ->orderBy('end_date', 'desc');
            }])
            ->get();

        $this->info("Ditemukan {$employees->count()} karyawan");

        $setToInactiveCount = 0;
        $setToActiveCount = 0;
        $noChangeCount = 0;

        foreach ($employees as $employee) {
            $activeLeave = $employee->leaveRequests->first(function($leave) use ($today) {
                return $today->between($leave->start_date, $leave->end_date);
            });

            if ($activeLeave) {
                if ($employee->active_status === true) {
                    if (!$isDryRun) {
                        $employee->update(['active_status' => false]);
                    }
                    $setToInactiveCount++;
                    $this->line("  ⊘ {$employee->name} - Status diubah menjadi Inactive (sedang cuti: {$activeLeave->start_date->format('d M Y')} - {$activeLeave->end_date->format('d M Y')})");
                } else {
                    $noChangeCount++;
                }
            } else {
                if ($employee->active_status === false) {
                    $recentEndedLeave = $employee->leaveRequests->first(function($leave) use ($today) {
                        return $leave->end_date->lt($today) && 
                               $leave->end_date->gte($today->copy()->subDays(30));
                    });

                    if ($recentEndedLeave || $employee->leaveRequests->isEmpty()) {
                        if (!$isDryRun) {
                            $employee->update(['active_status' => true]);
                        }
                        $setToActiveCount++;
                        $this->line("  ✓ {$employee->name} - Status diubah menjadi Active");
                    } else {
                        $noChangeCount++;
                    }
                } else {
                    $noChangeCount++;
                }
            }
        }

        $this->newLine();
        $this->info("Ringkasan:");
        $this->info("  - Diubah menjadi Inactive: {$setToInactiveCount}");
        $this->info("  - Diubah menjadi Active: {$setToActiveCount}");
        $this->info("  - Tidak ada perubahan: {$noChangeCount}");
        
        if ($isDryRun) {
            $this->warn("  - Ini adalah DRY RUN. Tidak ada perubahan data yang dilakukan.");
        } else {
            $this->info("  - Sinkronisasi status karyawan selesai!");
        }

        return Command::SUCCESS;
    }
}

