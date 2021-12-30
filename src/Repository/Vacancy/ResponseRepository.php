<?php

namespace App\Repository\Vacancy;

use App\Entity\User;
use App\Entity\UserParam;
use App\Entity\Vacancy\Response;
use App\Repository\BaseRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class ResponseRepository
 */
class ResponseRepository extends BaseRepository
{
    /**
     * @var string|null
     */
    protected ?string $entityClass = Response::class;

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        $builder = parent::getQueryBuilder();

        $user = $this->token->getUser();
        if ($user instanceof User) {
            $userParam = $user->getParams()->get('vacancies.responses.filter') ?? new UserParam();
            $this->filter($builder, $userParam->getValue());
        }

        return $builder;
    }

    /**
     * @param QueryBuilder $builder
     * @param string $sortBy
     * @param string $sortOrder
     */
    protected function orderByUpdatedAt(QueryBuilder $builder, string $sortBy, string $sortOrder): void
    {
        $builder->addOrderBy("w.$sortBy", $sortOrder);
    }

    /**
     * @param QueryBuilder $builder
     * @param string $value
     */
    protected function filterByVacancy(QueryBuilder $builder, string $value)
    {
        $builder
            ->leftJoin('t.vacancy', 'v')
            ->andWhere($builder->expr()->eq('v.uuid', ':vacancy'))
            ->setParameter(':vacancy', $value);
    }

    /**
     * @param QueryBuilder $builder
     * @param string $value
     *
     * @throws \Exception
     */
    protected function filterByPublishedAt(QueryBuilder $builder, string $value)
    {
        $dateTime = new \DateTime($value);
        $dateFrom = $dateTime->format('Y-m-d') . ' 00:00:00';
        $dateTo = $dateTime->format('Y-m-d') . ' 23:59:59';
        $builder
            ->andWhere($builder->expr()->between('w.createdAt', ':publishedFrom', ':publishedTo'))
            ->setParameter(':publishedFrom', $dateFrom)
            ->setParameter(':publishedTo', $dateTo);
    }

    /**
     * @param QueryBuilder $builder
     * @param string $value
     */
    public function filterBySearch(QueryBuilder $builder, string $value)
    {
        $builder->andWhere(
            $builder->expr()->orX(
                $builder->expr()->like('t.fullName', ':search'),
                $builder->expr()->like('t.email', ':search'),
                $builder->expr()->like('t.phone', ':search'),
            )
        );

        $builder->setParameter(':search', "%$value%");
    }
}
