<?php

namespace App\Repository;

use App\Entity\Context;

/**
 * Class ContextRepository
 */
class ContextRepository extends BaseRepository
{
    /**
     * @var string|null
     */
    protected ?string $entityClass = Context::class;
}
