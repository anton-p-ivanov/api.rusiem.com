<?php

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class UserProvider
 *
 * @package App\Security
 */
class Provider implements UserProviderInterface
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * DeleteEntity constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $username
     *
     * @return UserInterface|null
     */
    public function loadUserByUsername(string $username): ?UserInterface
    {
        return $this->fetchUser($username);
    }

    /**
     * @param UserInterface $user
     *
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }

    /**
     * @param string $username
     *
     * @return User|null
     */
    private function fetchUser(string $username): ?User
    {
        $user = $this->entityManager
            ->getRepository(\App\Entity\User\User::class)
            ->findOneBy(['email' => $username]);

        if ($user) {
            $password = $salt = null;

            if ($user->getPasswords()->count()) {
                $userPassword = $user->getPasswords()->first();
                $password = $userPassword->getPassword();
                $salt = $userPassword->getSalt();
            }

            $result = new User();
            $result->setUsername($username);
            $result->setPassword($password);
            $result->setSalt($salt);

            return $result;
        }

        return null;
    }
}