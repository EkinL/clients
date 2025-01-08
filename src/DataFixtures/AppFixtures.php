<?php

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public const MAX_USERS = 10;

    public function load(ObjectManager $manager): void
    {
        $users = [];

        $this->createUsers($manager, $users);

        $manager->flush();
    }

    protected function createUsers(ObjectManager $manager, array &$users): void
    {
        for ($i = 0; $i < self::MAX_USERS; $i++) {
            $user = new User();
            $user->setEmail(email: "test_{$i}@example.com");
            $user->setUsername(username: "test_{$i}");
            $user->setPassword(password: 'motdepasse');
            $user->setAccountStatus(UserStatusEnum::ACTIVE);
            $users[] = $user;

            $manager->persist(object: $user);
        }

        $admin = new User();
        $admin->setEmail(email: "admin@example.com");
        $admin->setUsername(username: "admin");
        $admin->setPassword('motdepasse');
        $admin->setAccountStatus(UserStatusEnum::ACTIVE);
        $admin->addRole('ROLE_ADMIN');
        $manager->persist($admin);

        $normal = new User();
        $normal->setEmail(email: "demo@example.com");
        $normal->setUsername(username: "demo");
        $normal->setPassword('motdepasse');
        $normal->setAccountStatus(UserStatusEnum::ACTIVE);
        $normal->addRole('ROLE_USER');
        $manager->persist($normal);
    }
}