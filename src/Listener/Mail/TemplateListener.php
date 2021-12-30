<?php

namespace App\Listener\Mail;

use App\Entity\Content\Article;
use App\Entity\Mail\Template;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\ORMException;

/**
 * Class TemplateListener
 */
class TemplateListener
{
    /**
     * @param Template $template
     * @param PreUpdateEventArgs $args
     *
     * @throws ORMException
     */
    public function preUpdate(Template $template, PreUpdateEventArgs $args)
    {
        if ($args->hasChangedField('attachment')) {
            $previousAttachment = $args->getOldValue('attachment');

            if ($previousAttachment) {
                $args->getEntityManager()->remove($previousAttachment);
            }
        }
    }
}