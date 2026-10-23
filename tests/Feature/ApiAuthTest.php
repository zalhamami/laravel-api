<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_user_can_log_in_and_call_protected_endpoint_with_issued_token()
    {
        $this->artisan('passport:keys', ['--force' => true]);
        app(ClientRepository::class)->createPersonalAccessClient(null, 'Test Personal Access Client', 'http://localhost');
        $user = User::factory()->create();

        $login = $this->postJson('/api/oauth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $login->assertOk()->assertJsonStructure(['access_token', 'token_type', 'expires_at']);

        $this->app['auth']->forgetGuards();

        $this->withToken($login->json('access_token'))
            ->getJson('/api/me/user')
            ->assertOk()
            ->assertJsonPath('email', $user->email)
            ->assertHeader('X-Application-Name', config('app.name'));
    }

    public function test_protected_endpoint_rejects_requests_without_token()
    {
        $this->getJson('/api/me/user')
            ->assertStatus(401)
            ->assertJson(['code' => 401, 'message' => 'Unauthorized client.']);
    }

    public function test_cors_preflight_is_answered_by_builtin_middleware()
    {
        $this->call('OPTIONS', '/api/type', [], [], [], [
            'HTTP_ORIGIN' => 'https://client.example',
            'HTTP_ACCESS_CONTROL_REQUEST_METHOD' => 'POST',
        ])
            ->assertNoContent()
            ->assertHeader('Access-Control-Allow-Origin', '*');
    }
}
