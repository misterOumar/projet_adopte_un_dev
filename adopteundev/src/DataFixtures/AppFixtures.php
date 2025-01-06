<?php

namespace App\DataFixtures;

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
        // $product = new Product();
        // $manager->persist($product);
        $admin = new User();
        $admin->setEmail('admin@gmail.com');
        $admin->setUuid(Uuid::v6());
        $admin->setPassword($this->hasher->hashPassword($admin, 'password'));
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        $manager->flush();

        $manager->flush();
    }
}
