<?php

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteUnverifiedUsersWeekly extends TestCase
{
    use RefreshDatabase;

    public function testDeleteUnverifiedUsersIfNoUserFound()
    {
        $this->artisan('delete-weekly:unverified-users')
            ->expectsOutput('No User found!')
            ->assertExitCode(1);
    }

    public function testDeleteUnverifiedUsersWithNoAsReply()
    {
        $this->withoutExceptionHandling();
        User::factory()->create([
            'created_at' => today()->subWeek()
        ]);

        $this->artisan('delete-weekly:unverified-users')
            ->expectsConfirmation('Are you sure you want to delete all users fetched?', 'no')
            ->assertExitCode(1);
    }

    public function testDeleteUnverifiedUsersWithYesAsReply()
    {
        $this->withoutExceptionHandling();
        User::factory()->create([
            'created_at' => today()->subWeek()
        ]);

        $this->artisan('delete-weekly:unverified-users')
            ->expectsConfirmation('Are you sure you want to delete all users fetched?', 'yes')
            ->expectsOutput("Deleted all Unverified users");
    }
}
