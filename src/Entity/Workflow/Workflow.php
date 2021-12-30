<?php

namespace App\Entity\Workflow;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="workflow")
 * @ORM\Entity()
 */
class Workflow
{
    /**
     * @var string|null
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private ?string $uuid = null;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    private bool $isDeleted = false;

    /**
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="uuid", nullable=true)
     */
    private ?User $createdBy = null;

    /**
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="updated_by", referencedColumnName="uuid", nullable=true)
     */
    private ?User $updatedBy = null;

    /**
     * Workflow constructor.
     */
    public function __construct()
    {
        $defaults = [
            'createdAt' => new \DateTime(),
            'updatedAt' => new \DateTime(),
        ];

        foreach ($defaults as $property => $value) {
            $this->$property = $value;
        }
    }

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface|null $createdAt
     *
     * @return Workflow
     */
    public function setCreatedAt(?\DateTimeInterface $createdAt): Workflow
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeInterface|null $updatedAt
     *
     * @return Workflow
     */
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): Workflow
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    /**
     * @param User|null $createdBy
     *
     * @return Workflow
     */
    public function setCreatedBy(?User $createdBy): Workflow
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    /**
     * @param User|null $updatedBy
     *
     * @return Workflow
     */
    public function setUpdatedBy(?User $updatedBy): Workflow
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsDeleted(): bool
    {
        return $this->isDeleted;
    }

    /**
     * @param bool $isDeleted
     *
     * @return Workflow
     */
    public function setIsDeleted(bool $isDeleted): Workflow
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

}
