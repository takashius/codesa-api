<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setup(): void
    {
        parent::setup();
        $this->seed(UserSeeder::class);
    }

    /** @test */
    public function register_a_new_user(): void
    {
        $this->withoutDeprecationHandling();
        $credentials = [
            'name' => 'example test',
            'email' => 'example2@test.com',
            'password' => '123456789',
            'password_confirmation' => '123456789'
        ];
        $response = $this->post("{$this->apiBase}/auth/register", $credentials);
        $response->assertStatus(201);
    }

    /** @test */
    public function a_existing_user_can_login(): void
    {
        // $this->withoutDeprecationHandling();
        $credentials = ['email' => 'example2@test.com', 'password' => '123456789'];
        $response = $this->post("{$this->apiBase}/auth/login", $credentials);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['token']]);
    }

    /** @test */
    public function a_non_existing_user_cant_login(): void
    {
        $credentials = ['email' => 'example@noexist.com', 'password' => '123456789'];
        $response = $this->post("{$this->apiBase}/auth/login", $credentials);

        $response->assertStatus(400);
        $response->assertJsonStructure(['data' => ['token']]);
    }

    /** @test */
    public function email_must_be_required(): void
    {
        $credentials = ['password' => '123456789'];
        $response = $this->post("{$this->apiBase}/auth/login", $credentials);

        $response->assertStatus(400);
        $response->assertJsonStructure(['data' => ['token']]);
    }

    /** @test */
    public function password_must_be_required(): void
    {
        $credentials = ['email' => 'example@noexist.com'];
        $response = $this->post("{$this->apiBase}/auth/login", $credentials);

        $response->assertStatus(400);
        $response->assertJsonStructure(['data' => ['token']]);
    }

    /** @test */
    public function a_user_can_logout()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $response = $this->postJson('/auth/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Successfully logged out']);
    }

    /** @test */
    public function a_user_can_refresh_token()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $response = $this->postJson('/auth/refresh', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('access_token', $response->json());
    }

    /** @test */
    public function a_user_can_get_their_profile()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $response = $this->postJson('/auth/me', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'email' => $user->email,
        ]);
    }

    /** @test */
    public function a_user_can_send_verification_email()
    {
        $user = User::factory()->create([
            'email_verified_at' => null
        ]);
        $token = auth()->login($user);

        $response = $this->postJson('/auth/send-verification-email', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Verification email sent successfully']);
    }
}
