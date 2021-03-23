<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateMasterUserTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_master_user_can_be_created()
    {
        $password = $this->faker->word;

        $this->artisan('make:master-user')
            ->expectsQuestion('Enter first name', $this->faker->firstName)
            ->expectsQuestion('Enter last name', $this->faker->lastName)
            ->expectsQuestion('Enter email', $this->faker->email)
            ->expectsQuestion('Enter password', $password)
            ->expectsQuestion('Confirm password', $password)
            ->expectsConfirmation('Are you sure you want to create a new master user?', 'yes')
            ->expectsOutput('Master user created successfully!');
    }

    public function test_master_user_cannot_be_created_if_the_passwords_do_not_match()
    {

        $this->artisan('make:master-user')
            ->expectsQuestion('Enter first name', $this->faker->firstName)
            ->expectsQuestion('Enter last name', $this->faker->lastName)
            ->expectsQuestion('Enter email', $this->faker->email)
            ->expectsQuestion('Enter password', 'password1')
            ->expectsQuestion('Confirm password', 'password2')
            ->expectsOutput('Passwords do not match!');
    }
}
