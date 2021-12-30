<?php

namespace App\Repository\Form;

use App\Entity\Form\Status;
use App\Entity\Form\StatusLang;
use App\Repository\BaseRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * Class StatusRepository
 */
class StatusRepository extends BaseRepository
{
    /**
     * @var string|null
     */
    protected ?string $entityClass = Status::class;

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        $builder = parent::getQueryBuilder();

        $builder
            ->join(StatusLang::class, 't10n', Join::WITH, 't = t10n.status')
            ->andWhere($builder->expr()->eq('t10n.locale', ':locale'))
            ->setParameter(':locale', $this->request->headers->get('Accept-Language', 'en'));

        $builder
            ->leftJoin('t.form', 'f')
            ->andWhere($builder->expr()->eq('f.uuid', ':form'))
            ->setParameter(':form', $this->request->get('uuid'));

        return $builder;
    }

    /**
     * @param QueryBuilder $builder
     * @param string $sortBy
     * @param string $sortOrder
     *
     * @return void
     */
    protected function orderByTitle(QueryBuilder $builder, string $sortBy, string $sortOrder)
    {
        $builder->addOrderBy("t10n.$sortBy", $sortOrder);
    }
}
