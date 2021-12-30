<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="site")
 * @ORM\Entity(repositoryClass="App\Repository\SiteRepository")
 */
class Site
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
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $title = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $url = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $email = '';

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default"=100, "unsigned"=true})
     */
    private int $sort = 100;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    private bool $isDefault = false;

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
     * @return Site
     */
    public function setTitle(string $title): Site
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Site
     */
    public function setUrl(string $url): Site
    {
        $this->url = $url;

        return $this;
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
     * @return Site
     */
    public function setEmail(string $email): Site
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     *
     * @return Site
     */
    public function setSort(int $sort): Site
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsDefault(): bool
    {
        return $this->isDefault;
    }

    /**
     * @param bool $isDefault
     *
     * @return Site
     */
    public function setIsDefault(bool $isDefault): Site
    {
        $this->isDefault = $isDefault;

        return $this;
    }
}
