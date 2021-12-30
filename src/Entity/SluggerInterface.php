<?php
namespace App\Entity;

/**
 * Interface SluggerInterface
 */
interface SluggerInterface
{
    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return string|null
     */
    public function getSlug(): ?string;

    /**
     * @param string|null $slug
     *
     * @return $this
     */
    public function setSlug(?string $slug): self;
}