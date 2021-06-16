<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User(
            Uuid::uuid4(),
            'gerardo@latteandcode.com'
        );
        $password = $this->userPasswordEncoder->encodePassword(
            $user,
            'hola1234'
        );
        $user->setPassword($password);
        $manager->persist($user);
        $manager->flush();
    }
}
