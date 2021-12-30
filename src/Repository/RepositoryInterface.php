<?php

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;

interface RepositoryInterface
{
    public function getQueryBuilder(): QueryBuilder;
    public function getLookupQueryBuilder(): QueryBuilder;
}