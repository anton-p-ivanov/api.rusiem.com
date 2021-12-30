<?php

namespace App\Serializer\Normalizer;

use App\Entity\UserParam;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * Class CollectionNormalizer
 */
class CollectionNormalizer implements NormalizerInterface
{
    /**
     * @inheritDoc
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $collection = new ArrayCollection();

        if ($object instanceof Collection) {
            $collection = $object->map(function ($entity) {
                if ($entity instanceof UserParam) {
                    return (new Serializer([new UserParamNormalizer()]))->normalize($entity);
                }

                return $entity->getUuid();
            });
        }

        return $collection->toArray();
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof Collection;
    }
}