<?php

namespace App\Listener\Content;

use App\Entity\Content\Article;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\ORMException;

/**
 * Class ArticleListener
 */
class ArticleListener
{
    /**
     * @param Article $article
     * @param PreUpdateEventArgs $args
     *
     * @throws ORMException
     */
    public function preUpdate(Article $article, PreUpdateEventArgs $args)
    {
        if ($args->hasChangedField('attachment')) {
            $previousAttachment = $args->getOldValue('attachment');

            if ($previousAttachment) {
                $args->getEntityManager()->remove($previousAttachment);
            }
        }
    }
}