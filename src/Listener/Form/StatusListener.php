<?php

namespace App\Listener\Form;

use App\Entity\Form\Status;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class StatusListener
 */
class StatusListener
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Status $status
     */
    public function postPersist(Status $status): void
    {
        if ($status->getIsDefault()) {
            $this->setDefaultStatus($status);
        }
    }

    /**
     * @param Status $status
     */
    public function postUpdate(Status $status): void
    {
        if ($status->getIsDefault()) {
            $this->setDefaultStatus($status);
        }
    }

    /**
     * @param Status $status
     */
    private function setDefaultStatus(Status $status): void
    {
        $params = [
            'setDefault' => false,
            'isDefault' => true,
            'status' => $status->getName(),
            'form' => $status->getForm()
        ];

        $this->manager->createQueryBuilder()
            ->update(Status::class, 't')
            ->set('t.isDefault', ':setDefault')
            ->where('t.isDefault = :isDefault AND t.name != :status AND t.form = :form')
            ->setParameters($params)
            ->getQuery()
            ->execute();
    }
}