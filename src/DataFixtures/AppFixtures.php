<?php

namespace App\DataFixtures;

use App\Entity\Clients;
use App\Entity\User;
use App\Entity\Projects;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Enum\ClientsStatusEnum;
use App\Entity\Deliverables;
use App\Enum\DelivrerablesStatusEnum;



class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }



    public const MAX_USERS = 10;
    public const MAX_CLIENTS = 10;
    public const MAX_PROJECTS = 10;
    public const MAX_DELIVERABLES = 10;

    public function load(ObjectManager $manager): void
    {

        $clients = [];
        for ($i = 0; $i < self::MAX_CLIENTS; $i++) {
            $client = new Clients();
            $client->setName(name: "Client {$i}");
            $client->setEmail(email: "test_{$i}@example.com");
            $client->setPhone(phone: "0123456789");
            $client->setAddress(address: "1 rue de la paix");
            $manager->persist(object: $client);
            $clients[] = $client;
        }

        for ($i = 0; $i < self::MAX_PROJECTS; $i++) {
            $project = new Projects();
            $project->setTitle(title: "Project {$i}");
            $project->setDescription(description: "Description of project {$i}");
            $project->setStatus(ClientsStatusEnum::IN_PROGRESS);
            $project->setStartDate(startDate: new \DateTime());
            $project->setEndDate(endDate: new \DateTime('+1 month'));
            $project->setClient($clients[array_rand($clients)]);
            $project->setBudget(budget: 1000);
            $manager->persist(object: $project);
            $projects[] = $project;
        }


        for ($i = 0; $i < self::MAX_DELIVERABLES; $i++) {
            $deliverable = new Deliverables();
            $deliverable->setName(name: "Deliverable {$i}");
            $deliverable->setDescription(Description: "Description of deliverable {$i}");
            $deliverable->setDeliveryDate(deliveryDate: new \DateTime());
            $deliverable->setProject($projects[array_rand($projects)]);
            $deliverable->setStatus(DelivrerablesStatusEnum::COMPLETED);
            $manager->persist(object: $deliverable);
        }

        for ($i = 0; $i < self::MAX_USERS; $i++) {
            $user = new User();
            $user->setEmail(email: "test_{$i}@example.com");
            $user->setUsername(username: "test_{$i}");
            $hashedpassword = $this->passwordHasher->hashPassword($user, 'motdepasse');
            $user->setPassword($hashedpassword);
            $users[] = $user;

            $manager->persist(object: $user);
        }

        $admin = new User();
        $admin->setEmail(email: "admin@example.com");
        $admin->setUsername(username: "admin");
        $hashedpassword = $this->passwordHasher->hashPassword($admin, 'motdepasse');
        $admin->setPassword($hashedpassword);
        $manager->persist($admin);

        $normal = new User();
        $normal->setEmail(email: "demo@example.com");
        $normal->setUsername(username: "demo");
        $hashedpassword = $this->passwordHasher->hashPassword($normal, 'motdepasse');
        $normal->setPassword($hashedpassword);
        $manager->persist($normal);

        $manager->flush();
    }



}
    