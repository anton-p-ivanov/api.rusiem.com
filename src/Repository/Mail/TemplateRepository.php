<?php

namespace App\Repository\Mail;

use App\Entity\Mail\Template;
use App\Entity\Mail\TemplateLang;
use App\Entity\User;
use App\Entity\UserParam;
use App\Repository\BaseRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * Class TemplateRepository
 */
class TemplateRepository extends BaseRepository
{
    /**
     * @var string|null
     */
    protected ?string $entityClass = Template::class;

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        $builder = parent::getQueryBuilder();

        $user = $this->token->getUser();
        if ($user instanceof User) {
            $userParam = $user->getParams()->get('mail.templates.filter') ?? new UserParam();
            $this->filter($builder, $userParam->getValue());
        }

        $builder
            ->join(TemplateLang::class, 't10n', Join::WITH, 't = t10n.template')
            ->andWhere($builder->expr()->eq('t10n.locale', ':locale'))
            ->setParameter(':locale', $this->request->headers->get('Accept-Language', 'en'));

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
     * @param string $sortBy
     * @param string $sortOrder
     *
     * @return void
     */
    protected function orderBySubject(QueryBuilder $builder, string $sortBy, string $sortOrder)
    {
        $builder->addOrderBy("t10n.$sortBy", $sortOrder);
    }

    /**
     * @param QueryBuilder $builder
     * @param string $value
     */
    protected function filterBySearch(QueryBuilder $builder, string $value)
    {
        $condition = $builder->expr()->orX(
            $builder->expr()->like('t10n.subject', ':search'),
            $builder->expr()->like('t.code', ':search')
        );

        $builder
            ->andWhere($condition)
            ->setParameter(':search', "%$value%");
    }
}
