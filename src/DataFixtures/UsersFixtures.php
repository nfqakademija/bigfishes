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
            $user->setName($this->faker->firstName);
            $user->setEmail(sprintf('name%d@mail.com', $i));
            $user->setStatus(1);

            $user->setPassword($this->encoder->encodePassword(
                $user,
                '123456'
            ));

            $manager->persist($user);
        }

        for ($i = 0; $i < 3; $i++) {
            $admin = new User();
            $admin->setEmail(sprintf('admin%d@mail.com', $i));
            $admin->setName($this->faker->firstName);
            $admin->setRoles(['ROLE_ADMIN']);
            $admin->setPassword($this->encoder->encodePassword(
                $admin,
                'admin'
            ));
            $manager->persist($admin);
        }

        for ($i = 0; $i < 5; $i++) {
            $abonent = new User();
            $abonent->setEmail(sprintf('abonent%d@mail.com', $i));
            $abonent->setName($this->faker->firstName);
            $abonent->setRoles(['ROLE_ABONENT']);
            $abonent->setPassword($this->encoder->encodePassword(
                $abonent,
                'abonent'
            ));
            $manager->persist($abonent);
        }

        $manager->flush();
    }
}
