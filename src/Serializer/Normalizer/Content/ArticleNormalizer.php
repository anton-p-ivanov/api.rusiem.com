<?php

namespace App\Serializer\Normalizer\Content;

use App\Entity\Content\Article;
use App\Entity\Locale;
use App\Serializer\Normalizer\CollectionNormalizer;
use App\Serializer\Normalizer\Media\FileNormalizer;
use App\Serializer\Normalizer\WorkflowNormalizer;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * Class ArticleNormalizer
 */
class ArticleNormalizer implements NormalizerInterface
{
    /**
     * @inheritDoc
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $encoder = new JsonEncoder();

        $context[AbstractNormalizer::CALLBACKS] = [
            'locale' => fn(?Locale $locale) => $locale ? $locale->getSlug() : null,
            'meta' => fn(Collection $collection) => $collection->toArray(),
        ];

        $normalizers = [
            new WorkflowNormalizer(),
            new FileNormalizer(),
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
        return $data instanceof Article;
    }
}
