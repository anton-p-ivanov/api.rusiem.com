<?php

namespace App\Listener\Content;

use App\Entity\Content\News;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\ORMException;

/**
 * Class NewsListener
 */
class NewsListener
{
    /**
     * @param News $news
     * @param PreUpdateEventArgs $args
     *
     * @throws ORMException
     */
    public function preUpdate(News $news, PreUpdateEventArgs $args)
    {
        foreach (['imageSmall', 'imageLarge'] as $item) {
            if ($args->hasChangedField($item)) {
                $previousAttachment = $args->getOldValue($item);
                if ($previousAttachment) {
                    $args->getEntityManager()->remove($previousAttachment);
                }
            }
        }
    }
}