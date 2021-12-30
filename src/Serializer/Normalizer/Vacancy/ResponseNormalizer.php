<?php

namespace App\Serializer\Normalizer\Vacancy;

use App\Entity\Vacancy\Response;
use App\Serializer\Normalizer\Media\FileNormalizer;
use App\Serializer\Normalizer\WorkflowNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * Class ResponseNormalizer
 */
class ResponseNormalizer implements NormalizerInterface
{
    /**
     * @inheritDoc
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $encoder = new JsonEncoder();

        $normalizers = [
            new WorkflowNormalizer(),
            new DateTimeNormalizer(),
            new FileNormalizer(),
            new VacancyNormalizer(),
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
        return $data instanceof Response;
    }
}
