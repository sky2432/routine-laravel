<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_user()
    {
        $user = new User;
        $user->name = 'そら';
        $user->email = 'user1@test.com';
        $user->password = Hash::make(1234);
        $user->save();

        $this->assertDatabaseCount('users', 1);
    }
}
