<?php

namespace App\Serializer\Normalizer\Form;

use App\Entity\Form\Form;
use App\Entity\Form\Status;
use App\Entity\Form\StatusLang;
use App\Entity\Mail\Template;
use App\Serializer\Normalizer\CollectionNormalizer;
use App\Serializer\Normalizer\WorkflowNormalizer;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * Class StatusNormalizer
 */
class StatusNormalizer implements NormalizerInterface
{
    /**
     * @inheritDoc
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $encoder = new JsonEncoder();

        $context[AbstractNormalizer::IGNORED_ATTRIBUTES] = ['responses', 'statuses'];
        $context[AbstractNormalizer::CALLBACKS] = [
            'form' => fn(?Form $form) => $form ? $form->getUuid() : null,
            'template' => fn(?Template $template) => $template ? $template->getUuid() : null,
            'translations' => function (Collection $collection) {
                return $collection->map(function (StatusLang $lang) {
                    return [
                        'locale' => $lang->getLocale()->getSlug(),
                        'title' => $lang->getTitle(),
                    ];
                });
            }
        ];

        $normalizers = [
            new WorkflowNormalizer(),
            new DateTimeNormalizer(),
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
        return $data instanceof Status;
    }
}
