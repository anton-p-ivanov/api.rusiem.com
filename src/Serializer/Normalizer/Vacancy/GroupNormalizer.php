<?php

namespace App\Serializer\Normalizer\Vacancy;

use App\Entity\Vacancy\Group;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * Class GroupNormalizer
 */
class GroupNormalizer implements NormalizerInterface
{
    /**
     * @inheritDoc
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $encoder = new JsonEncoder();

        $context[AbstractNormalizer::IGNORED_ATTRIBUTES] = ['vacancies'];

        $normalizers = [
            new GetSetMethodNormalizer()
        ];

        $serializer = new Serializer($normalizers, [$encoder]);

        return $serializer->normalize($object, $format, $context);
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Group;
    }
}
