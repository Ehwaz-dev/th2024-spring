<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegistrationRequest;
use App\Http\Resources\UserResource;
use Illuminate\Auth\AuthenticationException;
use App\Services\UserService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(
        readonly protected UserService $userService,
    )
    {
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt([
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ])) {
            throw new AuthenticationException(
                'User not found',
            );
        }

        return JsonResource::make([
            'access_token' => $this->userService->createApiToken(Auth::user())->plainTextToken,
        ]);
    }

    public function register(RegistrationRequest $request)
    {

        $user = $this->userService->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ]);

        return UserResource::make($user);
    }
}
