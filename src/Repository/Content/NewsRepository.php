<?php

namespace App\Repository\Content;

use App\Entity\Content\News;
use App\Entity\User;
use App\Entity\UserParam;
use App\Repository\BaseRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class NewsRepository
 */
class NewsRepository extends BaseRepository
{
    /**
     * @var string|null
     */
    protected ?string $entityClass = News::class;

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        $builder = parent::getQueryBuilder();

        $user = $this->token->getUser();
        if ($user instanceof User) {
            $userParam = $user->getParams()->get('content.news.filter') ?? new UserParam();
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
    protected function filterByTitle(QueryBuilder $builder, string $value)
    {
        $builder
            ->andWhere($builder->expr()->like('t.title', ':title'))
            ->setParameter(':title', "%$value%");
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
            ->andWhere($builder->expr()->between('t.publishedAt', ':publishedFrom', ':publishedTo'))
            ->setParameter(':publishedFrom', $dateFrom)
            ->setParameter(':publishedTo', $dateTo);
    }

    /**
     * @param QueryBuilder $builder
     * @param string $value
     */
    protected function filterByLocale(QueryBuilder $builder, string $value)
    {
        $builder
            ->leftJoin('t.locale', 'locale')
            ->andWhere($builder->expr()->eq('locale.slug', ':locale'))
            ->setParameter(':locale', $value);
    }

    /**
     * @param QueryBuilder $builder
     * @param string $value
     */
    protected function filterBySite(QueryBuilder $builder, string $value)
    {
        $builder
            ->leftJoin('t.sites', 'sites')
            ->andWhere($builder->expr()->eq('sites.uuid', ':site'))
            ->setParameter(':site', $value);
    }

    /**
     * @param QueryBuilder $builder
     * @param string $value
     */
    protected function filterByIsPublished(QueryBuilder $builder, string $value)
    {
        $builder
            ->andWhere($builder->expr()->eq('t.isPublished', ':isPublished'))
            ->setParameter(':isPublished', $value === 'true');
    }
}
