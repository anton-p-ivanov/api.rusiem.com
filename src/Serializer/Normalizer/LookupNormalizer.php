<?php

namespace App\Serializer\Normalizer;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\MappingException;
use Symfony\Component\HttpFoundation as Http;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class LookupNormalizer
 *
 * @package App\Serializer\Normalizer
 */
class LookupNormalizer implements NormalizerInterface
{
    /**
     * @var Http\Request|null
     */
    private ?Http\Request $request;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    /**
     * LookupNormalizer constructor.
     *
     * @param Http\RequestStack $requestStack
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(Http\RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->manager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        if (method_exists($object, 'getLookup')) {
            return $object->getLookup();
        }

        return [
            'value' => $this->getValue($object),
            'label' => $object->getTitle()
        ];
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $this->request->headers->has('X-Lookup-Request')
            && is_object($data)
            && strstr(get_class($data), 'App\Entity') !== false;
    }

    /**
     * @param $object
     *
     * @return null
     */
    protected function getValue($object)
    {
        $meta = $this->manager->getClassMetadata(get_class($object));
        try {
            $identifier = $meta->getSingleIdentifierFieldName();
            $methodName = 'get' . ucfirst($identifier);

            return $object->$methodName();
        } catch (MappingException $e) {
            return null;
        }
    }
}