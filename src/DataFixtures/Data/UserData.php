<?php

namespace App\DataFixtures\Data;

class UserData
{
    public static array $userData = [
        [
            'email' => 'renaud.l@selfsignal.fr',
            'roles' => 'ROLE_SUPER_ADMIN',
            'password' => '$2y$13$gydYwHQ0igUJxYZTiRwIQ.Vi3.S8xLSt6tMY7H8IOVxrfQVCmLKxq'
        ],
        [
            'email' => 'ellylldhan@protonmail.com',
            'roles' => 'ROLE_ADMIN',
            'password' => '$2y$13$gydYwHQ0igUJxYZTiRwIQ.Vi3.S8xLSt6tMY7H8IOVxrfQVCmLKxq'
        ]
    ];
}