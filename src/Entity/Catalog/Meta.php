<?php

namespace App\Entity\Catalog;

use App\Entity\EntityInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="catalog_meta")
 * @ORM\Entity()
 */
class Meta implements EntityInterface
{
    /**
     * @var string|null
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private ?string $uuid = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default"="name"})
     */
    private string $property = 'name';

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $name = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $content = '';

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     *
     * @return $this
     */
    public function setUuid(string $uuid): Meta
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @param string $property
     *
     * @return Meta
     */
    public function setProperty(string $property): Meta
    {
        $this->property = $property;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Meta
     */
    public function setName(string $name): Meta
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return Meta
     */
    public function setContent(string $content): Meta
    {
        $this->content = $content;

        return $this;
    }

}
