<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class ViolationNormalizer
 */
class ViolationNormalizer implements NormalizerInterface
{
    /**
     * @inheritDoc
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $normalizedData = [];

        foreach ($object as $error) {
            $propertyPath = $error->getPropertyPath();
            if (preg_match('/\[(\d+)]$/', $propertyPath)) {
                $propertyPath = preg_replace('/\[\d+]$/', '', $propertyPath);
            }

            if (!array_key_exists($propertyPath, $normalizedData)) {
                $normalizedData[$propertyPath] = [$error->getMessage()];
            } else if (!in_array($error->getMessage(), $normalizedData[$propertyPath])) {
                $normalizedData[$propertyPath][] = $error->getMessage();
            }
        }

        return $normalizedData;
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof ConstraintViolationList;
    }
}