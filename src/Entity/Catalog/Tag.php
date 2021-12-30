<?php

namespace App\Entity\Catalog;

use App\Entity\Context;
use App\Entity\EntityInterface;
use App\Entity\SluggerInterface;
use App\Entity\SluggerTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="catalog_tag")
 * @ORM\Entity(repositoryClass="App\Repository\Catalog\TagRepository")
 */
class Tag implements EntityInterface, SluggerInterface
{
    use SluggerTrait;

    /**
     * @var string|null
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private ?string $uuid = null;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $title = '';

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=true})
     */
    private bool $isPublished = true;

    /**
     * @var Context|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Context")
     * @ORM\JoinColumn(name="context", referencedColumnName="slug")
     */
    private ?Context $context = null;

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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Tag
     */
    public function setTitle(string $title): Tag
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
     * @return Tag
     */
    public function setIsPublished(bool $isPublished): Tag
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * @return Context|null
     */
    public function getContext(): ?Context
    {
        return $this->context;
    }

    /**
     * @param Context $context
     *
     * @return Tag
     */
    public function setContext(Context $context): Tag
    {
        $this->context = $context;

        return $this;
    }
}
