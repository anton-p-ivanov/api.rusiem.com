<?php

namespace App\Repository;

use App\Entity\Site;

/**
 * Class SiteRepository
 */
class SiteRepository extends BaseRepository
{
    /**
     * @var string|null
     */
    protected ?string $entityClass = Site::class;
}
