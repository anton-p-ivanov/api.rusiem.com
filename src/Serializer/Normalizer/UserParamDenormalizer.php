<?php

namespace App\Serializer\Normalizer;

use App\Entity\User;
use App\Entity\UserParam;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Class UserParamDenormalizer
 */
class UserParamDenormalizer implements DenormalizerInterface
{
    /**
     * @var User|null
     */
    private ?User $user = null;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    /**
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager)
    {
        $this->manager = $entityManager;

        if ($tokenStorage->getToken()) {
            $this->user = $tokenStorage->getToken()->getUser() ?? null;
        }
    }

    /**
     * @inheritDoc
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if ($this->user instanceof User && $data["param"]) {
            $object = $this->manager->find($type, ["user" => $this->user, "param" => $data["param"]]);

            if ($object instanceof $type) {
                return $object->setValue($data["value"]);
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return $type === UserParam::class;
    }
}