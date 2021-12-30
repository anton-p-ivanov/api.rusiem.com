<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\UnicodeString;

/**
 * Trait SluggerTrait
 */
trait SluggerTrait
{
    /**
     * @var string|null
     *
     * @ORM\Column(type="string", unique=true)
     */
    private ?string $slug = null;

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string|null $slug
     *
     * @return mixed
     */
    public function setSlug(?string $slug): self
    {
        $this->slug = (new UnicodeString($slug))->lower();

        return $this;
    }
}