<?php

namespace App\Entity;

/**
 * Interface TranslatorLangInterface
 */
interface TranslatorLangInterface
{
    /**
     * @return Locale
     */
    public function getLocale(): Locale;

    /**
     * @param Locale $locale
     *
     * @return self
     */
    public function setLocale(Locale $locale): self;
}