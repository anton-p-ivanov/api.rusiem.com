<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 *
 * @package App\Security
 */
class User implements UserInterface, EquatableInterface, \Serializable
{
    /**
     * @var string
     */
    private string $username = '';

    /**
     * @var string
     */
    private string $password = '';

    /**
     * @var string
     */
    private string $salt = '';

    /**
     * @var array
     */
    private array $roles = [];

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getSalt(): string
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     * @return void
     */
    public function setSalt(string $salt): void
    {
        $this->salt = $salt;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return void
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * To be implemented
     */
    public function eraseCredentials()
    {
        /* to be implemented */
    }

    /**
     * @param UserInterface $user
     *
     * @return bool
     */
    public function isEqualTo(UserInterface $user): bool
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize(): string
    {
        return serialize(
            [
                $this->username,
                $this->roles,
            ]
        );
    }

    /**
     * @param $serialized
     *
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized): void
    {
        list (
            $this->username,
            $this->roles
            ) = unserialize($serialized);
    }

}