<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersFixtures extends Fixture
{
    private $encoder;
    private $faker;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $name = $this->faker->firstName;
            $user->setName($name);
            $user->setEmail($name.'@mail.com');
            $user->setRoles(['ROLE_USER']);

            $password = $this->encoder->encodePassword($user, '123456');
            $user->setPassword($password);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
