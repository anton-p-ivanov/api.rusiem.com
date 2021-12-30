<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_param")
 * @ORM\Entity()
 */
class UserParam
{
    const AVAILABLE_PARAMS = [
        "content.news.filter"
    ];

    /**
     * @var User
     *
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="params")
     * @ORM\JoinColumn(name="user_uuid", referencedColumnName="uuid")
     */
    public User $user;

    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    public string $param;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    public array $value = [];

    /**
     * @return string
     */
    public function getParam(): string
    {
        return $this->param;
    }

    /**
     * @param string $param
     *
     * @return UserParam
     */
    public function setParam(string $param): UserParam
    {
        $this->param = $param;

        return $this;
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->value;
    }

    /**
     * @param array $value
     *
     * @return UserParam
     */
    public function setValue(array $value): UserParam
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return UserParam
     */
    public function setUser(User $user): UserParam
    {
        $this->user = $user;

        return $this;
    }

}
