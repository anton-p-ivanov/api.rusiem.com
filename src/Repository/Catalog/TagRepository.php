<?php

namespace App\Repository\Catalog;

use App\Entity\Catalog\Tag;
use App\Repository\BaseRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class TagRepository
 */
class TagRepository extends BaseRepository
{
    /**
     * @var string|null
     */
    protected ?string $entityClass = Tag::class;

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        $builder = parent::getQueryBuilder();

        if ($slug = $this->request->query->get('context')) {
            $builder
                ->leftJoin('t.context', 'c')
                ->andWhere($builder->expr()->like('c.slug', ':context'))
                ->setParameter(':context', $slug);
        }

        return $builder;
    }

    /**
     * @return QueryBuilder
     */
    public function getLookupQueryBuilder(): QueryBuilder
    {
        $builder = parent::getLookupQueryBuilder();

        if ($slug = $this->request->query->get('context')) {
            $builder
                ->leftJoin('t.context', 'c')
                ->andWhere($builder->expr()->like('c.slug', ':context'))
                ->setParameter(':context', $slug);
        }

        return $builder;
    }
}
