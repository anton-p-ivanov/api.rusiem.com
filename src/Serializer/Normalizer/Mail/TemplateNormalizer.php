<?php

namespace App\Serializer\Normalizer\Mail;

use App\Entity\Mail\Template;
use App\Entity\Mail\TemplateLang;
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
 * Class TemplateNormalizer
 */
class TemplateNormalizer implements NormalizerInterface
{
    /**
     * @inheritDoc
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $encoder = new JsonEncoder();

        $context[AbstractNormalizer::CALLBACKS] = [
            'extraHeaders' => function (?array $data) {
                return $data ? json_encode($data) : null;
            },
            'translations' => function (Collection $collection) {
                return $collection->map(function (TemplateLang $lang) {
                    return [
                        'locale' => $lang->getLocale()->getSlug(),
                        'subject' => $lang->getSubject(),
                        'html' => $lang->getHtml(),
                        'text' => $lang->getText(),
                    ];
                });
            }
        ];

        $normalizers = [
            new WorkflowNormalizer(),
            new DateTimeNormalizer(),
            new FileNormalizer(),
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
        return $data instanceof Template;
    }
}
