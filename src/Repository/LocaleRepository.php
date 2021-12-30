<?php

namespace App\Repository;

use App\Entity\Locale;

/**
 * Class LocaleRepository
 */
class LocaleRepository extends BaseRepository
{
    /**
     * @var string|null
     */
    protected ?string $entityClass = Locale::class;
}
