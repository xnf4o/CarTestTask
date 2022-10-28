<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testLogin()
    {
        $baseUrl = Config::get('app.url') . route('login');
        $email = Config::get('api.apiEmail');
        $password = Config::get('api.apiPassword');

        $response = $this->json('POST', $baseUrl . '/', [
            'email' => $email,
            'password' => $password
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token', 'token_type', 'expires_in'
            ]);
    }

    public function testLogout()
    {
        $user = User::where('email', Config::get('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = Config::get('app.url') . '/api/logout?token=' . $token;

        $response = $this->json('POST', $baseUrl, []);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'message' => 'Successfully logged out'
            ]);
    }

    public function testRefresh()
    {
        $user = User::where('email', Config::get('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = Config::get('app.url') . '/api/refresh?token=' . $token;

        $response = $this->json('POST', $baseUrl, []);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token', 'token_type', 'expires_in'
            ]);
    }

    public function testGetCars()
    {
        $baseUrl = Config::get('app.url') . '/api/cars';

        $response = $this->json('GET', $baseUrl . '/', []);

        $response->assertStatus(200);
    }

    public function testGetAvailableCars()
    {
        $baseUrl = Config::get('app.url') . '/api/cars';

        $response = $this->json('GET', $baseUrl . '/available', []);

        $response->assertStatus(200);
    }

    public function testGetUserCars()
    {
        $user = User::where('email', Config::get('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = Config::get('app.url') . '/api/cars';

        $response = $this->json('GET', $baseUrl . '/user?token=' . $token, []);

        $response->assertStatus(200);
    }

    public function testRentCar()
    {
        $user = User::where('email', Config::get('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = Config::get('app.url') . '/api/cars';

        $response = $this->json('POST', $baseUrl . '/rent/1?token=' . $token, []);

        $response->assertStatus(200);
    }

    public function testReturnCar()
    {
        $user = User::where('email', Config::get('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = Config::get('app.url') . '/api/cars';

        $response = $this->json('POST', $baseUrl . '/return?token=' . $token, []);

        $response->assertStatus(200);
    }

    public function testGetUserCarsHistory()
    {
        $user = User::where('email', Config::get('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = Config::get('app.url') . '/api/cars';

        $response = $this->json('GET', $baseUrl . '/history?token=' . $token, []);

        $response->assertStatus(200);
    }
}
