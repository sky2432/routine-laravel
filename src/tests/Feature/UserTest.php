<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function test_update_password()
    {
        $user = User::factory()->create();

        $current_password = 1234;
        $new_password = 12345;

        $isTrue = Hash::check($current_password, $user->password);
        $this->assertTrue($isTrue);

        $payload = [
            'password' => $current_password,
            'new_password' => $new_password
        ];

        $response = $this->put("api/users/" . $user->id . "/password", $payload);
        $response->assertOk();

        $updated_user = User::find($user->id);
        $isSame = Hash::check($new_password, $updated_user->password);
        $this->assertTrue($isSame);
    }
}
