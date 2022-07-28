<?php

namespace App\Tests\Unit;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoleAdminTest extends WebTestCase
{
    /**
     * @dataProvider hasAccessUrls
     */
    public function testHasAccess($method, $url): void
    {
        $client = self::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneByEmail('renaud.l@selfsignal.fr'); // ROLE_SUPER_ADMIN

        $client->loginUser($user);

        $client->request($method, $url);

        $this->assertResponseIsSuccessful();
    }

    public function hasAccessUrls(): \Generator
    {
        // User
        yield ['GET', '/admin/user/browse'];
        yield ['GET', '/admin/user/read/1'];
        yield ['GET', '/admin/user/edit/1'];
        yield ['GET', '/admin/user/add'];
        yield ['POST', '/admin/user/edit/1'];
        yield ['POST', '/admin/user/add'];
    }

    /**
     * @dataProvider accessDeniedUrls
     */
    public function testAccessDeniedUrls($method, $url): void
    {
        $client = self::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneByEmail('ellylldhan@protonmail.com'); // ROLE_ADMIN

        $client->loginUser($user);

        $client->request($method, $url);

        $this->assertResponseStatusCodeSame(403);
    }

    public function accessDeniedUrls(): \Generator
    {
        // User
        yield ['GET', '/admin/user/browse'];
        yield ['GET', '/admin/user/read/1'];
        yield ['GET', '/admin/user/edit/1'];
        yield ['GET', '/admin/user/add'];
        yield ['GET', '/admin/user/delete/1'];
        yield ['POST', '/admin/user/edit/1'];
        yield ['POST', '/admin/user/add'];
    }
}
