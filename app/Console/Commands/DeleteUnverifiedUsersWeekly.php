<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeleteUnverifiedUsersWeekly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete-weekly:unverified-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all unverified Users on a weekly basis';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::whereIsVerified(false)
            ->where(function ($query) {
                $query->whereDate('created_at', today()->subWeek());
            });

        if (!$users->count()) {
            $this->error("No User found!");
            return 1;
        }

        $this->info("{$users->count()} unverified user(s) fetched");

        if ($this->confirm("Are you sure you want to delete all users fetched?")) {
            foreach ($users->cursor() as $user) {
                $this->info("Deleting all {$users->count()} users...");

                $user->delete();

                $this->info("Deleted all Unverified users");
            }
        }

        return 1;
    }
}
