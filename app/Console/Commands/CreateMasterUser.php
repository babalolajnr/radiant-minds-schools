<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateMasterUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:master-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a master user';

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
        $firstName = $this->ask('Enter first name');
        $lastName = $this->ask('Enter last name');
        $email = $this->ask('Enter email');
        $password = $this->secret('Enter password');
        $password2 = $this->secret('Confirm password');

        if ($password != $password2) {
            $this->error("Passwords do not match!");
            return 1;
        }

        if ($this->confirm('Are you sure you want to create a new master user?')) {
            $userData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => bcrypt($password)
            ];

            $user = User::create($userData);
            $user->is_verified = true;
            $user->user_type = 'master';
            $user->is_active = true;
            $user->email_verified_at = now();

            $user->save();
            $this->info('Master user created successfully!');
        }

        return 1;
    }
}
