<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Developer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}
    public function load(ObjectManager $manager): void
    {
        // admin
        $admin = new User();
        $admin->setEmail('admin@gmail.com');
        $admin->setUuid(Uuid::v6());
        $admin->setPassword($this->hasher->hashPassword($admin, 'password'));
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        $manager->flush();

        // dev test
        $userDev = new User();
        $userDev->setEmail('dev@gmail.com');
        $userDev->setUuid(Uuid::v6());
        $userDev->setPassword($this->hasher->hashPassword($userDev, 'password'));
        $userDev->setRoles(['ROLE_DEV']);
        $manager->persist($userDev);
        $manager->flush();

        $developeur= new Developer();
        $developeur->setNom('John Doe');
        $developeur->setPrenom('John');
        $developeur->setMobile('098743345');
        $developeur->setVille('Rennes');
        $manager->persist($developeur);
        $manager->flush();

    }
}
