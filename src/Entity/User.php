<?php

namespace App\Entity;

use App\Entity\Workflow\WorkflowInterface;
use App\Entity\Workflow\WorkflowTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="`user`")
 * @ORM\Entity()
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface, WorkflowInterface
{
    use WorkflowTrait;

    /**
     * @var string|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private ?string $uuid = null;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $lastName = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $middleName = '';

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    private bool $isActive = false;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var string|null The hashed password
     *
     * @ORM\Column(type="string")
     */
    private ?string $password = null;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $lastLogin = null;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\UserParam", indexBy="param", mappedBy="user", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private Collection $params;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->params = new ArrayCollection();
    }

    /**
     * User clone.
     */
    public function __clone()
    {
        $this->workflow = null;
    }

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     *
     * @return $this
     */
    public function setRoles(array $roles): User
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName(string $firstName): User
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName(string $lastName): User
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getMiddleName(): string
    {
        return $this->middleName;
    }

    /**
     * @param string $middleName
     *
     * @return User
     */
    public function setMiddleName(string $middleName): User
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTimeInterface|null $lastLogin
     *
     * @return User
     */
    public function setLastLogin(?\DateTimeInterface $lastLogin): User
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     *
     * @return User
     */
    public function setIsActive(bool $isActive): User
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return trim(implode(' ', [
            $this->firstName,
            $this->lastName
        ]));
    }

    /**
     * @return Collection
     */
    public function getParams(): Collection
    {
        return $this->params;
    }

    /**
     * @param Collection $params
     *
     * @return User
     */
    public function setParams(Collection $params): User
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @param UserParam $param
     */
    public function addParam(UserParam $param)
    {
        if (!$this->params->contains($param)) {
            $this->params->add($param->setUser($this));
        }
    }
}
