<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Truncate only the users table
        DB::table('users')->truncate();
        DB::table('vaccine_schedules')->truncate();
    }

    /**
     * Test user registration with valid data.
     *
     * @return void
     */
    public function testUserCanRegisterSuccessfully()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'nid' => '123456',
            'phone' => '8801234567890',
            'vaccine_center_id' => '1'
        ];

        $response = $this->postJson(route('user.register'), $userData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'User successfully registered',
                'data' => [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'nid' => $userData['nid'],
                    'phone' => $userData['phone'],
                    'vaccine_center_id' => $userData['vaccine_center_id'],

                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
        ]);
    }

}
