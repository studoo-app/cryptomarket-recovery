<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{


    public function __construct(
        private readonly UserPasswordHasherInterface $passwordEncoder
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();

        foreach($users as $user) {
            $user->setPassword($this->passwordEncoder->hashPassword($user, $user->getPassword()));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
