<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Workflow\Workflow;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserFixtures
 */
class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $hasher;

    /**
     * @param UserPasswordHasherInterface $hasher
     */
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setEmail('developer@rusiem.com')
            ->setFirstName('Developer')
            ->setIsActive(true)
            ->setWorkflow(new Workflow())
            ->setRoles(['ROLE_ADMIN']);

        $user->setPassword($this->hasher->hashPassword($user, 'P@ssw0rd'));

        $manager->persist($user);
        $manager->flush();
    }
}
