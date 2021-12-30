<?php

namespace App\Serializer\Normalizer\Media;

use App\Entity\Media\File;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class FileNormalizer
 */
class FileNormalizer implements NormalizerInterface
{
    /**
     * @inheritDoc
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        if ($object instanceof File) {
            return [
                'uuid' => $object->getUuid(),
                'name' => $object->getOriginalName(),
                'size' => $object->getSize(),
                'type' => $object->getMimeType(),
                'image' => str_starts_with($object->getMimeType(), 'image')
                    ? [
                        'width' => $object->getImageWidth(),
                        'height' => $object->getImageHeight(),
//                        'src' => $object->getContext()->getSlug() . '/' . $object->getStoredName()
                    ]
                    : null
            ];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof File;
    }
}
