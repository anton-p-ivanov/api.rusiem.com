<?php

namespace App\Serializer\Normalizer;

use App\Entity\User;
use App\Entity\Workflow\Workflow;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * Class WorkflowNormalizer
 */
class WorkflowNormalizer implements NormalizerInterface
{
    /**
     * @inheritDoc
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $encoder = new JsonEncoder();

        $normalizers = [
            new DateTimeNormalizer(),
            new GetSetMethodNormalizer()
        ];

        $context[AbstractNormalizer::ATTRIBUTES] = ['createdAt', 'createdBy', 'updatedAt', 'updatedBy', 'isDeleted'];
        $context[AbstractNormalizer::CALLBACKS] = [
            'createdBy' => fn (?User $user) => $user ? $user->getFullName() : null,
            'updatedBy' => fn (?User $user) => $user ? $user->getFullName() : null,
        ];

        $serializer = new Serializer($normalizers, [$encoder]);

        return $serializer->normalize($object, $format, $context);
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof Workflow;
    }
}