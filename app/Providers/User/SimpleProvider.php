<?php

namespace App\Providers\User;

use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class SimpleProvider implements UserProvider
{

    private GenericUser $user;

    /**
     * @param GenericUser $user
     */
    public function __construct()
    {
        $this->user = new GenericUser([
            "id" => "khannedy",
            "name" => "khannedy",
            "token" => "secret",
        ]);
    }

    public function retrieveByCredentials(array $credentials)
    {
        if($credentials["token"] == $this->user->token) {
            return $this->user;
        }

        return null;
    }


    public function retrieveById($identifier)
    {
        // TODO: Implement retrieveById() method.
    }

    public function retrieveByToken($identifier, $token)
    {
        // TODO: Implement retrieveByToken() method.
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // TODO: Implement updateRememberToken() method.
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // TODO: Implement validateCredentials() method.
    }

}
