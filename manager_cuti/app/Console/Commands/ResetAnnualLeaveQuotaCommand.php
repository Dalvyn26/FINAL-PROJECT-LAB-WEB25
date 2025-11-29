<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ResetAnnualLeaveQuotaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:reset-quota {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset annual leave quota for all eligible employees to 12 days (runs on January 1st)';

    /**
     * Default annual leave quota
     */
    const DEFAULT_QUOTA = 12;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');
        
        $this->info('Starting annual leave quota reset process...');
        
        if ($isDryRun) {
            $this->warn('DRY RUN MODE: No changes will be made');
        }

        // Get all active users
        $users = User::where('active_status', true)
            ->whereNotNull('join_date')
            ->get();

        $this->info("Found {$users->count()} active users with join dates");

        $resetCount = 0;
        $skippedCount = 0;
        $currentYear = Carbon::now()->year;
        $januaryFirst = Carbon::create($currentYear, 1, 1);

        foreach ($users as $user) {
            $joinDate = Carbon::parse($user->join_date);
            $eligibilityDate = $januaryFirst->copy()->subYear();
            
            if ($joinDate->lte($eligibilityDate)) {
                if (!$isDryRun) {
                    $user->update(['leave_quota' => self::DEFAULT_QUOTA]);
                }
                
                $resetCount++;
                $this->line("  ✓ {$user->name} ({$user->email}) - Quota reset to " . self::DEFAULT_QUOTA . " days");
            } else {
                $skippedCount++;
                $this->line("  ⊘ {$user->name} ({$user->email}) - Not eligible yet (joined: {$joinDate->format('Y-m-d')})");
            }
        }

        $this->newLine();
        $this->info("Summary:");
        $this->info("  - Users reset: {$resetCount}");
        $this->info("  - Users skipped: {$skippedCount}");
        
        if ($isDryRun) {
            $this->warn("  - This was a DRY RUN. No actual changes were made.");
        } else {
            $this->info("  - Annual leave quota reset completed successfully!");
        }

        return Command::SUCCESS;
    }
}