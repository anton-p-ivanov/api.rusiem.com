<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class BaseRepository
 */
class BaseRepository extends ServiceEntityRepository implements RepositoryInterface
{
    /**
     * @var bool
     */
    public bool $isResultsFiltered = false;

    /**
     * @var string|null
     */
    protected ?string $entityClass = null;

    /**
     * @var TokenInterface|null
     */
    protected ?TokenInterface $token;

    /**
     * @var Request|null
     */
    protected ?Request $request;

    /**
     * @param ManagerRegistry $registry
     * @param TokenStorageInterface $tokenStorage
     * @param RequestStack $requestStack
     */
    public function __construct(
        ManagerRegistry $registry,
        TokenStorageInterface $tokenStorage,
        RequestStack $requestStack
    ) {
        $this->token = $tokenStorage->getToken();
        $this->request = $requestStack->getCurrentRequest();

        parent::__construct($registry, $this->entityClass);
    }

    /**
     * @return QueryBuilder
     */
    public function getLookupQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('t')->select(['t']);
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        $sort = $this->request->get('sort');
        $page = (int)$this->request->get('page', 1);
        $limit = (int)$this->request->get('size', 10);

        $properties = $this->_class->getReflectionProperties();
        $builder = $this->createQueryBuilder('t')->select(['t']);

        if (array_key_exists('workflow', $properties)) {
            $builder->addSelect(['w'])
                ->leftJoin('t.workflow', 'w')
                ->andWhere($builder->expr()->orX(
                    $builder->expr()->isNull('w.uuid'),
                    $builder->expr()->eq('w.isDeleted', ':isDeleted')
                ))
                ->setParameter(':isDeleted', false);
        }

        if ($sort) {
            $sortOrder = Criteria::ASC;
            $sortBy = $sort;
            if (str_starts_with($sort, '-')) {
                $sortOrder = Criteria::DESC;
                $sortBy = substr($sort, 1);
            }

            $this->order($builder, $sortBy, $sortOrder);
        }

        $builder
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return $builder;
    }

    /**
     * @param QueryBuilder $builder
     * @param string $sortBy
     * @param string $sortOrder
     */
    protected function order(QueryBuilder $builder, string $sortBy, string $sortOrder): void
    {
        $methodName = 'orderBy' . ucfirst($sortBy);
        if (method_exists($this, $methodName)) {
            $this->$methodName($builder, $sortBy, $sortOrder);
        } else {
            $builder->addOrderBy("t.$sortBy", $sortOrder);
        }
    }

    /**
     * @param QueryBuilder $builder
     * @param array $rules
     */
    protected function filter(QueryBuilder $builder, array $rules)
    {
        foreach ($rules as $name => $value) {
            $isEmptyValue = in_array($value, [null, ""]);
            if ($isEmptyValue) {
                continue;
            }

            $methodName = 'filterBy' . ucfirst($name);
            if (method_exists($this, $methodName)) {
                $this->$methodName($builder, $value);
                $this->isResultsFiltered = true;
            }
        }
    }
}
