<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_signup()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->make();
        $response = $this->post('api/auth/register', [
            'email' => $user->email,
            'password' => $user->password,
            'fullname' => $user->fullname,
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'message' => 'Account created'
        ]);
    }

    public function test_create_pin_code()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create([
            'status' => User::DISABLE
        ]);

        Customer::factory()
            ->for($user)
            ->create();

        $response = $this->post('api/auth/create-pin', [
            'email' => $user->email,
            'pin_code' => '123456',
            'pin_code_confirmation' => '123456',
            'phone_no' => '08067865933',
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'message' => 'Pin code created successfully'
        ]);
    }

    public function test_user_login()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->make([
            'status' => User::ENABLE
        ])->toArray();

        $password = '@Password2';
        $user['password'] = bcrypt($password);

        $newUser = User::create($user);

        $response = $this->post('api/auth/login', [
            'email' => $newUser->email,
            'password' => $password,
            'remember_me' => true
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'login successful'
        ]);
    }
}
