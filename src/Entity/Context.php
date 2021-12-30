<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="context")
 * @ORM\Entity(repositoryClass="App\Repository\ContextRepository")
 */
class Context implements EntityInterface
{
    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string")
     */
    private string $slug = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $title = '';

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description = null;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=true})
     */
    private bool $isPublished = true;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Context
     */
    public function setTitle(string $title): Context
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsPublished(): bool
    {
        return $this->isPublished;
    }

    /**
     * @param bool $isPublished
     *
     * @return Context
     */
    public function setIsPublished(bool $isPublished): Context
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return Context
     */
    public function setSlug(string $slug): Context
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return Context
     */
    public function setDescription(?string $description): Context
    {
        $this->description = $description;

        return $this;
    }

}
