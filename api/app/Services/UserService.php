<?php
namespace App\Services;

use App\Models\User;
use Laravel\Sanctum\NewAccessToken;

const USER_PUBLIC_API_TOKEN_NAME = "API";
const USER_PUBLIC_API_TOKEN_ABILITIES = ["*"];

class UserService
{

    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * @inheritDoc
     */
    public function createApiToken(User $user): NewAccessToken
    {
        return $user->createToken(USER_PUBLIC_API_TOKEN_NAME, USER_PUBLIC_API_TOKEN_ABILITIES);
    }
}
