<?php

namespace App\Repository\Vacancy;

use App\Entity\Vacancy\Group;
use App\Repository\BaseRepository;

/**
 * Class GroupRepository
 */
class GroupRepository extends BaseRepository
{
    /**
     * @var string|null
     */
    protected ?string $entityClass = Group::class;
}
