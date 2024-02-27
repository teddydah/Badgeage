<?php

namespace App\DataFixtures\Data;

class UserData
{
    public static array $userData = [
        [
            'email' => 'john.doe@gmail.com',
            'roles' => 'ROLE_ADMIN',
            'password' => '$2y$13$gydYwHQ0igUJxYZTiRwIQ.Vi3.S8xLSt6tMY7H8IOVxrfQVCmLKxq'
        ],
        [
            'email' => 'john.doe@live.fr',
            'roles' => 'ROLE_USER',
            'password' => '$2y$13$gydYwHQ0igUJxYZTiRwIQ.Vi3.S8xLSt6tMY7H8IOVxrfQVCmLKxq'
        ]
    ];
}
