<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Trait TranslatorTrait
 */
trait TranslatorTrait
{
    /**
     * @var Collection
     */
    private Collection $translations;

    /**
     * @param string|null $locale
     *
     * @return TranslatorLangInterface
     */
    public function translate(string $locale = null): TranslatorLangInterface
    {
        return $this->translations->get($locale);
    }

    /**
     * @param string $locale
     *
     * @return bool
     */
    public function hasTranslation(string $locale): bool
    {
        return $this->translations->containsKey($locale);
    }

    /**
     * @return Collection
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    /**
     * @param array $translations
     *
     * @return self
     */
    public function setTranslations(array $translations): self
    {
        $this->translations = new ArrayCollection($translations);

        return $this;
    }
}