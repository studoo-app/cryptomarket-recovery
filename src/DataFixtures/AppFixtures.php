<?php

namespace App\DataFixtures;

use App\Entity\Asset;
use App\Entity\Currency;
use App\Entity\User;
use App\Entity\Wallet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
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
        $faker = Factory::create("fr_FR");

        $this->loadUsers($manager, $faker);
        $this->loadCurrencies($manager, $faker);
        $this->loadAssets($manager, $faker);
    }

    private function createUser(
        ObjectManager $manager,
        string $email,
        string $password,
        bool $isAdmin,
        Generator $faker
    ): void
    {
        $user = new User();
        $user->setEmail($email);
        $user->setRoles($isAdmin ? ['ROLE_ADMIN'] : ['ROLE_USER']);
        $user->setPassword($this->passwordEncoder->hashPassword($user, $password));
        if(!$isAdmin){
            $user->setWallet(new Wallet($faker->sha256()));
        }
        $manager->persist($user);
    }

    private function loadUsers(ObjectManager $manager, Generator $faker): void
    {
        $this->createUser($manager, 'admin@cryptomarket.dev', 'admin', true,$faker);
        $this->createUser($manager, 'user@cryptomarket.dev', 'user', false,$faker);

        for ($i = 0; $i < 50; $i++) {
            $this->createUser($manager, $faker->email, $faker->lexify('?????????'), false,$faker);
        }

        $manager->flush();
    }

    private function loadCurrencies(ObjectManager $manager, Generator $faker){

        $data = file_get_contents('https://api.coincap.io/v2/assets');

        $currencies = json_decode($data, true);

        foreach($currencies['data'] as $curData){
            $currency = new Currency();
            $currency->setName($curData['name']);
            $currency->setShortName($curData['symbol']);
            $currency->setValue($curData['priceUsd']);
            $manager->persist($currency);
        }

        $manager->flush();
    }

    private function loadAssets(ObjectManager $manager, Generator $faker){

        $users = $manager->getRepository(User::class)->findAll();
        $currencies = $manager->getRepository(Currency::class)->findAll();

        foreach($users as $user){
            if($user->getRoles() === ['ROLE_ADMIN']){
                continue;
            }
            for($i = 0; $i < random_int(1,15); $i++){
                $currency = $currencies[array_rand($currencies)];
                $asset = new Asset();
                $asset->setCurrency($currency);
                $asset->setVolume($faker->numberBetween(0, 100));
                $asset->setWallet($user->getWallet());
                $manager->persist($asset);
            }
        }

        $manager->flush();
    }
}
