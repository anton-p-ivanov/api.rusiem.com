<?php
namespace App\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * Interface TranslatorInterface
 */
interface TranslatorInterface
{
    /**
     * @param string|null $locale
     *
     * @return TranslatorLangInterface
     */
    public function translate(string $locale = null): TranslatorLangInterface;

    /**
     * @param string $locale
     *
     * @return bool
     */
    public function hasTranslation(string $locale): bool;

    /**
     * @return Collection
     */
    public function getTranslations(): Collection;

    /**
     * @param array $translations
     *
     * @return self
     */
    public function setTranslations(array $translations): self;
//
//    /**
//     * @param TranslatorLangInterface $translation
//     *
//     * @return self
//     */
//    public function addTranslation(TranslatorLangInterface $translation): self;
}