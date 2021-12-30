<?php

namespace App\Serializer\Normalizer\Vacancy;

use App\Entity\Locale;
use App\Entity\Vacancy\Group;
use App\Entity\Vacancy\Vacancy;
use App\Serializer\Normalizer\CollectionNormalizer;
use App\Serializer\Normalizer\WorkflowNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * Class VacancyNormalizer
 */
class VacancyNormalizer implements NormalizerInterface
{
    /**
     * @inheritDoc
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $encoder = new JsonEncoder();

        $context[AbstractNormalizer::CALLBACKS] = [
            'locale' => fn(?Locale $locale) => $locale ? $locale->getSlug() : null,
            'group' => fn(?Group $group) => $group ? $group->getUuid() : null,
        ];

        $normalizers = [
            new WorkflowNormalizer(),
            new DateTimeNormalizer(),
            new CollectionNormalizer(),
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
        return $data instanceof Vacancy;
    }
}
