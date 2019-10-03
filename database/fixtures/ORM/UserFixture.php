<?php

declare(strict_types=1);

namespace App\DataFixtures\ORM;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserFixture extends Fixture implements OrderedFixtureInterface
{
    private static $USERS_CATALOG = [
        'user1@example.com' => ['Secret@1', 'Foo', 'Bar', true, false, User::STATUS_ACTIVE],
        'user2@example.com' => ['Secret@2', 'Bar', 'Baz', true, true, User::STATUS_ACTIVE],
        'user3@example.com' => ['Secret@3', 'Baz', 'Foo', true, false, User::STATUS_INACTIVE],
    ];

    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $userCount = 1;
        foreach (self::$USERS_CATALOG as $email => $userData) {
            list($password, $firstname, $lastname, $isConditionsAccepted, $isOptinCommercial, $status) = $userData;

            $user = new User();

            $user
                ->setEmail($email)
                ->setPassword($this->userPasswordEncoder->encodePassword($user, $password))
                ->setFirstname($firstname)
                ->setLastname($lastname)
                ->setConditionsAccepted($isConditionsAccepted)
                ->setOptinCommercial($isOptinCommercial)
                ->setStatus($status)
            ;

            $manager->persist($user);
            $this->addReference(sprintf('User_%d', $userCount), $user);

            $userCount++;
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }
}
