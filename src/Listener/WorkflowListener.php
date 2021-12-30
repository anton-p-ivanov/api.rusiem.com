<?php

namespace App\Listener;

use App\Entity\User;
use App\Entity\Workflow\Workflow;
use App\Entity\Workflow\WorkflowInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class WorkflowListener
 */
class WorkflowListener
{
    /**
     * @var TokenStorageInterface
     */
    private TokenStorageInterface $tokenStorage;

    /**
     * WorkflowListener constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param WorkflowInterface $entity
     * @param LifecycleEventArgs $event
     */
    public function prePersist(WorkflowInterface $entity, LifecycleEventArgs $event)
    {
        $workflow = $this->getWorkflow($entity, $event);
        $entity->setWorkflow($workflow);
    }

    /**
     * @param WorkflowInterface $entity
     * @param LifecycleEventArgs $event
     */
    public function postUpdate(WorkflowInterface $entity, LifecycleEventArgs $event)
    {
        $workflow = $this->getWorkflow($entity, $event);
        $entity->setWorkflow($workflow);

        $manager = $event->getObjectManager();
        $manager->persist($workflow);
        $manager->flush();
    }

    /**
     * @param WorkflowInterface $entity
     * @param LifecycleEventArgs $event
     *
     * @return Workflow
     */
    private function getWorkflow(WorkflowInterface $entity, LifecycleEventArgs $event): Workflow
    {
        $currentUser = $this->getUser($event->getObjectManager());
        $currentDate = new \DateTime();

        if (($workflow = $entity->getWorkflow()) === null) {
            $workflow = new Workflow();
            $workflow->setCreatedAt($currentDate);
            $workflow->setCreatedBy($currentUser);
        }

        $workflow->setUpdatedAt($currentDate);
        $workflow->setUpdatedBy($currentUser);

        return $workflow;
    }

    /**
     * @param ObjectManager $manager
     *
     * @return User|null
     */
    private function getUser(ObjectManager $manager): ?User
    {
        $user = null;
        $token = $this->tokenStorage->getToken();

        if ($token && $token->getUser()) {
            $user = $manager->getRepository(User::class)
                ->findOneBy(['email' => $token->getUser()->getUserIdentifier()]);
        }

        return $user;
    }
}