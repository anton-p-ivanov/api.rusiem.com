<?php

namespace App\Repository\Form;

use App\Entity\Form\Response;
use App\Repository\BaseRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;

/**
 * Class ResponseRepository
 */
class ResponseRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected string $entityName = Response::class;

    /**
     * @return QueryBuilder
     */
    public function prepareQueryBuilder(): QueryBuilder
    {
        $builder = parent::prepareQueryBuilder();

        if ($status = $this->request->query->get('status_uuid')) {
            $builder
                ->leftJoin('t.status', 's')
                ->andWhere('s.uuid = :status')
                ->setParameter('status', $status);
        }

        $builder->orderBy('w.createdAt', Criteria::DESC);

        return $builder;
    }
}
